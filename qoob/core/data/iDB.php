<?php
/**
 * qoob database adapter interface
 * im still not sure i'm going to need this.
 * im trying to get db type hinting into the
 * model class this way.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 0.1
 * @package qoob
 * @subpackage core.data  
 */
interface iDB {
	/**
	 * get instance function
	 * returns a singleton instance of the database class
	 *
	 * @return object $instance
	 */
	public static function getInstance();
    /**
     * connect
     * creates the mySQL database connection and selects the
     * appropriate table. throws a dbException on failure.
     */
    public function connect();
	/**
	 * sanitize function
	 * check and clean a string for SQL injections
	 *
	 * @param string $string the string to be sanitized
	 * @return string
	 */
	public function sanitize($string);
	/**
	 * query function
	 * submit a string to be used as an SQL query.
	 * if the results boolean is true, an array of the query results will be returned 
	 *
	 * @param string $statement the SQL statement to be executed
	 * @param boolean $results default = true
	 * @return mixed array|boolean
	 */	
	public function query($statement, $results = true);
	/**
	 * SQL query generation function
	 * pass this function a stored procedure and an array of parameters
	 * in name values pairs to replace in the spored procedure.
	 *
	 * @param string $sp stored procedure
	 * @param array $args
	 * @return string
	 */
	public function makeQuery($sp, $args);
}

?>