<?php
/**
 * database session class
 * used to manipulate session data, but saved in the database.
 * why? for added security and use across domains.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage core.users
 */
class dbsession extends controller {
	/**
	 * the time to live for each session
	 *
	 * @var int
	 */
	private $sessionLifetime;
	/**
	 * md5 hash that represents the user
	 *
	 * @var string
	 * @see fingerprinting() method
	 */
	private $fingerprint;	
	/**
	 * session database model
	 *
	 * @var model
	 */
	private $sm;
	/**
	 * constructor
	 * the magic method that starts the session (if necessary).
	 * 
	 */
	public function __construct() {
        //---force cookies
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_only_cookies', 1);

        //---get session lifetime
        $ttl = get_cfg_var("session.gc_maxlifetime");
        $this->sessionLifetime = isset($ttl) ? $ttl : 1440; //defaults to 24 minutes

		//---create fingerprint
		$this->fingerprint = $this->fingerprinting();

        //---register the new handler
        session_set_save_handler(
            array(&$this, 'open'),
            array(&$this, 'close'),
            array(&$this, 'read'),
            array(&$this, 'write'),
            array(&$this, 'destroy'),
            array(&$this, 'clean')
        );
        register_shutdown_function('session_write_close');

        //---cross subdomain setup
        session_name('elabs');
		//ini_set("session.cookie_domain", "e-missions.net");

		//---start the session
		if(!@session_start()) { 
			@session_start();
		}
	}
    /**
     * regenerator
     * creates a new random session id
     *
     * @todo should i also reset the expiration???
     */
	public function regenerate() {
		//---capture the old session id
		$oldSessionID = session_id();

		if (function_exists("session_regenerate_id")) { 
            if (version_compare(phpversion(), "5.1.0", ">=")) { 
                session_regenerate_id(true); 
            } else { 
                session_regenerate_id(); 
            } 
	        //---destroy it
	        $this->destroy($oldSessionID);
        }
	}
	/**
	 * open function
	 * load the session model
	 */
	public function open($save_path, $session_name) {
		$this->sm = $this->model("session");
	}
	/**
	 * close function
	 * does nothing???
	 */
	public function close() {
		//ignore me!
	}
	/**
	 * read function
	 *
	 * @param string $session_id
	 * @return string
	 */
	public function read($session_id) {
		$result = $this->sm->getSession($session_id, $this->fingerprint, time());
		if(isset($result[0])) {
			//---return data
			return $result[0]['data'];
		} else {
			//---this must be an empty string
			return "";
		}
	}
	/**
	 * write function
	 *
	 * @param string $session_id
	 * @param string $session_data
	 */
	public function write($session_id, $session_data) {
		//---check for existing session
		$check = $this->sm->checkSession($session_id);
		$expires = time() + $this->sessionLifetime;
		if(!isset($check[0])) {
			//---add new session
			$this->sm->addSession($session_id, $this->randomHash(), $this->fingerprint, $expires, $session_data);
		} else {
			//---modify existing session
			$this->sm->modSession($session_id, $expires, $session_data);
		}
		return true;
	}
	/**
	 * destroy function
	 *
	 * @param string $session_id
	 */
	public function destroy($session_id) {
		//---remove from array
		$_SESSION = array();
		unset($_SESSION);	
		//---delete from db
		$this->sm->delSession($session_id);	
	}
	/**
	 * garbage collection
	 *
	 * @param int $maxlifetime
	 */
	public function clean($maxlifetime) {
		$this->sm->cleanSessions(time() - $this->sessionLifetime);
	}
	/**
	 * count the users currently online
	 *
	 * @return int
	 */
	public function countUsers() {
		$count = $this->sm->countUsers(time());
		if(isset($count[0])) {
			return intval($count[0]['theCount']);
		} else {
			return 0;
		}
	}
	/**
	 * fingerprinting
	 * creates an MD5 fingerprint of the user.
	 * based on user-agent, the first 2 blocks
	 * of the ip address, the current session id,
	 * and a user defined salt.
	 * 
	 * @return string
	 */
	public function fingerprinting() {
		//---start w/ a secret key
		$fp = library::catalog()->hashpass;
		
		//---add the first 2 blocks of the ip
		$blocks = explode(".", $_SERVER['REMOTE_ADDR']); 
		$fp .= $blocks[0].".".$blocks[1]; 

		//---mix in the browser id
		$fp.= $_SERVER['HTTP_USER_AGENT'];
		
		//---add the session id
		//$fp.= session_id();
		
		//---hash the whole thing
		return md5($fp);
	}	
	/**
	 * random hash
	 * generates a random MD5 hash.
	 * 
	 * @return string
	 */
	public function randomHash() {
		//---reseed the randomizer
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
		//---generate
		return md5(uniqid(mt_rand(), true));
	}	
	/**
	 * validation
	 * checks if a users session fingerprint matches
	 * a newly generated fingerprint.
	 * 
	 * @return boolean
	 */
	public function validate() {
		$sess = $this->sm->checkSession(session_id());
		if(!isset($sess[0]) || $sess[0]['data'] == '' || $sess[0]['fingerprint'] != $this->fingerprint) {
			return false;
		} else {
			if(time() >= (time()+$this->sessionLifetime)) {
				return false;
			} else {
				return true;
			}
		}
	}
}