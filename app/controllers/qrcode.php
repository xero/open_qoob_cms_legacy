<?php
/**
 * QR code controller
 * generate a QR code and render it to the screen
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.02
 * @package app
 * @subpackage controllers
 */
class qrcode extends controller {
	
	function __construct() {
		$imports = array(
			"qr" => array(
				"type" => qoob_types::utility, 
				"class" => "qr", 
				"dir" => "QR/"));
		parent::__construct($imports);
	}
	
	function index() {
		$url = (DIRTY) ? explode(BASE_URL."?/qr/", RAW_URL) : explode(BASE_URL."qr/", RAW_URL);
		$url = isset($url[1]) ? $url[1] : QOOB_DOMAIN;
		$this->qr->generate($url, "L", 4, 5);
	}
}

?>