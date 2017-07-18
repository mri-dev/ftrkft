<?php
namespace FlexTimeResort;

use ExceptionManager\RedirectException;
use FlexTimeResort\Allasok;
use PortalManager\User;
use AlertsManager\Alerts;
use MailManager\Mailer;
use MailManager\Mails;

class Requests
{
  public $db = null;
  public $controller = null;
  public $smarty = null;
  private $admin = false;
  public $settings = array();
	public $current_page = 1;
  public $total_items = 0;
  public $total_pages = 1;
  public $tree = false;
  public $request_info = array();
  public $infos = array();

	private $current_category = false;
  public $tree_ids = array();
  private $last_tree_id = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
  private $close_loop = false;

  public function __construct( $arg = array() )
  {
    if ( isset($arg['controller']) ) {
			$this->controller = $arg['controller'];
			$this->db = $arg['controller']->db;
			$this->settings = $arg['controller']->settings;
			$this->smarty = $arg['controller']->smarty;
		}
    if(isset($arg['admin']) && $arg['admin'] === true ){
      $this->admin = true;
    }
		return $this;
  }

  public function getTree( $arg = array() )
	{
		$tree = array();
    $filters = $arg['filters'];
		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
        r.hashkey,
        r.allas_id,
        r.user_id,
        r.request_at,
        r.accepted,
        r.accepted_at,
        r.admin_accept_id,
        r.show_author_info,
        r.admin_pick,
        r.finished,
        r.declined,
        r.declined_at,
        a.name as admin_name,
        ap.name as pick_admin_name
			FROM ".\FlexTimeResort\Allasok::DB_REQUEST_X." as r
      LEFT OUTER JOIN admin as a ON a.ID = r.admin_accept_id
      LEFT OUTER JOIN admin as ap ON ap.ID = r.admin_pick
			WHERE 1=1 ";


    // Filterek
    if (isset($filters) && !empty($filters)) {
      if (isset($filters['ad_ids'])) {
        $qry .= " and r.allas_id IN(".implode(",",$filters['ad_ids']).")";
      }
      if (isset($filters['accepted'])) {
        $qry .= " and r.accepted = ".$filters['accepted'];
      }

      if (isset($filters['onlyunpicked']) && $filters['onlyunpicked'] === true ) {
        $qry .= " and r.admin_pick IS NULL";
      }

      if (isset($filters['onlydeclined']) && $filters['onlydeclined'] === true ) {
        $qry .= " and r.declined = 1";
      }

      if (isset($filters['onlyaccepted']) && $filters['onlyaccepted'] === true ) {
        $qry .= " and r.accepted = 1";
      }

      if (isset($filters['onlypickedby'])) {
        $qry .= " and r.admin_pick = ".$filters['onlypickedby'];
      }
    }

    // Order
    $qry .= " ORDER BY r.finished ASC, r.declined ASC, r.accepted ASC, r.admin_pick ASC, r.request_at ASC ";

    // Limit
		$limit = $this->getLimit($arg);
		$qry .= " LIMIT ".$limit[0].", ".$limit[1];

    //echo $qry;

		$top_cat_qry 	= $this->db->query($qry);
		$top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

    $this->total_items 	= $this->db->query("SELECT FOUND_ROWS();")->fetchColumn();
		$this->total_pages 	= ceil( $this->total_items / $limit[1] );

		if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( $top_cat_data as $top_cat ) {
			$this->tree_items++;

      $top_cat['messanger'] = $this->getMessangerSession($top_cat['allas_id'], $top_cat['user_id']);

      if (!in_array($top_cat['allas_id'], $this->tree_ids)) {
        $this->tree_ids[] = $top_cat[allas_id];
      }

      if ($top_cat['accepted']) {
        $this->infos['requests']['accepted']++;
        $this->request_info[$top_cat['allas_id']]['requests']['accepted']++;
      } else {
        $this->infos['requests']['not_accepted']++;
        $this->request_info[$top_cat['allas_id']]['requests']['not_accepted']++;

      }

      if($top_cat['finished'] == 1){
        $this->request_info[$top_cat['allas_id']]['requests']['finished']++;
        $this->infos['requests']['finished']++;
      } else {
        $this->request_info[$top_cat['allas_id']]['requests']['not_finished']++;
        $this->infos['requests']['not_finished']++;
      }

      if($top_cat['declined'] == 1){
        $this->infos['requests']['declined']++;
      }

      if($top_cat['finished'] == 0 && ($top_cat['declined'] == 0 || $top_cat['accepted'] == 0)){
          $this->request_info[$top_cat['allas_id']]['requests']['in_progress']++;
      }

      if(!isset($this->tree_steped_item[$top_cat[allas_id]]['ID'])){
        $this->tree_steped_item[$top_cat[allas_id]]['ID'] = $top_cat['allas_id'];
        $this->tree_steped_item[$top_cat[allas_id]]['data'] = (new Allasok(array('controller' => $this->controller)))->load($top_cat['allas_id']);
        $tree[$top_cat[allas_id]]['ID'] = $top_cat['allas_id'];
      }

      $top_cat['user'] = (new User($top_cat['user_id'], array('controller' => $this->controller)));

			$this->tree_steped_item[$top_cat[allas_id]][items][] = $top_cat;
			$tree[$top_cat[allas_id]][items][] = $top_cat;
		}
		$this->tree = $tree;
		return $this;
	}

