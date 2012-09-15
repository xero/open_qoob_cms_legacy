<?php
/**
 * url controller
 * class to mine the url into qoob controllers and actions.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.011
 * @package app
 * @subpackage controllers
 */
class url extends controller {
	/**
	 * constructor function
	 * mine the url into qoob controllers and actions. then load 
	 * the controller class and call the method action.
	 */
	public function __construct() {
		parent::__construct(null, false);

		// -- find controller
		if (library::catalog()->url[0] != "") {
			// --- nonslashed empty query string
			if (library::catalog()->url[0] == "?") {
				header("location: ".BASE_URL."?/");
			}
			// --- check against database
			$routes = $this->model("routes");
			$result = $routes->checkRoute(library::catalog()->url[0]);
			
			if (count($result) > 0) {
				$controller = $result[0]["controller"];
				$controllerURL = $result[0]["name"];
				$parent = $result[0]["route_id"];
			} else {
				throw new Exception("invalid controller url", statusCodes::HTTP_NOT_FOUND);
			}
		} else {
			$controller = DEFAULT_CONTROLLER;
			$controllerURL = DEFAULT_CONTROLLER;
		}
		define("QOOB_CONTROLLER", $controller);
		define("QOOB_CONTROLLER_URL", $controllerURL);

		// --- find action
		if (count(library::catalog()->url) > 1) {
			if($controller == "blog" || $controller == "code" || $controller == "gallery" || $controller == "feeds" || $controller == "qrcode") {
				if($controller == "blog") {
					switch (library::catalog()->url[1]) {
						case 'page':
							$action = DEFAULT_ACTION;
						break;
						case 'tag':
							$action = 'tag';
						break;
						default:
							$found = false;
							$bm = $this->model("blogModel");
							$result = $bm->checkCategory(library::catalog()->url[1]);
							if (count($result) > 0) {
								$action = "category";
								$found = true;
							} 
							$result = $bm->checkPost(library::catalog()->url[1]);
							if (count($result) > 0) {
								$action = "post";
								$found = true;
							}							
							if(!$found) {
								throw new Exception("invalid controller url", statusCodes::HTTP_NOT_FOUND);
							}							
						break;
					}
				} else {
					$action = DEFAULT_ACTION;
				}
			} else {
				$routes = $this->model("routes");
				$result = $routes->checkRoute(library::catalog()->url[1], $parent);
				if (count($result) > 0) {
					$action = $result[0]["controller"];
				} else {
					throw new Exception("invalid method url", statusCodes::HTTP_NOT_FOUND);
				}			
			}
		} else {
			$action = DEFAULT_ACTION;
		}
		
		define("QOOB_ACTION", $action);
		
		$page = registry::register(qoob_types::controller, $controller);

		if (count(library::catalog()->url) > 2) {
			$params = array_slice(library::catalog()->url, 2);
			$page->$action($params);
		} else {
			$page->$action();
		}
	}	
}

?>