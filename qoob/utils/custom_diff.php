<?php
/**
 * custom diff class
 * pass two comma separated lists of numbers to the run function
 * and it will return an array of the new values, and values removed.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.1
 * @package qoob
 * @subpackage utils
 * @example $this->library(qoob_types::utility, "custom_diff");
 *			$result = $this->custom_diff->run('1,2', '2,5');
 * 			$result = Array (
 * 			       [add] => Array (
 * 			            [1] => 5
 *  			   )
 *  			   [del] => Array (
 * 			            [0] => 1
 * 			        )
 * 			)
 */
final class custom_diff {
	/**
	 * run function
	 * finds the differences, additions and subtractions,
	 * between two numeric lists.
	 *
	 * @param string $old a comma delimited string
	 * @param string $new a comma delimited string
	 * @return array
	 */
	public function run($old, $new) {
		//explode to arrays
		$old = explode(",", $old);
		$new = explode(",", $new);
		//sort them numerically
		sort($old, SORT_NUMERIC);
		sort($new, SORT_NUMERIC);
		//find differences
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);
		//return
		$r = array(
			'add' => $add, 
			'del' => $del
		);
		return $r;
	}
}

?>