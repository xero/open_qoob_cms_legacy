<?php
/**
 * inflector class
 * functions for formatting string into diffrent nomeclatures.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.2
 * @package qoob
 * @subpackage utils
 */
class inflector {
	/**
	 * the camelize function takes a space " " or underscore "_" 
	 * delimited string and returns a CamelCase string.
	 * 
	 * @static 
	 * @example "open qoob framework" = "OpenQoobFramework"
	 * @example "o_p_e_n q_o_o_b" = "OPENQOOB"
	 * @param string $str
	 * @return string
	 */
	public function camelize($str) {
        return str_replace(' ', '', ucwords(str_replace('_',' ', $this->clean($str))));
    }
    /**
     * the underscore function takes a space " " delimited
     * or CamelCase strings and returns a *lowercase* 
     * undercore "_" delimited string.
     *
     * @static 
     * @example "open qoob framework" = "open_qoob_framework"
     * @example "CamelSyntaxIsAwesome" = "camel_syntax_is_awesome"
     * @param  string $str
     * @return string
     */
    public function underscore($str) {
    	$str = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $str));
		return $this->clean(preg_replace('/[\s]+/', '_', strtolower(trim($str))));
    }
    /**
     * the humanize function takes an underscore "_" delimited
     * string and returns a space " " delimited string.
     *
     * @example "open_qoob_framework" = "open qoob framework"
     * @param string $str
     * @return string
     */
    public function humanize($str) {
        return str_replace('_', ' ', $str);
    }
    /**
     * a function to remove all characters besides
     * letters, numbers, underscores, and hyphens.
     *
     * @todo figure out what's wrong with '&'
     * 
     * @example "o!p@e#n$ %q^o~o*b" = "open qoob"
     * @param string $str
     * @return string
     */
    public function clean($str) {
    	// substitutes anything but letters, numbers and '_' with separator 
	    $str = preg_replace('~[^\\pL0-9_]+~u', '_', $str);
	    $str = trim($str, "-");
	    // take care of international characters
	    $str = iconv("utf-8", "us-ascii//TRANSLIT", $str);
	    $str = strtolower($str);
	    // keep only letters, numbers, '_' and separator
	    $str = preg_replace('~[^-a-z0-9_]+~', '', $str); 
	    return $str;
    }
	/**
	 * ordinalize function
     * converts a number to its english ordinal form.
     * 
     * @example 1 = 1st
     * @example 13 = 13th
     * @param integer $number
     * @return string
     */
    function ordinalize($number) {
        if (in_array(($number % 100),range(11,13))){
            return $number.'th';
        } else {
            switch (($number % 10)) {
                case 1:
                	return $number.'st';
                break;
                case 2:
                	return $number.'nd';
                break;
                case 3:
                	return $number.'rd';
                default:
                	return $number.'th';
                break;
            }
        }
    }    
}

?>