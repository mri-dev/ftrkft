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

  public function loadMessages( $uid )
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
      ms.subject,
      ms.created_at,
      ms.notice_by_admin,
      ms.notice_by_user,
      ms.created_at,
      ms.closed_at,
      ms.closed
    FROM ".self::DBTABLE_MESSAGES." as m
    LEFT OUTER JOIN ".self::DBTABLE." as ms ON ms.sessionid = m.sessionid
    WHERE 1=1";

    $qry .= " and (m.user_from_id = {$uid} or m.user_to_id = {$uid})";

    if ($this->admin) {
      //$qry .= " ORDER BY m.";
    } else {
      $qry .= " ORDER BY m.user_readed_at ASC, m.send_at DESC";
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
        $datas['list'][$d['sessionid']]['from'] = array(
          'name' => 'Admin',
          'ID' => 1
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

      $datas['list'][$d['sessionid']]['msg'][] = array(
        'ID' => (int)$d['ID'],
        'msg' => $d['message'],
        'admin_readed_at' => $d['admin_readed_at'],
        'user_readed_at' => $d['user_readed_at'],
        'unreaded' => $unreaded
      );
    }

    $datas['unreaded_msg'] = $unreads;
    $datas['unreaded_group'] = count($unreaded_msg_sessions);
    $datas['unreaded_msg_ids'] = $unreaded_msg_ids;
    $datas['unreaded_msg_sessions'] = $unreaded_msg_sessions;

    return $datas;
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
