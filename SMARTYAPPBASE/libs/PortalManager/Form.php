<?php
namespace PortalManager;

class Form {
	private $response = array();

	public function __construct( $exception_response )
	{
		$response = explode( "::", base64_decode( $exception_response ) );

		if( count( $response ) > 1 ) {
			$this->response[$response[0]] = array(
				'form' 	=> $response[0],
				'type' 	=> $response[1],
				'msg' 	=> $response[2],
				'error_field' => $response[3]
			);
		}
	}

	public function getFormId( $index )
	{
		return $this->response[ $index ]['form'];
	}

	public function getErrorFields( $index )
	{
		return explode(",", $this->response[ $index ]['error_field']);
	}

	public function hasError($index, $field )
	{
		$fields = $this->getErrorFields($index);

		if (!empty($fields) && in_array($field, $fields)) {
		 return true;
		}

		return false;
	}

	public function getType( $index )
	{
		return $this->response[ $index ]['type'];
	}

	public function getMsg( $index )
	{
		if( !$this->response[ $index ]['msg'] ) return false;
		$head = '';
		switch ($this->getType( $index )) {
			case 'success':
				$head = '<h4><i class="fa fa-check"></i> Rendszerüzenet</h4>';
			break;
		}
		return '<div class="alert alert-'.$this->getType( $index ).'">' .$head . (nl2br($this->response[ $index ]['msg'])) . '</div>';
	}

	public function getPost( $key = false, $array_item = false )
	{
		if( !$this->response) return false;

		//$post = json_decode( $_COOKIE['_form_post'], true );
		$post = json_decode($_SESSION['_form_post'][trim($_GET['tag'],"/")], true );

		if( $key ) {
			if( $array_item ) {
				return $post[$key][$array_item];
			} else {
				return $post[$key];
			}
		} else {
			return $post;
		}
	}

	public static function formDone( $msg = false, $form_id = false, $url = false, $anchor = false )
	{
		$to 	= $_POST['return'];
		$form 	= ($form_id) ? (int)$form_id : (int)$_POST['form'];

		if( $url ) {
			$to = $url;
		}

		//print_r($_GET);

		//return false;

		if( true ) {
			ob_start();

			setcookie( "_form_post", null, time() - 60 );
			unset($_SESSION['_form_post']);

			if( $msg ) {
				//$url = rtrim( $url, '/' );
				$xurl = explode('?',$url);
				$ret_get = '';

				if( count( $xurl ) > 1 ) {
					$ret_get = end($xurl).'&';
					$url = $xurl[0] .'?'. $ret_get;
				} else {
					$url .= '?';
				}
				$url = $url . 'response='.base64_encode( $form.'::success::'.$msg );
			}

			if( $anchor ) {
				$url .= '#'.$anchor;
			}

			header( 'Location: '.$url );

			ob_end_flush();
		}
	}

	public static function formError( $msg = false, $form_id = false, $url = false, $anchor = false )
	{
		$to 	= $_POST['return'];
		$form 	= ($form_id) ? (int)$form_id : (int)$_POST['form'];

		if( $url ) {
			$to = $url;
		}
		//return false;

		if( true ) {
			ob_start();

			setcookie( "_form_post", null, time() - 60 );
			unset($_SESSION['_form_post']);

			if( $msg ) {
				$url = rtrim( $url, '/' );
				$xurl = explode('?',$url);
				$ret_get = '';

				if( count( $xurl ) > 1 ) {
					$ret_get = end($xurl).'&';
					$url = $xurl[0] .'?'. $ret_get;
				} else {
					$url .= '?';
				}
				$url = $url . 'response='.base64_encode( $form.'::success::'.$msg );
			}

			if( $anchor ) {
				$url .= '#'.$anchor;
			}

			header( 'Location: '.$url );

			ob_end_flush();
		}

	}

}
?>
