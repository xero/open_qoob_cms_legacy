<?php
require(QOOB_PATH.SLASH.'core'.SLASH.'data'.SLASH.'iDB.php');
/**
 * model class
 * this base class has the necessary functions
 * to load a database adapter or a library class
 * (e.g. utilities).
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage core.mvc
 */
class model {
	/**
	 * database hook
	 * use this variable to access database functions like so:
	 * $yourModel->DB->query("SELECT * FROM `whatever` LIMIT 1");
	 *
	 * @var iDB
	 */
	public $DB;
//_____________________________________________________________________________________________
//                                                                              setup functions	
	/**
	 * model constructor
	 * change the database type by passing the string name
	 * to the constructor. mysql is the default.
	 *
	 * @param string $dbtype
	 */
	public function __construct($dbtype = "mysql") {
		$this->init($dbtype);
	}
	/**
	 * initilizer function
	 * used to load the correct database adapter into
	 * the qoob framework and your model class.
	 *
	 * @param string $adapter
	 */
	private function init($adapter) {
		switch ($adapter) {
			case "mysql":
				$this->DB = registry::register(qoob_types::core, "mysql", "data/", true);
			break;
			case "mssql":
				//$this->DB = registry::register(qoob_types::core, "mssql", "data/", true);
			break;
			default:
				throw new Exception("invalid database adapter", statusCodes::HTTP_INTERNAL_SERVER_ERROR);				
			break;
		}
	}
//_____________________________________________________________________________________________
//                                                                              loader function
	/**
	 * library loader function
	 * used to register classes into the qoob framework as public functions
	 * in your controller. use them in $this->class->method format.
	 *
	 * @param string $type
	 * @param string $class
	 * @param string $path
	 * @param boolean $singleton
	 */
	public function library($type = "", $class = "", $path = "", $singleton = false){
		if($type == "" || $class == "") return false;
		
		$publicVar = $class;
        $this->$publicVar = registry::register($type, $class, $path, $singleton);
	}	
}

?>