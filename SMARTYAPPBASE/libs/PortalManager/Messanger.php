<?php
namespace PortalManager;

class Messanger
{
  const DBTABLE = 'messanger';
  const DBTABLE_MESSAGES = 'messanger_messages';

  public $db = null;
  private $controller = null;
  private $smarty = null;
  private $settings = array();
  private $admin = false;

  function __construct( $arg = array() )
	{
		if ( isset($arg['controller']) ) {
			$this->controller = $arg['controller'];
			$this->db = $arg['controller']->db;
			$this->settings = $arg['controller']->settings;
			$this->smarty = $arg['controller']->smarty;
		}

		return $this;
  }

  public function loadMessages( $uid, $arg = array() )
  {
    $datas = array();
    $unreads = 0;
    $unreaded_msg_ids = array();
    $unreaded_msg_sessions = array();

    $qry = "SELECT
      m.ID,
      m.sessionid,
      m.message,
      m.user_readed_at,
      m.admin_readed_at,
      m.send_at,
      m.user_from_id,
      m.user_to_id,
      ms.subject,
      ms.created_at,
      ms.notice_by_admin,
      ms.notice_by_user,
      ms.created_at,
      ms.closed_at,
      ms.closed,
      ms.start_by_id,
      IF(m.from_admin, from_admin.name, from_user.name) as from_name,
      IF(ms.start_by = 'user', sess_user.name, sess_admin.name) as session_starter_name,
      IF(ms.start_by = 'user', 'outbox', 'inbox') as controll_for
    FROM ".self::DBTABLE_MESSAGES." as m
    LEFT OUTER JOIN ".self::DBTABLE." as ms ON ms.sessionid = m.sessionid
    LEFT OUTER JOIN accounts as from_user ON from_user.ID = m.user_from_id
    LEFT OUTER JOIN accounts as sess_user ON sess_user.ID = ms.start_by_id
    LEFT OUTER JOIN admin as from_admin ON from_admin.ID = m.user_from_id
    LEFT OUTER JOIN admin as sess_admin ON sess_admin.ID = ms.start_by_id
    WHERE 1=1";

    $qry .= " and (m.user_from_id = {$uid} or m.user_to_id = {$uid})";

    if (isset($arg['controll_by']) && in_array($arg['controll_by'], array('inbox', 'outbox'))) {
      $cby = array(
        'inbox' => 'admin',
        'outbox' => 'user'
      );
      $qry .= " and (ms.start_by = '".$cby[$arg['controll_by']]."')";
    }

    if (isset($arg['show_archiv']) && $arg['show_archiv'] == true) {
      $qry .= " and ms.archived_by_user = 1 ";
    } else {
      $qry .= " and ms.archived_by_user = 0 ";
    }

    if ($this->admin) {
      //$qry .= " ORDER BY m.";
    } else {
      $qry .= " ORDER BY m.send_at DESC";
    }

    $arg = array();
    $arg['multi'] = true;
    extract($this->db->q($qry, $arg));

    foreach ((array)$data as $d)
    {
      if (!isset($datas['list'][$d['sessionid']]['ID'])) {
        $datas['list'][$d['sessionid']]['session'] = $d['sessionid'];
        $datas['list'][$d['sessionid']]['subject'] = ($d['subject'] != '') ? $this->controller->lang($d['subject']) : $this->controller->lang('Nincs tárgy');
        $datas['list'][$d['sessionid']]['notice_by_admin'] = $d['notice_by_admin'];
        $datas['list'][$d['sessionid']]['notice_by_user'] = $d['notice_by_user'];
        $datas['list'][$d['sessionid']]['created_at'] = $d['created_at'];
        $datas['list'][$d['sessionid']]['closed_at'] = $d['closed_at'];
        $datas['list'][$d['sessionid']]['closed'] = ($d['closed'] == 1) ? true : false;
        $datas['list'][$d['sessionid']]['controll_for'] = $d['controll_for'];

        $datas['list'][$d['sessionid']]['from'] = array(
          'name' => $d['session_starter_name'],
          'ID' => $d['start_by_id']
        );
      }

      $unreaded = ( ($this->admin) ? (is_null($d['admin_readed_at']) ? true : false) : (is_null($d['user_readed_at']) ? true : false) );

      if ($unreaded) {
        $unreads++;
        $unreaded_msg_ids[] = (int)$d['ID'];

        if (!in_array($d['sessionid'], $unreaded_msg_sessions)) {
          $unreaded_msg_sessions[$d['sessionid']]++;
          $datas['list'][$d['sessionid']]['unreaded']++;
        }
      }

      $is_today = (date('Ymd') == date('Ymd', strtotime($d['send_at']))) ? true : false;

      $datas['list'][$d['sessionid']]['msg'][] = array(
        'ID' => (int)$d['ID'],
        'msg' => $d['message'],
        'admin_readed_at' => $d['admin_readed_at'],
        'user_readed_at' => $d['user_readed_at'],
        'send_at' => ($is_today) ? date('H:i', strtotime($d['send_at'])) : date('Y. m. d. H:i', strtotime($d['send_at'])),
        'from_id' => $d['user_from_id'],
        'to_id' => $d['user_to_id'],
        'unreaded' => $unreaded,
        'from' => array(
          'name' => $d['from_name'],
          'ID' => $d['user_from_id']
        )
      );
    }

    $datas['unreaded_msg'] = $unreads;
    $datas['unreaded_group'] = count($unreaded_msg_sessions);
    $datas['unreaded_msg_ids'] = $unreaded_msg_ids;
    $datas['unreaded_msg_sessions'] = $unreaded_msg_sessions;

    return $datas;
  }

  public function addMessage($session, $from, $to, $msg, $admin)
  {
    if ($this->isMessageSessionClosed($session)) {
      throw new \Exception($this->controller->lang('Ez a beszélgetés időközben lezárásra került.'));
    }

    $this->db->insert(
      self::DBTABLE_MESSAGES,
      array(
        'sessionid' => $session,
        'message' => $msg,
        'from_admin' => ($admin) ? 1 : 0,
        'user_from_id' => $from,
        'user_to_id' => $to,
        'user_readed_at' => ($admin) ? NULL : NOW,
        'admin_readed_at' => ($admin) ? NOW : NULL,
      )
    );

    return $this->db->lastInsertId();
  }

  public function setReadedMessage($by, $session)
  {
    $this->db->update(
      self::DBTABLE_MESSAGES,
      array(
        $by => NOW
      ),
      sprintf($by." IS NULL and sessionid = '%s'", $session)
    );
  }

  public function isMessageSessionClosed($session)
  {
    return ($this->db->query("SELECT closed FROM ".self::DBTABLE." WHERE sessionid = '{$session}'")->fetchColumn() == 1) ? true : false;
  }

  public function editMessageData($session, $key, $value)
  {
    $this->db->update(
      self::DBTABLE,
      array(
        $key => $value
      ),
      sprintf("sessionid = '%s'", $session)
    );
  }

  public function __destruct()
	{
		$this->db = null;
		$this->smarty = false;
		$this->settings = null;
		$this->controller = null;
	}
}

?>
