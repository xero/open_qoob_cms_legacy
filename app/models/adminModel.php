<?php
/**
 * admin model
 * SQL functions for adding information to the database from the backend.
 * functions for adding, modifying, and deleting records for administrators,
 * pages, blogs, code, and galleries.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 7.2
 * @package app
 * @subpackage models
 */
class adminModel extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */	
	public function __construct() {
		parent::__construct("mysql");
	}
//________________________________________________________________________________________________________________
//                                                                                                          admins	
	/**
	 * checks a submitted username and password against the database
	 *
	 * @param string $username
	 * @return boolean
	 */
	public function checkUser($username) {
		$username = $this->DB->sanitize($username);
		return $this->DB->query("SELECT * FROM `admin` WHERE `username` = '$username' LIMIT 1;");
	}
	/**
	 * checks an email against the admin table of the database
	 *
	 * @param string $email
	 * @return boolean
	 */
	public function checkAdmin($email) {
		$email = $this->DB->sanitize($email);
		return $this->DB->query("SELECT * FROM `admin` WHERE `email` = '$email' LIMIT 1;");
	}	
	/**
	 * adds an administrator to the database
	 *
	 * @param array $args
	 */
	public function addAdmin($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		$this->DB->query("INSERT INTO `admin` (`admin_id`, `name`, `username`, `password`, `email`) VALUES (NULL, '".$args["name"]."', '".$args["user"]."', '".$args["pass"]."', '".$args["email"]."');", false);
	}
	/**
	 * returns all admins
	 *
	 * @return array
	 */
	public function getAllAdmins() {
		return $this->DB->query("SELECT * FROM `admin` ORDER BY `admin_id` ASC;");
	}
	/**
	 * returns an admin by ID
	 *
	 * @param int $id
	 * @return array
	 */
	public function getAdminByID($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `admin` WHERE `admin_id` = '$id' LIMIT 0,1;");
	}
	/**
	 * modify the properties of an admin
	 * @param array $args
	 */
	public function modAdmin($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		$this->DB->query("UPDATE `admin` SET `name` = '".$args["name"]."', `username` = '".$args["user"]."', `password` = '".$args["pass"]."', `email` = '".$args["email"]."' WHERE `admin_id` = '".$args["admin_id"]."' LIMIT 1;", false);
	}
	/**
	 * delete an admin by ID
	 *
	 * @param int $id
	 */
	public function deleteAdmin($id) {
		$id   = $this->DB->sanitize($id);
		$this->DB->query("DELETE FROM `admin` WHERE `admin_id` = '$id' LIMIT 1;", false);
	}
