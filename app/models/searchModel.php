<?php
/**
 * search model
 * SQL functions for user searches
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage models
 */
class searchModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * search the pages table of the database
	 *
	 * @param string $terms
	 * @return array
	 */
	public function searchPages($terms) {
		$terms = $this->DB->sanitize($terms);
		return $this->DB->query("SELECT * FROM `pages` WHERE `title` LIKE '%$terms%' OR `subtitle` LIKE '%$terms%' OR `body` LIKE '%$terms%';");
	}
	/**
	 * search the blog table of the database
	 *
	 * @param string $terms
	 * @return array
	 */
	public function searchBlog($terms) {
		$terms = $this->DB->sanitize($terms);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `title` LIKE '%$terms%' OR `subtitle` LIKE '%$terms%' OR `excerpt` LIKE '%$terms%' OR `content` LIKE '%$terms%' AND `live` = 1;");
	}
	/**
	 * search the code table of the database
	 *
	 * @param string $terms
	 * @return array
	 */
	public function searchCode($terms) {
		$terms = $this->DB->sanitize($terms);
		return $this->DB->query("SELECT * FROM `code` WHERE `name` LIKE '%$terms%' OR `subtitle` LIKE '%$terms%' OR `description` LIKE '%$terms%' OR `readme` LIKE '%$terms%';");
	}
	/**
	 * search the gallery table of the database
	 *
	 * @param string $terms
	 * @return array
	 */
	public function searchGallery($terms) {
		$terms = $this->DB->sanitize($terms);
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `name` LIKE '%$terms%' OR `title` LIKE '%$terms%' OR `excerpt` LIKE '%$terms%' OR `description` LIKE '%$terms%' AND `live` = 1;");
	}
	/**
	 * get the parent gallery
	 *
	 * @param int $id
	 * @return array
	 */
	public function getParentGallery($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `gallery_cat_id` = $id LIMIT 1;");
	}
}

?>