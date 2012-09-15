<?php
/**
 * epic fail class
 * global error and exception handlers. 
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.01
 * @package qoob
 * @subpackage core.data
 */
final class epic_fail {
	/**
	 * constructor function
	 * setup global error and exception handlers. 
	 */
	public function __construct() {
		set_error_handler(array(&$this, 'error_handler'));
		set_exception_handler(array(&$this, 'exception_handler'));
	}
	/**
	 * exception handler
	 * create qoob pages for exceptions.
	 *
	 * @param object $exc the php exception object
	 */
	public static function exception_handler($exc) {
		$code = $exc->getCode();
		$msg = $exc->getMessage();		
		statusCodes::setHeader($code);
		
		switch ($code) {
			case statusCodes::HTTP_NOT_FOUND:
				$page = registry::register(qoob_types::controller , "error");
				$page->render(statusCodes::getMessage($code), $msg);
				exit;
			break;
		
			case statusCodes::HTTP_INTERNAL_SERVER_ERROR:
				$page = registry::register(qoob_types::controller , "error");
				$page->render(statusCodes::getMessage($code), $msg);
				exit;
			break;
			
			default:
				$page = registry::register(qoob_types::controller , "error");
				$page->render(statusCodes::getMessage($code), $msg);
			break;
		}
	}
	/**
	 * error handler
	 * create qoob pages for errors.
	 *
	 * @param int $num the error code
	 * @param string $str the error message
	 * @param string $file the file throwing the error
	 * @param int $line the line number in the file throwing the error
	 * @param array $ctx the context of the error
	 */	
	public static function error_handler($num, $str, $file, $line, $ctx) {
		statusCodes::setHeader(statusCodes::HTTP_INTERNAL_SERVER_ERROR);
		
		if(library::catalog()->debug === true) {		
			$page = registry::register(qoob_types::controller , "error");
			$msg = 'Sorry, a server error has occured.<pre>num  : ' . $num . '<br/>str  : ' . $str . '<br/>file : ' . $file . '<br/>line : ' . $line;
			$page->render(statusCodes::getMessage(500), $msg);
			exit;
		} else {
			$page = registry::register(qoob_types::controller , "error");
			$page->render(statusCodes::getMessage(500), "Sorry, a server error has occured.");
			exit;
		}
	}
}
?>