<?php
/**
 * hash class
 * functions creating password hashes.
 * based on ideas from Nils Reimers (www.php-einfach.de)
 * ImprovedHashAlgorithm (IHA) released open-source under 
 * the GNU Lesser General Public License version 2.1
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.3
 * @package qoob
 * @subpackage utils.crypto
 * @category cryptography
 */
class hash {
	/**
	 * @var string $salt an optional string used for encryption
	 */
	private $salt = "";
	/**
	 * @var boolean $sha1 a boolean if true the sha1 algorithm will be used if false md5 is used.
	 */
	private $sha1 = false;
	/**
	 * @var int $rounds the number of times the hash will be key-stretched.  a higher value yealds increased security but at the cost of increased calculation time.
	 */
	private $rounds = 2500;
	/**
	 * @var int $saltLength the length of the salt string
	 */
	private $saltLength = 8;
	/**
	 * make hash function
	 * returns a strong hash value from a weak password.
	 * 
	 * @example $this->library(qoob_types::utility, "hash", "crypto/");
	 * $this->hash->saltLength = 10;
	 * $this->hash->salt = "aCgbEDzq9h";
	 * $this->hash->sha1 = true;
	 * $this->hash->rounds = 5000;
	 * $hash = $this->hash->make($pass);
	 * 	
	 * // $hash would be something like:
	 * // gAATiA;aCgbEDzq9h;iPl9xBKgfBGtE6iR4pQU1g5VgKs=
	 * 
	 * @param string $pass
	 * @return string
	 */
	public function make($pass) {
		return $this->generateHash($pass);
	}
	/**
	 * compare function
	 * checks if the value of $pass belongs to the value of $hash.
	 * 
	 * @todo what do you do when the hash is not in the correct format?
	 *       bacause false is if the hash and pass dont match... ?:P
	 * 
	 * @param string $pass
	 * @param string $hash
	 * @return boolean
	 */
	public function compare($pass, $hash) {
		if(substr_count($hash, ";") != 2) {
			return false;
		}
		list($header,$salt,$value) = explode(";", $hash);

		$this->salt = $salt;
		$header = base64_decode($header);
		$this->rounds = ord($header{1})<<16 | ord($header{2})<<8 | ord($header{3});
		$flag = ord($header{0});
		$this->sha1 = ((($flag&0x80)>>7) == 1);

		return ($this->generateHash($pass) == $hash);
	}
	/**
	 * benchmark function
	 * makes a benchmark of the key stretching method.
	 * $times is the number of hashes to preform and average.
	 * 
	 * @param int $times
	 * @return string
	 */
	public function benchmark($times = 10) {
		$str = "generated $times hashes ";
		
		$start = (double)microtime() + time();
		
		for($i=0;$i<$times;$i++) {
			$this->make("open_qoob_framework");
		}
		
		$end = (double)microtime() + time();
		$diff = round($end-$start, 4);
		
		return $str."in $diff seconds; average ".($diff/$times)." per hash.";
	}
	/**
	 * variable setter magic method
	 * 
	 * @param string $var
	 * @param string $val
	 */
	public function __set($var, $val) {
		$this->$var = $val;
	}
	/**
	 * variable getter magic method
	 * 
	 * @param string $var
	 * @return mixed
	 */
	public function __get($var) {
		return (isset($this->$var)) ? $this->$var : false;
	}
	/**
	 * hash generation function
	 * generates the hash of a password. the returned string is a
	 * semicolon deliminated string in header;salt;key format.
	 * an install unique string in your qoob app config is used
	 * as an added layer of security.
	 * 
	 * @internal
	 * @param $pass
	 */
	private function generateHash($pass) {
		if (empty($this->salt)) {
			$this->salt = $this->generateSalt();
		}
		$header = $this->generateHeader();
		// --- hashpass is set in the app config
		$key = $this->salt.$pass.library::catalog()->hashpass;
		
		if($this->sha1 == true && function_exists("sha1")) {
			$key = sha1($key);
		} else {
			$key = md5($key);
		}
		
		$key = base64_encode(pack("H*", $this->keyStretching($key)));
		
		return $header.";".$this->salt.";".$key;
	}
	/**
	 * salt generation function
	 * this creates a random salt based on the current time
	 * 
	 * @return string
	 */
	private function generateSalt() {
		$salt = base64_encode(pack("H*", md5(microtime())));
		return substr($salt, 0, $this->saltLength);
	}
	/**
	 * header generation function
	 * used to indentify what settings were used to generate the hash.
	 * 
	 * @internal
	 * @return string
	 */
	private function generateHeader() {
		$flag = (($this->sha1 == true && function_exists("sha1"))?1:0)<<7;
		return substr(base64_encode(pack("N*", $this->rounds | $flag<<24)), 0, 6);
	}
	/**
	 * key stretching function
	 * encrypting the key repeatedly makes cracking a password much more time consuming.
	 * 
	 * @internal
	 * @param string $key
	 * @return string
	 */
	private function keyStretching($key) {
		if($this->sha1 == true && function_exists("sha1")) {
			for ($i=0;$i<$this->rounds;++$i) {
				$key = sha1($key);
			}
		} else {
			for ($i=0;$i<$this->rounds;++$i) {
				$key = md5($key);
			}
		}
		return $key;
	}
}

?>