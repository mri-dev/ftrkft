<?
namespace MailManager;

use MailManager\Mailer;

class Mails
{
	private $mailer = null;
	private $mail_subject 	= 'Subject';
	private $mail_title 	= 'Demo Mail';
	private $mail_from 		= 'demo@example.com';
	private $mail_fromname	= 'Demo Mailer';
	private $reply_to 		= 'demo@example.com';
	private $reply_toname	= 'Demo Mailer';

	private $controller 	= null;
	private $langkey 		= 'hu';
	private $passed_vars 	= false;

	public function __construct( $controller, $mail_template, $to = array(), $arg = array() )
	{
		$this->arg = $arg;
		$this->controller = $controller;

		$this->mail_subject 	= $this->controller->settings['page_title'];


		$this->mail_from 		= $this->controller->settings['email_noreply_address'];
		$this->mail_fromname 	= $this->controller->settings['page_title'];

		$this->reply_to 		= ( isset( $arg['from_email'] ) ) 	? $arg['from_email'] 	: $this->controller->settings['email_noreply_address'];
		$this->reply_toname 	= ( isset( $arg['from_name'] ) ) 	? $arg['from_name'] 	: $this->controller->settings['page_title'];

		$this->langkey = $this->controller->LANGUAGES->getCurrentLang();

		///////////////////////////////////

		$this->mailer = new Mailer(
			$this->mail_fromname,
			$this->mail_from,
			$this->controller->settings['email_noreply_address']
		);

		if( is_array($to) ) {
			foreach( $to as $r ) {
				$this->mailer->add( $r );
			}
		} else {
			$this->mailer->add( $to );
		}

		if( isset( $arg['infoMsg'] ) ) {
			$this->controller->smarty->assign( 'infoMsg', $arg['infoMsg'] );
		} else {
			$this->controller->smarty->assign( 'infoMsg', $this->controller->lang['lng_mail_donotreply'] );
		}


		// Argument pass
		if( !empty( $arg ) ) {
			foreach ( $arg as $key => $value ) {
				$this->controller->smarty->assign( $key, $value );
			}
		}

		// Összes paraméter
		if( $this->controller->smarty ) {
			$list = $this->controller->smarty->tpl_vars;

	       foreach ( $list as $key => $value ) {
	          $this->passed_vars[$key] = $value->value;
	       }
		}

		$this->{$mail_template}();

		return $this;
	}

	public function setSubject( $sub )
	{
		$this->mail_subject = $sub;

		return $this;
	}

	public function setFrom( $t )
	{
		$this->mail_from = $t;
		return $this;
	}

	public function setFromName( $t )
	{
		$this->mail_fromname = $t;
		return $this;
	}

	/*======================================
	=            MAIL TEMPLATES            =
	======================================*/

	// Felhasználó jelszó reszetelés
	private function password_reset()
	{
		$this->setSubject($this->controller->lang('MAIL_RESETPASSWORD_SUBJECT'));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/password_reset.tpl' ) );

		return $this;
	}

	// Felhasználó jelszó csere
	private function password_changed()
	{
		$this->setSubject($this->controller->lang('MAIL_CHANGEDPASSWORD_SUBJECT'));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/password_changed.tpl' ) );

		return $this;
	}

	// Felhasználó jelszó csere admin által
	private function password_changed_by_admin()
	{
		$this->setSubject($this->controller->lang('MAIL_ADMINCHANGEDPASSWORD_SUBJECT'));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/password_changed_by_admin.tpl' ) );

		return $this;
	}

	/*=====  End of MAIL TEMPLATES  ======*/

	private function testMsg()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_tesztmsg']);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/teszt.tpl' ) );

		return $this;
	}

	public function send()
	{
		$this->mailer->AddReplyTo( $this->reply_to, $this->reply_toname );
		$this->mailer->SetFrom( $this->mail_from, $this->mail_fromname );
		$this->mailer->setSubject( $this->mail_subject );
		$this->mailer->sendMail();
	}

	public function __destruct()
	{
		$this->mailer = null;
		$this->controller = null;
		$this->passed_vars = false;
	}
}

?>
