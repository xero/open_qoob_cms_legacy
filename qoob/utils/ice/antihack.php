<?php
/**
 * antihack
 * an advanced attack detection mechanism, providing functions to 
 * scan incoming data for malicious appearing script fragments.
 *
 * detects many variants of XSS, SQL injection, header injection, directory traversal, 
 * RFE/LFI, DoS and LDAP attacks, and through special conversion algorithms is even 
 * able to detect heavily obfuscated attacks – this covers several charsets like UTF-7, 
 * entities of all forms – such as javascript unicode, decimal, and hex-entities as 
 * well as comment obfuscation, obfuscation through concatenation, shell code and 
 * many other variants.
 *
 * furthermore it is able to detect yet unknown attack patterns with the centrifuge component. 
 * this component does in depth string analysis and measurement and detects about 85% to 90% of 
 * all tested vectors given a minimum length of 25 characters. 
 *
 * antihack is a fork of the PHPIDS (PHP-Intrusion Detection System) by Mario Heiderich
 * Copyright (GNU) v3 2008 PHPIDS group (https://phpids.org)
 *
 * @author Mario Heiderich <mario.heiderich@gmail.com>
 * @author Christian Matthies <ch0012@gmail.com>
 * @author Lars Strojny <lars@strojny.net>
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.6
 * @package qoob
 * @subpackage utils.ice
 * @category intrusion countermeasure extensions
 */
