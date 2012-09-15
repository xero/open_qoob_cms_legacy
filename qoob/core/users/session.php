<?php
/**
 * session class
 * used to manipulate session data
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.522
 * @package qoob
 * @subpackage core.users
 */
class session extends controller {
	/**
	 * instance of the session controller
	 *
	 * @var session
	 */
	private static $instance;
	/**
	 * constructor
	 * the magic method that starts the session (if necessary).
	 */
	public function __construct() {
		if(!session_start()) @session_start();
		parent::__construct(null, false);
	}
	/**
	 * singleton
	 * the singleton function either returns the existing instance
	 * of session class. otherwise it creates an instance of the
	 * class then returns it.
	 *
	 * @return session
	 */
    public static function singleton() {
        if(!isset(self::$instance)){
	        self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * regenerator
     * creates a new random session id
     */
	public function regenerate() {
		if (function_exists("session_regenerate_id")) { 
            if (version_compare(phpversion(), "5.1.0", ">=")) { 
                session_regenerate_id(true); 
            } else { 
                session_regenerate_id(); 
            } 
        }
        /* @todo should i also reset the expiration??? */
	}
	/**
	 * setter
	 * set values into the session
	 * 
	 * @param string $key
	 * @param mixed $val
	 */
	public function set($key, $val) {
		if($key == "access" && $val == 0) {
			$val = -1;
		}
		$_SESSION[$key] = $val;
	}
	/**
	 * array setter
	 * set values into the session from an array
	 * 
	 * @param array $data
	 */
	public function set_data($data = array()) {
		if(is_array($data)) {
			foreach($data as $key => $val) {
				$_SESSION[$key] = $val;
			}
		}
	} 
	/**
	 * getter
	 * returns values from the session.
	 * the the key is not found, it returns false.
	 * 
	 * @param string $key
	 * @return mixed string|boolean
	 */
	public function get($key) {
		if(!empty($_SESSION[$key])){
			return $_SESSION[$key];
		} else {
			return false;
		}
	}
	/**
	 * destroyer 
	 * removes all data from a session.
	 */
	public function destroy() {
		/*
		$_SESSION = array();
		session_destroy();
		*/
		$_SESSION = array();
		session_destroy();
		$cookieParams = session_get_cookie_params();
		setcookie(session_name(), '', 0, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
		session_unset();
		unset($_SESSION);		
	}
	/**
	 * fingerprint
	 * creates an MD5 fingerprint of the user.
	 * based on user-agent, the first 2 blocks
	 * of the ip address, the current session id,
	 * and a user defined salt.
	 * 
	 * @return string
	 */
	public function fingerprint() {
		//start w/ a secret key
		$fp = library::catalog()->hashpass;
		
		//add the first 2 blocks of the ip
		$blocks = explode(".", $_SERVER['REMOTE_ADDR']); 
		$fp .= $blocks[0].".".$blocks[1]; 

		//mix in the browser id
		$fp.= $_SERVER['HTTP_USER_AGENT'];
		
		//finally add the session id
		$fp.= session_id();
		
		//and hash the whole thing
		return md5($fp);
	}
	/**
	 * validation
	 * checks if a users session fingerprint matches
	 * a newly generated fingerprint.
	 * 
	 * @return boolean
	 */
	public function validate() {
		if(!isset($_SESSION["fingerprint"]) || $_SESSION["fingerprint"] != $this->fingerprint()) {
			return false;
		} else {
			if(time() >= $_SESSION["expires"]) {
				return false;
			} else {
				return true;
			}
		}
	}
	/**
	 * random hash
	 * generates a random MD5 hash.
	 * 
	 * @return string
	 */
	public function randomHash() {
		//reseed the randomizer
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
		//generate
		return md5(uniqid(mt_rand(), true));
	}
	/**
	 * create a qoob session
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $username
	 * @param string $email
	 */
	public function setup($id, $name, $username, $email) {
		$this->regenerate();
		$this->set("qoob_blog_id", $this->randomHash());
		$this->set("fingerprint", $this->fingerprint());
		$this->set("expires", time()+ 86400); //one day from now (seconds)
		$this->set("qoob_admin_id", $id);
		$this->set("name", $name);
		$this->set("username", $username);
		$this->set("email", $email);
	}
}

?>