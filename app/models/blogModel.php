<?php
/**
 * blog model
 * SQL functions for loading blog information from the database
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.72
 * @package app
 * @subpackage models
 */
class blogModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * check category
	 * check if a category url exists in the database
	 *
	 * @param string $url
	 * @return array
	 */
	public function checkCategory($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_categories` WHERE `url` = '$url' LIMIT 0, 1");
	}
	/**
	 * check subcategory
	 * check if a subcategory exists in the database
	 *
	 * @param int $cat
	 * @param string $sub
	 * @return array
	 */
	public function checkSubCategory($cat, $sub) {
		$cat = $this->DB->sanitize($cat);
		$sub = $this->DB->sanitize($sub);
		$greater = $cat;
		$less = $cat + 1;
		$cats = $this->DB->query("SELECT * FROM `blog_categories` WHERE `blog_cat_id` >= $greater AND `blog_cat_id` < $less AND `url` = '$sub' LIMIT 0, 1");
		return $cats;
	}
	/**
	 * check post
	 * check if a post url exists in the database
	 *
	 * @param string $url
	 * @return array
	 */
	public function checkPost($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `url` = '$url' LIMIT 0, 1");
	}	
	/**
	 * get newest post
	 * fetches a given nuber of the newest blog posts
	 *
	 * @param int $count
	 * @return array
	 */
	public function getNewest($count) {
		$count = $this->DB->sanitize($count);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `live` = 1 ORDER BY `date` DESC LIMIT 0,$count");
	}
	/**
	 * get posts by page
	 * fetches a given nuber of the blog posts by page offset
	 *
	 * @param int $page
	 * @param int $per
	 * @return array
	 */
	public function getPosts($page, $per) {
		$page = $this->DB->sanitize($page);
		$per = $this->DB->sanitize($per);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `live` = 1 ORDER BY `date` DESC LIMIT $page,$per;");
	}
	/**
	 * get total post count
	 * returns the total number of posts 
	 * in the blog. used for paging functions
	 * 
	 * @return array 'total'
	 */
	public function getTotalPostCount() {
		return $this->DB->query("SELECT COUNT(`post_id`) AS `total` FROM `blog_posts` WHERE `live` = 1");
	}
	/**
	 * get post tags
	 * returns tags for a given post
	 *
	 * @param int $id
	 * @return array
	 */
	public function getPostTags($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT t.`name`, t.`url` FROM `blog_meta` m INNER JOIN `blog_tags` t ON m.`meta_val` = t.`tag_id` WHERE m.`blog_id` = $id AND m.`meta_key` = 'tag' = 1");
	}
	/**
	 * get post categories
	 * returns all categories for a given post
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getPostCats($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT c.`blog_cat_id`, c.`name`, c.`url` FROM `blog_meta` m INNER JOIN `blog_categories` c ON m.`meta_val` = c.`blog_cat_id` WHERE m.`blog_id` = $id AND m.`meta_key` = 'category'");
	}
	/**
	 * get category by id
	 * returns a category for a given id
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getCatByID($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT `name`, `url` FROM `blog_categories` WHERE `blog_cat_id` = $id LIMIT 0,1");
	}
	/**
	 * get post by url
	 * returns a post for a given url segment
	 * 
	 * @param string $url
	 * @return array
	 */
	public function getPostByURL($url) {
		$url = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `url` = '$url' AND `live` = 1 LIMIT 0,1;");
	}
	/**
	 * get posts by sub category
	 * returns posts from a given sub category
	 * page is the offset and per is the number
	 * of items returned.
	 *
	 * @param int $subid
	 * @param int $page
	 * @param int $per
	 * @return array
	 */
	public function getPostsBySubCat($subid, $page, $per) {
		$subid = $this->DB->sanitize($subid);
		$page = $this->DB->sanitize($page);
		$per = $this->DB->sanitize($per);
		return $this->DB->query("SELECT p.`post_id`, m.`meta_val`, p.`url`, p.`title`, p.`subtitle`, p.`excerpt`, p.`content`, p.`comments`, p.`date` FROM `blog_meta` as m INNER JOIN `blog_posts` as p ON m.`blog_id` = p.`post_id` WHERE m.`meta_key` = 'category' AND m.`meta_val` = $subid AND p.`live` = 1 GROUP BY p.`post_id` ORDER BY p.`date` DESC LIMIT $page, $per;");
	}
	/**
	 * get post count by sub category
	 * returns the total number of posts 
	 * in a sub category. used for paging functions
	 *
	 * @param int $subid
	 * @return array
	 */
	public function getSubCatPostCount($subid) {
		$subid = $this->DB->sanitize($subid);
		return $this->DB->query("SELECT COUNT(`blog_id`) as `total` FROM `blog_meta`  WHERE `meta_key` = 'category' AND `meta_val` = $subid;");
	}
	/**
	 * get posts by category
	 * returns posts from a given category
	 * page is the offset and per is the 
	 * number of items returned.
	 *
	 * @param int $catid
	 * @param int $page
	 * @param int $per
	 * @return array
	 */
	public function getPostsByCat($catid, $page, $per) {
		$catid = $this->DB->sanitize($catid);
		$page = $this->DB->sanitize($page);
		$per = $this->DB->sanitize($per);
		$greater = $catid;
		$less = $catid + 1;
		return $this->DB->query("SELECT p.`post_id`, m.`meta_val`, p.`url`, p.`title`, p.`subtitle`, p.`excerpt`, p.`content`, p.`comments`, p.`date` FROM `blog_meta` as m INNER JOIN `blog_posts` as p ON m.`blog_id` = p.`post_id` WHERE m.`meta_key` = 'category' AND m.`meta_val` >= $greater AND m.`meta_val` < $less AND p.`live` = 1 GROUP BY p.`post_id` ORDER BY p.`date` DESC LIMIT $page, $per;");
	}
	/**
	 * get post count by category
	 * returns the total number of posts 
	 * in a category. used for paging functions
	 *
	 * @param int $catid
	 * @return array
	 */
	public function getCatPostCount($catid) {
		$catid = $this->DB->sanitize($catid);
		$greater = $catid;
		$less = $catid + 1;
		return $this->DB->query("SELECT COUNT(DISTINCT(`blog_id`)) as `total` FROM `blog_meta`  WHERE `meta_key` = 'category' AND `meta_val` >= $greater AND `meta_val` < $less;");
	}
	/**
	 * check tag
	 * returns the id of a tag url
	 *
	 * @param string $tag
	 * @return array
	 */
	public function checkTag($tag) {
		$tag = $this->DB->sanitize($tag);
		return $this->DB->query("SELECT * FROM `blog_tags` WHERE `url` = '$tag' LIMIT 0,1;");
	}
	/**
	 * get posts by tag
	 * returns posts with a given tag.
	 * page is the offset and per is the 
	 * number of items returned.
	 *
	 * @param int $tag
	 * @param int $page
	 * @param int $per
	 * @return array
	 */
	public function getPostsByTag($tag, $page, $per) {
		$tag = $this->DB->sanitize($tag);
		$page = $this->DB->sanitize($page);
		$per = $this->DB->sanitize($per);		
		return $this->DB->query("SELECT p.`post_id`, m.`meta_val`, p.`url`, p.`title`, p.`subtitle`, p.`excerpt`, p.`content`, p.`comments`, p.`date` FROM `blog_meta` as m INNER JOIN `blog_posts` as p ON m.`blog_id` = p.`post_id` WHERE m.`meta_key` = 'tag' AND m.`meta_val` = $tag AND p.`live` = 1 GROUP BY p.`post_id` ORDER BY p.`date` DESC LIMIT $page, $per;");
	}
	/**
	 * get post count by tag
	 * returns the total number of posts 
	 * with a given tag. used for paging functions
	 *
	 * @param int $tag
	 * @return array
	 */
	public function getTagPostCount($tag) {
		$tag = $this->DB->sanitize($tag);
		return $this->DB->query("SELECT COUNT(`blog_id`) as `total` FROM `blog_meta`  WHERE `meta_key` = 'tag' AND `meta_val` = $tag;");		
	}
	/**
	 * returns all blog tags
	 * 
	 * @return array
	 */
	public function getTags() {
		return $this->DB->query("SELECT * FROM `blog_tags` ORDER BY `name` ASC;");		
	}	
	/**
	 * returns the newest tweet
	 * 
	 * @return array
	 */
	public function getNewestTweet() {
		return $this->DB->query("SELECT * FROM `twitter` ORDER BY `tweet_id` DESC LIMIT 0,1;");
	}
	/**
	 * get first post
	 * returns the first post
	 *
	 * @return array
	 */
	public function getFirstPost() {
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `live` = 1 ORDER BY `date` ASC LIMIT 0, 1");
	}
	/**
	 * get previous post
	 * returns the previous post
	 * relative to the given date
	 *
	 * @param int $date
	 * @return array
	 */
	public function getPrevPost($date) {
		$date = $this->DB->sanitize($date);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `date` < $date AND `live` = 1 ORDER BY `date` DESC LIMIT 0, 1");
	}
	/**
	 * get next post
	 * returns the next post
	 * relative to the given date
	 *
	 * @param int $date
	 * @return array
	 */
	public function getNextPost($date) {
		$date = $this->DB->sanitize($date);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `date` > $date AND `live` = 1 ORDER BY `date` ASC LIMIT 0, 1");
	}	
	/**
	 * returns all blog categories
	 * 
	 * @return array
	 */
	public function getBlogCategories() {
		return $this->DB->query("SELECT * FROM `blog_categories` ORDER BY `blog_cat_id` ASC;");
	}
}

?>