//________________________________________________________________________________________________________________
//                                                                                                           pages	
	/**
	 * check to see if a route for a page url is used or not.
	 *
	 * @param string $url
	 */
	public function checkPageRoute($url) {
		$url   = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `routes` WHERE `name` = '$url' and `parent` = '0' LIMIT 0, 1");
	}
	/**
	 * check to see if a page url has change during page modification
	 *
	 * @param int $id
	 * @param string $url
	 * @return boolean true = url change / false = no change
	 */
	public function checkPageRouteChange($id, $url) {
		$id    = $this->DB->sanitize($id);
		$url   = $this->DB->sanitize($url);
		$result = $this->DB->query("SELECT * FROM `pages` WHERE `id` = ".$id." AND `url` = '".$url."';");
		return isset($result[0]) ? false : true;
	}
	/**
	 * get page and route ids.
	 *
	 * @param string $url
	 */
	public function getPageRouteIDs($url) {
		$url 	= $this->DB->sanitize($url);
		$route 	= $this->DB->query("SELECT `route_id` FROM `routes` WHERE `name` = '$url' and `parent` = '0' LIMIT 0, 1");
		$page 	= $this->DB->query("SELECT `id` FROM `pages` WHERE `url` = '$url' LIMIT 0, 1");
		if(!isset($route[0]) or !isset($page[0])) {
			return false;
		} else {
			return array('r_id' => $route[0]['route_id'], 'p_id' => $page[0]['id']);
		}
	}
	/**
	 * returns all pages id and url fields.
	 */
	public function getPages() {
		return $this->DB->query("SELECT `id`, `url` FROM `pages` ORDER BY `id` ASC");		
	}
	/**
	 * returns a single page from the database
	 *
	 * @param int $id
	 */
	public function getPage($id) {
		$id 	= $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `pages` WHERE `id` = $id LIMIT 0,1");			
	}
	/**
	 * add a page and a route to the page to the database.
	 *
	 * @param array $args
	 */
	public function addPage($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		//add route
		$this->DB->query("INSERT INTO `routes` (`route_id`, `name`, `controller`, `parent`) VALUES (NULL, '".$args['url']."', 'pages', '0');", false);
		//add page
		$this->DB->query("INSERT INTO `pages` (`id`, `url`, `title`, `subtitle`, `body`, `script`, `mainCat`, `meta`, `sidebar`) VALUES (NULL, '".$args['url']."', '".$args['title']."', '".$args['subtitle']."', '".$args['body']."', '".$args['script']."', '".$args['selected']."', '".$args['meta']."', '".$args['sidebar']."');", false);
		return true;
	}
	/**
	 * modify a page and it's route to the page to the database.
	 *
	 * @param array $args
	 */
	public function modPage($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		//update route
		$this->DB->query("UPDATE `routes` SET `name` = '".$args['url']."' WHERE `route_id` = ".$args['route_id']." LIMIT 1 ;", false);
		//update page
		$this->DB->query("UPDATE `pages` SET `url` = '".$args['url']."', `title` = '".$args['title']."', `subtitle` = '".$args['subtitle']."', `body` = '".$args['body']."', `script` = '".$args['script']."', `mainCat` = '".$args['selected']."', `meta` = '".$args['meta']."', `sidebar` = '".$args['sidebar']."' WHERE `id` = ".$args['page_id']." LIMIT 1;", false);
		return true;
	}
	/**
	 * deletes a page and a route from the database.
	 *
	 * @param array $args
	 */
	public function delPage($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		//add route
		$this->DB->query("DELETE FROM `routes` WHERE `route_id` = ".$args['route_id']." LIMIT 1;", false);
		//add page
		$this->DB->query("DELETE FROM `pages` WHERE `id` = ".$args['page_id']." LIMIT 1;", false);
		return true;
	}
