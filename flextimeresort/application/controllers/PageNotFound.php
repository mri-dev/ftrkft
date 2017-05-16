<?
class PageNotFound extends Controller  {
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Az oldal nem létezik';

			$this->out('404page', true);
			$this->out('hidehometop', true);

			header("HTTP/1.0 404 Not Found");
		}

		function __destruct(){
			// RENDER OUTPUT
			parent::bodyHead();					# HEADER
			$this->displayView( __CLASS__.'/index', true );		# CONTENT
			parent::__destruct();				# FOOTER
		}
	}

?>
