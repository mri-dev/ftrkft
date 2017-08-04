<?
use PortalManager\Messanger;
use MailManager\Mailer;

class cron extends Controller  {
	const MSG_SEND_LIMIT = 50;
	const MSG_SEND_WAITING_MS = 100;

	public $ctrl;
	public $lang;

	function __construct(){
		$this->ctrl = parent::__construct();
		$title = null;

		$this->lang = $this->ctrl->LANGUAGES->getCurrentLang();

	}

	/**
	* CRONTAB esemény
	* Minden 5 percben futó esemény
	**/
	public function unreadedMessagesAlert()
	{
		$messangers = new Messanger(array('controller' => $this->ctrl));

		$unreadeds = $messangers->collectAllUnreadedMessagesForEmailAlert(
			'user',
			$this->settings['ALERTS_MESSANGER_UNREADED_NOTIFY_EMAIL']
		);

		if ($unreadeds && count($unreadeds['user_ids']) > 0) {
			$send_loop = 0;
			foreach ((array)$unreadeds['data'] as $user_id => $user) {

				if($send_loop > self::MSG_SEND_LIMIT) break;

				$email = $user['user']['email'];

				if (!empty($email)) {
					$mail = new Mailer(
		        $this->settings['page_title'],
		        $this->settings['email_noreply_address'],
		        $this->settings['mail_sender_mode']
		      );
		  		$mail->add( $email );

					$this->smarty->assign( 'user', $user['user'] );
					$this->smarty->assign( 'items', $user['items'] );
					$this->smarty->assign( 'unreaded_num', $user['total_unreaded'] );

		      $mail->setSubject( $this->ctrl->lang('MAIL_SUBJECT_CRONALERT_UNREADEDMSG', array('db' => $user['total_unreaded'])));

					$body = $this->smarty->fetch( 'mails/'.$this->lang.'/cronalerts_messanger_unreaded.tpl');

					//echo $body;

		  		$mail->setMsg( $body );
		  		$re = $mail->sendMail();
					//print_r($re);
					if(!empty($re['success'])){
						foreach ((array)$user['items'] as $s) {
							foreach ((array)$s['items'] as $m) {
								$this->db->query("UPDATE ".\PortalManager\Messanger::DBTABLE_MESSAGES." SET user_alerted = 1 WHERE ID = ".$m['ID']);
							}
						}
					}
				}
				$send_loop++;
			 	usleep(self::MSG_SEND_WAITING_MS);
			}
		}
		/* * /
		echo '<pre>';
		print_r($unreadeds);
		echo '</pre>';
		/*  */
	}

	function __destruct(){
		// RENDER OUTPUT
	}
}

?>
