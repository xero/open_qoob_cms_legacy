<?php
/**
 * XBD, Extended Browser Detection
 * library to implement browser specific features on web applications or websites.
 *
 * extended by xero harrison for use in the open qoob framework.
 * wrapped into a class, and added ip and hostname lookup.
 *
 * released open-source under the GNU Lesser General Public License version 3
 * see <http://www.gnu.org/licenses/>.
 *
 * @author Guillermo Azurdia <www.nopticon.com> https://github.com/nopticon/xbd
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.501
 * @package qoob
 * @subpackage utils
 * @category browser heuristics
 */
class xbd {
	function _print($name, $ret)
	{
		echo $name . '<br />';
		
		if (!$this->f($ret))
		{
			$ret = 'false';
		}
		elseif ($ret == '1')
		{
			$ret = 'true';
		}
		
		echo '<blockquote><pre>';
		print_r($ret);
		echo '</pre></blockquote>';
		
		return;
	}
	
	function v_server($a)
	{
		return (isset($_SERVER[$a])) ? $_SERVER[$a] : '';
	}
	
	function f($s)
	{
		return !empty($s);
	}
	
	function w($a = '', $d = false)
	{
		if (!$this->f($a) || !is_string($a)) return array();
		
		$e = explode(' ', $a);
		if ($d !== false)
		{
			foreach ($e as $i => $v)
			{
				$e[$v] = $d;
				unset($e[$i]);
			}
		}
		
		return $e;
	}
	/*
	If you want to add more browsers, do it but be careful... order matters.
	*/
	function browser($a_browser = false, $a_version = false, $name = false, $d_name = false, $ret_ary = false)
	{
		$browser_list  = 'nokia motorola samsung sonyericsson blackberry iphone htc android ';
		$browser_list .= 'flock firefox konqueror lobo msie netscape navigator mosaic netsurf lynx amaya omniweb ';
		$browser_list .= 'googlebot googlebot-image feedfetcher-google gigabot msnbot thunderbird fennec minimo ';
		$browser_list .= 'minefield chrome wget cheshire safari avant camino seamonkey aol bloglines ';
		$browser_list .= 'wii playstation netfront opera mozilla gecko ubuntu';

		$browser_type = array(
			'mobile' => 'nokia motorola samsung sonyericsson blackberry iphone fennec minimo htc android',
			'console' => 'wii playstation',
			'bot' => 'googlebot googlebot-image feedfetcher-google gigabot msnbot bloglines'
		);		
		$platforms = array(
			'linux' => $this->w('linux'),
			'mac' => array('macintosh', 'mac platform x', 'mac os x'),
			'windows' => $this->w('windows win32')
		);
		
		$user_browser = strtolower($this->v_server('HTTP_USER_AGENT'));
		
		$this_version = $this_browser = $this_platform = '';
		
		if ($a_browser == '*') {
			$a_browser = $a_version = $name = false;
			$d_name = true;
		}
		
		if ($a_browser === false && $a_version === false && $name === false && $d_name !== false)
		{
			return $user_browser;
		}
		
		foreach ($this->w('user_browser a_browser a_version name d_name') as $row)
		{
			$vrow = $$row;
			if (is_string($vrow)) {
				$$row = strtolower($vrow);
			}
		}
		
		$browser_limit = strlen($user_browser);
		foreach ($this->w($browser_list) as $row)
		{
			$row = ($a_browser !== false) ? $a_browser : $row;
			$n = stristr($user_browser, $row);
			if (!$n || $this->f($this_browser)) continue;
			
			$this_browser = $row;
			$j = strpos($user_browser, $row) + strlen($row);
			$j2 = substr($user_browser, $j, 1);
			if (preg_match('#[\/\_\-\ ]#', $j2)) {
				$j += 1;
			}
			
			for (; $j <= $browser_limit; $j++)
			{
				$s = trim(substr($user_browser, $j, 1));
				if (!preg_match('/[\w\.\-]/', $s)) break;
				
				$this_version .= $s;
			}
		}
		
		if ($a_browser !== false && ($d_name === false || $name === true) && $ret_ary === false)
		{
			$ret = false;
			if (strtolower($a_browser) == $this_browser)
			{
				$ret = true;
				if ($a_version !== false)
				{
					if ($this->f($this_version))
					{
						$a_sign = explode(' ', $a_version);
						if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false) {
							$ret = false;
							$vf = true;
						}
					}
					else
					{
						$ret = false;
					}
				}
			}
			
			if ($name !== true)
			{
				return $ret;
			}
		}
		
		foreach ($platforms as $os => $match)
		{
			foreach ($match as $os_name)
			{
				if (strpos($user_browser, $os_name) !== false)
				{
					$this_platform = $os;
					break 2;
				}
			}
		}
		
		$this_type = '';
		if ($this->f($this_browser))
		{
			foreach ($browser_type as $type => $browsers)
			{
				foreach ($this->w($browsers) as $row)
				{
					if (strpos($this_browser, $row) !== false)
					{
						$this_type = $type;
						break 2;
					}
				}
			}
			
			if (!$this_type) $this_type = 'desktop';
		}
		
		if ($name !== false && $ret_ary === false)
		{
			if ($a_browser !== false && $a_version !== false && $ret === false)
			{
				return false;
			}
			
			$s_browser = '';
			$s_data = array($this_type, $this_platform, $this_browser, $this_version);
			foreach ($s_data as $row)
			{
				if ($this->f($row)) $s_browser .= (($s_browser != '') ? ' ' : '') . $row;
			}
			
			return $s_browser;
		}
		// --- added by xero harrison [04.02.10]
		$addr = $_SERVER['REMOTE_ADDR'];
		if(strpos($addr, ":") > 0) {
			// ---  need an ipv6 solution
			$host = "unknown";
		} else {
			$host = @gethostbyaddr($addr);
		}

		return array(
			'browser' => $this_browser,
			'version' => $this_version,
			'platform' => $this_platform,
			'type' => $this_type,
			'useragent' => $user_browser,
   		    // --- added by xero harrison [04.02.10]
			'ipaddress' => $addr,
			'hostname' => $host
		);
	}
}

?>