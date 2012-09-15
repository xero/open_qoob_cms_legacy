<?php
/**
 * open qoob main class
 * this class calls the bootstrapper function to
 * load the necessary base classes, then initilizes
 * the error handeling and main url routing classes.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.2
 * @package qoob
 */
final class open_qoob {
	/**
	 * open_qoob constructor
	 * loads the core classes with the the bootstrapper, 
	 * sets up error handeling, initilizes the config,
	 * then executes the url routing.
	 */
	public function __construct() {		
		/**
		 * load core classes
		 */
		$this->bootstrapper();
		/**
		 * start error handeling
		 */
		new epic_fail();
		/**
		 * create a new instance of the config class
		 */
		new config();
		/**
		 * URL decoder
		 */
		new URLdecoder();
		/**
		 * intrusion countermeasure extensions
		 */
		new ice();
		/**
		 * execute url logic
		 */ 
		new url();
	}
	/**
	 * bootstrapper function
	 * includes the required core classes
	 *
	 * @see /qoob/core/error/epic_fail.php
	 * @see /qoob/core/data/library.php
	 * @see /qoob/core/data/qoob_config.php
	 * @see /qoob/core/mvc/registry.php
	 * @see /qoob/core/mvc/controller.php
	 * @see /qoob/core/mvc/model.php
	 * @see /qoob/core/routing/URLdecoder.php
	 * @see /qoob/utils/statusCodes.php
	 * @see /qoob/utils/post.php
	 * @see /app/config.php
	 * @see /app/controllers/ice.php
	 * @see /app/controllers/url.php
	 */
	private function bootstrapper() {
		require_once QOOB_PATH.SLASH."core".SLASH."error".SLASH."epic_fail.php";
		require_once QOOB_PATH.SLASH."core".SLASH."data".SLASH."library.php";
		require_once QOOB_PATH.SLASH."core".SLASH."data".SLASH."qoob_config.php";
		require_once QOOB_PATH.SLASH."core".SLASH."mvc".SLASH."registry.php";
		require_once QOOB_PATH.SLASH."core".SLASH."mvc".SLASH."controller.php";
		require_once QOOB_PATH.SLASH."core".SLASH."mvc".SLASH."model.php";
		require_once QOOB_PATH.SLASH."core".SLASH."routing".SLASH."URLdecoder.php";
		require_once QOOB_PATH.SLASH."utils".SLASH."statusCodes.php";
		require_once QOOB_PATH.SLASH."utils".SLASH."post.php";
		require_once APP_PATH.SLASH."config.php";
		require_once APP_PATH.SLASH."controllers".SLASH."ice.php";
		require_once APP_PATH.SLASH."controllers".SLASH."url.php";
	}
}

?>