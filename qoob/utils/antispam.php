<?php
/**
 * antispam class
 * functions for using the Akismet spam protection system. 
 * check out the full api docs at: http://akismet.com/development/api/
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.1
 * @package qoob
 * @subpackage utils
 * @example $this->library(qoob_types::utility, 'antispam');
 *     $vars = array(
 *         'user_ip'               => $_SERVER['REMOTE_ADDR'],
 *         'user_agent'            => $_SERVER['HTTP_USER_AGENT'],
 *         'referrer'              => $_SERVER['HTTP_REFERER'],
 *         'comment_author'        => $name,
 *         'comment_author_email'  => $from,
 *         'comment_content'       => $msg
 *    );
 *     if($this->antispam->test($vars)) {
 *       //---spam!
 *       header("Location: ".QOOB_DOAMIN."spam");      
 *     } else {
 *       //---send email
 *       mail($to, $subject, $msg, $from_header);
 *       header("Location: ".QOOB_DOAMIN."thank_you");      
 *     }
 */
class antispam {
	private $akismetURL		= 'rest.akismet.com';
	private $akismetVersion	= '1.1';
	private $akismetKey		= false;
	private $siteURL		= false;
	private $siteName		= false;
	private $error			= false;
	private $ignore = array('HTTP_COOKIE', 
							'HTTP_X_FORWARDED_FOR', 
							'HTTP_X_FORWARDED_HOST', 
							'HTTP_MAX_FORWARDS', 
							'HTTP_X_FORWARDED_SERVER', 
							'REDIRECT_STATUS', 
							'SERVER_PORT', 
							'PATH',
							'DOCUMENT_ROOT',
							'SERVER_ADMIN',
							'QUERY_STRING',
							'PHP_SELF');

	/**
	 * constructor
	 * setup the akismet API key, site url and name.
	 *
	 * @param string $key API key
	 * @param string $site page being protected
	 * @param string $name user-agent string to prepend
	 */
	public function antispam($key = false, $site = false, $name = false) {
		if($key  == false) { 
			$key = library::catalog()->akismetKey;
		}
		if($site == false) { 
			$site = library::catalog()->siteURL;
		}
		if($name   == false) { 
			$name = library::catalog()->siteName;
		}
		
		//--save the information
		$this->akismetKey	= $key;
		$this->siteURL		= $site;
		$this->siteName		= $name;
	}

	/**
	 * test function
	 * test your string against the akismet database/ruleset
	 *
	 * @param string $vars info about the comment, in key/val pairs
	 * @return boolean true if it's spam, false if not
	 */
	public function test($vars) {
		if(!$this->login()) { return false; }
		$host = $this->akismetKey.".".$this->akismetURL;
		$url = "http://$host/".$this->akismetVersion."/comment-check";
		$result	= $this->send($vars, $host, $url);
		return ($result == "false") ? false : true;
	}

	/**
	 * spam function
	 * mark as spam
	 * 
	 * @param string $vars info about the comment, in key/val pairs
	 * @return boolean true on success
	 */
	public function spam($vars) {
		if(!$this->login()) { return false; }
		$host = $this->akismetKey.".".$this->akismetURL;
		$url = "http://$host/".$this->akismetVersion."/submit-spam";
		return $this->send($vars, $host, $url);
	}

	/**
	 * ham function
	 * mark as ham (not spam)
	 *
	 * @param string $vars info about the comment, in key/val pairs
	 * @return boolean true on success
	 */
	public function ham($vars) {
		if(!$this->login()) { return false; }
		$host = $this->akismetKey.".".$this->akismetURL;
		$url = "http://$host/".$this->akismetVersion."/submit-ham";
		return $this->send($vars, $host, $url);
	}

	/**
	 * login function
	 * login to the akismet with your API key
	 *
	 * @return boolean true on successful key verification
	 */
	private function login() {
		$args = array("key"  => $this->akismetKey);
		$host = $this->akismetURL;
		$url = "http://$host/" . $this->akismetVersion . "/verify-key";
		$valid = $this->send($args, $host, $url);
		return ($valid == "valid") ? true : false; 
	}

	/**
	 * send function
	 * make an akismet request
	 *
	 * @param array $args arguments to send to the akismet server
	 * @param string $host host to talk to
	 * @param string $url URL to send to the host
	 * @return mixed false on error or the server response
	 */
	private function send($args = "", $host = "", $url = "") {
		//---mandatory
		if(!(is_array($args))){ return false; }
		if($host == "")       { return false; }
		if($url  == "")       { return false; }
	
		$args["blog"] = $this->siteURL;
		//---remove any possibility revealing information
		$args = array_diff($args, $this->ignore);
		//---format request
		$content = "";
		foreach ($args as $key => $val) {
			$content .= "$key=".rawurlencode(stripslashes($val))."&";
		}

		//---create HTTP request
		$request = "POST $url HTTP/1.0\r\n"
				 . "Host: $host\r\n"
				 . "Content-Type: application/x-www-form-urlencoded\r\n"
				 . "User-Agent: " . $this->siteName . " | open.qoob.nu\r\n"
				 . "Content-Length: " . strlen($content) . "\r\n\r\n"
				 . "$content\r\n";
		$port = 80;
		$response = "";
		$errCode = 0;
		$errMsg = "";
		//---open a TCP file handle to the server and send data
		$fh = @fsockopen($host, $port, $errCode, $errMsg, 3);
		if($errCode != 0) {
			throw new Exception('failed to connect to: '.$host.'<br/>error code: '.$errCode.'<br/>error message: '.$errMsg, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
		}
		if($fh !== false) {
			@fwrite($fh, $request);
			while (!feof($fh)) { 
				$response .= fgets($fh, 1160); 
			}
			fclose($fh);	
			//---split header and footer
			$response = explode("\r\n\r\n", $response, 2);
		}
		return $response[1];
	}
}

?>