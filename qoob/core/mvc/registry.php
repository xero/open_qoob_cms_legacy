<?php
/**
 * global registry class
 * passing a string name to the static register function will 
 * first check the previous existance of a class. if found 
 * it's instance is returned. otherwise, it will check if 
 * the class exists in the core classes folder, the core 
 * utilities folder, or the application controllers folder. 
 * if found, an instance of the class will be created, 
 * added to the object registry, then returned. if the db
 * boolean is passed the database singleton is returned.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.01
 * @package qoob
 * @subpackage core.mvc
 * @example registry::register(qoob_types::utility, 'xbd');
 */
final class registry {
	public static function register($type, $class, $path = "", $db = false) {
		//check existance
		if(qoob_registry::catalog()->$class !== NULL) {
			return qoob_registry::catalog()->$class;
		}
		
		switch ($type) {
			// --- core classes
			case "core":
				if(file_exists(QOOB_PATH."/core/".$path.$class.".php")) {
					require(QOOB_PATH."/core/".$path.$class.".php");
				} else {
					throw new Exception("failed to register core class: ".$class, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
				}
			break;
			// --- utility classes
			case "util":
				if(file_exists(QOOB_PATH."/utils/".$path.$class.".php")) {
					require(QOOB_PATH."/utils/".$path.$class.".php");
				}else {
					throw new Exception("failed to register utility class: ".$class, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
				}				
			break;
			// --- controller classes
			case "controller":
				if(file_exists(APP_PATH."/controllers/".$path.$class.".php")) {
					require(APP_PATH."/controllers/".$path.$class.".php");
				}else {
					throw new Exception("failed to register controller class: ".$class, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
				}				
			break;		
			// --- application classes
			case "app":
				if(file_exists(APP_PATH."/".$path.$class.".php")) {
					require(APP_PATH.$path.$class.".php");
				}else {
					throw new Exception("failed to register application class: ".$class, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
				}				
			break;
		}	
		
		//$object = null;
		if (!$db) {
			qoob_registry::catalog()->$class = new $class();
			$object = qoob_registry::catalog()->$class;
		} else {
			if($class == "mysql") {
				qoob_registry::catalog()->$class = mysql::getInstance();
				$object = qoob_registry::catalog()->$class;
			} else {
				//microsoft sql perhaps?
				throw new Exception("unknown database adapter", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		
		if(is_object($object))
		return $object;
	}
}
/**
 * qoob class types
 * 
 * used by the register function for correct import locations.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package qoob
 * @subpackage core.mvc
 */
final class qoob_types {
	/**
	 * @var application
	 */
	const application = "app";
	/**
	 * @var core
	 */
	const core = "core";
	/**
	 * @var utility
	 */
	const utility = "util";
	/**
	 * @var controller
	 */
	const controller = "controller";	
}

?>