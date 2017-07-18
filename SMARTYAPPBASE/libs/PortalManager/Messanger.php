<?php
namespace PortalManager;

use FlexTimeResort\Allasok;
use PortalManager\User;
use MailManager\Mailer;
use MailManager\Mails;

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

  public function readInfos( $uid = false )
  {
    $datas = array();
    $outbox_unreaded = 0;
    $inbox_unreaded = 0;

    if (!$uid) {
      return false;
    }

    $qry = "SELECT
      ms.start_by,
      ms.start_by_id,
      m.user_readed_at,
      m.admin_readed_at,
      m.user_from_id,
      m.user_to_id
    FROM ".self::DBTABLE_MESSAGES." as m
    LEFT OUTER JOIN ".self::DBTABLE." as ms ON ms.sessionid = m.sessionid
    WHERE 1=1";

    $qry .= " and (m.user_from_id = {$uid} or m.user_to_id = {$uid}) ";

    $arg = array();
    $arg['multi'] = true;
    extract($this->db->q($qry, $arg));

    foreach ((array)$data as $d)
    {
      if($uid == $d['user_to_id'] && $d['start_by_id'] == $uid && is_null($d['user_readed_at'])){
          $outbox_unreaded++;
      }
      if($uid == $d['user_to_id'] && $d['start_by_id'] != $uid && is_null($d['user_readed_at'])){
          $inbox_unreaded++;
      }
    }

    $datas['inbox_unreaded'] = $inbox_unreaded;
    $datas['outbox_unreaded'] = $outbox_unreaded;
    $datas['total_unreaded'] = $inbox_unreaded + $outbox_unreaded;

    return $datas;
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
      ms.archived_by_user,
      ms.archived_by_admin,
      ms.allas_id,
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

    if (isset($arg['controll_by']) && $arg['controll_by'] == 'msg') {

    }

    if ($arg['controll_by'] != 'msg') {
      if (isset($arg['show_archiv']) && $arg['show_archiv'] == true) {
        $qry .= " and ms.archived_by_user = 1 ";
      } else {
        $qry .= " and ms.archived_by_user = 0 ";
      }
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
        $datas['list'][$d['sessionid']]['archived_by_user'] = (int)$d['archived_by_user'];
        $datas['list'][$d['sessionid']]['archived_by_admin'] = (int)$d['archived_by_admin'];
        $datas['list'][$d['sessionid']]['allas'] = $this->loadAllasData($d['allas_id']);

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

  private function loadAllasData( $id = false )
  {
    if (!$id) {
      return false;
    }

    $data = array();

    $allas = (new Allasok(array('controller' => $this->controller)))->load($id);

    $data['text'] = $allas->shortDesc();
    $data['cat'] = $allas->get('cat_name');
    $data['type'] = $allas->get('tipus_name');
    $data['city'] = $allas->getCity();
    $data['url'] = $allas->getUrl();

    return $data;
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

  public function createSession( $data, $by = 'admin' )
  {
    extract($data);
    $createdSession = false;

    $lang = $this->controller->LANGUAGES->getCurrentLang();

    if (empty($msg)) {
      throw new \Exception($this->controller->lang("Első üzenet tartalmát kötelező megadni."));
    }

    if (empty($subject)) {
      throw new \Exception($this->controller->lang("A beszélgetés létrehozásához adja meg a témát."));
    }
    $createdSession = uniqid();

    $this->db->insert(
      self::DBTABLE,
      array(
        'sessionid' => $createdSession,
        'subject' => $subject,
        'allas_id' => (isset($allas_id) && !empty($allas_id)) ? (int)$allas_id : NULL,
        'allas_requester_user_id' => (isset($user_id) && !empty($user_id)) ? (int)$user_id : NULL,
        'start_by' => $by,
        'start_by_id' => ($by == 'admin') ? $admin_id : $user_id
      )
    );

    // Üzenet beszúrása
    $this->addMessage(
      $createdSession,
      $admin_id,
      $user_id,
      $msg,
      ($by == 'admin') ? true : false
    );

    // E-mail alert
    if (isset($user_id) && !empty($user_id))
    {
      $allas = (new Allasok(array('controller' => $this->controller)))->load($allas_id);
      $requestedUser = new User($user_id, array('controller' => $this->controller));

      $mail = new Mailer(
        $this->settings['page_title'],
        $this->settings['email_noreply_address'],
        $this->settings['mail_sender_mode']
      );
  		$mail->add( $requestedUser->getEmail() );

      $this->smarty->assign( 'subject', $subject );
      $this->smarty->assign( 'msg', $msg );
      $this->smarty->assign( 'settings', $this->controller->settings );
      $this->smarty->assign( 'allas', $allas );
      $this->smarty->assign( 'user', $requestedUser );
      $this->smarty->assign( 'msgurl', '/ugyfelkapu/uzenetek/msg/'.$createdSession.'/?rel=email-alert' );

      $mail->setSubject( $this->controller->lang('MAIL_CP_MESSANGER_UJ_UZENET_BESZELGETES', array('tema' => $subject)));

  		$mail->setMsg( $this->smarty->fetch( 'mails/'.$lang.'/messanger_new_sesssion_user.tpl' ) );
  		$re = $mail->sendMail();
    }

    return $createdSession;
  }

  public function setReadedMessage($by, $session)
  {
    $aby = ($by == 'user_readed_at') ? 'user_alerted' : 'admin_alerted';
    $this->db->update(
      self::DBTABLE_MESSAGES,
      array(
        $by => NOW,
        $aby => 1
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
