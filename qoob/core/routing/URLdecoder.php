<?php
/**
 * URL decoder
 * create the URL based constants needed for the qoob framework.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.1
 * @package qoob
 * @subpackage utils
 */
final class URLdecoder {
	/**
	 * constructor
	 */
	public function __construct() {
		$this->getURL();
	}
	/**
	 * the get url function defines url related variables.
	 * 
	 * BASE_URL  : the server root url
	 * RAW_URL   : the actual url entered
	 * CLEAN_URL : the raw url minus the querystring
	 * DIRTY     : a boolean of whether the querystring exists
	 * 
	 * and an array of the clean url values
	 * stored in the library under $url
	 *
	 */
	private function getURL() {		
		define("BASE_URL",		strtolower("http://".dirname($_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"])."/"));
		define("RAW_URL",		strtolower($this->cleanExtraction("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], false)));
		define("CLEAN_URL",		strtolower($this->cleanExtraction("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], true)));
		define("DIRTY",			$this->checkCleanliness(RAW_URL));
		define("QOOB_DOMAIN",	DIRTY ? BASE_URL."?/" : BASE_URL);
		
		$url = explode(BASE_URL, CLEAN_URL);
		
		if(isset($url[1])) {
			$url = @explode("/", $url[1]);
		} else {
			$url[0] = "";
		}
		
		library::catalog()->url = $url;
	}
	/**
	 * the clean extraction function takes a raw url and
	 * removes both leading and trailing slashes, the
	 * root file name (index.php) and the query string
	 * question mark and trailing slash.
	 *
	 * @param string $url
	 * @param boolean $clean 
	 * @return string
	 */
	private function cleanExtraction($url, $clean) {
		if($clean) $url = str_replace("?/", "", $url);
		if ('/' == substr($url, 0, 1)) $url = substr_replace($url, '', 0, 1); 
		if ('/' == substr($url, strlen($url)-1)) $url = substr_replace($url, '', strlen($url)-1); 
		return str_replace("index.php", "", $url);
	}
	/**
	 * the check cleanliness function returns a boolean
	 * depending on the existance of the query string.
	 *
	 * @param string $url
	 * @return booleans
	 */
	private function checkCleanliness($url) {
		if(library::catalog()->dirtyURLs) {
			return true;
		} else {
			return substr_count($url, "?")>0 ? true : false;
		}
	}
}

?>