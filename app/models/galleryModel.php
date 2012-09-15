<?php
/**
 * gallery model
 * SQL functions for loading galleries and their images
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.2
 * @package app
 * @subpackage models
 */
class galleryModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * get main categories
	 *
	 * @return array
	 */
	public function getMainCats() {
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `live` = 1 AND `gallery_cat_id` REGEXP '^[0-9]+$';");
	}
	/**
	 * get sub categories
	 *
	 * @param int $id
	 * @return array
	 */
	public function getSubCats($id) {
		$id = $this->DB->sanitize($id);
		$less = intval($id);
		$greater = $less++;
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `gallery_cat_id` > $greater AND `gallery_cat_id` < $less ORDER BY `gallery_cat_id` ASC;");
	}
	/**
	 * get category by url
	 *
	 * @param string $url
	 * @return array
	 */
	public function getCat($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `url` = '$url' LIMIT 1;");
	}
	/**
	 * get images by category by id
	 *
	 * @param int $id
	 * @return array
	 */
	public function getCatImgs($id) {
		$id = $this->DB->sanitize($id);
		//return $this->DB->query("SELECT * FROM `gallery_meta` WHERE `meta_key` = 'category' AND `meta_val` = $id ORDER BY `meta_id` ASC;");
		return $this->DB->query("SELECT i.`url`, i.`filename`, i.`title`, i.`subtitle`, i.`excerpt`, i.`description` FROM `gallery_meta` as m INNER JOIN `gallery_images` as i ON m.`gallery_id` = i.`image_id` WHERE `meta_val` = $id AND i.`live` = 1 ORDER BY `gallery_id` ASC;");
	}
	/**
	 * get image by url
	 *
	 * @param string $url
	 * @return array
	 */
	public function getImg($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `gallery_images` WHERE `url` = '$url' LIMIT 1;");
	}
}

?>