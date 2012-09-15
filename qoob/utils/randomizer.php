<?php
/**
 * randomizer class
 * a php pseudo-random number generator (PRNG) using a mersenne twister algorithm
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 8.06
 * @package qoob
 * @subpackage utils
 * @category math
 */
final class randomizer {
	var $data = array();
	function add($string,$weight=1){
		$this->data[] = array('s' => $string, 'w' => $weight);
	}
	function getData() {
		return $this->data; 
	}
	function optimize(){
		$new = array();
		foreach($this->data as $var){
			if($new[$var['s']]){
				$new[$var['s']] += $var['w'];
			}else{
				$new[$var['s']] = $var['w'];
			}
		}
		unset($this->data);
		foreach($new as $key=>$var){
			$this->data[] = array('s' => $key, 'w' => $var);
		}
	}
	/** 
	 * shuffle
	 * shuffles the array using the Fisher-Yates shuffle. 
	 * 
	 * D. E. Knuth: The Art of Computer Programming, Volume 2, 
	 * Third edition. Section 3.4.2, Algorithm P, pp 145. Reading: 
	 * Addison-Wesley, 1997. ISBN: 0-201-89684-2. 
	 * 
	 * R. A. Fisher and F. Yates: Statistical Tables. London, 1938. 
	 */ 
	function shuffle() {
		$total = count($this->data); 
   		for ($i = 0; $i<$total; $i++) {
			$j = @mt_rand(0, $i);
			$temp = $this->data[$i];
			$this->data[$i] = $this->data[$j];
			$this->data[$j] = $temp;
		}
	}
	function reseed() {
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		mt_srand($seed);
	}
	function select($amount=1){
		if($amount == 1){
			$this->reseed();
			$rand = mt_rand(0, count($this->data)-1);
			$result = $this->data[$rand]['s'];
		}else{
			$i = 0;
			while($i<$amount){
				$this->reseed();				
				$result[] = $this->data[mt_rand(0, count($this->data)-1)]['s'];
				++$i;
			}
		}
		return $result;
	}
	function select_unique($amount=1){
		if($amount == 1){
			$this->reseed();
			$rand = mt_rand(0, count($this->data)-1);
			$result = $this->data[$rand]['s'];
		}else{
			$rand = array_rand($this->data, $amount);
			foreach($rand as $var){
				$result[] = $this->data[$var]['s'];
			}
		}
		return $result;
	}
	
	function select_weighted($amount=1){
		$count = count($this->data);
		$i = 0;
		$max = -1;
		while($i < $count){
			$max += $this->data[$i]['w'];
			++$i;
		}
		if(1 == $amount){
			$this->reseed();
			$rand = mt_rand(0, $max);
			$w = 0; $n = 0;
			while($w <= $rand){
				$w += $this->data[$n]['w'];
				++$n;
			}
			$key = $this->data[$n-1]['s'];
		}else{
			$i = 0;
			while($i<$amount){
				$this->reseed();
				$random[] = mt_rand(0, $max);
				++$i;
			}
			sort($random);
			$i = 0;
			$n = 0;
			$w = 0;
			while($i<$amount){
				while($w<=$random[$i]){
					$w += $this->data[$n]['w'];
					++$n;
				}
				$key[] = $this->data[$n-1]['s'];
				++$i;
			}
		}
		return $key;
	}
	
	function select_weighted_unique($amount=1){
		$count = count($this->data);
		$i = 0;
		$sub = 0;
		if($amount >= $count){
			while($i < $count){
				$return[] = $this->data[$i]['s'];
				++$i;
			}
			return $return;
		}else{
			$max = -1;
			while($i < $count){
				$max += $this->data[$i]['w'];
				++$i;
			}
			
			$i = 0;
			while($i < $amount){
				$max -= $sub;
				$w = 0;
				$n = 0;
				$this->reseed();
				$num = mt_rand(0,$max);
				while($w <= $num){
					$w += $this->data[$n]['w'];
					++$n;
				}
				$sub = $this->data[$n-1]['w'];
				$key[] = $this->data[$n-1]['s'];
				
				unset($this->data[$n-1]);
				$this->data = array_merge($this->data);
				++$i;
			}
			return $key;
		}
	}
}
?>