<?php
/**
 * I.C.E.
 * intrusion contermeasure extensions.
 * controller for any/all antihacker code.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 3.2
 * @package app
 * @subpackage controllers
 */
final class ice extends controller {
	/**
	 * constructor
	 * load the antihack utility and test it with all the
	 * outside variables (request, get, post, cookie, etc)
	 */
	public function __construct() {
		//ice causes problems w/ the QR code generator
		if(library::catalog()->url[0] != 'qr') {
			// create an arry of what we're testing
			$request = array_merge($_REQUEST, $_GET, $_POST, $_COOKIE);
			$ignore = array(
				'__utmz' => '',
				'__utmc' => '',
				'COOKIE.__utmz' => '',
				'COOKIE.__utmc' => '',
				'REQUEST.__utmz' => '',
				'REQUEST.__utmc' => '',
				//IGNORE FOR ADMIN ONLY!!!!
				'txtExcerpt' => '',
				'txtBody' => '',
				'txtScript' => '',
				'txtDateTime' => '',
				'txtDescript' => '',
				'txtTags' => ''
			);
			// set the action to be taken at a certain impact level
			// im not sure about these levels/actions yet...
			$threshold = array(
				'log'      => 3,
				'mail'     => 9,
				'warn'     => 27,
				'ban'      => 81
			);
			$ah = registry::register(qoob_types::utility, "antihack", "ice/");
			$report = $ah->run($request, $ignore);	

			if($report['impact'] > 0) {
				if($report['impact'] >= $threshold['ban']) {
					$action = 'ban';
				} else if($report['impact'] >= $threshold['warn']) {
					$action = 'warn';
				} else if($report['impact']>= $threshold['mail']) {
					$action = 'mail';
				} else if($report['impact'] >= $threshold['log']) {
					$action = 'log';
				}			
				$brk = "\r\n";
				$now = date("F j, Y, g:i a");
				$msg = '[THREAT]'.$brk.'[date] '.$now.$brk.'[attacker] '.$report["attackerIP"].' @ '.$report["attackerHost"].$brk.'[impact: '.$report["impact"].' / action: '.$action.']'.$brk.'[tags: '. implode(', ', $report["tags"]).']'.$brk.'[rules]'.$brk.implode($brk, $report["rules"]).$brk.'[attack vectors]'.$brk.implode($brk, $report["vectors"]).$brk.'[/THREAT]'.$brk;
				$this->logMSG($msg, 'ice.log');
	
				throw new Exception("error processing request", statusCodes::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
	}
}

?>