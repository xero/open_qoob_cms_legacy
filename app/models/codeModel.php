<?php
/**
 * code model
 * SQL functions for loading code information from the database
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage models
 */
class codeModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * get repositories
	 *
	 * @return array
	 */
	public function getRepos() {
		return $this->DB->query("SELECT * FROM `code`;");
	}
	/**
	 * get repository by url
	 *
	 * @param string $url
	 * @return array
	 */
	public function getRepo($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `code` WHERE `url` = '$url' LIMIT 0, 1;");
	}
}

?>