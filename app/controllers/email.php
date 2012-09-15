<?php
/**
 * email controller
 * class for sendig emails
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
final class email extends controller {
	/**
     * index function
     * validate the address, test the message against the antispam service, send the message.
	 */
	public function index() {
		$subject = "open qoob - website email";
		$to = library::catalog()->email;
		$name =  getRequest("txtName", "post", FILTER_SANITIZE_STRING);
		$from = getRequest("txtEmail", "post", FILTER_SANITIZE_EMAIL);
		$msg = getRequest("txtMsg", "post", FILTER_SANITIZE_STRING);
		$header = "From: webform@qoob.nu";

		//---validate email
		if (!filter_var($from, FILTER_VALIDATE_EMAIL) ) { 
			header("Location: ".QOOB_DOMAIN."contact_bad_email");
		} else {	
			//---check for blank vals
			if($from == "" || $msg == "") {
				header("Location: ".QOOB_DOMAIN."contact_missing");
			} else {
				//---spam check
				$this->library(qoob_types::utility, 'antispam');
				$vars = array(
					'user_ip'               => $_SERVER['REMOTE_ADDR'],
					'user_agent'            => $_SERVER['HTTP_USER_AGENT'],
					'referrer'              => $_SERVER['HTTP_REFERER'],
					'comment_author'        => $name,
					'comment_author_email'  => $from,
					'comment_content'       => $msg
				);
				if($this->antispam->test($vars)) {
					//---spam!
					header("Location: ".QOOB_DOMAIN."contact_spam");
				} else {
					//---send msg
					$msg = "from: ".$from." \n\n".$msg;
					mail($to, $subject, $msg, $header);
					header("Location: ".QOOB_DOMAIN."contact_thank_you");
				}
			}
		}
	}
}

?>