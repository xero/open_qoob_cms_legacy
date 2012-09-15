<?php
/**
 * BBCode Parser
 * takes bbcode strings, and returns html equivalents.
 * based on http://www.phpit.net/article/create-bbcode-php/  
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 2.0
 * @package qoob
 * @subpackage utils
 */
final class bbcode {
	/**
	 * format
	 * takes a bbcode string and creates it's html equivalent.
	 *
	 * @param string $str the bbcode string
	 * @return string
	 */
	function format ($str) {  
	    //$str = htmlentities($str);
	    $simple_search = array(  
	                //added line break  
	                '/\[br\]/is',  
	                '/\[b\](.*?)\[\/b\]/is',  
	                '/\[i\](.*?)\[\/i\]/is',  
	                '/\[u\](.*?)\[\/u\]/is',  
	                '/\[url\=(.*?)\](.*?)\[\/url\]/is',  
	                '/\[url\](.*?)\[\/url\]/is',  
	                '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',  
	                '/\[img\](.*?)\[\/img\]/is',  
	                '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',  
	                '/\[mail\](.*?)\[\/mail\]/is',  
	                '/\[font\=(.*?)\](.*?)\[\/font\]/is',  
	                '/\[size\=(.*?)\](.*?)\[\/size\]/is',  
	                '/\[color\=(.*?)\](.*?)\[\/color\]/is',  
	                  //added textarea for code presentation  
	               '/\[codearea\](.*?)\[\/codearea\]/is',  
	                 //added pre class for code presentation  
	              '/\[code\](.*?)\[\/code\]/is',  
	                //added paragraph  
	              '/\[p\](.*?)\[\/p\]/is',  
	                );  
	  
	    $simple_replace = array(  
					//added line break  
	               '<br />',  
	                '<strong>$1</strong>',  
	                '<em>$1</em>',  
	                '<u>$1</u>',  
					// added nofollow to prevent spam  
	                '<a href="$1" rel="nofollow" title="$2 - $1">$2</a>',  
	                '<a href="$1" rel="nofollow" title="$1">$1</a>',  
	                '<div style="text-align: $1;">$2</div>',  
					//added alt attribute for validation  
	                '<img src="$1" alt="" />',  
	                '<a href="mailto:$1">$2</a>',  
	                '<a href="mailto:$1">$1</a>',  
	                '<div style="font-family: $1; display: inline;">$2</div>',  
	                '<div style="font-size: $1; display: inline;">$2</div>',  
	                '<div style="color: $1; display: inline;">$2</div>',  
					//added textarea for code presentation  
					'<textarea class="code_container" rows="30" cols="70">$1</textarea>',  
					//added pre class for code presentation  
					'<pre class="code">$1</pre>',  
					//added paragraph  
					'<p>$1</p>',  
	                );  
	  
	    // Do simple BBCode's  
	    $str = preg_replace ($simple_search, $simple_replace, $str);  
	  
	    // Do <blockquote> BBCode  
	    $str = $this->quote($str);  
	  
	    // convert new lines to line breaks
	    return nl2br($str);  
	}	  
	/**
	 * quote
	 * create an html quote box from bb codes
	 *
	 * @param string $str the string to be quoted
	 * @return string
	 */ 
	function quote($str) {  
	    //added div and class for quotes  
	    $open = '<blockquote><div class="quote">';  
	    $close = '</div></blockquote>';  
	  
	    // How often is the open tag?  
	    preg_match_all ('/\[quote\]/i', $str, $matches);  
	    $opentags = count($matches['0']);  
	  
	    // How often is the close tag?  
	    preg_match_all ('/\[\/quote\]/i', $str, $matches);  
	    $closetags = count($matches['0']);  
	  
	    // Check how many tags have been unclosed  
	    // And add the unclosing tag at the end of the message  
	    $unclosed = $opentags - $closetags;  
	    for ($i = 0; $i < $unclosed; $i++) {  
	        $str .= '</div></blockquote>';  
	    }  
	  
	    // Do replacement  
	    $str = str_replace ('[' . 'quote]', $open, $str);  
	    $str = str_replace ('[/' . 'quote]', $close, $str);  
	  
	    return $str;  
	}  	
}

?>