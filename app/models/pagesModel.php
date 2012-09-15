<?php
/**
 * pages model
 * SQL functions for loading pages
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage models
 */
class pagesModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * get page function
	 * fetches a page's content.
	 *
	 * @param string $url
	 * @return array
	 */
	public function getPage($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `pages` WHERE `url` = '".$url."' LIMIT 1");
	}
}

?>