//________________________________________________________________________________________________________________
//                                                                                                            blog	
	/**
	 * returns a blog post by id
	 *
	 * @param int $id
	 */
	public function getBlogByID($id) {
		$id 	= $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `blog_posts` WHERE `post_id` = $id LIMIT 1;");
	}
	/**
	 * return all blog posts
	 * 
	 */
	public function getAllBlogPosts() {
		return $this->DB->query("SELECT * FROM `blog_posts` ORDER BY `date` DESC;");
	}
	/**
	 * check if a blog url is used or not
	 * 
	 * @param string $url
	 * @return boolean false is url is used
	 */
	public function checkBlogRoute($url) {
		$url 	= $this->DB->sanitize($url);
		$result = $this->DB->query("SELECT * FROM `blog_posts` WHERE `url` = '$url' LIMIT 1;");
		return isset($result[0]) ? false : true;
	}
	/**
	 * adds a blog category to the database
	 *
	 * @param string $name
	 * @param string $url
	 * @param number $parent
	 */
	public function addBlogCategory($name, $url, $parent) {
		$name 	= $this->DB->sanitize($name);
		$url 	= $this->DB->sanitize($url);
		$parent = $this->DB->sanitize($parent);
		$this->DB->query("INSERT INTO `blog_categories` (`qoob_cat_id`, `blog_cat_id`, `name`, `url`) VALUES (NULL, '$parent', '$name', '$url');", false);
		$id = $this->DB->insertID();
		$return = "";
		//---update the blog category id if the parent was 0
		if($parent == 0) {
			$this->DB->query("UPDATE `blog_categories` SET `blog_cat_id` = '$id' WHERE `qoob_cat_id` = $id;", false);
		} else {
			//---create bounds
			$next = $parent+1;
			//---get the count of that sub category
			$count = $this->DB->query("SELECT COUNT(`qoob_cat_id`) as 'count' FROM  `blog_categories` WHERE `blog_cat_id` > $parent AND `blog_cat_id` < $next;");
			//---generate new category id
			$count = $count[0]["count"];
			$catID = $parent + (($count + 1) * 0.001);
			$this->DB->query("UPDATE `blog_categories` SET `blog_cat_id` = '$catID' WHERE `qoob_cat_id` = $id;", false);
		}
	}
	/**
	 * returns all blog categories
	 * 
	 * @return array
	 */
	public function getBlogCategories() {
		return $this->DB->query("SELECT * FROM `blog_categories` ORDER BY `blog_cat_id` ASC;");
	}
	/**
	 * check if a blog category name or url already exist
	 * 
	 * @param string $name
	 * @param string $url
	 * @return array
	 */
	public function checkBlogCategory($name, $url) {
		$name 	= $this->DB->sanitize($name);
		$url 	= $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_categories` WHERE `name` = '$name' OR `url` = '$url';");
	}
	/**
	 * add a new blog tag to the database
	 * 
	 * @param string $name
	 * @param string $url
	 */
	public function addBlogTag($name, $url) {
		$name 	= $this->DB->sanitize($name);
		$url 	= $this->DB->sanitize($url);
		$this->DB->query("INSERT INTO `blog_tags` (`tag_id`, `name`, `url`, `tag_count`) VALUES (NULL, '$name', '$url', '0');", false);
	}
	/**
	 * check if a blog tag name or url already exist
	 * 
	 * @param string $name
	 * @param string $url
	 * @return array
	 */
		public function checkBlogTag($name, $url) {
		$name 	= $this->DB->sanitize($name);
		$url 	= $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_tags` WHERE `name` = '$name' OR `url` = '$url';");
	}
	/**
	 * returns all blog tags
	 * 
	 * @return array
	 */
	public function getBlogTags() {
		return $this->DB->query("SELECT * FROM `blog_tags` ORDER BY `name` ASC;");		
	}
	/**
	 * add a blog post to the database
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $subtitle
	 * @param string $excerpt
	 * @param string $body
	 * @param int	 $epoch
	 * @param int	 $live
	 * @return int	 $id
	 */
	public function addBlogPost($url, $title, $subtitle, $excerpt, $body, $epoch, $live) {
		$url 		= $this->DB->sanitize($url);
		$title 		= $this->DB->sanitize($title);
		$subtitle 	= $this->DB->sanitize($subtitle);
		$excerpt 	= $this->DB->sanitize($excerpt);
		$body 		= $this->DB->sanitize($body);
		$epoch 		= $this->DB->sanitize($epoch);
		$live 		= $this->DB->sanitize($live);
		$this->DB->query("INSERT INTO `blog_posts` (`post_id`, `url`, `title`, `subtitle`, `excerpt`, `content`, `comments`, `date`, `live`) VALUES (NULL,  '$url',  '$title',  '$subtitle',  '$excerpt',  '$body',  '0',  '$epoch',  '$live');", false);

		return $this->DB->insertID();
	}	
	/**
	 * modify a blog post int the database
	 * 
	 * @param int	 $post_id
	 * @param string $url
	 * @param string $title
	 * @param string $subtitle
	 * @param string $excerpt
	 * @param string $body
	 * @param int	 $epoch
	 * @param int	 $live
	 * @return int	 $id
	 */
	public function modBlogPost($post_id, $url, $title, $subtitle, $excerpt, $body, $epoch, $live) {
		$post_id	= $this->DB->sanitize($post_id);
		$url		= $this->DB->sanitize($url);
		$title 		= $this->DB->sanitize($title);
		$subtitle	= $this->DB->sanitize($subtitle);
		$body		= $this->DB->sanitize($body);
		$excerpt	= $this->DB->sanitize($excerpt);
		$epoch		= $this->DB->sanitize($epoch);
		$live		= $this->DB->sanitize($live);
		$this->DB->query("UPDATE `blog_posts` SET `url` = '$url', `title` = '$title', `subtitle` = '$subtitle', `excerpt` = '$excerpt', `content` = '$body', `date` = '$epoch', `live` = '$live' WHERE `post_id` = $post_id LIMIT 1;", false);
	}
	/**
	 * delete blog post from the database
	 *
	 * @param int $id
	 */
	public function delBlogPost($id) {
		$id = $this->DB->sanitize($id);
		//find post meta
		$meta = $this->DB->query("SELECT * FROM `blog_meta` WHERE `blog_id` = $id;", true);
		if(isset($meta[0])) {
			//deccrement each tag's count
			foreach($meta as $obj){ 
				if($obj["meta_key"] == "tag") {
					$count = $this->DB->query("SELECT `tag_count` FROM `blog_tags` WHERE `tag_id` = ".$obj["meta_val"]." LIMIT 1;");
					if(isset($count[0])) {
						$count = intval($count[0]["tag_count"]);
						$count--;
						$this->DB->query("UPDATE `blog_tags` SET `tag_count` = '$count' WHERE `tag_id` = ".$obj["meta_val"].";", false);
					}
				}
			}
		}
		//delete meta
		$this->DB->query("DELETE FROM `blog_meta` WHERE `blog_id` = $id;", false);
		//delete post
		$this->DB->query("DELETE FROM `blog_posts` WHERE `post_id` = $id LIMIT 1;", false);
		return true;
	}
	/**
	 * add blog meta data to the database
	 * 
	 * @param int	 $id
	 * @param string $key
	 * @param string $val
	 */
	public function addBlogMeta($id, $key, $val) {
		$id 	= $this->DB->sanitize($id);
		$key 	= $this->DB->sanitize($key);
		$val	= $this->DB->sanitize($val);
		$this->DB->query("INSERT INTO `blog_meta` (`meta_id`, `blog_id`, `meta_key`, `meta_val`) VALUES (NULL , '$id', '$key', '$val');", false);
		//increment the tag's count
		if($key == "tag") {
			$count = $this->DB->query("SELECT `tag_count` FROM `blog_tags` WHERE `tag_id` = $val LIMIT 1;");
			if(isset($count[0])) {
				$count = intval($count[0]["tag_count"]);
				$count++;
				$this->DB->query("UPDATE `blog_tags` SET `tag_count` = '$count' WHERE `tag_id` = '$val';", false);
			}
		}
	}	
	/**
	 * delete blog meta data to the database
	 * 
	 * @param int	 $id
	 * @param string $key
	 * @param string $val
	 */
	public function delBlogMeta($id, $key, $val) {
		$id 	= $this->DB->sanitize($id);
		$key 	= $this->DB->sanitize($key);
		$val	= $this->DB->sanitize($val);
		$this->DB->query("DELETE FROM `blog_meta` WHERE `blog_id` = '$id' AND `meta_key` = '$key' and `meta_val` = '$val';", false);
		//deccrement the tag's count
		if($key == "tag") {
			$count = $this->DB->query("SELECT `tag_count` FROM `blog_tags` WHERE `tag_id` = $val LIMIT 1;");
			if(isset($count[0])) {
				$count = intval($count[0]["tag_count"]);
				$count--;
				$this->DB->query("UPDATE `blog_tags` SET `tag_count` = '$count' WHERE `tag_id` = $val;", false);
			}
		}		
	}		
	/**
	 * returns a blog post and it's meta by id
	 *
	 * @param int $id
	 */
	public function getBlogAndMetaByID($id) {
		$id 	= $this->DB->sanitize($id);
		$post = $this->DB->query("SELECT * FROM `blog_posts` WHERE `post_id` = $id LIMIT 1;");
		if(isset($post[0])) {
			$tags = $this->DB->query("SELECT t.`tag_id` FROM `blog_meta` m INNER JOIN `blog_tags` t ON m.`meta_val` = t.`tag_id` WHERE m.`blog_id` = $id AND m.`meta_key` = 'tag';");
			$taglist = '';
			foreach ($tags as $key => $value) {
				$taglist .= $value["tag_id"].",";
			}
			$taglist = substr($taglist, 0, -1);
			$post[0]["tags"] = $taglist;

			$cats = $this->DB->query("SELECT c.`blog_cat_id` FROM `blog_meta` m INNER JOIN `blog_categories` c ON m.`meta_val` = c.`blog_cat_id` WHERE m.`blog_id` = $id AND m.`meta_key` = 'category';");
			$catlist = '';
			foreach ($cats as $key => $value) {
				$catlist .= $value["blog_cat_id"].",";
			}
			$catlist = substr($catlist, 0, -1);
			$post[0]["cats"] = $catlist;
		}
		return $post;
	}	
