<?php
/**
 * cookie class
 * used to manipulate cookies
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package qoob
 * @subpackage core.users
 */
class cookie {
	
	private $time;
	private $path;
	private $domain;
	
	function __construct() {
		$this->time = time() + (7*24*60*60);
		$this->path = "/";
		$this->domain = "dev.cet.edu";
		echo ("hello cookie!<br/>");
	}
	
	function set($key, $val) {
		setcookie($key, $val, $this->time, $this->path, $this->domain, false, true);
	}

	function set_data($data = array()) {
		if(is_array($data)) {
			foreach($data as $key => $val) {
				$_COOKIE[$key] = $val;
			}
		}
	}
		
	function get($key) {
		if(!empty($_COOKIE[$key])) {
			return $_COOKIE[$key];
		} else {
			return false;
		}
	}	

	function _unset() {
		$_COOKIE = array();
	}
}
 
?>