final class antihack {
	/**
	 * @var simpleXMLobject $rules the filter rules loaded from the xml
	 */
	private $rules;
	/**
	 * @var array $request the arrays to be filtered
	 */
	private $request;
	/**
	 * @var threat_report|array $report the results of the test
	 */
    private $report;
	/**
     * constructor
	 * setup defaults and loads the xml filter rules
	 */	
	public function __construct() {
        $this->report = new threat_report();
		
		$path = dirname( __FILE__).SLASH.'rules.xml';

		if (!file_exists($path)) {
			throw new Exception('Failed to load rules from: '.$path, statusCodes::HTTP_INTERNAL_SERVER_ERROR);
		} else {
			 $this->rules = simplexml_load_file($path, null, LIBXML_COMPACT);
		}
	}
	/**
	 * run
     * test an array of values $request against the PHP-IDS
     * rule set. array keys in $ignore will not be tested.
	 *
	 * @param array $request values to be tested
     * @param array $ignore values to be ignored
	 * @return array
	 */
	public function run(array $request, array $ignore = array()) {
        if(!empty($ignore)) {
            $request = array_diff($request, $ignore);
            $request = array_diff_key($request, $ignore);
        }
		if (!empty($request)) {
			$this->request = $request;
			foreach ($this->request as $key => $value) {
				$this->iterate($key, $value);
			}
		}
		return $this->report->make();
	}	
	/**
     * iterate function
	 * recursively loop and test both keys and values
	 *
	 * @param string $key
	 * @param string|array $value
	 */	
	private function iterate($key, $value) {
        if (!is_array($value)) {
            if (is_string($value)) {
				$this->detect($key);
				$this->detect($value);
            }
        } else {
            foreach ($value as $subKey => $subValue) {
                $this->iterate($key.'.'.$subKey, $subValue);
            }
        }	
	}
	/**
     * detect
	 * prefilters, removes magic quotes, cleans the value, 
	 * then tests it against the entire rule set
	 *
	 * @param string $value string to be tested
	 */		
	private function detect($value){
        // define the pre-filter
        $prefilter = '/[^\w\s\/@!?\.]+|(?:\.\/)|(?:@@\w+)' 
            . '|(?:\+ADw)|(?:union\s+select)/i';
        
        // to increase performance, only start detection if value isn't alphanumeric
        if (!$value || !preg_match($prefilter, $value)) {
            return false;
        } 
		
        // check for magic quotes and remove them if necessary
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        if(function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc() && version_compare(PHP_VERSION, '5.3.0', '>=')) {
            $value = preg_replace('/\\\(["\'\/])/im', '$1', $value);
        }
		
		// clean the value
		$value = $this->convert($value);

        //test it against the rules
        foreach($this->rules->filter as $rule) {
            if ($this->match($value, $rule->rule)) {
                $this->report->addImpact($rule->impact);
                $this->report->addRule($rule->description);
                $this->report->addVector($value);
                foreach ($rule->tags->tag as $tag) {
                    $this->report->addTag($tag);
                }
            }
        }
        if($this->report->checkImpact() > 0) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if(strpos($ip, ":") > 0) {
                // ---  need an ipv6 solution
                $host = "unknown";
            } else {
                $host = @gethostbyaddr($ip);
            }
            $this->report->setAttacker($ip, $host);
        }
	}	
	/**
     * match
	 * test the filtered value against the rule (regular expression)
	 *
	 * @param string $value string to test
	 * @param string $filter regex string
	 * @return boolean
	 */
	private function match($value, $filter) {
        if (!is_string($value)) {
            throw new Exception('Invalid argument. Expected a string, received '.gettype($value), statusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return (bool) preg_match('/'.$filter.'/ms', strtolower($value));	
	}
//________________________________________________________________________________________________________________________
//                                                                                                      conversion methods
/**
 * the following code is from convert.php
 * last updated 05/14/2012 
 * PHPIDS (PHP-Intrusion Detection System) by Mario Heiderich
 * Copyright (GNU) v3 2008 PHPIDS group (https://phpids.org)
 */
	/**
	 * runs all conversion methods
	 *
     * @param string $val the value to convert
     * @return string
	 */
	private function convert($val) {
		$val = $this->convertFromCommented($val);
		$val = $this->convertFromWhiteSpace($val);
		$val = $this->convertFromJSCharcode($val);
		$val = $this->convertJSRegexModifiers($val);
		$val = $this->convertEntities($val);
		$val = $this->convertQuotes($val);
		$val = $this->convertFromSQLHex($val);
		$val = $this->convertFromSQLKeywords($val);
		$val = $this->convertFromControlChars($val);
		$val = $this->convertFromNestedBase64($val);
		$val = $this->convertFromOutOfRangeChars($val);
		$val = $this->convertFromXML($val);
		$val = $this->convertFromJSUnicode($val);
		$val = $this->convertFromUTF7($val);
		$val = $this->convertFromConcatenated($val);
		$val = $this->convertFromProprietaryEncodings($val);
		$val = $this->runCentrifuge($val);
		return $val;
	}
	/**
     * check for comments and erases them if available
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromCommented($value) {
        // check for existing comments
        if (preg_match('/(?:\<!-|-->|\/\*|\*\/|\/\/\W*\w+\s*$)|' .
            '(?:--[^-]*-)/ms', $value)) {

            $pattern = array(
                '/(?:(?:<!)(?:(?:--(?:[^-]*(?:-[^-]+)*)--\s*)*)(?:>))/ms',
                '/(?:(?:\/\*\/*[^\/\*]*)+\*\/)/ms',
                '/(?:--[^-]*-)/ms'
            );

            $converted = preg_replace($pattern, ';', $value);
            $value    .= "\n" . $converted;
        }
        
        //make sure inline comments are detected and converted correctly
        $value = preg_replace('/(<\w+)\/+(\w+=?)/m', '$1/$2', $value);
        $value = preg_replace('/[^\\\:]\/\/(.*)$/m', '/**/$1', $value);
        $value = preg_replace('/([^\-&])#.*[\r\n\v\f]/m', '$1', $value);
        $value = preg_replace('/([^&\-])#.*\n/m', '$1 ', $value);
        $value = preg_replace('/^#.*\n/m', ' ', $value);

        return $value;
    }
    /**
     * strip newlines
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromWhiteSpace($value) {
        //check for inline linebreaks
        $search = array('\r', '\n', '\f', '\t', '\v');
        $value  = str_replace($search, ';', $value);

        // replace replacement characters regular spaces
        $value = str_replace('�', ' ', $value);

        //convert real linebreaks
        return preg_replace('/(?:\n|\r|\v)/m', '  ', $value);
    }
    /**
     * checks for common charcode pattern and decodes them
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromJSCharcode($value) {
        $matches = array();

        // check if value matches typical charCode pattern
        if (preg_match_all('/(?:[\d+-=\/\* ]+(?:\s?,\s?[\d+-=\/\* ]+)){4,}/ms',
            $value, $matches)) {

            $converted = '';
            $string    = implode(',', $matches[0]);
            $string    = preg_replace('/\s/', '', $string);
            $string    = preg_replace('/\w+=/', '', $string);
            $charcode  = explode(',', $string);

            foreach ($charcode as $char) {
                $char = preg_replace('/\W0/s', '', $char);

                if (preg_match_all('/\d*[+-\/\* ]\d+/', $char, $matches)) {
                    $match = preg_split('/(\W?\d+)/',
                                        (implode('', $matches[0])),
                                        null,
                                        PREG_SPLIT_DELIM_CAPTURE);

                    if (array_sum($match) >= 20 && array_sum($match) <= 127) {
                        $converted .= chr(array_sum($match));
                    }

                } elseif (!empty($char) && $char >= 20 && $char <= 127) {
                    $converted .= chr($char);
                }
            }

            $value .= "\n" . $converted;
        }

        // check for octal charcode pattern
        if (preg_match_all('/(?:(?:[\\\]+\d+[ \t]*){8,})/ims', $value, $matches)) {

            $converted = '';
            $charcode  = explode('\\', preg_replace('/\s/', '', implode(',',
                $matches[0])));

            foreach ($charcode as $char) {
                if (!empty($char)) {
                    if (octdec($char) >= 20 && octdec($char) <= 127) {
                        $converted .= chr(octdec($char));
                    }
                }
            }
            $value .= "\n" . $converted;
        }

        // check for hexadecimal charcode pattern
        if (preg_match_all('/(?:(?:[\\\]+\w+\s*){8,})/ims', $value, $matches)) {
            $converted = '';
            $charcode  = explode('\\', preg_replace('/[ux]/', '', implode(',',
                $matches[0])));

            foreach ($charcode as $char) {
                if (!empty($char)) {
                    if (hexdec($char) >= 20 && hexdec($char) <= 127) {
                        $converted .= chr(hexdec($char));
                    }
                }
            }
            $value .= "\n" . $converted;
        }
        return $value;
    }    
    /**
     * eliminates JS regex modifiers
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertJSRegexModifiers($value) {
        $value = preg_replace('/\/[gim]+/', '/', $value);
        return $value;
    }
    /**
     * converts from hex/dec entities
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertEntities($value) {
        $converted = null;
        
        //deal with double encoded payload 
        $value = preg_replace('/&amp;/', '&', $value);     
        
        if (preg_match('/&#x?[\w]+/ms', $value)) {
            $converted = preg_replace('/(&#x?[\w]{2}\d?);?/ms', '$1;', $value);
            $converted = html_entity_decode($converted, ENT_QUOTES, 'UTF-8');
            $value    .= "\n" . str_replace(';;', ';', $converted);
        }
        // normalize obfuscated protocol handlers
        $value = preg_replace(
            '/(?:j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\s*:)|(d\s*a\s*t\s*a\s*:)/ms', 
            'javascript:', $value
        );
        return $value;
    }
    /**
     * normalize quotes
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertQuotes($value) {
        // normalize different quotes to "
        $pattern = array('\'', '`', '´', '’', '‘');
        $value   = str_replace($pattern, '"', $value);

        //make sure harmless quoted strings don't generate false alerts
        $value = preg_replace('/^"([^"=\\!><~]+)"$/', '$1', $value);
        return $value;
    }
    /**
     * converts SQLHEX to plain text
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromSQLHex($value) {
        $matches = array();
        if(preg_match_all('/(?:(?:\A|[^\d])0x[a-f\d]{3,}[a-f\d]*)+/im', $value, $matches)) {
            foreach($matches[0] as $match) {
                $converted = '';
                foreach(str_split($match, 2) as $hex_index) {
                    if(preg_match('/[a-f\d]{2,3}/i', $hex_index)) {
                      $converted .= chr(hexdec($hex_index));
                    }
                }
                $value = str_replace($match, $converted, $value);
            }
        }
        // take care of hex encoded ctrl chars
        $value = preg_replace('/0x\d+/m', ' 1 ', $value);      
        return $value;
    }
    /**
     * converts basic SQL keywords and obfuscations
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromSQLKeywords($value) {
        $pattern = array('/(?:is\s+null)|(like\s+null)|' .
            '(?:(?:^|\W)in[+\s]*\([\s\d"]+[^()]*\))/ims');
        $value   = preg_replace($pattern, '"=0', $value);
        
        $value   = preg_replace('/[^\w\)]+\s*like\s*[^\w\s]+/ims', '1" OR "1"', $value);
        $value   = preg_replace('/null([,"\s])/ims', '0$1', $value);
        $value   = preg_replace('/\d+\./ims', ' 1', $value);
        $value   = preg_replace('/,null/ims', ',0', $value);
        $value   = preg_replace('/(?:between)/ims', 'or', $value);
        $value   = preg_replace('/(?:and\s+\d+\.?\d*)/ims', '', $value);
        $value   = preg_replace('/(?:\s+and\s+)/ims', ' or ', $value);

        $pattern = array('/(?:not\s+between)|(?:is\s+not)|(?:not\s+in)|' .
                         '(?:xor|<>|rlike(?:\s+binary)?)|' .
                         '(?:regexp\s+binary)|' .
                         '(?:sounds\s+like)/ims');
        $value   = preg_replace($pattern, '!', $value);
        $value   = preg_replace('/"\s+\d/', '"', $value);
        $value   = preg_replace('/(\W)div(\W)/ims', '$1 OR $2', $value);
        $value   = preg_replace('/\/(?:\d+|null)/', null, $value);

        return $value;
    }
    /**
     * replaces nullbytes and controls chars via ord()
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromControlChars($value) {
        // critical ctrl values
        $search = array(
            chr(0), chr(1), chr(2), chr(3), chr(4), chr(5),
            chr(6), chr(7), chr(8), chr(11), chr(12), chr(14),
            chr(15), chr(16), chr(17), chr(18), chr(19), chr(24), 
            chr(25), chr(192), chr(193), chr(238), chr(255), '\\0'
        );
        
        $value = str_replace($search, '%00', $value);

        //take care for malicious unicode characters
        $value = urldecode(preg_replace('/(?:%E(?:2|3)%8(?:0|1)%(?:A|8|9)' .
            '\w|%EF%BB%BF|%EF%BF%BD)|(?:&#(?:65|8)\d{3};?)/i', null,
                urlencode($value)));
        $value = urldecode(
            preg_replace('/(?:%F0%80%BE)/i', '>', urlencode($value)));
        $value = urldecode(
            preg_replace('/(?:%F0%80%BC)/i', '<', urlencode($value)));
        $value = urldecode(
            preg_replace('/(?:%F0%80%A2)/i', '"', urlencode($value)));
        $value = urldecode(
            preg_replace('/(?:%F0%80%A7)/i', '\'', urlencode($value)));     

        $value = preg_replace('/(?:%ff1c)/', '<', $value);
        $value = preg_replace(
            '/(?:&[#x]*(200|820|200|820|zwn?j|lrm|rlm)\w?;?)/i', null,$value
        );
        $value = preg_replace('/(?:&#(?:65|8)\d{3};?)|' .
                '(?:&#(?:56|7)3\d{2};?)|' .
                '(?:&#x(?:fe|20)\w{2};?)|' .
                '(?:&#x(?:d[c-f])\w{2};?)/i', null,
                $value);
                
        $value = str_replace(
            array('«', '〈', '＜', '‹', '〈', '⟨'), '<', $value
        );
        $value = str_replace(
            array('»', '〉', '＞', '›', '〉', '⟩'), '>', $value
        );
        return $value;
    }
    /**
     * matches and translates base64 strings and fragments used in data URIs
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromNestedBase64($value) {
        $matches = array();
        preg_match_all('/(?:^|[,&?])\s*([a-z0-9]{50,}=*)(?:\W|$)/im',
            $value,
            $matches);

        foreach ($matches[1] as $item) {
            if (isset($item) && !preg_match('/[a-f0-9]{32}/i', $item)) {
                $base64_item = base64_decode($item);
                $value = str_replace($item, $base64_item, $value);
            }
        }

        return $value;
    }
    /**
     * replaces nullbytes and controls chars via ord()
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromOutOfRangeChars($value) {
        $values = str_split($value);
        foreach ($values as $item) {
            if (ord($item) >= 127) {
                $value = str_replace($item, ' ', $value);
            }
        }
        return $value;
    }
    /**
     * strip XML patterns
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromXML($value) {
        $converted = strip_tags($value);

        if ($converted && ($converted != $value)) {
            return $value . "\n" . $converted;
        }
        return $value;
    }
    /**
     * converts JS unicode code points to regular characters
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromJSUnicode($value) {
        $matches = array();

        preg_match_all('/\\\u[0-9a-f]{4}/ims', $value, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $chr = chr(hexdec(substr($match, 2, 4))); 
                $value = str_replace($match, $chr, $value);
            }
            $value .= "\n\u0001";
        }

        return $value;
    }
    /**
     * converts relevant UTF-7 tags to UTF-8
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromUTF7($value) {
        if(preg_match('/\+A\w+-?/m', $value)) {
            if (function_exists('mb_convert_encoding')) {
                if(version_compare(PHP_VERSION, '5.2.8', '<')) {
                    $tmp_chars = str_split($value);
                    $value = '';
                    foreach($tmp_chars as $char) {
                        if(ord($char) <= 127) {
                            $value .= $char;    
                        }
                    }     
                }
                $value .= "\n" . mb_convert_encoding($value, 'UTF-8', 'UTF-7');
            } else {
                //list of all critical UTF7 codepoints
                $schemes = array(
                    '+ACI-'      => '"',
                    '+ADw-'      => '<',
                    '+AD4-'      => '>',
                    '+AFs-'      => '[',
                    '+AF0-'      => ']',
                    '+AHs-'      => '{',
                    '+AH0-'      => '}',
                    '+AFw-'      => '\\',
                    '+ADs-'      => ';',
                    '+ACM-'      => '#',
                    '+ACY-'      => '&',
                    '+ACU-'      => '%',
                    '+ACQ-'      => '$',
                    '+AD0-'      => '=',
                    '+AGA-'      => '`',
                    '+ALQ-'      => '"',
                    '+IBg-'      => '"',
                    '+IBk-'      => '"',
                    '+AHw-'      => '|',
                    '+ACo-'      => '*',
                    '+AF4-'      => '^',
                    '+ACIAPg-'   => '">',
                    '+ACIAPgA8-' => '">'
                );
    
                $value = str_ireplace(array_keys($schemes),
                    array_values($schemes), $value);
            }
        }
        return $value;
    }
    /**
     * converts basic concatenations
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromConcatenated($value) {
        //normalize remaining backslashes
        if ($value != preg_replace('/(\w)\\\/', "$1", $value)) {
            $value .= preg_replace('/(\w)\\\/', "$1", $value);
        }

        $compare = stripslashes($value);
        
        $pattern = array('/(?:<\/\w+>\+<\w+>)/s',
            '/(?:":\d+[^"[]+")/s',
            '/(?:"?"\+\w+\+")/s',
            '/(?:"\s*;[^"]+")|(?:";[^"]+:\s*")/s',
            '/(?:"\s*(?:;|\+).{8,18}:\s*")/s',
            '/(?:";\w+=)|(?:!""&&")|(?:~)/s',
            '/(?:"?"\+""?\+?"?)|(?:;\w+=")|(?:"[|&]{2,})/s',
            '/(?:"\s*\W+")/s',
            '/(?:";\w\s*\+=\s*\w?\s*")/s',
            '/(?:"[|&;]+\s*[^|&\n]*[|&]+\s*"?)/s',
            '/(?:";\s*\w+\W+\w*\s*[|&]*")/s',
            '/(?:"\s*"\s*\.)/s',
            '/(?:\s*new\s+\w+\s*[+",])/',
            '/(?:(?:^|\s+)(?:do|else)\s+)/',
            '/(?:[{(]\s*new\s+\w+\s*[)}])/',
            '/(?:(this|self)\.)/',
            '/(?:undefined)/',
            '/(?:in\s+)/');

        // strip out concatenations
        $converted = preg_replace($pattern, null, $compare);

        //strip object traversal
        $converted = preg_replace('/\w(\.\w\()/', "$1", $converted);

        // normalize obfuscated method calls
        $converted = preg_replace('/\)\s*\+/', ")", $converted);

        //convert JS special numbers
        $converted = preg_replace('/(?:\(*[.\d]e[+-]*[^a-z\W]+\)*)' .
            '|(?:NaN|Infinity)\W/ims', 1, $converted);

        if ($converted && ($compare != $converted)) {
            $value .= "\n" . $converted;
        }

        return $value;
    }
    /**
     * collects and decodes proprietary encoding types
     *
     * @param string $value the value to convert
     * @return string
     */
    private function convertFromProprietaryEncodings($value) {
        //Xajax error reportings
        $value = preg_replace('/<!\[CDATA\[(\W+)\]\]>/im', '$1', $value);

        //strip false alert triggering apostrophes
        $value = preg_replace('/(\w)\"(s)/m', '$1$2', $value);

        //strip quotes within typical search patterns
        $value = preg_replace('/^"([^"=\\!><~]+)"$/', '$1', $value);

        //OpenID login tokens
        $value = preg_replace('/{[\w-]{8,9}\}(?:\{[\w=]{8}\}){2}/', null, $value);

        //convert Content and \sdo\s to null
        $value = preg_replace('/Content|\Wdo\s/', null, $value);

        //strip emoticons
        $value = preg_replace(
            '/(?:\s[:;]-[)\/PD]+)|(?:\s;[)PD]+)|(?:\s:[)PD]+)|-\.-|\^\^/m',
            null,
            $value
        );
        
        //normalize separation char repetion
        $value = preg_replace('/([.+~=*_\-;])\1{2,}/m', '$1', $value);

        //normalize multiple single quotes
        $value = preg_replace('/"{2,}/m', '"', $value);
        
        //normalize quoted numerical values and asterisks
        $value = preg_replace('/"(\d+)"/m', '$1', $value);

        //normalize pipe separated request parameters
        $value = preg_replace('/\|(\w+=\w+)/m', '&$1', $value);

        //normalize ampersand listings
        $value = preg_replace('/(\w\s)&\s(\w)/', '$1$2', $value);
        
        //normalize escaped RegExp modifiers
        $value = preg_replace('/\/\\\(\w)/', '/$1', $value);        
        
        return $value;
    }
    /**
     * this method is the centrifuge prototype
     *
     * @param string      $value   the value to convert
     * @return string
     */
    private function runCentrifuge($value) {
        $threshold = 3.49;
        if (strlen($value) > 25) {
            
            //strip padding
            $tmp_value = preg_replace('/\s{4}|==$/m', null, $value);
            $tmp_value = preg_replace(
                '/\s{4}|[\p{L}\d\+\-=,.%()]{8,}/m', 
                'aaa', 
                $tmp_value
            );
            
            // Check for the attack char ratio
            $tmp_value = preg_replace('/([*.!?+-])\1{1,}/m', '$1', $tmp_value);
            $tmp_value = preg_replace('/"[\p{L}\d\s]+"/m', null, $tmp_value);

            $stripped_length = strlen(preg_replace('/[\d\s\p{L}\.:,%&\/><\-)!|]+/m',
                null, $tmp_value));
            $overall_length  = strlen(
                preg_replace('/([\d\s\p{L}:,\.]{3,})+/m', 'aaa',
                preg_replace('/\s{2,}/m', null, $tmp_value))
            );

            if ($stripped_length != 0
                && $overall_length/$stripped_length <= $threshold) {
                $value .= "\n$[!!!]";
            }
        }

        if (strlen($value) > 40) {
            // Replace all non-special chars
            $converted =  preg_replace('/[\w\s\p{L},.:!]/', null, $value);

            // Split string into an array, unify and sort
            $array = str_split($converted);
            $array = array_unique($array);
            asort($array);

            // Normalize certain tokens
            $schemes = array(
                '~' => '+',
                '^' => '+',
                '|' => '+',
                '*' => '+',
                '%' => '+',
                '&' => '+',
                '/' => '+'
            );

            $converted = implode($array);
            
            $_keys = array_keys($schemes);
            $_values = array_values($schemes);
            
            $converted = str_replace($_keys, $_values, $converted);
            
            $converted = preg_replace('/[+-]\s*\d+/', '+', $converted);
            $converted = preg_replace('/[()[\]{}]/', '(', $converted);
            $converted = preg_replace('/[!?:=]/', ':', $converted);
            $converted = preg_replace('/[^:(+]/', null, stripslashes($converted));

            // Sort again and implode
            $array = str_split($converted);
            asort($array);

            $converted = implode($array);

            if (preg_match('/(?:\({2,}\+{2,}:{2,})|(?:\({2,}\+{2,}:+)|' .
                '(?:\({3,}\++:{2,})/', $converted)) {
                return $value . "\n" . $converted;
            }
        }
        return $value;
    }
}
/**
 * threat report
 * an array of information that is returned to the user
 */