//________________________________________________________________________________________________________________
//                                                                                                         gallery	
	/**
	 * returns all gallery categories
	 * 
	 * @return array
	 */
	public function getGalleryCategories() {
		return $this->DB->query("SELECT * FROM `gallery_categories` ORDER BY `gallery_cat_id` ASC;");
	}
	/**
	 * returns a gallery category by id
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getGalleryCatByID($id) {
		$id 	= $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `gallery_categories` WHERE `gallery_cat_id` = $id LIMIT 1;");
	}
	/**
	 * check if a gallery category name or url already exists
	 * 
	 * @param string $name
	 * @param string $url
	 * @return array
	 */
	public function checkGalleryCategory($name, $url) {
		$name 	= $this->DB->sanitize($name);
		$url 	= $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `blog_categories` WHERE `name` = '$name' OR `url` = '$url';");
	}
	/**
	 * adds a gallery category to the database
	 *
	 * @param number $parent
	 * @param string $name
	 * @param string $url
	 * @param string $title
	 * @param string $excerpt
	 * @param string $description
	 * @param number $live
	 */
	public function addGalleryCategory($parent, $name, $url, $title, $excerpt, $description, $live) {
		$parent 		= $this->DB->sanitize($parent);
		$name 			= $this->DB->sanitize($name);
		$url 			= $this->DB->sanitize($url);
		$title 			= $this->DB->sanitize($title);
		$excerpt 		= $this->DB->sanitize($excerpt);
		$description 	= $this->DB->sanitize($description);
		$live 			= $this->DB->sanitize($live);
		$this->DB->query("INSERT INTO `gallery_categories` (`qoob_cat_id`, `gallery_cat_id`, `name`, `url`, `title`, `excerpt`, `description`, `mainImg`, `live`) VALUES (NULL, '$parent', '$name', '$url', '$title', '$excerpt', '$description', '0', '$live');", false);

		$id = $this->DB->insertID();
		$return = "";
		//---update the gallery category id if the parent was 0
		if($parent == 0) {
			$this->DB->query("UPDATE `gallery_categories` SET `gallery_cat_id` = '$id' WHERE `qoob_cat_id` = $id;", false);
		} else {
			//---create bounds
			$next = $parent+1;
			//---get the count of that sub category
			$count = $this->DB->query("SELECT COUNT(`qoob_cat_id`) as 'count' FROM  `gallery_categories` WHERE `gallery_cat_id` > $parent AND `gallery_cat_id` < $next;");
			//---generate new category id
			$count = $count[0]["count"];
			$catID = $parent + (($count + 1) * 0.001);
			$this->DB->query("UPDATE `gallery_categories` SET `gallery_cat_id` = '$catID' WHERE `qoob_cat_id` = $id;", false);
		}
	}
	/**
	 * modify a gallery category in the database
	 *
	 * @param number $id
	 * @param number $parent
	 * @param string $name
	 * @param string $url
	 * @param string $title
	 * @param string $excerpt
	 * @param string $description
	 * @param number $live
	 */
	public function modGalleryCategory($id, $parent, $name, $url, $title, $excerpt, $description, $live) {
		$id 			= $this->DB->sanitize($id);
		$parent 		= $this->DB->sanitize($parent);
		$name 			= $this->DB->sanitize($name);
		$url 			= $this->DB->sanitize($url);
		$title 			= $this->DB->sanitize($title);
		$excerpt 		= $this->DB->sanitize($excerpt);
		$description 	= $this->DB->sanitize($description);
		$live 			= $this->DB->sanitize($live);
		$this->DB->query("UPDATE `gallery_categories` SET `name` = '$name', `url` = '$url', `title` = '$title', `excerpt` = '$excerpt', `description` = '$description', `mainImg` = '0', `live` = '$live' WHERE `gallery_cat_id` = $id LIMIT 1;", false);

		if(intval($id) != intval($parent)) {
			$qoobid = $this->DB->query("SELECT `qoob_cat_id` FROM `gallery_categories` WHERE `gallery_cat_id` = $id LIMIT 1");
			if(isset($qoobid[0])) {
				$qoobid = $qoobid[0]["qoob_cat_id"];
				if($parent == 0) {
					$this->DB->query("UPDATE `gallery_categories` SET `gallery_cat_id` = '$qoobid' WHERE `qoob_cat_id` = $qoobid;", false);
				} else {
					//---create bounds
					$next = $parent+1;
					//---get the count of that sub category
					$count = $this->DB->query("SELECT COUNT(`qoob_cat_id`) as 'count' FROM  `gallery_categories` WHERE `gallery_cat_id` > $parent AND `gallery_cat_id` < $next;");
					//---generate new category id
					$count = $count[0]["count"];
					$catID = $parent + (($count + 1) * 0.001);
					$this->DB->query("UPDATE `gallery_categories` SET `gallery_cat_id` = '$catID' WHERE `qoob_cat_id` = $qoobid;", false);
				}
			}
		}
	}
	/**
	 * check if a gallery image url already exists
	 * 
	 * @param string $url
	 * @return array
	 */
	public function checkGalleryImg($url) {
		$url 	= $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `gallery_images` WHERE `url` = '$url';");
	}
	/**
	 * adds a gallery image to the database
	 *
	 * @param string $url
	 * @param string $filename
	 * @param string $title
	 * @param string $subtitle
	 * @param string $excerpt
	 * @param string $description
	 * @param number $live
	 * @return int
	 */
	public function addGalleryImg($url, $filename, $title, $subtitle, $excerpt, $description, $live) {
		$url 			= $this->DB->sanitize($url);
		$filename 		= $this->DB->sanitize($filename);
		$title 			= $this->DB->sanitize($title);
		$subtitle 		= $this->DB->sanitize($subtitle);
		$excerpt 		= $this->DB->sanitize($excerpt);
		$description 	= $this->DB->sanitize($description);
		$live 			= $this->DB->sanitize($live);
		$date 			= time();
		$this->DB->query("INSERT INTO `gallery_images` (`image_id`, `url`, `filename`, `title`, `subtitle`, `excerpt`, `description`, `comments`, `date`, `live`) VALUES (NULL, '$url', '$filename', '$title', '$subtitle', '$excerpt', '$description', '0', '$date', '$live');", false);
		return $this->DB->insertID();
	}
	/**
	 * add gallery image meta data to the database
	 * 
	 * @param int	 $id
	 * @param string $key
	 * @param string $val
	 */
	public function addGalleryImgMeta($id, $key, $val) {
		$id 	= $this->DB->sanitize($id);
		$key 	= $this->DB->sanitize($key);
		$val	= $this->DB->sanitize($val);
		$this->DB->query("INSERT INTO `gallery_meta` (`meta_id`, `gallery_id`, `meta_key`, `meta_val`) VALUES (NULL , '$id', '$key', '$val');", false);
	}			
	/**
	 * get gallery images by category id
	 *
	 * @param int $id
	 * @return array
	 */
	public function getGalleryImgByCat($id) {
		$id 		= $this->DB->sanitize($id);
		if(strpos($id, '.') == 0) {
			//---root category
			$greater	= intval($id);
			$less		= $greater+1;
			return $this->DB->query("SELECT i.`image_id`, m.`meta_val`, i.`url`, i.`filename`, i.`title`, i.`subtitle`, i.`excerpt`, i.`description`, i.`live`FROM `gallery_meta` as m INNER JOIN `gallery_images` as i ON m.`gallery_id` = i.`image_id` WHERE m.`meta_key` = 'category' AND m.`meta_val` >= $greater AND m.`meta_val` < $less GROUP BY i.`image_id` ORDER BY i.`date` DESC;");
		} else {
			//---sub category
			return $this->DB->query("SELECT i.`image_id`, m.`meta_val`, i.`url`, i.`filename`, i.`title`, i.`subtitle`, i.`excerpt`, i.`description`, i.`live`FROM `gallery_meta` as m INNER JOIN `gallery_images` as i ON m.`gallery_id` = i.`image_id` WHERE m.`meta_key` = 'category' AND m.`meta_val` = $id GROUP BY i.`image_id` ORDER BY i.`date` DESC;");
		}
	}
	/**
	 * get gallery image and metadata by id
	 *
	 * @param int $id
	 * @return array
	 */
	public function getGalleryImgAndMetaByID($id) {
		$id 	= $this->DB->sanitize($id);
		$img = $this->DB->query("SELECT * FROM `gallery_images` WHERE `image_id` = $id LIMIT 1;");
		if(isset($img[0])) {
			$cats = $this->DB->query("SELECT c.`gallery_cat_id` FROM `gallery_meta` m INNER JOIN `gallery_categories` c ON m.`meta_val` = c.`gallery_cat_id` WHERE m.`gallery_id` = $id AND m.`meta_key` = 'category';");
			$catlist = '';
			foreach ($cats as $key => $value) {
				$catlist .= $value["gallery_cat_id"].",";
			}
			$catlist = substr($catlist, 0, -1);
			$img[0]["cats"] = $catlist;
		}
		return $img;
	}
	/**
	 * modify a gallery image in the database
	 *
	 * @param int 	 $id
	 * @param string $url
	 * @param string $title
	 * @param string $subtitle
	 * @param string $excerpt
	 * @param string $description
	 * @param number $live
	 */
	public function modGalleryImg($id, $url, $title, $subtitle, $excerpt, $description, $live) {
		$id 			= $this->DB->sanitize($id);
		$url 			= $this->DB->sanitize($url);
		$title 			= $this->DB->sanitize($title);
		$subtitle 		= $this->DB->sanitize($subtitle);
		$excerpt 		= $this->DB->sanitize($excerpt);
		$description 	= $this->DB->sanitize($description);
		$live 			= $this->DB->sanitize($live);
		$date 			= time();
		$this->DB->query("UPDATE `gallery_images` SET `url` = '$url', `title` = '$title', `subtitle` = '$subtitle', `excerpt` = '$excerpt', `description` = '$description', `date` = '$date', `live` = '$live' WHERE `image_id` = $id;", false);
	}
	/**
	 * delete gallery image from the database
	 * 
	 * @param int	 $id
	 */
	public function delGalleryImg($id) {
		$id 	= $this->DB->sanitize($id);
		$this->DB->query("DELETE FROM `gallery_images` WHERE `image_id` = '$id' LIMIT 1;", false);
	}
	/**
	 * delete gallery image meta data from the database
	 * 
	 * @param int	 $id
	 * @param string $key
	 * @param string $val
	 */
	public function delGalleryImgMeta($id, $key, $val) {
		$id 	= $this->DB->sanitize($id);
		$key 	= $this->DB->sanitize($key);
		$val	= $this->DB->sanitize($val);
		$this->DB->query("DELETE FROM `gallery_meta` WHERE `gallery_id` = '$id' AND `meta_key` = '$key' and `meta_val` = '$val';", false);
	}
	/**
	 * get a count of images in a given gallery category
	 * 
	 * @param int	 $id
	 */
	public function getGalleryImgCount($id) {
		$id 	= $this->DB->sanitize($id);
		if(strpos($id, ".") > 0) {
			//subcategory
			return $this->DB->query("SELECT COUNT(`meta_id`) as 'theCount' FROM `gallery_meta` WHERE `meta_val` = $id AND `meta_key` = 'category';");
		} else {
			//root category
			$greater	= intval($id);
			$less		= $greater+1;		
			return $this->DB->query("SELECT COUNT(`meta_id`) as 'theCount' FROM `gallery_meta` WHERE `meta_val` >= $greater AND `meta_val` < $less AND `meta_key` = 'category';");
		}
	}
	/**
	 * get a count of subgalleries for a given gallery category
	 * 
	 * @param int	 $id
	 */
	public function getSubGalleryCount($id) {
		$id 		= $this->DB->sanitize($id);
		$greater	= intval($id);
		$less		= $greater+1;		
		return $this->DB->query("SELECT COUNT(`gallery_cat_id`) as 'theCount' FROM `gallery_categories` WHERE `gallery_cat_id` > $greater AND `gallery_cat_id` < $less;");
	}
	/**
	 * deletes a category from the database. if the second parameter is 1
	 * the images in that category will be deleted. if the parameter is 0
	 * the images will become uncategorized.
	 *
	 * @param int|float $id gallery_id
	 * @param int $delete boolean
	 * @return array list of file names
	 */
	public function delGalleryAndImgs($id, $delete) {
		$id 	= $this->DB->sanitize($id);
		$delete = $this->DB->sanitize($delete);
		$files = array();
		if(strpos($id, ".") > 0) {
			//subcategory
			$imgs = $this->DB->query("SELECT `gallery_id` FROM `gallery_meta` WHERE `meta_val` = $id AND `meta_key` = 'category';");
			if(isset($imgs[0])) {
				foreach($imgs as $img) { 
					$imgid = $img['gallery_id'];
					$filename = $this->DB->query("SELECT `filename` FROM `gallery_images` WHERE `image_id` = $imgid LIMIT 1");
					if(isset($filename[0])) {
						$files[] = $filename[0]['filename'];
					}
					if($delete == 1) {
						$this->DB->query("DELETE FROM `gallery_meta` WHERE `gallery_id` = $imgid;", false);
						$this->DB->query("DELETE FROM `gallery_images` WHERE `image_id` = $imgid LIMIT 1;", false);
					} else {
						$this->DB->query("UPDATE `gallery_meta` SET `meta_val` = '1' WHERE `gallery_id` = $imgid;", false);
					}
				}
			}
			$this->DB->query("DELETE FROM `gallery_categories` WHERE `gallery_cat_id` = $id LIMIT 1;", false);
		} else {
			//root & subcategory
			$greater	= intval($id);
			$less		= $greater+1;
			$cats 		= $this->DB->query("SELECT `gallery_cat_id` FROM `gallery_categories` WHERE `gallery_cat_id` >= $greater AND `gallery_cat_id` < $less;");
			if(isset($cats[0])) {
				foreach ($cats as $cat) {
					$catid 	= $cat['gallery_cat_id'];
					$imgs 	= $this->DB->query("SELECT `gallery_id` FROM `gallery_meta` WHERE `meta_val` = $catid AND `meta_key` = 'category';");
					if(isset($imgs[0])) {
						foreach($imgs as $img) { 
							$imgid 	= $img['gallery_id'];
							$filename = $this->DB->query("SELECT `filename` FROM `gallery_images` WHERE `image_id` = $imgid LIMIT 1");
							if(isset($filename[0])) {
								$files[] = $filename[0]['filename'];
							}							
							if($delete == 1) {
								$this->DB->query("DELETE FROM `gallery_meta` WHERE `gallery_id` = $imgid;", false);
								$this->DB->query("DELETE FROM `gallery_images` WHERE `image_id` = $imgid LIMIT 1;", false);
							} else {
								$this->DB->query("UPDATE `gallery_meta` SET `meta_val` = '1' WHERE `gallery_id` = $imgid;", false);								
							}
						}
					}
					$this->DB->query("DELETE FROM `gallery_categories` WHERE `gallery_cat_id` = $catid LIMIT 1;", false);
				}
			}
		}
		return $files;
	}
