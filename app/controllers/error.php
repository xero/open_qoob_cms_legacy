<?php
/**
 * error controller
 * loaded a view, and show an error.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
class error extends controller {
	/**
	 * render function
	 * display the error screen
	 *
	 * @param string $title page title
	 * @param string $body error message body
	 */
	function render($title, $body) {
		$html = array();
		$html["title"] = $title;
		$html["meta"] = '';
		$html["sidebar"] = '';
		$html["selected"] = '';
		$html["script"] = '';
		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Error!',
			'subtitle' => $title,
			'content' => library::catalog()->debug ? $body : '',
			'comments' => 0
		);
		$post["content"] .= '<img src="'.BASE_URL.'/style/img/bomb.png" alt="error bomb" /><img src="'.BASE_URL.'/style/img/bomb.png" alt="error bomb" /><img src="'.BASE_URL.'/style/img/bomb.png" alt="error bomb" />';
		$html["body"] = $this->view("post", $post, true);
		$this->view("pixelgraff", $html);
	}
}
?>