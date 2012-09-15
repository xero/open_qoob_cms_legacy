<?php
/**
 * qoob_config class
 * acts as install qoob_config file. it's uses for storing variables needed to
 * run the qoob in the global library (e.g. database connection information).
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package qoob
 * @subpackage core.data
 */
class qoob_config {
	/**
	 * data the array that holds the data
	 * @var array
	 */
	public $data = array();
	/**
	 * constructor magic method
	 * calls the init then execute functions
	 */
	public function __construct() {
		$this->init();
		$this->execute();
	}
	/**
	 * init function
	 * to be extended by the user to set variables in
	 */
	public function init() {
		// to be overridden
	}
	/**
	 * execute function
	 * adds data into the global library
	 * 
	 * @see library.php
	 */
	public function execute() {
		foreach ($this->data as $key=>$val) {
			library::catalog()->$key = $val;
		}
	}
}
?>