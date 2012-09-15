<?php
/**
 * session model
 * SQL functions for managing sessions
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.02
 * @package app
 * @subpackage models
 */
class session extends model {
	/**
	 * constructor function
	 * sets the database adapter type to mySQL.
	 */
	public function __construct() {
		parent::__construct("mysql");
	}
	/**
	 * check if a user has an existing session
	 *
	 * @param string $id
	 * @return array
	 */
	public function checkSession($id) {
		$id = $this->DB->sanitize($id);
		return $this->DB->query("SELECT * FROM `session` WHERE `session_id` = '$id' LIMIT 1;");
	}
	/**
	 * return a session from the database
	 * if the fingerprints match and has not expired
	 *
	 * @param string $id
	 * @param string $fingerprint
	 * @param int 	 $expires
	 * @return array
	 */
	public function getSession($id, $fingerprint, $expires) {
		$id 			= $this->DB->sanitize($id);
		$fingerprint 	= $this->DB->sanitize($fingerprint);
		$expires 		= $this->DB->sanitize($expires);
		return $this->DB->query("SELECT * FROM `session` WHERE `session_id` = '$id' AND `fingerprint` = '$fingerprint' AND `expires` > $expires");
	}
	/**
	 * add a new session to the database
	 *	 
	 * @param string $session_id
	 * @param string $qoob_id
	 * @param string $fingerprint
	 * @param int 	 $expires 
	 * @param string $data
	 */
	public function addSession($session_id, $qoob_id, $fingerprint, $expires, $data) {
		$session_id 	= $this->DB->sanitize($session_id);
		$qoob_id 		= $this->DB->sanitize($qoob_id);
		$fingerprint 	= $this->DB->sanitize($fingerprint);
		$expires 		= $this->DB->sanitize($expires);
		$data 			= $this->DB->sanitize($data);
		$this->DB->query("INSERT INTO `session` (`auto_id`, `session_id`, `qoob_id`, `fingerprint`, `expires`, `data`) VALUES (NULL, '$session_id', '$qoob_id', '$fingerprint', '$expires', '$data');", false);
	}
	/**
	 * modify an existing session
	 *
	 * @param string $id
	 * @param string $expires
	 * @param string $data
	 */
	public function modSession($id, $expires, $data) {
		$id 		= $this->DB->sanitize($id);
		$expires 	= $this->DB->sanitize($expires);
		$data 		= $this->DB->sanitize($data);
		$this->DB->query("UPDATE `session` SET `data` = '$data', `expires` = '$expires' WHERE `session_id` = '$id';", false);
	}
	/**
	 * delete a session
	 *
	 * @param string $id
	 */
	public function delSession($id) {
		$id = $this->DB->sanitize($id);
		$this->DB->query("DELETE FROM `session` WHERE `session_id` = '$id';", false);
	}	
	/**
	 * deletes old sessions from the database
	 *
	 * @param int $expires
	 */
	public function cleanSessions($expires) {
		$expires = $this->DB->sanitize($expires);
		$this->DB->query("DELETE FROM `session` WHERE `expires` < $expires;", false);
	}
	/**
	 * returns a count of the total number of active sessions
	 *
	 * @param int $expires
	 */
	public function countUsers($expires) {
		$expires = $this->DB->sanitize($expires);
		return $this->DB->query("SELECT COUNT(`session_id`) as 'theCount' FROM `session` WHERE `expires` > $expires;");
	}
}

?>