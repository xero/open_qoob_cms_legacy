<?php
/**
 * paging utility
 * this class is used to generate paging links. 
 * 
 * use the "init" function to setup the variables necessary
 * to generate the paging links, then call the "render" function
 * to create the html code.
 * 
 * @example 
 *		$this->library(qoob_types::utility, "paging");
 *		$this->paging->init(array("base_url" => "http://dev.cet.edu/qoob/videos/page/",
 *							      "total_rows" => $count, 
 *								  "per_page" => $limit,
 *								  "cur_page" => $page,
 *								  "num_tag_open" => '<div class="page">',
 *								  "num_tag_close" => "</div>",
 *								  "cur_tag_open" => '<div class="cur_page">',
 *								  "cur_tag_close" => "</div>",
 *								  "next_tag_open" => '<div class="next_page">',
 *								  "next_tag_close" => "</div>",
 *								  "prev_tag_open" => '<div class="prev_page">',
 *								  "prev_tag_close" => "</div>",
 *								  "first_tag_open" => '<div class="first_page">',
 *								  "first_tag_close" => "</div>",
 *								  "last_tag_open" => '<div class="last_page">',
 *								  "last_tag_close" => "</div>",
 *								  "full_tag_open" => '<div id="paging">',
 *								  "full_tag_close" => "</div>"));
 *		$html["body"] .= $this->paging->render();
 * 
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.2
 * @package qoob
 * @subpackage utils
 */
class paging {
	/**
	 * base url
	 * the url your paging links will be appended to
	 *
	 * @var string $base_url
	 * @example http://open.qoob.nu/blog/page/
	 */
	var $base_url			= '';
	/**
	 * total rows
	 * the total number of items we're paging
	 *
	 * @var int $total_rows
	 */
	var $total_rows  		= 0;
	/**
	 * per page
	 * maximum number of items displayed per page
	 *
	 * @var int $per_page
	 */
	var $per_page	 		= 10;
	/**
	 * number of links
	 * the number of "digit" links to show before/after the currently viewed page
	 *
	 * @var int $num_links
	 */
	var $num_links			= 10;
	/**
	 * current page
	 * the page number being currently viewed
	 *
	 * @var int $cur_page
	 */
	var $cur_page	 		=  0;
	/**
	 * html/css text displayed for the first link
	 *
	 * @var string $first_link
	 */
	var $first_link   		= '&lsaquo;&lsaquo; First';
	/**
	 * html/css text displayed for the next link
	 *
	 * @var string $next_link
	 */
	var $next_link			= '&gt;';
	/**
	 * html/css text displayed for the previous link
	 *
	 * @var string $prev_link
	 */
	var $prev_link			= '&lt;';
	/**
	 * html/css text displayed for the last link
	 *
	 * @var string $last_link
	 */
	var $last_link			= 'Last &rsaquo;&rsaquo;';
	/**
	 * opening html/css for the entire paging
	 *
	 * @var string $full_tag_open
	 */
	var $full_tag_open		= '';
	/**
	 * closing html/css for the entire paging
	 *
	 * @var string $full_tag_close
	 */
	var $full_tag_close		= '';
	/**
	 * opening html/css for the first page link
	 *
	 * @var string $first_tag_open
	 */
	var $first_tag_open		= '';
	/**
	 * closing html/css for the first page link
	 *
	 * @var string $first_tag_close
	 */
	var $first_tag_close	= '&nbsp;';
	/**
	 * opening html/css for the last page link
	 *
	 * @var string $last_tag_open
	 */
	var $last_tag_open		= '&nbsp;';
	/**
	 * closing html/css for the last page link
	 *
	 * @var string $last_tag_close
	 */
	var $last_tag_close		= '';
	/**
	 * opening html/css for the current page
	 *
	 * @var string $cur_tag_open
	 */
	var $cur_tag_open		= '&nbsp;<strong>';
	/**
	 * closing html/css for the current page
	 *
	 * @var string $cur_tag_close
	 */
	var $cur_tag_close		= '</strong>';
	/**
	 * opening html/css for the next page link
	 *
	 * @var string $next_tag_open
	 */
	var $next_tag_open		= '&nbsp;';
	/**
	 * closing html/css for the next page link
	 *
	 * @var string $next_tag_close
	 */
	var $next_tag_close		= '&nbsp;';
	/**
	 * opening html/css for the previous page link
	 *
	 * @var string $prev_tag_open
	 */
	var $prev_tag_open		= '&nbsp;';
	/**
	 * closing html/css for the previous page link
	 *
	 * @var string $prev_tag_close
	 */
	var $prev_tag_close		= '';
	/**
	 * opening html/css for a page link
	 *
	 * @var string $num_tag_open
	 */
	var $num_tag_open		= '&nbsp;';
	/**
	 * closing html/css for a page link
	 *
	 * @var string $num_tag_close
	 */
	var $num_tag_close		= '';

	/**
	 * initilizer function
	 * used to set the necessary variables to create paging.
	 *
	 * @param array $params
	 */	
	function init($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				if (isset($this->$key)) {
					$this->$key = $val;
				}
			}
		}
	}
	/**
	 * render function
	 * creates the paging html code
	 *
	 * @return string
	 */
	function render() {
		// if item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0) {
			return '';
		}

		// calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// only one page? nothing more to do here then.
		if ($num_pages == 1) {
			return '';
		}
		
		if (!is_numeric($this->cur_page)){
			$this->cur_page = 0;
		}

		// page number beyond the result range? then show the last page
		if ($this->cur_page > $this->total_rows){
			$this->cur_page = ($num_pages - 1) * $this->per_page;
		}

		// calculate the start and end numbers. used to determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		$html = "";

		// render "first" link
		if  ($this->cur_page != $start) {
			$html .= $this->first_tag_open.'<a href="'.$this->base_url.'1/">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// render "previous" link
		if  ($this->cur_page != 1) {
			$prev = $this->cur_page-1;
			if($prev <= 0) $prev = '';
			$html .= $this->prev_tag_open.'<a href="'.$this->base_url.$prev.'/">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}

		// render digit links
		for ($i = $start -1; $i <= $end; $i++) {			
			if ($i > 0) {
				// current page
				if ($this->cur_page == $i) {
					$html .= $this->cur_tag_open.$i.$this->cur_tag_close;
				} else {
					$n = ($i == 0) ? "" : $i;
					$html .= $this->num_tag_open.'<a href="'.$this->base_url.$n.'/">'.$i.'</a>'.$this->num_tag_close;
				}
			}
		}

		// render "next" link
		if ($this->cur_page < $num_pages) {
			$next = $this->cur_page+1;
			if($next >= $end) $prev = $end;
			$html .= $this->next_tag_open.'<a href="'.$this->base_url.$next.'/">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		
		// render "last" link
		if  ($this->cur_page != $end) {
			$html .= $this->last_tag_open.'<a href="'.$this->base_url.$end.'/">'.$this->last_link.'</a>'.$this->last_tag_close;
		}
		
		// add the wrapper HTML and return
		return $this->full_tag_open.$html.$this->full_tag_close;
	}
}

?>