final class threat_report {
    /**
     * @var int $impact the attack severity level
     */
    private $impact =  0;
    /**
     * @var int $attackerIP the attacker ip address
     */
    private $attackerIP = 0;
    /**
     * @var string $attackerHost the attacker hostname
     */
    private $attackerHost = 'unknown';
    /**
     * @var array $tags attack identification metadata
     */
    private $tags = array();
    /**
     * @var array $rules rules broken
     */
    private $rules = array();
    /**
     * @var array $vectors !DANGER! detected attack vectors !DANGER!
     */
    private $vectors = array();
    /**
     * make function
     * generate the threat report
     *
     * @return array
     */
    public function make() {
        $report = array(
            'impact' => $this->impact, 
            'attackerIP' => $this->attackerIP, 
            'attackerHost' => $this->attackerHost, 
            'tags' => array_unique($this->tags),
            'rules' => array_unique($this->rules),
            'vectors' => array_unique($this->vectors), 
        );
        return $report;
    }
    /**
     * check impact
     * return the current impact value
     *
     * @return int $impact
     */
    public function checkImpact() {
        return $this->impact;
    }
    /**
     * set attacker
     * info about the attack origin point
     *
     * @param $ip string attackerIP
     * @param $host string attackerHostName default 'unknown'
     */
    public function setAttacker($ip, $host = 'unknown') {
        $this->attackerIP = $ip;
        $this->attackerHost = $host;
    }
    /**
     * add impact
     * increase the attack severity level
     *
     * @param $impact int
     */
    public function addImpact($impact) {
        $this->impact += $impact;
    }
    /**
     * add tag
     * metadata about the attack vector
     *
     * @param $tag string
     */
    public function addTag($tag) {
        $this->tags[] = $tag;
    }
    /**
     * add rule
     * the rule that was broken
     *
     * @param $rule string
     */
    public function addRule($rule) {
        $this->rules[] = $rule;
    }
    /**
     * add vector
     * !DANGER! live attack code !DANGER!
     *        use at your own risk!
     *
     * @param $vector string 
     */
    public function addVector($vector) {
        $this->vectors[] = $vector;
    }

}

?>