  private function getMessangerSession( $id = 0, $user_id = 0 )
  {
    $sessionId = $this->db->query("SELECT sessionid FROM ".\PortalManager\Messanger::DBTABLE." WHERE allas_id IS NOT NULL and allas_id = ".$id." and allas_requester_user_id = ".$user_id)->fetch(\PDO::FETCH_ASSOC);

    if(empty($sessionId)) {
      return false;
    }

    return $sessionId;
  }

  public function pick($admin = false, $hashkey = null)
  {
    if (!$admin || is_null($hashkey)) {
      return false;
    }

    $cs = (int)$this->db->query("SELECT admin_pick FROM ".\FlexTimeResort\Allasok::DB_REQUEST_X." WHERE hashkey = '{$hashkey}'")->fetchColumn();

    if ( $cs !== 0) {
      throw new \Exception("Időközben már felvette egy adminisztrátor a kérelem kezelését.");
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_REQUEST_X,
      array(
        'admin_pick' => 1
      ),
      sprintf("hashkey = '%s'", $hashkey)
    );

    return true;
  }

  public function setDecline($admin = false, $hashkey = null)
  {
    if (!$admin || is_null($hashkey)) {
      return false;
    }

    $this->db->update(
      \FlexTimeResort\Allasok::DB_REQUEST_X,
      array(
        'declined' => 1,
        'declined_at' => NOW,
        'finished' => 1
      ),
      sprintf("hashkey = '%s'", $hashkey)
    );

    return true;
  }

  public function setAllow($admin = false, $hashkey = null, $show_author_info = false)
  {
    if (!$admin || is_null($hashkey)) {
      return false;
    }

    $lang = $this->controller->LANGUAGES->getCurrentLang();

    $this->db->update(
      \FlexTimeResort\Allasok::DB_REQUEST_X,
      array(
        'admin_accept_id' => $admin,
        'accepted' => 1,
        'accepted_at' => NOW,
        'show_author_info' => ($show_author_info) ? 1 : 0,
        'finished' => 1
      ),
      sprintf("hashkey = '%s'", $hashkey)
    );

    $requestDatas = $this->db->query("SELECT allas_id, user_id, request_at, accepted_at FROM ".\FlexTimeResort\Allasok::DB_REQUEST_X." WHERE hashkey = '{$hashkey}'")->fetch(\PDO::FETCH_ASSOC);
    $allas = (new Allasok(array('controller' => $this->controller)))->load($requestDatas['allas_id']);

    $requestedUser = new User($requestDatas['user_id'], array('controller' => $this->controller));

    /**
    * Értesítők
    **/

    // ügyfélkapu értesítés

    (new Alerts(array('controller' => $this->controller)))->add(
      $requestDatas['user_id'],
      'allas_jelentkezes_hozzaferes_engedelyezes',
      $requestDatas['allas_id']
    );

    // e-mail értesítés
		$mail = new Mailer(
      $this->settings['page_title'],
      $this->settings['email_noreply_address'],
      $this->settings['mail_sender_mode']
    );
		$mail->add( $requestedUser->getEmail() );

    $this->smarty->assign( 'allas', $allas );
    $this->smarty->assign( 'user', $requestedUser );
    $this->smarty->assign( 'hashkey', $hashkey );
    $this->smarty->assign( 'request_at', $requestDatas['request_at'] );
    $this->smarty->assign( 'accepted_at', $requestDatas['accepted_at'] );

    $mail->setSubject( $this->controller->lang('MAIL_CP_ALLAS_JELENTKEZES_ENGEDELY_MEGADVA') );

		$mail->setMsg( $this->smarty->fetch( 'mails/'.$lang.'/request_user_accept.tpl' ) );
		$re = $mail->sendMail();

    return true;
  }

  public function get()
  {
    return $this->current_category;
  }

  public function walk()
  {
    if( !$this->tree_steped_item ) return false;

    if ($this->walk_step == $this->last_tree_id) {
      if($this->close_loop){
        $this->walk_step = 0;
        $this->current_category = false;
        return false;
      }
      $this->close_loop = true;
    }

    if( !$this->last_tree_id ){
      $this->last_tree_id = end($this->tree_ids);
      reset($this->tree_ids);
    }

    if ( !$this->current_category ) {
      reset($this->tree_ids);
      $this->walk_step = current($this->tree_ids);
    } else {
      $this->walk_step = next($this->tree_ids);
    }

    //$this->current_category = $this->walk_step;
    $this->current_category = $this->tree_steped_item[$this->walk_step];

    return true;
  }

  private function getLimit( $arg = array() )
	{
		$limit = array( 0, 25 );
		if( isset($arg['limit']) ) {
			$limit[1] = $arg['limit'];
		}
		$page = $arg['page'];
		if( isset($page) && $page > 0 ) {
		} else {
			$page = 1;
		}
		$limit[0] = $limit[1] * $page - $limit[1];
		$this->limit[0] = $limit[0] + 1;
		$this->limit[1] = $limit[0] + $limit[1];
		$this->current_page = $page;
		return $limit;
	}
}
?>
