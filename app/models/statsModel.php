<?php
/**
 * stats model
 * SQL functions for statistical analysis
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package app
 * @subpackage models
 */
class statsModel extends model {
	/**
	 * constructor 
	 * set the database type to mySQL
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * save function
	 * insert the statistics into the database
	 *
	 * @param array $stats
	 */
	public function save($stats) {
		if(!is_array($stats)) {
			throw new Exception("missing data in the stats", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
		} else {			
			$stats["referer"] = $this->DB->sanitize($stats["referer"]);
			$stats["referer_checksum"] = $this->DB->sanitize($stats["referer_checksum"]);
			$stats["domain"] = $this->DB->sanitize($stats["domain"]);
			$stats["domain_checksum"] = $this->DB->sanitize($stats["domain_checksum"]);
			$stats["resource"] = $this->DB->sanitize($stats["resource"]);
			$stats["resource_checksum"] = $this->DB->sanitize($stats["resource_checksum"]);
			$stats["resource_title"] = $this->DB->sanitize($stats["resource_title"]);
			$stats["resolution"] = $this->DB->sanitize($stats["resolution"]);
			$stats["browser"] = $this->DB->sanitize($stats["browser"]);
			$stats["version"] = $this->DB->sanitize($stats["version"]);
			$stats["platform"] = $this->DB->sanitize($stats["platform"]);
			$stats["type"] = $this->DB->sanitize($stats["type"]);
			$stats["useragent"] = $this->DB->sanitize($stats["useragent"]);
			$stats["ipaddress"] = $this->DB->sanitize($stats["ipaddress"]);
			$stats["hostname"] = $this->DB->sanitize($stats["hostname"]);
			$stats["flash_version"] = $this->DB->sanitize($stats["flash_version"]);
			$stats["location"] = $this->DB->sanitize($stats["location"]);
			$stats["date"] = $this->DB->sanitize($stats["date"]);
			//defaults check
			if($stats["platform"] == '') { $stats["platform"] = 'unknown'; }
			if($stats["location"] == '') { $stats["location"] == 'unknown'; }
			$this->DB->query("INSERT INTO `stats` (`auto_id`, `referer`, `referer_checksum`, `domain`, `domain_checksum`, `resource`, `resource_checksum`, `resource_title`, `resolution`, `browser`, `version`, `platform`, `type`, `useragent`, `ipaddress`, `hostname`, `location`, `flash_version`, `date`) VALUES (NULL , '".$stats["referer"]."' ,  '".$stats["referer_checksum"]."',  '".$stats["domain"]."',  '".$stats["domain_checksum"]."',  '".$stats["resource"]."',  '".$stats["resource_checksum"]."',  '".$stats["resource_title"]."',  '".$stats["resolution"]."',  '".$stats["browser"]."',  '".$stats["version"]."',  '".$stats["platform"]."',  '".$stats["type"]."', '".$stats["useragent"]."',  '".$stats["ipaddress"]."',  '".$stats["hostname"]."', '".$stats["location"]."',  '".$stats["flash_version"]."',  '".$stats["date"]."');", false);
		}		
	}
	/**
	 * browsers
	 * retrieve statistics about users browsers
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function browsers($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `browser`, COUNT(`browser`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `browser` ORDER BY `total` DESC;");
	}
	/**
	 * platforms
	 * retrieve statistics about users opertating system platforms
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function platforms($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `platform`, COUNT(`platform`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `platform` ORDER BY `total` DESC;");
	}
	/**
	 * resolutuions
	 * retrieve statistics about screen resolutions
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function resolutions($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `resolution`, COUNT(`resolution`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end AND `resolution` NOT LIKE '0x0' GROUP BY `resolution` ORDER BY `total` DESC LIMIT 20;");
	}
	/**
	 * resolutions count
	 * retrieve a count of the statistics about screen resolutions
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function resolutions_count($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);
		return $this->DB->query("SELECT COUNT(*) as 'res_count' FROM `stats` WHERE `date` >= $start AND `date` <= $end AND `resolution` NOT LIKE '0x0';");
	}
	/**
	 * flash
	 * retrieve statistics about users flash plugin version
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function flash($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `flash_version`, COUNT(`flash_version`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `flash_version` ORDER BY `total` DESC;");
	}
	/**
	 * visits
	 * retrieve a count of the statistics about visits
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function visits($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `ipaddress`, COUNT(`ipaddress`) as `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `ipaddress` ORDER BY `total` DESC");
	}
	/**
	 * visits
	 * retrieve statistics about visits
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function visits_div($start, $end){
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);
		return $this->DB->query("SELECT `ipaddress`, `date` FROM `stats` WHERE `date` >= $start AND `date` <= $end ORDER BY `date`;");
	}
	/**
	 * location
	 * retrieve statistics about users location
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function location($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `location`, COUNT(`location`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `location` ORDER BY `total` DESC;");
	}
	/**
	 * resource
	 * retrieve statistics about users browsers
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @param int $limit number of results 
	 * @return array
	 */
	public function resource($start, $end, $limit) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		$limit 	= $this->DB->sanitize($limit);	
		if($limit == 0) {
			$limitSQL = ';';
		} else {
			$limitSQL = ' LIMIT '.$limit.';';
		}
		return $this->DB->query("SELECT `resource`, COUNT(`resource`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `resource` ORDER BY `total` DESC ".$limitSQL);
	}
	/**
	 * referrers
	 * retrieve statistics about referring sites
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @param int $limit number of results 
	 * @return array
	 */
	public function referrers($start, $end, $limit) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		$limit 	= $this->DB->sanitize($limit);	
		if($limit == 0) {
			$limitSQL = ';';
		} else {
			$limitSQL = ' LIMIT '.$limit.';';
		}
		return $this->DB->query("SELECT `domain`, COUNT(`domain`) AS `total` FROM `stats` WHERE `date` >= $start AND `date` <= $end GROUP BY `domain` ORDER BY `total` DESC ".$limitSQL);
	}
	/**
	 * searches
	 * retrieve statistics about referring search engines
	 *
	 * @param int $start starting date
	 * @param int $end ending date
	 * @return array
	 */
	public function searches($start, $end) {
		$start 	= $this->DB->sanitize($start);
		$end 	= $this->DB->sanitize($end);		
		return $this->DB->query("SELECT `auto_id`, `referer` FROM `stats` WHERE `date` >= $start AND `date` <= $end AND lower(`referer`) LIKE '%google%' OR lower(`referer`) LIKE '%bing%' OR lower(`referer`) LIKE '%msn%' OR lower(`referer`) LIKE '%live%' OR lower(`referer`) LIKE '%voila%' OR lower(`referer`) LIKE '%yahoo%' OR lower(`referer`) LIKE '%aol%' OR lower(`referer`) LIKE '%tiscali%' OR lower(`referer`) LIKE '%lycos%' OR lower(`referer`) LIKE '%alexa%' OR lower(`referer`) LIKE '%alltheweb%' OR lower(`referer`) LIKE '%altavista%' OR lower(`referer`) LIKE '%dmoz%' OR lower(`referer`) LIKE '%netscape%' OR lower(`referer`) LIKE '%search%' OR lower(`referer`) LIKE '%excite%' ORDER BY `referer` ASC;");
	}
}

?>