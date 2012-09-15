<?php
/**
 * coming soon controller
 * class to display a simple coming soon screen
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.011
 * @package app
 * @subpackage controllers
 */
final class soon extends controller {

	public function index() {
		$html["title"] = 'blog';
		$html["meta"] = '';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = '';
		$html["script"] = '';
		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Blog',
			'subtitle' => 'Coming soon...',
			'content' => '',
			'comments' => 0
		);
		$html["body"] = $this->view("post", $post, true);
		$this->view("pixelgraff", $html);
	}
}

?>