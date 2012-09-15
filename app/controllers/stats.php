<?php
/**
 * stats controller
 * class for creating, validating, and saving statistic information.
 * user and browser information is gathered by including a javascript
 * function in the page. that code is generated in the js function.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 7.7
 * @package app
 * @subpackage controllers
 */
class stats extends controller {
	/**
	 * index function
	 * there is no default action!
	 * so this redirects the user to the qoob root.
	 */
	public function index() {
		header("location: ".QOOB_DOMAIN);
	}
	/**
	 * js function
	 * display the javascript to gather and send the statistics
	 */
	public function js() {
		Header("content-type: application/x-javascript");
		?> var qoob_stats=new Object;qoob_stats={path:"",query:"",title:"",referer:"",resource:"",resolution:"0x0",flash:0,getFlash:function(){var a=navigator.userAgent.toLowerCase();if(navigator.plugins&&navigator.plugins.length){var b=navigator.plugins["Shockwave Flash"];if(typeof b=="object"){for(var c=16;c>=3;c--){if(b.description&&b.description.indexOf(" "+c+".")!=-1){this.flash=c;break}}}}else if(a.indexOf("msie")!=-1&&a.indexOf("win")!=-1&&parseInt(navigator.appVersion)>=4&&a.indexOf("16bit")==-1){var d="<scr"+'ipt language="VBScript"> \nOn Error Resume Next \nDim obFlash \nFor i = 16 To 3 Step -1 \n   Set obFlash = CreateObject("ShockwaveFlash.ShockwaveFlash." & i) \n   If IsObject(obFlash) Then \n      qoob_stats.flash = i \n      Exit For \n   End If \nNext \n<'+"/scr"+"ipt> \n";document.write(d)}},getInfo:function(){var a=new Date;this.title=document.title;this.referer=window.decodeURI?window.decodeURI(document.referrer):document.referrer;this.resource=window.decodeURI?window.decodeURI(document.URL):document.URL;this.resolution=screen.width+"x"+screen.height;this.query="&referer="+escape(this.referer)+"&resource="+escape(this.resource)+"&resource_title="+escape(this.title)+"&resolution="+escape(this.resolution)+"&flash="+escape(this.flash)+"&time="+a.getTime();this.path="<?php echo(BASE_URL."?/qoob_stats/save/&key=".$this->generateKey()); ?>"+this.query},sendStats:function(){this.getFlash();this.getInfo();if(window.XMLHttpRequest){xmlhttp=new XMLHttpRequest}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")}xmlhttp.open("GET",this.path,true);xmlhttp.send()}};qoob_stats.sendStats(); <?php
	}
	/**
	 * save function
	 * recieves the data from the javascript function, analyzes it, and saves it to the database
	 */
	public function save() {
		$key = getRequest("key", "get", FILTER_SANITIZE_STRING);
		$valid = ($this->verifyKey($key)) ? true : false;
		if($valid) {
			$stat = array();
			$stat["date"] = @time();
			$stat["referer"] = $this->sanitizeURL(preg_replace('/#.*$/', '', htmlentities(getRequest("referer", "get", FILTER_SANITIZE_URL))));
			$stat["referer_checksum"] = crc32($stat["referer"]);
			$stat["domain"] = preg_replace('/(^([^:]+):\/\/(www\.)?|(:\d+)?\/.*$)/', '', $stat["referer"]);
			$stat["domain_checksum"] = crc32($stat["domain"]);
			$stat["resource"] = $this->sanitizeURL(preg_replace('/#.*$/', '', htmlentities(getRequest("resource", "get",FILTER_SANITIZE_URL))));
			$stat["resource_checksum"] = crc32($stat["resource"]);
			$stat["resource_title"] = trim(str_replace('\n', ' ', preg_replace('/%u([\d\w]{4})/', '&#x$1;', getRequest("resource_title", "get", FILTER_SANITIZE_STRING))));
			$stat["resolution"] = trim(getRequest("resolution", "get", FILTER_SANITIZE_STRING));
			$stat["flash_version"] = trim(getRequest("flash", "get", FILTER_SANITIZE_NUMBER_FLOAT));
			$stat["location"] = 'unknown';
			$this->library(qoob_types::utility, "xbd");
			$browser = $this->xbd->browser();		
			$stat = array_merge($stat, $browser);
			$this->library(qoob_types::utility, "geoip", "geoip/");
			$stat["location"] = $this->geoip->getCountry($stat["ipaddress"]);
			$sm = $this->model("statsModel");
			$sm->save($stat);
		}
	}
	/**
	 * generate key function
	 * generate a simple key to hide in the javascript. the key is the current
	 * epoch time with a random number (between 9-18) of random letters mixed in.
	 */
	private function generateKey() {
		$key = time();
		$s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$times = rand(9, 18);
		$i = 0;
		while($i < $times) {
			$random = $s[rand(0, strlen($s)-1)];
			$point = rand(0, strlen($key));
			$key = substr($key, 0, $point).$random.substr($key, $point, strlen($key));
			$i++;
		}
		return bin2hex(strrev($key));	
	}
	/**
	 * verify key function
	 * decodes the key and determines if it is older than 25 seconds.
	 */
	private function verifyKey($key) {
		$key = preg_replace("/(.{2})/","%$1",$key);
		$key = rawurldecode($key);
		$key = strrev($key);
		$key = preg_replace("/([a-z])/i",'',$key);
		return ((time() - $key) < 25) ? true : false;
	}	
	/**
	 * sanitize url function
	 * removes javascript based attack vectors
	 *
	 * @todo is this necessary? should be already covered by the I.C.E controller
	 */
	private function sanitizeURL($url) {
		$javascript = str_replace(' ', '\s*', ' j a v a s c r i p t :');
		return preg_replace("#^{$javascript}.*#i", '', $url);
	}	
}

?>