<?php
/**
 * tag cloud
 * takes an array of tags (tag_id, name, url, tag_count)
 * and generates an html tag cloud from them.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported 
 * @version 1.0
 * @package qoob
 * @subpackage utils
 */
final class cloud {
	/**
	 * @var int $max_size maximun font size in percent
	 */
	private $max_size = 250;
	/**
	 * @var int $min_size minimun font size in percent
	 */
	private $min_size = 80;
	/**
	 * set maximun font size
	 * @param int $max
	 */	
	function setMax($max) {
		$this->max_size = $max;
	}
	/**
	 * set minimum font size
	 * @param int $min
	 */	
	function setMin($min) {
		$this->min_size = $min;
	}
	/**
	 * generate function
	 * returns the html tag cloud from an array.
	 * 
	 * @param array $tags
	 * @return string
	 */
	function generate($tags) {  
		if(!is_array($tags)) {
			return false;
		}

		$numbers = array();
		foreach($tags as $obj)
		{
		   $numbers[] = $obj['tag_count'];
		}
		$max_qty = max($numbers);
		$min_qty = min($numbers);
		
		$spread = $max_qty - $min_qty;
		
		if (0 == $spread) { //we don't want to divide by zero
			$spread = 1;
		}					
		$step = ($this->max_size - $this->min_size)/($spread); 
		
		$html = '<ul id="tags">';
		$i = 0;
		while ($i < count($tags)) {
			$size = $this->min_size + (($tags[$i]['tag_count'] - $min_qty) * $step);
			//$size = ceil($size); //uncomment for % whole sizes
			$html .= '<li><span onclick="tagit(\''.$tags[$i]['tag_id'].'\');" id="tag'.$tags[$i]['tag_id'].'" style="font-size: '.$size.'%" title="'.$tags[$i]['tag_count'].' posts tagged '.$tags[$i]['name'].'">'.$tags[$i]['name'].'</span></li> ';//the space at the end is important!
			$i++;
		}
		$html .= '</ul>';
		return $html;
	}
	function make($tags, $url, $highlight = '', $cat = '') {
		if(!is_array($tags)) {
			return false;
		}

		$numbers = array();
		foreach($tags as $obj)
		{
		   $numbers[] = $obj['tag_count'];
		}
		$max_qty = max($numbers);
		$min_qty = min($numbers);
		
		$spread = $max_qty - $min_qty;
		
		if (0 == $spread) { //we don't want to divide by zero
			$spread = 1;
		}					
		$step = ($this->max_size - $this->min_size)/($spread); 
		
		$html = '<ul>';
		$i = 0;
		while ($i < count($tags)) {
			$size = $this->min_size + (($tags[$i]['tag_count'] - $min_qty) * $step);
			//$size = ceil($size); //uncomment for % whole sizes
			if($tags[$i]['url'] == $highlight) {
				$html .= '<li class="highlight"><a href="'.$url.$tags[$i]['url'].'" style="font-size: '.$size.'%" title="'.$tags[$i]['tag_count'].' posts tagged '.$tags[$i]['name'].'">'.$tags[$i]['name'].'</a></li> ';//the space at the end is important!
			} else {
				$html .= '<li><a href="'.$url.$tags[$i]['url'].'" style="font-size: '.$size.'%" title="'.$tags[$i]['tag_count'].' posts tagged '.$tags[$i]['name'].'">'.$tags[$i]['name'].'</a></li> ';//the space at the end is important!
			}
			$i++;
		}
		$html .= '</ul>';
		return $html;
	}
}

?>