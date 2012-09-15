<?php
/**
 * singleton class
 * a class that can only be instantiated once. every
 * subsequent request will return the same instance 
 * of the class. to be extended. 
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage core.data
 */
class singleton {
	/**
	 * @private
	 * @static
	 * @var object $instance a single instance of the singleton class
	 */
	private static $instance;
	/**
	 * @var array $data the data held in the singleton class
	 */
	public $data = array();
	/**
	 * null constructor
	 */	
	private function __construct() {}
	/**
	 * @static
	 * @return object
	 */	
	public static function catalog() {
		if(!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class;
		}
		return self::$instance;
	}
	/**
	 * set data
	 * add data to the singleton array
	 *
	 * @param string|int $key the array key
	 * @param mixed $value the array value 
	 * @return boolean
	 */
	public function __set($key, $value) {
		$this->data[$key] = $value;
		return true;
	}
	/**
	 * get data
	 * retrieve data from the singleton array
	 *
	 * @param string|int $key the array key
	 * @return mixed
	 */
	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			return $this->data[$key];
		} else {
			return null;
		}
	}
	/**
	 * clone magic method
	 * calling clone on a singleton will throw an error,
	 * since the point of a singleton is to only have a single instance of it.
	 *
	 * @return exception
	 */
	public function __clone() {
		 throw new Exception("library cloning is not allowed!", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
	}
}
/**
 * qoob registry
 * a singleton library used to store instances 
 * of classes in the open qoob framework.
 * @see /qoob/core/mvc/registry.php
 */
final class qoob_registry extends singleton {}
/**
 * library class
 * a singleton library used to store global variables
 * in the open qoob framework. 
 * @see /qoob/core/data/qoob_config.php
 */
final class library extends singleton {}

?>