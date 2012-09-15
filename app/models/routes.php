<?php
/**
 * routes model
 * SQL functions for url routing
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage models
 */
class routes extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * check route
	 * checks if a given url segment exists in the database.
	 *
	 * @param string $name url segment
	 * @param int $parent id number of parent url segment (default = 0)
	 */	
	public function checkRoute($name, $parent = 0) {
		$name = $this->DB->sanitize($name);
		$parent = $this->DB->sanitize($parent);
		return $this->DB->query("SELECT * FROM `routes` WHERE `name` = '$name' and `parent` = '$parent' LIMIT 0, 1");
	}
}

?>