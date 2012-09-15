<?php
/**
 * calendar class
 * functions for dynamically generating different calendars.
 * 
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package qoob
 * @subpackage utils
 */
class calendar {
	/**
	 * @var array $days an array of values for styling specific dates in the calendar array key is the date, first value is the link, the second is a style name, and the third is the value to be displayed in the cell.
	 * @example $days = array( 
	 * 		2=>array(null,"linked-day", '<span style="color: #00ff00; font-weight: bold; font-size: large; text-decoration: blink;" onclick="show()">2!</span>'), 
	 * 		3=>array("archive/2011/oct/03","linked-day", "3"), 
	 * 		8=>array("archive/2011/oct/08","linked-day", "8"), 
	 * 		22=>array("/archive/2011/oct/22","linked-day", "22"), 
	 * 	); 
	 */
	private $days;
	/**
	 * @var int $month the month of the calendar to be displayed
	 */
	private $month;
	/**
	 * @var int $year the year of the calendar to be displayed
	 */
	private $year;
	/**
	 * @var int $dayNameLength a value from 0-4 which dictates the length of the day of the week name. (0 = no names, 1= single character, and greater then three will display the entire name)
	 */
	private $dayNameLength = 3;
	/**
	 * @var string $monthLink and optional url to be linked to the month name.
	 */
	private $monthLink;
	/**
	 * @var int $firstDay and value from 0-6 which dictates witch day of the week will be displayed first on the calendar (0 = sunday, 1=monday, etc...) 
	 */
	private $firstDay = 0;
	/**
	 * @var array $canonicals previous and next links
	 */
	private $canonicals = array();
	/**
	 * generate
	 * public interface to make a calendar
	 */
	public function generate() {
		if(!isset($this->year)) {
			$this->year = date("Y");
		}
		if(!isset($this->month)) {
			$this->month = date("m");
		}
		return $this->generate_calendar($this->year, $this->month);
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
	 * generate_calendar
	 * makes the css table version.
	 * 
	 * @param int $year
	 * @param int $month
	 */
	private function generate_calendar($year, $month){
		// --- calculations
		$first_of_month = gmmktime(0,0,0,$month,1,$year);
		/* remember that mktime will automatically correct if invalid dates are entered
		   for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		   this provides a built in "rounding" feature to generate_calendar() */
	
		$day_names = array(); // generate all the day names according to the current locale
		for($n=0,$t=(3+$this->firstDay)*86400; $n<7; $n++,$t+=86400) // January 4, 1970 was a Sunday
			$day_names[$n] = ucfirst(gmstrftime('%A',$t)); // %A means full textual day name
	
		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
		$weekday = ($weekday + 7 - $this->firstDay) % 7; // adjust for $firstDay
		$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year; // note that some locales don't capitalize month and day names
	
		// --- make calendar header
		@list($p, $pl) = each($this->canonicals); @list($n, $nl) = each($this->canonicals); // previous and next links, if applicable
		if($p) $p = '<div class="prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</div>&nbsp;';
		if($n) $n = '&nbsp;<div class="next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</div>';
		$calendar = '<div class="calendar"><div class="month">'.$p.($this->monthLink ? '<a href="'.htmlspecialchars($this->monthLink).'">'.$title.'</a>' : $title).$n."</div>";

		// --- make days of the week titles
		if($this->dayNameLength){ //if the day names should be shown ($day_name_length > 0)
			foreach($day_names as $d)
				$calendar .= '<div class="box '.htmlentities($d).'">'.htmlentities($this->dayNameLength < 4 ? substr($d,0,$this->dayNameLength) : $d).'</div>';
			$calendar .= '<br><div class="dom">';
		}

		// --- make days of the month
		if($weekday > 0) $calendar .= '<div class="box offset'.$weekday.'">&nbsp;</div>'; // initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			if($weekday == 7) $weekday   = 0; // start a new week

			if(isset($this->days[$day]) and is_array($this->days[$day])){
				@list($link, $classes, $content) = $this->days[$day];
				if(is_null($content))  $content  = $day;
				$calendar .= '<div class="box'.($classes ? ' '.htmlspecialchars($classes).'">' : '">').
					($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</div>';
			}
			else $calendar .= '<div class="box">'.$day.'</div>';
		}
		if($weekday != 7) $calendar .= '<div class="box offset'.(7-$weekday).'">&nbsp;</div>'; // remaining "empty" days

		return $calendar.'<br class="clear"/></div></div>';
	}
	/**
	 * generate_calendar
	 * makes the crappy table version.
	 * 
	 * @param int $year
	 * @param int $month
	 */
	private function make_calendar($year, $month){
		$first_of_month = gmmktime(0,0,0,$month,1,$year);
		#remember that mktime will automatically correct if invalid dates are entered
		# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		# this provides a built in "rounding" feature to generate_calendar()
	
		$day_names = array(); #generate all the day names according to the current locale
		for($n=0,$t=(3+$this->firstDay)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
			$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name
	
		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
		$weekday = ($weekday + 7 - $this->firstDay) % 7; #adjust for $firstDay
		$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names
	
		#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
		@list($p, $pl) = each($this->canonicals); @list($n, $nl) = each($this->canonicals); #previous and next links, if applicable
		if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
		if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
		$calendar = '<table class="calendar">'."\n".
			'<caption class="calendar-month">'.$p.($this->monthLink ? '<a href="'.htmlspecialchars($this->monthLink).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";
	
		if($this->dayNameLength){ #if the day names should be shown ($day_name_length > 0)
			#if day_name_length is >3, the full name of the day will be printed
			foreach($day_names as $d)
				$calendar .= '<th abbr="'.htmlentities($d).'">'.htmlentities($this->dayNameLength < 4 ? substr($d,0,$this->dayNameLength) : $d).'</th>';
			$calendar .= "</tr>\n<tr>";
		}
	
		if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			if($weekday == 7){
				$weekday   = 0; #start a new week
				$calendar .= "</tr>\n<tr>";
			}
			if(isset($this->days[$day]) and is_array($this->days[$day])){
				@list($link, $classes, $content) = $this->days[$day];
				if(is_null($content))  $content  = $day;
				$calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
					($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
			}
			else $calendar .= "<td>$day</td>";
		}
		if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days
	
		return $calendar."</tr>\n</table>\n";
	}	
}


?>