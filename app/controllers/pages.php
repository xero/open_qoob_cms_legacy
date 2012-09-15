<?php
/**
 * pages controller
 * generate pages from the database
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
final class pages extends controller {
	/**
	 * index function
	 * render a page for a given url
	 */	
	public function index() {
		// --- set default is missing
		$url = library::catalog()->url[0];
		if ($url == "" || $url == "home") {
			$url = DEFAULT_ACTION;
		}
		
		// --- check url against database
		$pages = $this->model("pagesModel");
		$page = $pages->getPage($url);
		
		// --- create html array for rendering
		$html = array();

		// --- display page
		if(isset($page[0])) {
			$html["title"] = $page[0]['title'];
			$html["meta"] = html_entity_decode($page[0]['meta'], ENT_QUOTES).PHP_EOL;
			$html["meta"] .= '<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>';			
			$html["selected"] = $page[0]['mainCat'];
			$html["script"] = html_entity_decode($page[0]['script'], ENT_QUOTES).PHP_EOL;
			$html["script"] .= $this->view('initShadowboxJS', array(), true);
			$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>';
			$html["sidebar"] = ($page[0]['sidebar'] == '') ? $this->view("blog/sidebar_qr", array(), true) : html_entity_decode($page[0]['sidebar'], ENT_QUOTES);
			$post = array(
				'mainCat' => $page[0]['mainCat'],
				'url' => $page[0]['url'],
				'title' => $page[0]['title'],
				'subtitle' => $page[0]['subtitle'],
				'content' => html_entity_decode($page[0]['body'], ENT_QUOTES),
				'comments' => 0
			);
			$html["body"] = $this->view("post", $post, true);
		// --- display 404
		} else {
			throw new Exception("invalid page url", statusCodes::HTTP_NOT_FOUND);
		}		
		$this->view("pixelgraff", $html);
	}
}

?>