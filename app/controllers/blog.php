<?php
/**
 * blog controller
 * generate blog pages.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.14
 * @package app
 * @subpackage controllers
 */
final class blog extends controller {
	/**
	 * index function
	 * load the newest blog posts
	 */
	public function index() {
		$page = 0;
		if(isset(library::catalog()->url[2])) {
			$page = (int)filter_var(library::catalog()->url[2], FILTER_SANITIZE_NUMBER_INT);
		}
		$content = $this->model("blogModel");
		$count = $content->getTotalPostCount();
		$limit = library::catalog()->posts_per_page;
		$offset = ($page == 0) ? 0 : (($page * $limit) - $limit);
		if($page == 0) {
			$page++;
		}
		$total = ceil($count[0]["total"] / $limit);

		$posts = $content->getPosts($offset, $limit);
		
		// --- create html array for rendering
		$html = array();
		$html["selected"] = 'blog';
		$html["body"] = '';
		$html["script"] = '		SyntaxHighlighter.defaults.toolbar = false;'.PHP_EOL.'		SyntaxHighlighter.all();'.PHP_EOL;
		$html["script"].= $this->view('initShadowboxJS', array(), true);
		$html['jsfiles'] = '<script type="text/javascript" src="'.BASE_URL.'style/js/syntaxHighlighter.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-core-css" href="'.BASE_URL.'style/css/shCoreDefault.css" media="all"/>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-default-css" href="'.BASE_URL.'style/css/shThemeDefault.css" media="all"/>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>'.PHP_EOL;
		$html["title"] = ($page > 1) ? 'blog page '.$page : 'blog';
		$html["sidebar"] = '';
		$html["comments"] = false;
		$html["canonicalNow"] = ($page == 1) ? '' : 'all posts page : '.$page;
		$html["canonicalBack"] = ($page > 1) ? '<a href="'.QOOB_DOMAIN.'blog/page/'.($page-1).'">&lt; &lt; next posts</a>' : '';
		$html["canonicalNext"] = ($page == $total) ? '' : '<a href="'.QOOB_DOMAIN.'blog/page/'.($page+1).'">previous posts &gt; &gt; </a>';
		$canonicalStartTitle = 'all posts page 1';
		$canonicalStartURL = QOOB_DOMAIN.'blog/page/1';
		$canonicalBackTitle = 'all posts page '.($page-1);
		$canonicalBackURL = ($page > 1) ? QOOB_DOMAIN.'blog/page/'.($page-1) : '';
		$canonicalNextTitle = 'all posts page '.($page+1);
		$canonicalNextURL =  ($page == $total) ? '' : QOOB_DOMAIN.'blog/page/'.($page+1);
		$canonicalURL = QOOB_DOMAIN.'blog/page/'.$page;

		$html["meta"] = "<link rel='index' title='".QOOB_DOMAIN."blog' href='".QOOB_DOMAIN."blog/' />".PHP_EOL;
		$html["meta"].= "	<link rel='canonical' href='$canonicalURL' />".PHP_EOL;
		$html["meta"].= "	<link rel='start' title='$canonicalStartTitle' href='$canonicalStartURL' />".PHP_EOL;
		if($canonicalBackURL != '') {
			$html["meta"].= "	<link rel='prev' title='$canonicalBackTitle' href='$canonicalBackURL' />".PHP_EOL;
		}
		if($canonicalNextURL != '') {
			$html["meta"].= "	<link rel='next' title='$canonicalNextTitle' href='$canonicalNextURL' />".PHP_EOL;
		}
		
		// --- display page
		if(isset($posts[0])) {
			for ($i=0; $i<count($posts);$i++) {
				$cats = $content->getPostCats($posts[$i]["post_id"]);
				$tags = $content->getPostTags($posts[$i]["post_id"]);

				if(isset($cats[0])) {
					$id = (int)$cats[0]["blog_cat_id"];
					$cat = $content->getCatByID($id);
					$mainCat = isset($cat[0]) ? $cat[0]["url"] : "uncategorized";
				} else {
					$mainCat = "uncategorized";
				}
								
				$catlist = '';
				if(is_array($cats)) {
					for ($x = 0; $x < count($cats); $x++) {
						$id = (int)$cats[$x]["blog_cat_id"];
						if($id != $cats[$x]["blog_cat_id"]) {
							$cat = $content->getCatByID($id);
							$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/" title="view all posts in: '.$cat[0]["name"].'">'.$cat[0]["name"].'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
						} else {
							$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
						}		
						if($x < count($cats)-1) {
							$catlist.= ', ';
						}
					}
				}
				
				$taglist = '';
				if(is_array($tags)) {
					for ($x = 0; $x < count($tags); $x++) {
						$taglist.= '<a href="'.QOOB_DOMAIN.'blog/tag/'.$tags[$x]["url"].'" rel="tag">'.$tags[$x]["name"].'</a>';
						if($x < count($tags)-1) {
							$taglist.= ', ';
						}
					}
				}
			
				$meta = array(
					"day" => date("d", $posts[$i]["date"]),
					"month" => date("M", $posts[$i]["date"]),
					"year" => date("Y", $posts[$i]["date"]),
					"cats" => ($catlist == '') ? $mainCat : $catlist,
					"tags" => $taglist,
					"comments" => $posts[$i]["comments"],
					"trackbacks" => "0"
				);
				$metabox = $this->view("blog/post_meta", $meta, true);

				$excerptContent = html_entity_decode($posts[$i]["excerpt"]);
				if($posts[$i]["excerpt"] != $posts[$i]["content"]) {
					$excerptContent.= '<a class="more" href="'.QOOB_DOMAIN.'blog/'.$posts[$i]["url"].'"><strong>Read:</strong> '.$posts[$i]["title"].' &raquo;</a>';	
				}
				$post = array(
					'mainCat' => $mainCat,
					'url' => 'blog/'.$posts[$i]["url"],
					'title' => $posts[$i]["title"],
					'subtitle' => $posts[$i]["subtitle"],
					'content' => $excerptContent.$metabox,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true).'<br class="clear" />';
			}
			$this->library(qoob_types::utility, "paging");
			$this->paging->init(array("base_url" => QOOB_DOMAIN."blog/page/",
								      "total_rows" => $count[0]["total"], 
									  "per_page" => $limit,
									  "cur_page" => $page,
									  "num_tag_open" => '<div class="page">',
									  "num_tag_close" => "</div>",
									  "cur_tag_open" => '<div class="cur_page">',
									  "cur_tag_close" => "</div>",
									  "next_tag_open" => '<div class="next_page">',
									  "next_tag_close" => "</div>",
									  "prev_tag_open" => '<div class="prev_page">',
									  "prev_tag_close" => "</div>",
									  "first_tag_open" => '<div class="first_page">',
									  "first_tag_close" => "</div>",
									  "last_tag_open" => '<div class="last_page">',
									  "last_tag_close" => "</div>",
									  "full_tag_open" => '<div id="paging">',
									  "full_tag_close" => "</div>"));
			$html["body"] .= $this->paging->render().'<br class="clear"/><br/>';
			//---sidebar
			//tag cloud
			$tags = $content->getTags();
			$this->library(qoob_types::utility, "cloud");
			$this->cloud->setMax(175);
			$this->cloud->setMin(80);
			$cloud = array(
				"tags" => $this->cloud->make($tags, QOOB_DOMAIN.'blog/tag/')
			);
			$html["sidebar"].= $this->view("blog/sidebar_tags", $cloud, true);
			//categories
			$html["sidebar"].= $this->view("blog/sidebar_categories", array(), true);
			//qr code
			$html["sidebar"].= $this->view("blog/sidebar_qr", array(), true);
			//feeds
			$feeds = array(
				"showNewest" => true,
				"showCat" => false,
				"cat" => '',
				"showTag" => false,
				"tag" => '',
				"showComments" => false,
				"post" => '',
			);
			$html["sidebar"].= $this->view("blog/sidebar_feeds", $feeds, true);

		// --- no posts
		} else {
			$html["sidebar"].= $this->view("blog/sidebar_qr", array(), true);
			$post = array(
				'mainCat' => '',
				'url' => '',
				'title' => "Blog",
				'subtitle' => "",
				'content' => "no posts found",
				'comments' => 0
			);
			$html["body"] .= $this->view("post", $post, true).'<br class="clear" />';
		}		
		$this->view("pixelgraff", $html);
	}
	/**
	 * category function
	 * load the blog posts by category/subcategory
	 */	
	public function category() {
		$page = 0;
		$catid = 0;
		$subid = 0;

		if(isset(library::catalog()->url[0])) {
			$routes = $this->model("blogModel");
			$result = $routes->checkCategory(library::catalog()->url[1]);
			if (count($result) > 0) {
				$catName = $result[0]['name'];
				$catURL = $result[0]['url'];
				$catid = $result[0]['blog_cat_id'];
			}
			if(isset(library::catalog()->url[2])) {
				if(library::catalog()->url[2] == 'page') {
					if(isset(library::catalog()->url[3])) {
						$page = (int)filter_var(library::catalog()->url[3], FILTER_SANITIZE_NUMBER_INT);
					}
				} else {
					$result = $routes->checkSubCategory($catid, library::catalog()->url[2]);
					if (count($result) > 0) {
						$subName = $result[0]['name'];
						$subURL = $result[0]['url'];
						$subid = $result[0]['blog_cat_id'];
					} else {
						throw new Exception("invalid sub-category", statusCodes::HTTP_NOT_FOUND);
					}
				}
			}
			if(isset(library::catalog()->url[3])) {
				if(library::catalog()->url[3] == 'page') {
					if(isset(library::catalog()->url[4])) {
						$page = (int)filter_var(library::catalog()->url[4], FILTER_SANITIZE_NUMBER_INT);
					}
				}
			}
			$content = $this->model("blogModel");
			$limit = library::catalog()->posts_per_page;
			$offset = ($page == 0) ? 0 : (($page * $limit) - $limit);
			if($page == 0) {
				$page++;
			}
			if($subid > 0) {
				$posts = $content->getPostsBySubCat($subid, $offset, $limit);
				$count = $content->getSubCatPostCount($subid);
				$url = QOOB_DOMAIN.'blog/'.library::catalog()->url[1].'/'.library::catalog()->url[2].'/';
			} else {
				$posts = $content->getPostsByCat($catid, $offset, $limit);
				$count = $content->getCatPostCount($catid);
				$url = QOOB_DOMAIN.'blog/'.library::catalog()->url[1].'/';
			}
			$total = ceil($count[0]["total"] / $limit);

			// --- create html array for rendering
			$html = array();
			$html["selected"] = 'blog';
			$html["body"] = '';
			$html["script"] = '		SyntaxHighlighter.defaults.toolbar = false;'.PHP_EOL.'		SyntaxHighlighter.all();'.PHP_EOL;
			$html["script"].= $this->view('initShadowboxJS', array(), true);
			$html['jsfiles'] = '<script type="text/javascript" src="'.BASE_URL.'style/js/syntaxHighlighter.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-core-css" href="'.BASE_URL.'style/css/shCoreDefault.css" media="all"/>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-default-css" href="'.BASE_URL.'style/css/shThemeDefault.css" media="all"/>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>'.PHP_EOL;
			$html["sidebar"] = '';
			$html["title"] = ($subid > 0) ? 'blog/categories/'.$catName.'/'.$subName.'/' : 'blog/category/'.$catName.'/';
			$html["title"].= ($page > 1) ? 'page '.$page : '';
			$html["comments"] = false;
			$html["canonicalNow"] = ($subid > 0) ? 'category: <a href="'.QOOB_DOMAIN.'blog/'.$catURL.'">'.$catName.'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$catURL.'/'.$subURL.'">'.$subName.'</a>' : 'category: <a href="'.QOOB_DOMAIN.'blog/'.$catURL.'">'.$catName.'</a>';
			$html["canonicalBack"] = ($page > 1) ? '<a href="'.$url.'page/'.($page-1).'">&lt; &lt; next posts</a>' : '';
			$html["canonicalNext"] = ($page == $total) ? '' : '<a href="'.$url.'page/'.($page+1).'">previous posts &gt; &gt; </a>';
			$canonicalStartTitle = 'posts from the category: ';
			$canonicalStartTitle .= ($subid > 0) ? $catName.' - '.$subName.' page 1' : $catName.' page 1';
			$canonicalStartURL = $url.'page/1';
			$canonicalBackTitle = 'posts from the category: ';
			$canonicalBackTitle .= ($subid > 0) ? $catName.' - '.$subName.' page '.($page-1) : $catName.' page '.($page-1);
			$canonicalBackURL = ($page > 1) ? $url.'page/'.($page-1) : '';
			$canonicalNextTitle = 'posts from the category: ';
			$canonicalNextTitle = ($subid > 0) ? $catName.' - '.$subName.' page '.($page+1) : $catName.' page '.($page+1);
			$canonicalNextURL =  ($page == $total) ? '' : $url.'page/'.($page+1);
			$canonicalURL = $url.'page/'.$page;

			$html["meta"] = "<link rel='index' title='".QOOB_DOMAIN."blog' href='".QOOB_DOMAIN."blog/' />".PHP_EOL;
			$html["meta"].= "	<link rel='canonical' href='$canonicalURL' />".PHP_EOL;
			$html["meta"].= "	<link rel='start' title='$canonicalStartTitle' href='$canonicalStartURL' />".PHP_EOL;
			if($canonicalBackURL != '') {
				$html["meta"].= "	<link rel='prev' title='$canonicalBackTitle' href='$canonicalBackURL' />".PHP_EOL;
			}
			if($canonicalNextURL != '') {
				$html["meta"].= "	<link rel='next' title='$canonicalNextTitle' href='$canonicalNextURL' />".PHP_EOL;
			}

			// --- display page
			if(isset($posts[0])) {
				for ($i=0; $i<count($posts);$i++) {
					$cats = $content->getPostCats($posts[$i]["post_id"]);
					$tags = $content->getPostTags($posts[$i]["post_id"]);

					if(isset($cats[0])) {
						$id = (int)$cats[0]["blog_cat_id"];
						$cat = $content->getCatByID($id);
						$mainCat = isset($cat[0]) ? $cat[0]["url"] : "uncategorized";
					} else {
						$mainCat = "uncategorized";
					}
					
					$catlist = '';
					if(is_array($cats)) {
						for ($x = 0; $x < count($cats); $x++) {
							$id = (int)$cats[$x]["blog_cat_id"];
							if($id != $cats[$x]["blog_cat_id"]) {
								$cat = $content->getCatByID($id);
								$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/" title="view all posts in: '.$cat[0]["name"].'">'.$cat[0]["name"].'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
							} else {
								$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
							}		
							if($x < count($cats)-1) {
								$catlist.= ', ';
							}
						}
					}
					
					$taglist = '';
					if(is_array($tags)) {
						for ($x = 0; $x < count($tags); $x++) {
							$taglist.= '<a href="'.QOOB_DOMAIN.'blog/tag/'.$tags[$x]["url"].'" rel="tag">'.$tags[$x]["name"].'</a>';
							if($x < count($tags)-1) {
								$taglist.= ', ';
							}
						}
					}
				
					$meta = array(
						"day" => date("d", $posts[$i]["date"]),
						"month" => date("M", $posts[$i]["date"]),
						"year" => date("Y", $posts[$i]["date"]),
						"cats" => ($catlist == '') ? $mainCat : $catlist,
						"tags" => $taglist,
						"comments" => $posts[$i]["comments"],
						"trackbacks" => "0"
					);
					$metabox = $this->view("blog/post_meta", $meta, true);

					$excerptContent = html_entity_decode($posts[$i]["excerpt"]);
					if($posts[$i]["excerpt"] != $posts[$i]["content"]) {
						$excerptContent.= '<a class="more" href="'.QOOB_DOMAIN.'blog/'.$posts[$i]["url"].'"><strong>Read:</strong> '.$posts[$i]["title"].' &raquo;</a>';	
					}

					$post = array(
						'mainCat' => $mainCat,
						'url' => 'blog/'.$posts[$i]["url"],
						'title' => $posts[$i]["title"],
						'subtitle' => $posts[$i]["subtitle"],
						'content' => $excerptContent.$metabox,
						'comments' => 0
					);
					$html["body"] .= $this->view("post", $post, true).'<br class="clear" />';

				}
				$this->library(qoob_types::utility, "paging");
				$this->paging->init(array("base_url" => $url."page/",
									      "total_rows" => $count[0]["total"], 
										  "per_page" => $limit,
										  "cur_page" => $page,
										  "num_tag_open" => '<div class="page">',
										  "num_tag_close" => "</div>",
										  "cur_tag_open" => '<div class="cur_page">',
										  "cur_tag_close" => "</div>",
										  "next_tag_open" => '<div class="next_page">',
										  "next_tag_close" => "</div>",
										  "prev_tag_open" => '<div class="prev_page">',
										  "prev_tag_close" => "</div>",
										  "first_tag_open" => '<div class="first_page">',
										  "first_tag_close" => "</div>",
										  "last_tag_open" => '<div class="last_page">',
										  "last_tag_close" => "</div>",
										  "full_tag_open" => '<div id="paging">',
										  "full_tag_close" => "</div>"));
				$html["body"] .= $this->paging->render().'<br class="clear"/><br/>';
				//---sidebar
				//tag cloud
				$tags = $content->getTags();
				$this->library(qoob_types::utility, "cloud");
				$this->cloud->setMax(175);
				$this->cloud->setMin(80);
				$cloud = array(
					"tags" => $this->cloud->make($tags, QOOB_DOMAIN.'blog/tag/')
				);
				$html["sidebar"].= $this->view("blog/sidebar_tags", $cloud, true);
				//categories
				$html["sidebar"].= $this->view("blog/sidebar_categories", array(), true);
				//qr code
				$html["sidebar"].= $this->view("blog/sidebar_qr", array(), true);
				//feeds
				$feeds = array(
					"showNewest" => true,
					"showCat" => true,
					"cat" => $subid > 0 ? $subURL : $catURL,
					"showTag" => false,
					"tag" => '',
					"showComments" => false,
					"post" => '',
				);
				$html["sidebar"].= $this->view("blog/sidebar_feeds", $feeds, true);

				$this->view("pixelgraff", $html);
			} else {
				throw new Exception("no posts found", statusCodes::HTTP_NOT_FOUND);				
			}
		} else {
			throw new Exception("invalid category", statusCodes::HTTP_NOT_FOUND);
		}
	}
	/**
	 * tag function
	 * load the blog posts by tag
	 */
	public function tag() {
		$tag = library::catalog()->url[2];
		$content = $this->model("blogModel");
		$tid = $content->checkTag($tag);

		if(isset($tid[0])) {
			$name = $tid[0]["name"]; 
			$tid = $tid[0]["tag_id"];

			$page = 0;
			if(isset(library::catalog()->url[3]) && isset(library::catalog()->url[4])) {
				if(library::catalog()->url[3] == 'page') {
					$page = (int)filter_var(library::catalog()->url[4], FILTER_SANITIZE_NUMBER_INT);	
				}
			}
			$limit = library::catalog()->posts_per_page;
			$offset = ($page == 0) ? 0 : (($page * $limit) - $limit);
			if($page == 0) {
				$page++;
			}
			$count = $content->getTagPostCount($tid);
			$total = ceil($count[0]["total"] / $limit);

			$posts = $content->getPostsByTag($tid, $offset, $limit);
			
			// --- create html array for rendering
			$html = array();
			$html["selected"] = 'blog';
			$html["body"] = '';
			$html["script"] = '		SyntaxHighlighter.defaults.toolbar = false;'.PHP_EOL.'		SyntaxHighlighter.all();'.PHP_EOL;
			$html["script"].= $this->view('initShadowboxJS', array(), true);
			$html['jsfiles'] = '<script type="text/javascript" src="'.BASE_URL.'style/js/syntaxHighlighter.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-core-css" href="'.BASE_URL.'style/css/shCoreDefault.css" media="all"/>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-default-css" href="'.BASE_URL.'style/css/shThemeDefault.css" media="all"/>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>'.PHP_EOL;
			$html["sidebar"] = '';
			$html["title"] = ($page > 1) ? 'blog/tag/'.$tag.'/page '.$page : 'blog/tag/'.$tag;
			$html["comments"] = false;
			$html["canonicalNow"] = 'tag : <a href="'.QOOB_DOMAIN.'blog/'.'tag/'.$tag.'">'.$name.'</a>';
			$html["canonicalBack"] = ($page > 1) ? '<a href="'.QOOB_DOMAIN.'blog/tag/'.$tag.'/page/'.($page-1).'">&lt; &lt; next posts</a>' : '';
			$html["canonicalNext"] = ($page == $total) ? '' : '<a href="'.QOOB_DOMAIN.'blog/tag/'.$tag.'/page/'.($page+1).'">previous posts &gt; &gt; </a>';
			$canonicalStartTitle = 'posts tagged: '.$tag.' page 1';
			$canonicalStartURL = QOOB_DOMAIN.'blog/tag/'.$tag.'/page/1';
			$canonicalBackTitle = 'posts tagged: '.$tag.' page '.($page-1);
			$canonicalBackURL = ($page > 1) ? QOOB_DOMAIN.'blog/tag/'.$tag.'/page/'.($page-1) : '';
			$canonicalNextTitle = 'posts tagged: '.$tag.' page '.($page+1);
			$canonicalNextURL =  ($page == $total) ? '' : QOOB_DOMAIN.'blog/tag/'.$tag.'/page/'.($page+1);
			$canonicalURL = QOOB_DOMAIN.'blog/tag/'.$tag.'/page/'.$page;

			$html["meta"] = "<link rel='index' title='".QOOB_DOMAIN."blog' href='".QOOB_DOMAIN."blog/' />".PHP_EOL;
			$html["meta"].= "	<link rel='canonical' href='$canonicalURL' />".PHP_EOL;
			$html["meta"].= "	<link rel='start' title='$canonicalStartTitle' href='$canonicalStartURL' />".PHP_EOL;
			if($canonicalBackURL != '') {
				$html["meta"].= "	<link rel='prev' title='$canonicalBackTitle' href='$canonicalBackURL' />".PHP_EOL;
			}
			if($canonicalNextURL != '') {
				$html["meta"].= "	<link rel='next' title='$canonicalNextTitle' href='$canonicalNextURL' />".PHP_EOL;
			}

			for ($i=0; $i<count($posts);$i++) {
				$cats = $content->getPostCats($posts[$i]["post_id"]);
				$tags = $content->getPostTags($posts[$i]["post_id"]);

				if(isset($cats[0])) {
					$id = (int)$cats[0]["blog_cat_id"];
					$cat = $content->getCatByID($id);
					$mainCat = isset($cat[0]) ? $cat[0]["url"] : "uncategorized";
				} else {
					$mainCat = "uncategorized";
				}
								
				$catlist = '';
				if(is_array($cats)) {
					for ($x = 0; $x < count($cats); $x++) {
						$id = (int)$cats[$x]["blog_cat_id"];
						if($id != $cats[$x]["blog_cat_id"]) {
							$cat = $content->getCatByID($id);
							$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/" title="view all posts in: '.$cat[0]["name"].'">'.$cat[0]["name"].'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
						} else {
							$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
						}		
						if($x < count($cats)-1) {
							$catlist.= ', ';
						}
					}
				}
				
				$taglist = '';
				if(is_array($tags)) {
					for ($x = 0; $x < count($tags); $x++) {
						$taglist.= '<a href="'.QOOB_DOMAIN.'blog/tag/'.$tags[$x]["url"].'" rel="tag">'.$tags[$x]["name"].'</a>';
						if($x < count($tags)-1) {
							$taglist.= ', ';
						}
					}
				}
			
				$meta = array(
					"day" => date("d", $posts[$i]["date"]),
					"month" => date("M", $posts[$i]["date"]),
					"year" => date("Y", $posts[$i]["date"]),
					"cats" => ($catlist == '') ? $mainCat : $catlist,
					"tags" => $taglist,
					"comments" => $posts[$i]["comments"],
					"trackbacks" => "0"
				);
				$metabox = $this->view("blog/post_meta", $meta, true);

				$excerptContent = html_entity_decode($posts[$i]["excerpt"]);
				if($posts[$i]["excerpt"] != $posts[$i]["content"]) {
					$excerptContent.= '<a class="more" href="'.QOOB_DOMAIN.'blog/'.$posts[$i]["url"].'"><strong>Read:</strong> '.$posts[$i]["title"].' &raquo;</a>';	
				}

				$post = array(
					'mainCat' => $mainCat,
					'url' => 'blog/'.$posts[$i]["url"],
					'title' => $posts[$i]["title"],
					'subtitle' => $posts[$i]["subtitle"],
					'content' => $excerptContent.$metabox,
					'comments' => 0
				);
				$html["body"] .= $this->view("post", $post, true).'<br class="clear" />';
			}
			$url = QOOB_DOMAIN.'blog/tag/'.library::catalog()->url[2].'/';

			$this->library(qoob_types::utility, "paging");
			$this->paging->init(array("base_url" => $url."page/",
								      "total_rows" => $count[0]["total"], 
									  "per_page" => $limit,
									  "cur_page" => $page,
									  "num_tag_open" => '<div class="page">',
									  "num_tag_close" => "</div>",
									  "cur_tag_open" => '<div class="cur_page">',
									  "cur_tag_close" => "</div>",
									  "next_tag_open" => '<div class="next_page">',
									  "next_tag_close" => "</div>",
									  "prev_tag_open" => '<div class="prev_page">',
									  "prev_tag_close" => "</div>",
									  "first_tag_open" => '<div class="first_page">',
									  "first_tag_close" => "</div>",
									  "last_tag_open" => '<div class="last_page">',
									  "last_tag_close" => "</div>",
									  "full_tag_open" => '<div id="paging">',
									  "full_tag_close" => "</div>"));
			$html["body"] .= $this->paging->render().'<br class="clear"/><br/>';
			//---sidebar
			//tag cloud
			$tags = $content->getTags();
			$this->library(qoob_types::utility, "cloud");
			$this->cloud->setMax(175);
			$this->cloud->setMin(80);
			$cloud = array(
				"tags" => $this->cloud->make($tags, QOOB_DOMAIN.'blog/tag/', $tag),
			);
			$html["sidebar"].= $this->view("blog/sidebar_tags", $cloud, true);
			//categories
			$html["sidebar"].= $this->view("blog/sidebar_categories", array(), true);
			//qr code
			$html["sidebar"].= $this->view("blog/sidebar_qr", array(), true);
			//feeds
			$feeds = array(
				"showNewest" => true,
				"showCat" => false,
				"cat" => '',
				"showTag" => true,
				"tag" => $tag,
				"showComments" => false,
				"post" => '',
			);
			$html["sidebar"].= $this->view("blog/sidebar_feeds", $feeds, true);

			$this->view("pixelgraff", $html);
		} else {
			throw new Exception("tag not found", statusCodes::HTTP_NOT_FOUND);				
		}		
	}
	/**
	 * post function
	 * load an individual blog posts
	 */
	public function post() {
		$content = $this->model("blogModel");
		$posts = $content->getPostByURL(library::catalog()->url[1]);
		
		// --- create html array for rendering
		$html = array();
		$html["selected"] = 'blog';
		$html["body"] = '';
		$html["script"] = '		SyntaxHighlighter.defaults.toolbar = false;'.PHP_EOL.'		SyntaxHighlighter.all();'.PHP_EOL;
		$html["script"].= $this->view('initShadowboxJS', array(), true);
		$html['jsfiles'] = '<script type="text/javascript" src="'.BASE_URL.'style/js/syntaxHighlighter.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-core-css" href="'.BASE_URL.'style/css/shCoreDefault.css" media="all"/>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="syntax-default-css" href="'.BASE_URL.'style/css/shThemeDefault.css" media="all"/>'.PHP_EOL.'	<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>'.PHP_EOL.'	<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>'.PHP_EOL;
		$html["sidebar"] = '';
		$html["title"] = '';
		$html["comments"] = true;
		$html["canonicalBack"] = '';
		$html["canonicalNow"] = '';
		$html["canonicalNext"] = '';
		
		// --- display page
		if(isset($posts[0])) {
			$cats = $content->getPostCats($posts[0]["post_id"]);
			$tags = $content->getPostTags($posts[0]["post_id"]);

			$html["title"] = 'blog/'.strip_tags($posts[0]["title"]);

			if(isset($cats[0])) {
				$id = (int)$cats[0]["blog_cat_id"];
				$cat = $content->getCatByID($id);
				$mainCat = isset($cat[0]) ? $cat[0]["url"] : "uncategorized";
				$mainCatName = isset($cat[0]) ? $cat[0]["name"] : "uncategorized";
			} else {
				$mainCat = "uncategorized";
			}
			$first = $content->getFirstPost();
			$prev = $content->getPrevPost($posts[0]["date"]);
			$next = $content->getNextPost($posts[0]["date"]);
			$html["canonicalNext"] = isset($prev[0]) ? '<a href="'.QOOB_DOMAIN.'blog/'.$prev[0]["url"].'">previous post &gt; &gt;</a>' : '';
			$html["canonicalBack"] = isset($next[0]) ? '<a href="'.QOOB_DOMAIN.'blog/'.$next[0]["url"].'">&lt; &lt; next post </a>' : '';
			$html["canonicalNow"] = '<a href="'.QOOB_DOMAIN.'blog/'.$mainCat.'">'.$mainCatName.'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$posts[0]["url"].'">'.$posts[0]["title"].'</a>';
			$canonicalStartTitle = isset($first[0]) ? $first[0]["title"] : '';
			$canonicalStartURL = isset($first[0]) ? QOOB_DOMAIN.'blog/'.$first[0]["url"] : '';
			$canonicalURL = QOOB_DOMAIN.'blog/'.$posts[0]["url"];
			$canonicalNextTitle = isset($next[0]) ? $next[0]["title"] : '';			
			$canonicalNextURL = isset($next[0]) ? QOOB_DOMAIN.'blog/'.$next[0]["url"] : '';
			$canonicalBackTitle = isset($prev[0])? $prev[0]["title"] : '';
			$canonicalBackURL = isset($prev[0]) ? QOOB_DOMAIN.'blog/'.$prev[0]["url"] : '';
			
			$html["meta"] = "<link rel='index' title='".QOOB_DOMAIN."blog' href='".QOOB_DOMAIN."blog/' />".PHP_EOL;
			$html["meta"].= "	<link rel='canonical' href='$canonicalURL' />".PHP_EOL;
			$html["meta"].= "	<link rel='start' title='$canonicalStartTitle' href='$canonicalStartURL' />".PHP_EOL;
			if($canonicalBackURL != '') {
				$html["meta"].= "	<link rel='prev' title='$canonicalBackTitle' href='$canonicalBackURL' />".PHP_EOL;
			}
			if($canonicalNextURL != '') {
				$html["meta"].= "	<link rel='next' title='$canonicalNextTitle' href='$canonicalNextURL' />".PHP_EOL;
			}

			$catlist = '';
			if(is_array($cats)) {
				for ($x = 0; $x < count($cats); $x++) {
					$id = (int)$cats[$x]["blog_cat_id"];
					if($id != $cats[$x]["blog_cat_id"]) {
						$cat = $content->getCatByID($id);
						$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/" title="view all posts in: '.$cat[0]["name"].'">'.$cat[0]["name"].'</a> / <a href="'.QOOB_DOMAIN.'blog/'.$cat[0]["url"].'/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
					} else {
						$catlist.= '<a href="'.QOOB_DOMAIN.'blog/'.$cats[$x]["url"].'/" title="view all posts in: '.$cats[$x]["name"].'">'.$cats[$x]["name"].'</a>';
					}		
					if($x < count($cats)-1) {
						$catlist.= ', ';
					}
				}
			}
			
			$taglist = '';
			if(is_array($tags)) {
				for ($x = 0; $x < count($tags); $x++) {
					$taglist.= '<a href="'.QOOB_DOMAIN.'blog/'.'tag/'.$tags[$x]["url"].'" rel="tag">'.$tags[$x]["name"].'</a>';
					if($x < count($tags)-1) {
						$taglist.= ', ';
					}
				}
			}

			$summary = strip_tags($posts[0]["title"].' : '.$posts[0]["subtitle"].'. posted in the categories: '.str_replace(' / ', ', ', $catlist).' and tagged: '.$taglist.'.');

			//---post meta
			$meta = array(
				"day" => date("d", $posts[0]["date"]),
				"month" => date("M", $posts[0]["date"]),
				"year" => date("Y", $posts[0]["date"]),
				"cats" => ($catlist == '') ? $mainCat : $catlist,
				"tags" => $taglist,
				"comments" => $posts[0]["comments"],
				"trackbacks" => "0"
			);
			$metabox = $this->view("blog/post_meta", $meta, true);

			//---post body
			$post = array(
				'mainCat' => $mainCat,
				'url' => 'blog/'.$posts[0]["url"],
				'title' => $posts[0]["title"],
				'subtitle' => $posts[0]["subtitle"],
				'content' => html_entity_decode($posts[0]["content"]).$metabox,
				'comments' => 0
			);
			$html["body"] = $this->view("post", $post, true);

			//---sidebar
			//meta
			$smeta = array(
				"title" => '<a href="'.QOOB_DOMAIN.'blog/'.$posts[0]["url"].'">'.$posts[0]["title"].'</a>',
				"date" => strtolower(date("l F jS o", $posts[0]["date"])).' at '.date("g:i a", $posts[0]["date"]),
				"cats" => ($catlist == '') ? $mainCat : $catlist,
				"tags" => $taglist,
				"comments" => $posts[0]["comments"]		
			);
			$html["sidebar"].= $this->view("blog/sidebar_meta", $smeta, true);
			//tag cloud
			$tags = $content->getTags();
			$this->library(qoob_types::utility, "cloud");
			$this->cloud->setMax(175);
			$this->cloud->setMin(80);
			$cloud = array(
				"tags" => $this->cloud->make($tags, QOOB_DOMAIN.'blog/tag/')
			);
			$html["sidebar"].= $this->view("blog/sidebar_tags", $cloud, true);
			//categories
			$html["sidebar"].= $this->view("blog/sidebar_categories", array(), true);
			//qr code
			$html["sidebar"].= $this->view("blog/sidebar_qr", array(), true);
			//feeds
			$feeds = array(
				"showNewest" => true,
				"showCat" => true,
				"cat" => $mainCat,
				"showTag" => false,
				"tag" => '',
				"showComments" => $posts[0]["comments"] == 0 ? false : true,
				"post" => $posts[0]["url"],
			);
			$html["sidebar"].= $this->view("blog/sidebar_feeds", $feeds, true);

		// --- display 404
		} else {
			throw new Exception("invalid url", statusCodes::HTTP_NOT_FOUND);
		}		
		$this->view("pixelgraff", $html);
	}
}

?>