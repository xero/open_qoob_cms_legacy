<?php
/**
 * benchmark class
 * this class enables you to mark points and calculate the 
 * time difference between them.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.01
 * @package qoob
 * @subpackage utils
 */
class benchmark {
	/**
	 * @var array $markers the points in time that are used to benchmark
	 */
	var $markers = array();
	/**
	 * set a benchmark marker
	 * multiple calls to this function can be made so that several
	 * execution points can be timed.
	 *
	 * @param	string	$name	name of the marker
	 * @return	void
	 */
	function mark($name) {
		$this->markers[$name] = microtime();
	}
	
	/**
	 * elapsed time function
	 * calculates the time difference between two marked points.
	 *
	 * @param	string 	$point1		a particular marked point
	 * @param	string 	$point2		a particular marked point
	 * @param	int 	$decimals	the number of decimal places
	 * @return	mixed
	 */
	function elapsed_time($point1 = "", $point2 = "", $decimals = 4) {
		if ($point1 == "") {
			return "";
		}

		if (!isset($this->markers[$point1])) {
			return "";
		}

		if (!isset($this->marker[$point2])) {
			$this->markers[$point2] = microtime();
		}

		list($sm, $ss) = explode(" ", $this->markers[$point1]);
		list($em, $es) = explode(" ", $this->markers[$point2]);

		return number_format(($em + $es) - ($sm + $ss), $decimals);
	}
}

?>