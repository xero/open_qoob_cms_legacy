<?php
/**
 * feeds controller
 * generate rss/atom feeds
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
class feeds extends controller {
	/**
	 * @var int $type the feed type (0 = rss, 1 = atom)
	 */
	private $type = 0;
	/**
	 * @var string $section the feed section (e.g. blog, code)
	 */
	private $section = '';
	/**
	 * @var string $action the feed type (e.g. category, tag)
	 */
	private $action = '';
	/**
	 * @var string $object the feed object (e.g tag name, category name, git sha1 id)
	 */
	private $object = '';
	/**
	 * index function
	 * mine the url and either throw and error or call a sub function
	 */
	function index() {
		if(!isset(library::catalog()->url[1])) {
			$this->map();
		} else {
			if(isset(library::catalog()->url[1])) {
				switch (library::catalog()->url[1]) {
					case 'rss':
						$this->type = 0;
					break;
					case 'atom':
						$this->type = 1;
					break;
					default:
						throw new Exception("invalid feed type", statusCodes::HTTP_NOT_FOUND);
					break;
				}
			}
			if(!isset(library::catalog()->url[2])) {
				$this->map();
			} else {
				switch (library::catalog()->url[2]) {
					case 'blog':
						$this->section = 'blog';
					break;
					case 'code':
						$this->section = 'code';
					break;
					default:
						throw new Exception("invalid feed section", statusCodes::HTTP_NOT_FOUND);
					break;
				}
				if(!isset(library::catalog()->url[3])) {
					$this->map();
				} else {
					switch ($this->section) {
						case 'blog':
							if(library::catalog()->url[3] == 'newest') {
								$this->action = 'newest';
							} else {
								if(!isset(library::catalog()->url[4])) {
									throw new Exception("missing feed object", statusCodes::HTTP_NOT_FOUND);
								} else {
									$this->object = library::catalog()->url[4];
									switch (library::catalog()->url[3]) {
										case 'category':
											$this->action = 'category';
										break;
										case 'tag':
											$this->action = 'tag';
										break;
										default:
											throw new Exception("invalid feed action", statusCodes::HTTP_NOT_FOUND);
										break;
									}
								}
							}
						break;
						case 'code':
							$this->object = library::catalog()->url[3];
							if(!isset(library::catalog()->url[4])) {
								$this->action = 'master';
							} else {
								$this->action = library::catalog()->url[4];
							}
						break;
					}
					$this->generate();
				}
			}			
		}
	}
	/**
	 * map function
	 * list all the possible feeds
	 */
	function map() {
		$html = array();
		$html["title"] = 'Feeds';
		$html["meta"] = '';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = '';
		$html["script"] = '';

		$url = QOOB_DOMAIN.'feeds/';

		$map = '<p>This is a map of the feeds for '.QOOB_DOMAIN.', from the blog and code sections.</p><br class="clear" /><div class="floatLeft gitFeeds"><h3>Blog by category:</h3><ul>';
		$blog = $this->model('blogModel');
		$cats = $blog->getBlogCategories();
		foreach ($cats as $cat) {
			if(strpos($cat['blog_cat_id'], '.') > 0) {
				$map .= '<li><a href="'.$url.'rss/blog/category/'.$cat['url'].'"><img src="'.BASE_URL.'style/img/feeds-rss.png" alt="RSS" title="RSS" width="16" height="16" /></a><a href="'.$url.'atom/blog/category/'.$cat['url'].'"><img src="'.BASE_URL.'style/img/feeds-atom.png" alt="atom" title="atom" width="16" height="16" /></a>&nbsp;&nbsp;. '.$cat['name'].'</li>';
			} else {
				$map .= '<li><a href="'.$url.'rss/blog/category/'.$cat['url'].'"><img src="'.BASE_URL.'style/img/feeds-rss.png" alt="RSS" title="RSS" width="16" height="16" /></a><a href="'.$url.'atom/blog/category/'.$cat['url'].'"><img src="'.BASE_URL.'style/img/feeds-atom.png" alt="atom" title="atom" width="16" height="16" /></a>&nbsp;'.$cat['name'].'</li>';				
			}
		}
		$map .= '</ul></div><div class="floatLeft gitFeeds" style="margin-left: 50px;"><h3>Blog by tag:</h3><ul>';
		$tags = $blog->getTags();
		foreach ($tags as $tag) {
			$map .= '<li><a href="'.$url.'rss/blog/tag/'.$tag['url'].'"><img src="'.BASE_URL.'style/img/feeds-rss.png" alt="RSS" title="RSS" width="16" height="16" /></a><a href="'.$url.'atom/blog/tag/'.$tag['url'].'"><img src="'.BASE_URL.'style/img/feeds-atom.png" alt="atom" title="atom" width="16" height="16" /></a>&nbsp;'.$tag['name'].'</li>';
		}
		$map .= '</ul></div><div class="floatLeft gitFeeds" style="margin-left: 50px;"><h3>Git Commit History:</h3><ul>';

		$code = $this->model('codeModel');
		$repos = $code->getRepos();

		$this->library(qoob_types::utility, 'git');

		foreach ($repos as $repo) {
			$map .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$repo['name'].'</strong></li>';
			$this->git->init(QOOB_ROOT.'/repos/'.$repo["repo"]);
			foreach ($this->git->branches as $branch) {
				$map .= '<li><a href="'.$url.'rss/code/'.$repo['url'].'/'.$branch.'/"><img src="'.BASE_URL.'style/img/feeds-rss.png" alt="RSS" title="RSS" width="16" height="16" /></a><a href="'.$url.'atom/code/'.$repo['url'].'/'.$branch.'/"><img src="'.BASE_URL.'style/img/feeds-atom.png" alt="atom" title="atom" width="16" height="16" /></a>&nbsp;'.$branch.'</li>';
			}
		}
		$map .= '</ul></div><br class="clear" />';

		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Feeds',
			'subtitle' => 'RSS/ATOM syndicaion',
			'content' => $map,
			'comments' => 0
		);
		$html["body"] = $this->view("post", $post, true);
		$this->view("pixelgraff", $html);
	}
	/**
	 * generate function
	 * render the rss/atom feed
	 */
	function generate() {
		switch ($this->section) {
			case 'blog':
				//feed description
				$descrip = array(
						'link' => QOOB_DOMAIN.'blog/',
						'title' => 'the blog of xero harrison',
						'description' => 'a blog about code, art, hacks, technology, video games, life and random stuff.',
						'descriptionHtml' => false
				);
				//load blog db model
				$blog = $this->model("blogModel");

				switch ($this->action) {
					case 'newest':
						$result = $blog->getNewest(7);
						$posts = array();		
						if(count($result) > 0) {
							for($i = 0; $i < count($result); $i++) {
								$posts[$i]['title'] = $result[$i]['title'];
								$posts[$i]['link'] = QOOB_DOMAIN.'blog/'.$result[$i]['url'];
								$posts[$i]['description'] = html_entity_decode($result[$i]['excerpt']);
								$posts[$i]['descriptionHtml'] = true;
								$posts[$i]['date'] = $result[$i]['date'];
								$posts[$i]['author'] = 'x@xero.nu (xero harrison)';
							}
						}
						$this->library(qoob_types::utility, "syndication");
						die ($this->syndication->generate($this->type, $descrip, $posts));
					break;
					case 'tag':
						$result = $blog->checkTag($this->object);
						if(count($result) > 0) {
							$id = $result[0]['tag_id'];
							$result = $blog->getPostsByTag($id, 0, 7);
							$posts = array();
							for($i = 0; $i < count($result); $i++) {
								$posts[$i]['title'] = $result[$i]['title'];
								$posts[$i]['link'] = QOOB_DOMAIN.'blog/'.$result[$i]['url'];
								$posts[$i]['description'] = html_entity_decode($result[$i]['excerpt']);
								$posts[$i]['descriptionHtml'] = true;
								$posts[$i]['date'] = $result[$i]['date'];
								$posts[$i]['author'] = 'x@xero.nu (xero harrison)';
							}
							$this->library(qoob_types::utility, "syndication");
							die ($this->syndication->generate($this->type, $descrip, $posts));
						} else {
							throw new Exception("unknown tag", statusCodes::HTTP_NOT_FOUND);
						}
					break;
					case 'category':
						$result = $blog->checkCategory($this->object);
						if(count($result) > 0) {
							$id = $result[0]['blog_cat_id'];
							$result = (strpos($id, ".") > 0) ? $blog->getPostsBySubCat($id, 0, 7) : $blog->getPostsByCat($id, 0, 7);
							$posts = array();
							for($i = 0; $i < count($result); $i++) {
								$posts[$i]['title'] = $result[$i]['title'];
								$posts[$i]['link'] = QOOB_DOMAIN.'blog/'.$result[$i]['url'];
								$posts[$i]['description'] = html_entity_decode($result[$i]['excerpt']);
								$posts[$i]['descriptionHtml'] = true;
								$posts[$i]['date'] = $result[$i]['date'];
								$posts[$i]['author'] = 'x@xero.nu (xero harrison)';
							}
							$this->library(qoob_types::utility, "syndication");
							die ($this->syndication->generate($this->type, $descrip, $posts));
						} else {
							throw new Exception("unknown category", statusCodes::HTTP_NOT_FOUND);
						}
					break;
					default:
						throw new Exception("invalid feed action", statusCodes::HTTP_NOT_FOUND);
					break;
				}
			break;
			case 'code':
			//feed description
				$descrip = array(
						'link' => QOOB_DOMAIN.'code/',
						'title' => 'public git repository of xero harrison',
						'description' => 'commit history for ',
						'descriptionHtml' => false
				);
				//load blog db model
				$code = $this->model("codeModel");
				$result = $code->getRepo($this->object);
				if(count($result) > 0) {
					//---load GLiP
					$this->library(qoob_types::utility, 'git');
					$this->git->init(QOOB_ROOT.'/repos/'.$result[0]["repo"]);
					
					//---check branch name
					if(!in_array($this->action, $this->git->branches)) {
						throw new Exception("unknown branch", statusCodes::HTTP_NOT_FOUND);
					}
					$descrip['description'] .= $this->object.' '.$this->action.' branch';
					
					$gitcommit = $this->git->getTip($this->action);
					$obj = $this->git->getObject($gitcommit);
					$hist = $obj->getHistory();
					$hist = array_reverse($hist);
					$posts = array();
					$i = 0;
					foreach ($hist as $event) {
						$posts[$i]['title'] = $this->object.' commit: '.sha1_hex($event->name);
						$posts[$i]['link'] = QOOB_DOMAIN.'code/'.$this->object.'/'.$this->action.'/'.sha1_hex($event->name);
						$posts[$i]['description'] = $event->summary;
						$posts[$i]['descriptionHtml'] = false;
						$posts[$i]['date'] = $event->committer->time;
						$posts[$i]['author'] = $event->committer->email.' ('.$event->committer->name.')';
						$i++;
					}
					$this->library(qoob_types::utility, "syndication");
					die ($this->syndication->generate($this->type, $descrip, $posts));					
				} else {
					throw new Exception("unknown repo", statusCodes::HTTP_NOT_FOUND);
				}
			break;
			default:
				throw new Exception("invalid feed", statusCodes::HTTP_NOT_FOUND);
			break;
		}
	}	
}
?>