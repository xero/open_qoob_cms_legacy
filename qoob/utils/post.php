<?php
/**
 * get request function
 * anonymous functions for securely dealing with post/get/request data.
 * trying to force users to use the php filter_var method on all user requests.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage utils
 * @category user data
 */
function getRequest($name = '', $type = 'post', $filter = '') {
	$var = '';
	switch ($type) {
		case "post":
			if(isset($_POST[$name])) {
				$var = $_POST[$name];
			}
		break;
		case "get":
			if(isset($_GET[$name])) {
				$var = $_GET[$name];
			}			
		break;
		case "request":
			if(isset($_REQUEST[$name])) {
				$var = $_REQUEST[$name];
			}
		break;
		default:
			/** 
			 * @todo throw error? return an error value?
			 */
		break;
	}
	if(isset($filter)) {
		switch ($filter) {
			//---sanitize filters
			case FILTER_SANITIZE_STRING:
				$var = filter_var($var, FILTER_SANITIZE_STRING);
			break;
			case FILTER_SANITIZE_NUMBER_INT:
				$var = filter_var($var, FILTER_SANITIZE_NUMBER_INT);
			break;
			case FILTER_SANITIZE_NUMBER_FLOAT:
				$var = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			break;
			case FILTER_SANITIZE_EMAIL:
				$var = filter_var($var, FILTER_SANITIZE_EMAIL);
			break;
			case FILTER_SANITIZE_URL:
				$var = filter_var($var, FILTER_SANITIZE_URL);
			break;
			case FILTER_SANITIZE_ENCODED:
				$var = filter_var($var, FILTER_SANITIZE_ENCODED);
			break;
			case FILTER_SANITIZE_SPECIAL_CHARS:
				$var = filter_var($var, FILTER_SANITIZE_SPECIAL_CHARS);
			break;
			//---validate filters
			case FILTER_VALIDATE_BOOLEAN:
				$var = filter_var($var, FILTER_VALIDATE_BOOLEAN);
			break;	
			case FILTER_VALIDATE_INT:
				$var = filter_var($var, FILTER_VALIDATE_INT);
			break;	
			case FILTER_VALIDATE_FLOAT:
				$var = filter_var($var, FILTER_VALIDATE_FLOAT);
			break;	
			case FILTER_VALIDATE_EMAIL:
				$var = filter_var($var, FILTER_VALIDATE_EMAIL);
			break;	
			case FILTER_VALIDATE_URL:
				$var = filter_var($var, FILTER_VALIDATE_URL);
			break;	
			case FILTER_VALIDATE_IP:
				$var = filter_var($var, FILTER_VALIDATE_IP);
			break;	
			case FILTER_VALIDATE_REGEXP:
				$var = filter_var($var, FILTER_VALIDATE_REGEXP);
			break;	
			//---validate filters
			default:
			/** 
			 * @todo throw error? return an error value?
			 */
			break;
		}
	}
	return $var;
}

?>