//________________________________________________________________________________________________________________
//                                                                                                            code	
	/**
	 * check to see if a url route for code is used or not.
	 *
	 * @param string $url
	 */
	public function checkCodeRoute($url) {
		$url   = $this->DB->sanitize($url);
		return $this->DB->query("SELECT * FROM `code` WHERE `url` = '$url' LIMIT 0, 1;");
	}
	/**
	 * add a git repo to the database
	 *
	 * @param array $args
	 */
	public function addCode($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		$this->DB->query("INSERT INTO `code` (`git_id`, `repo`, `url`, `name`, `subtitle`, `description`, `readme`) VALUES (NULL, '".$args["repo"]."', '".$args["url"]."', '".$args["name"]."', '".$args["subtitle"]."', '".$args["description"]."', '".$args["readme"]."');", false);
		return true;
	}
	/**
	 * return all repos
	 */
	public function getCodes() {
		return $this->DB->query("SELECT * FROM `code` ORDER BY `git_id` ASC;");
	}
	/**
	 * return all repos
	 *
	 * @param int $id
	 */
	public function getCode($id) {
		$id   = $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `code` WHERE `git_id` = $id LIMIT 0, 1;");
	}
	/**
	 * modify a git repo in the database
	 *
	 * @param array $args
	 */
	public function modCode($args) {
		foreach($args as $key => $val){ 
			$args[$val] = $this->DB->sanitize($val);
		}
		$this->DB->query("UPDATE `code` SET `repo` = '".$args["repo"]."', `url` = '".$args["url"]."', `name` = '".$args["name"]."', `subtitle` = '".$args["subtitle"]."', `description` = '".$args["description"]."', `readme` = '".$args["readme"]."' WHERE `code`.`git_id` = ".$args["git_id"]." LIMIT 1;", false);
		return true;
	}
	/**
	 * deletes a git repo from the database.
	 *
	 * @param int $id
	 */
	public function delCode($id) {
		$id = $this->DB->sanitize($id);
		$this->DB->query("DELETE FROM `code` WHERE `git_id` = ".$id." LIMIT 1;", false);
		return true;
	}	
}

?>