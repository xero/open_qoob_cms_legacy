<?php
/**
 * syndication class
 * for generation of RSS and ATOM feeds
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported 
 * @version 1.2
 * @package qoob
 * @subpackage utils
 */
final class syndication {
	var $feed = '';
	var $type = 0;
	var $link = '';
	var $title = '';
	var $posts = array();
	var $author = '';
	var $description = '';
	var $descriptionHtml = true;
	/**
	 * feed type setter function
	 *
	 * @param int $type
	 * @example $this->syndication->setType(feed_types::ATOM);
	 */
	public function setType($type) {
		$this->type = $type;
	}
	/**
	 * feed description setter function
	 * args is an array of values.
	 *
	 * @public
	 * @param array $args
	 * @example $this->syndication->setDescrip(array(
	 *              'link' => 'http://blog.xero.nu/',
	 *              'title' => 'blog.xero.nu',
	 *              'description' => 'a blog about code, art, hacks, technology,fonts video games, life and other random stuff.',
	 *              'descriptionHtml' => false
	 *	         ));
	 */	
	public function setDescrip($args) {
		$this->link = isset($args['link']) ? $args['link'] : '';
		$this->title = isset($args['title']) ? $args['title'] : '';
		$this->author = isset($args['author']) ? $args['author'] : '';
		$this->description = isset($args['description']) ? $args['description'] : '';
		$this->descriptionHtml = isset($args['descriptionHtml']) ? $args['descriptionHtml'] : '';
	}
	/**
	 * feed data setter function
	 * the data param is a multi-dimensional array of post data.
	 *
	 * @public
	 * @param array $data
	 * @example $blog = $this->model("blogModel");
	 *		$result = $blog->getNewest();
	 *		$posts = array();		
	 *		if(count($result) > 0) {
	 *			for($i = 0; $i < count($result); $i++) {
	 *				$posts[$i]['title'] = $result[$i]['title'];
	 *				$posts[$i]['link'] = 'http://blog.xero.nu/'.$result[$i]['url'];
	 *				$posts[$i]['description'] = $result[$i]['excerpt'];
	 *				$posts[$i]['descriptionHtml'] = true;
	 *				$posts[$i]['date'] = $result[$i]['date'];
	 *				$posts[$i]['author'] = 'xero harrison';
	 *			}
	 *		}
	 *		$this->syndication->setData($posts);
	 */		
	public function setData($data) {
		if(is_array($data)) {
			$count = count($data);
			if($count > 0) {
				for($i=0; $i<count($data); $i++) {
					$this->posts[$i]['title'] = isset($data[$i]['title']) ? $data[$i]['title'] : '';
					$this->posts[$i]['link'] = isset($data[$i]['link']) ? $data[$i]['link'] : '';
					$this->posts[$i]['description'] = isset($data[$i]['description']) ? $data[$i]['description'] : '';
					$this->posts[$i]['descriptionHtml'] = isset($data[$i]['descriptionHtml']) ? $data[$i]['descriptionHtml'] : '';
					$this->posts[$i]['date'] = isset($data[$i]['date']) ? $data[$i]['date'] : '';
					$this->posts[$i]['author'] = isset($data[$i]['author']) ? $data[$i]['author'] : '';
				}
			} else {
				$this->posts[0]['title'] = 'no posts found';
				$this->posts[0]['link'] = $link;
				$this->posts[0]['description'] = $description;
				$this->posts[0]['descriptionHtml'] = $descriptionHtml;
				$this->posts[0]['date'] = time();
				$this->posts[0]['author'] = '';				
			}
		} else {
			throw new Exception("data must be in array format", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
		}
	}
	/**
	 * feed generate function
	 * optionally set feed type, description, and post data
	 * arrays in the same ways as their public method counterparts.
	 *
	 * @public
	 * @param array $data
	 * @example $descrip = array(
	 *				'link' => 'http://blog.xero.nu/',
	 *				'title' => 'blog.xero.nu',
	 *				'description' => 'a blog about code, art, hacks, technology, video games, life and random stuff.',
	 *				'descriptionHtml' => false
	 *		);
	 *		$blog = $this->model("blogModel");
	 *		$result = $blog->getNewest();
	 *		$posts = array();		
	 *		if(count($result) > 0) {
	 *			for($i = 0; $i < count($result); $i++) {
	 *				$posts[$i]['title'] = $result[$i]['title'];
	 *				$posts[$i]['link'] = 'http://blog.xero.nu/'.$result[$i]['url'];
	 *				$posts[$i]['description'] = $result[$i]['excerpt'];
	 *				$posts[$i]['descriptionHtml'] = true;
	 *				$posts[$i]['date'] = $result[$i]['date'];
	 *				$posts[$i]['author'] = 'xero harrison';
	 *			}
	 *		}
	 *		$this->library(qoob_types::utility, "syndication");
	 *		$type = strtolower(library::catalog()->feedtype) == "atom" ? feed_types::ATOM : feed_types::RSS;
	 *		die ($this->syndication->generate($type, $descrip, $posts));
	 */	
	public function generate($type, $descrip, $data) {
		if(isset($type)) $this->setType($type);
		if(isset($descrip)) $this->setDescrip($descrip);
		if(isset($data)) $this->setData($data);
		
		switch($this->type) {
			case feed_types::ATOM:
				$this->makeATOM();
			break;

			case feed_types::RSS:
			//--fall through
			default:
				$this->makeRSS();
			break;
		}
		return $this->feed;
	}
	/**
	 * make RSS feed function
	 * internal function to mine variables
	 * into an RSS 2.0 feed
	 *
	 * @private
	 */
	private function makeRSS() {
		//header('Content-Type: application/rss+xml; charset=ISO-8859-1');
		header('Content-Type: text/xml; charset=ISO-8859-1');

		//header
		$this->feed = '<?xml version="1.0" encoding="ISO-8859-1"?>'.PHP_EOL;
		$this->feed.= '<!-- generator="open[qoob]" -->'.PHP_EOL;
		$this->feed.= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		//channel
		$this->feed.= '    <channel>'.PHP_EOL;
		$this->feed.= '        <title>'.$this->title.'</title>'.PHP_EOL;
        if($this->descriptionHtml){
			$this->feed.= '        <description><![CDATA['.$this->description.']]></description>'.PHP_EOL;
		} else {
			$this->feed.= '        <description>'.$this->description.'</description>'.PHP_EOL;
		}
		$this->feed.= '        <link>'.$this->link.'</link>'.PHP_EOL;
        $this->feed.= '        <lastBuildDate>'.date("r", time()).'</lastBuildDate>'.PHP_EOL;
        $this->feed.= '        <generator>open[qoob]</generator>'.PHP_EOL;
        $this->feed.= '        <atom:link href="'.RAW_URL.'" rel="self" type="application/rss+xml" />'.PHP_EOL;
        //body
        for($i=0; $i<count($this->posts); $i++) {
        	$this->feed.= '        <item>'.PHP_EOL;
        	$this->feed.= '            <title>'.$this->posts[$i]['title'].'</title>'.PHP_EOL;
        	$this->feed.= '            <link>'.$this->posts[$i]['link'].'</link>'.PHP_EOL;
        	$this->feed.= '            <guid>'.$this->posts[$i]['link'].'</guid>'.PHP_EOL;
	        if($this->posts[$i]['descriptionHtml']){
				$this->feed.= '            <description><![CDATA['.$this->posts[$i]['description'].']]></description>'.PHP_EOL;
			} else {
				$this->feed.= '            <description>'.$this->posts[$i]['description'].'</description>'.PHP_EOL;
			}
			$this->feed.= '            <author>'.$this->posts[$i]['author'].'</author>'.PHP_EOL;
			//$this->feed.= '            <category>technology</category>'.PHP_EOL;
			$this->feed.= '            <pubDate>'.date("r", $this->posts[$i]['date']).'</pubDate>'.PHP_EOL;
        	$this->feed.= '        </item>'.PHP_EOL;
        }
        //footer
		$this->feed.= '    </channel>'.PHP_EOL;
		$this->feed.= '</rss>'.PHP_EOL;
	}
	/**
	 * make ATOM feed function
	 * internal function to mine variables
	 * into an ATOM 1.0 feed
	 *
	 * @private
	 */	
	private function makeATOM() {
		//header('Content-type: application/atom+xml; charset=ISO-8859-1');
		header('Content-Type: text/xml; charset=ISO-8859-1');

		//header
		$this->feed = '<?xml version="1.0"  encoding="ISO-8859-1"?>'.PHP_EOL;
		$this->feed.= '<!-- generator="open[qoob]" -->'.PHP_EOL;
		//channel
		$this->feed.= '<feed xmlns="http://www.w3.org/2005/Atom">'.PHP_EOL;	
		$this->feed.= '    <title>'.$this->title.'</title>'.PHP_EOL;
        if($this->descriptionHtml){
			$this->feed.= '    <subtitle type="html">'.htmlspecialchars($this->description).'</subtitle>'.PHP_EOL;
		} else {
			$this->feed.= '    <subtitle type="text">'.htmlspecialchars($this->description).'</subtitle>'.PHP_EOL;
		}		
		$this->feed.= '    <link href="'.$this->link.'" />'.PHP_EOL;
        $this->feed.= '    <updated>'.date("c", time()).'</updated>'.PHP_EOL;
        if($this->author != ''){        
			$this->feed.= '    <author>'.PHP_EOL;
			$this->feed.= '        <name>'.$this->author.'</name>'.PHP_EOL;		
			$this->feed.= '    </author>'.PHP_EOL;
		}
		$this->feed.= '    <id>'.$this->link.'</id>'.PHP_EOL;
		$this->feed.= '    <link rel="self" href="'.RAW_URL.'" />'.PHP_EOL;
		$this->feed.= '    <generator uri="http://open.qoob.nu/">open[qoob]</generator>'.PHP_EOL;
        //body
        for($i=0; $i<count($this->posts); $i++) {
        	$this->feed.= '    <entry>'.PHP_EOL;
        	$this->feed.= '        <title>'.$this->posts[$i]['title'].'</title>'.PHP_EOL;
        	$this->feed.= '        <id>'.$this->posts[$i]['link'].'</id>'.PHP_EOL;
        	$this->feed.= '        <link rel="alternate" href="'.$this->posts[$i]['link'].'" />'.PHP_EOL;
	        if($this->posts[$i]['descriptionHtml']){
				$this->feed.= '        <summary type="html">'.htmlspecialchars($this->posts[$i]['description']).'</summary>'.PHP_EOL;
			} else {
				$this->feed.= '        <summary type="text">'.htmlspecialchars($this->posts[$i]['description']).'</summary>'.PHP_EOL;
			}
			$this->feed.= '        <author>'.PHP_EOL;
			$this->feed.= '            <name>'.$this->posts[$i]['author'].'</name>'.PHP_EOL;
			$this->feed.= '        </author>'.PHP_EOL;			
			//$this->feed.= '        <category term="technology"/>'.PHP_EOL;
			$this->feed.= '        <updated>'.date("c", $this->posts[$i]['date']).'</updated>'.PHP_EOL;
        	$this->feed.= '    </entry>'.PHP_EOL;
        }
        //footer
		$this->feed.= '</feed>'.PHP_EOL;
	}
}
/**
 * feed types 
 * constants used for feed generation code hinting
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (c)opyright MMXII xero.nu
 * @version 1.0
 * @package qoob
 * @subpackage utils
 */
final class feed_types {
	/**
	 * @var RSS
	 */
	const RSS = 0;
	/**
	 * @var ATOM
	 */
	const ATOM = 1;
}
?>