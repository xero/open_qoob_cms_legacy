<?php
/**
 * search controller
 * generate pages from the database
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
final class search extends controller {
	/**
	 * index function
	 * check if your search terms a valid (length, no the default phrase, etc),
	 * search the database, and display the results.
	 */
	public function index() {		
		$html = array();
		$html["title"] = 'search';
		$html["body"] = '';
		$html["meta"] = '';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = '';
		$html["script"] = '';

		$terms = trim(getRequest('search', 'post', FILTER_SANITIZE_STRING));

		if($terms == 'find something...') {
			$terms = '';
		}
		if($terms != '') {
			$results = 0;
			$sm = $this->model("searchModel");

			$codes = $sm->searchCode($terms);
			if(isset($codes[0])) {
				$codeResult = '';
				foreach ($codes as $code) {
					$codeResult .= '<a href="'.QOOB_DOMAIN.'code/'.$code["url"].'">'.$code["name"].'</a><br/><br/>';
					$results++;
				}
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Code',
					'subtitle' => '',
					'content' => $codeResult,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true);
			}

			$galleries = $sm->searchGallery($terms);
			if(isset($galleries[0])) {
				$galleryResult = '';
				foreach ($galleries as $gallery) {
					if(strpos($gallery["gallery_cat_id"], '.') > 0) {
						$parent = $sm->getParentGallery(intval($gallery["gallery_cat_id"]));
						if(isset($parent[0])) {
							$galleryResult .= '<a href="'.QOOB_DOMAIN.'portfolio/'.$parent[0]["url"].'/'.$gallery["url"].'">'.$gallery["name"].'</a><br/><br/>';
						}
					} else {
						$galleryResult .= '<a href="'.QOOB_DOMAIN.'portfolio/'.$gallery["url"].'">'.$gallery["name"].'</a><br/><br/>';
					}
					$results++;
				}
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Projects',
					'subtitle' => '',
					'content' => $galleryResult,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true);
			}

			$blogs = $sm->searchBlog($terms);
			if(isset($blogs[0])) {
				$blogResult = '';
				foreach ($blogs as $blog) {
					$blogResult .= '<a href="'.QOOB_DOMAIN.'blog/'.$blog["url"].'">'.$blog["title"].'</a><br/><br/>';
					$results++;
				}
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Blog Posts',
					'subtitle' => '',
					'content' => $blogResult,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true);
			}

			$pages = $sm->searchPages($terms);
			if(isset($pages[0])) {
				$pageResult = '';
				foreach ($pages as $page) {
					$pageResult .= '<a href="'.QOOB_DOMAIN.$page["url"].'">'.$page["title"].'</a><br/><br/>';
					$results++;
				}
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Pages',
					'subtitle' => '',
					'content' => $pageResult,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true);
			}

			if($results == 0) {
				$post = array(
					'mainCat' => '',
					'url' => '',
					'title' => 'Search',
					'subtitle' => 'Sorry...',
					'content' => 'No search results.',
					'comments' => 0
				);
				$html["body"] = $this->view("post", $post, true);
			}
		} else {
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => 'Search',
				'subtitle' => '',
				'content' => 'no search terms... no search results...',
				'comments' => 0
			);
			$html["body"] = $this->view("post", $post, true);
		}
		$this->view("pixelgraff", $html);
	}
}

?>