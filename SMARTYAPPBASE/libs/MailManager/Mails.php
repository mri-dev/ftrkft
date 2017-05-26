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

		$this->langkey = \PortalManager\Lang::getCurrentLang();

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

	// Hamarosan lejáró hirdetések kiértesítése
	private function alerts_ad_expire()
	{
		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/'.__FUNCTION__.'.tpl' ) );

		return $this;
	}

	// Már lejárt hiretések kiértesítése
	private function alerts_ad_expired_renew()
	{
		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/'.__FUNCTION__.'.tpl' ) );

		return $this;
	}

	// Egyenleg jóváírás - átutalásos
	private function balance_transfer_topup()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_topup_tranfer']);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_transfer_topup.tpl' ) );

		return $this;
	}

	// Egyenleg jóváírás - átutalásos
	private function balance_transfer_addition()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_topup_addition']);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_transfer_topup.tpl' ) );

		return $this;
	}

	// Szolgáltatás megrendelés - Hirdetés kiemelés országos
	private function balance_JOBADTOPO7()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_services_order'], $this->passed_vars['service_title']));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_services_ad_up.tpl' ) );

		return $this;
	}

	// Szolgáltatás megrendelés - Hirdetés kiemelés megyei
	private function balance_JOBADTOPM7()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_services_order'], $this->passed_vars['service_title']));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_services_ad_up.tpl' ) );

		return $this;
	}

	// Szolgáltatás megrendelés - Hirdetés kiemelés országos
	private function balance_EMPCONTACTWATCH()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_services_order'], $this->passed_vars['service_title']));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_services_EMPCONTACTWATCH.tpl' ) );

		return $this;
	}

	// Hirdetés meghosszabbítása
	private function balance_ad_renew()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_ad_renew'], $this->passed_vars['ad']->getName()));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_ad_renew.tpl' ) );

		return $this;
	}

	// Szolgáltatás megrendelése
	private function balance_services_order_ad()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_services_order'], $this->passed_vars['service']->getTitle()));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/balance_services_order_ad.tpl' ) );

		return $this;
	}

	// Munkáltató üzenet küldése a munkavállaló felé
	private function message_by_appjob()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_message_by_appjob'], $this->passed_vars['ad']->getName()));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/message_by_appjob.tpl' ) );

		return $this;
	}

	// Munkáltató üzenet küldése a munkavállaló felé
	private function message_by_employer()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_message_by_employer'], $this->passed_vars['employer']['data']['nev']));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/message_by_employer.tpl' ) );

		return $this;
	}


	// Felhasználó jelszó reszetelés
	private function password_reset()
	{
		$this->setSubject($this->controller->controller->lang('MAIL_RESETPASSWORD_SUBJECT'));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/password_reset.tpl' ) );

		return $this;
	}

	// Felhasználó jelszó csere
	private function password_change()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_passwordchange']);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/password_change.tpl' ) );

		return $this;
	}

	// Jelentkezés állásra értesítő - felhasználó
	private function jobapplicant_to_user()
	{
		$this->setSubject(sprintf($this->controller->lang['lng_mailtemp_subject_jobapplicant_foruser'], $this->passed_vars['ad']->getName().' - '.$this->passed_vars['ad']->getEmployerName()));

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/jobapplicant_to_user.tpl' ) );

		return $this;
	}

	// Jelentkezés állásra értesítő - hirdető
	private function jobapplicant_to_employer()
	{
		$this->setSubject( sprintf( $this->controller->lang['lng_mailtemp_subject_jobapplicant_foremployer'], $this->passed_vars['ad']->getName().' álláshirdetés' )  );

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/jobapplicant_to_employer.tpl' ) );

		return $this;
	}


	// Europass insert email
	private function europass_insert()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_'.__FUNCTION__]);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/europass_cv_insert.tpl' ) );

		return $this;
	}

	// Europass update email
	private function europass_update()
	{
		$this->setSubject($this->controller->lang['lng_mailtemp_subject_'.__FUNCTION__]);

		$this->mailer->setMsg( $this->controller->smarty->fetch( 'mails/'.$this->langkey.'/europass_cv_update.tpl' ) );

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
