<?php 
/**                                                                      
 * mySQL Class
 * This file contains three classes:
 * 
 * "mySQL" - the main database class (used as a singleton) containing connection 
 *           variables, open/close routines, query generation/cleaning/execution
 *           and auto-id retrieval 
 *           
 * "dbException" - the error messages thrown from the main class
 * 
 * "mySQLquery" - a class that formats the database results into php arrays.
 *                result["row_id"]["column_name"]
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage core.data
 */
class dbException extends Exception {
	/**
	 * constructor
	 * sets the error code and messag
	 * 
	 * @param string $message
	 * @param int $code 500
	 */
    public function __construct($message, $code = 500) {
        // -- set error message
        statusCodes::setHeader($code);
    	$this->code = $code;
    	$this->message = mysql_error()."\n<br/><br/>\n".$this->hightlight($message);
    }
    /**
     * formats/color the sql query for ease of reading/debugging
     * 
     * @param string $sql
     * @return string
     */
    private function hightlight($sql) {
    	// --- remove html for debugging
    	$sql = strip_tags($sql);
		// --- add html line breaks
    	$sql = nl2br($sql);
		// --- hightlight symbols
		$sql = preg_replace('#(\(|\)|\,|\=|\.|-|\+|\!|\@)#si', "<span style='color: navy'>$1</span>", $sql);
		// --- hightlight digits
		$sql = preg_replace('#([0-9]+)#si', "<span style='color: orange'>$1</span>", $sql);		
		// --- hightlight keywords
		$sql = preg_replace('#(SELECT|UPDATE|INSERT|DELETE|DROP|TRUNCATE|FROM|WHERE|IN|AS|JOIN|INNER|LEFT|RIGHT|LIMIT|GROUP BY|ORDER BY|ON|HAVING|COUNT|MIN|MAX)\s#si', "<b style='color: blue'>$1</b>&nbsp;", $sql);
		return $sql;
	}
}

class mySQL implements iDB {    
    /**
     * @var string $dbhost the database hostname
     */
	private $dbhost;
    /**
     * @var string $dbuser the database username
     */
    private $dbuser;
    /**
     * @var string $dbpass the database password
     */
    private $dbpass;
    /**
     * @var string $dbname the database name
     */
    private $dbname;
    
    /**
     * @var object $db the database reference
     */
    protected $db = null;
    /**
     * @var object $instance the singleton instance of the mySQL class
     */
    protected static $instance = null;
    /**
     * @var string $sql the sql query
     */    
    protected $sql = null;

    /**
     * getInstance
     * singleton pattern for instantiating the DB class
     * 
     * @return mySQL
     */
    public static function getInstance() {
        if(!self::$instance)
        {
            self::$instance = new mySQL();
        }
        return self::$instance;
    }
    /**
     * constructor
     * calls the init and connect functions
     */
    protected function __construct() {
    	$this->init();
    	$this->connect();
    }
    /**
     * initilizer
     * sets up database connection varibles from the config file
     */
    public function init() {
    	$this->dbhost = library::catalog()->db_host;
    	$this->dbuser = library::catalog()->db_user;
    	$this->dbpass = library::catalog()->db_pass;
    	$this->dbname = library::catalog()->db_name;
    }
    /**
     * connect
     * creates the mySQL database connection and selects the
     * appropriate table. throws a dbException on failure.
     */
    public function connect() {
        if(($db = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass)) === false) {
        	throw new dbException("Can't connect to {$this->dbuser}@{$this->dbhost}");
        }
        
        if((@mysql_select_db($this->dbname, $db)) === false) {
            throw new dbException("Can't connect to database {$this->dbname}");
        }
        $this->db = $db;
    }

    /**
     * sanitizing
     * simple method for injection attack protection
     * 
     * @param string $string
     * @return string
     */
    public function sanitize($string) {
        if(get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return mysql_real_escape_string($string);
    }
    /**
     * escape
     * simple method for injection attack protection which
     * replaces any non-ascii character with its hex code.
     * 
     * @param string $string
     * @return string
     */
	public function escape($string) {
	    $return = '';
	    for($i = 0; $i < strlen($string); ++$i) {
	        $char = $string[$i];
	        $ord = ord($char);
	        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
	            $return .= $char;
	        else
	            $return .= '\\x' . dechex($ord);
	    }
	    return $return;
	}
    
    /**
     * SQL query function
     * executes a mySQL query.
     * make sure all insert, and update statements have
     * the results flag set to false.
     * 
     * @param string $statement
     * @param boolean $results
     * @return object|boolean
     */
    public function query($statement, $results = true) {
        $this->sql = $statement;
        $query = new mySQLquery($statement, $this->db);
    	if($results) {
	        return $query->result();
    	} else {
    		return true;
    	}
    }

	/**
	 * SQL query generation function
	 * pass this function a stored procedure and an array of parameters
	 * in name values pairs to replace in the spored procedure.
	 *
	 * @param string $sp stored procedure
	 * @param array $args
	 * @return string
	 */
	public function makeQuery($sp, $args) {
		$haystack = $sp;
		foreach ($args as $key => $value) {
			$needle = "&".$key."&";
			$value = $this->sanitize($value);
			$haystack = str_replace($needle, $value, $haystack);
		}
		return $haystack;
	}
    
    /**
     * get insertID
     * used to get the last inserted record's id
     * 
     * @return int|string
     */
    public function insertID() {
        return mysql_insert_id($this->db);
    }
    
    /**
     * destructor
     * close the connection when finished
     */
    public function __destruct() {
        @mysql_close($this->db);
    }
}

class mySQLquery {
    protected $result;
    private $link = null;
    
    /**
     * constructor
     * gets the results of the mySQL query
     * or throws a dbException error.
     * 
     * @param string $query
     * @param object $link mysql_connection
     */
    public function __construct($query, $link) {
        $this->link = $link;
        if(($this->result = @mysql_query($query, $link)) === false) {
            throw new dbException($query, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * get result
     * returns the results of the mySQL query
     * 
     * @return array
     */
    public function result() {
        $result = array();            	
		//while ($row =  @mysql_fetch_assoc($this->result)) {
        while (($row =  @mysql_fetch_assoc($this->result)) != false) {
            $result[] = $row;
        }
        return $result;
    }

    /**
     * number of rows
     * returns the number of rows in a given result
     * 
     * @return int
     */
    public function num_rows() {
        return @mysql_num_rows($this->link);
    }

    /**
     * destructor
     * call's free result only if result has be used
     */
    public function __destruct() {
    	if(is_array($this->result)) {
        	@mysql_free_result($this->result);
    	}
    }
}

?>