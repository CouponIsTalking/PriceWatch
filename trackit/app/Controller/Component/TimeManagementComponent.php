<?php
/**
 * IA FrameWork
 * @package: Classes & Object Oriented Programming
 * @subpackage: Date & Time Manipulation
 * @author: ItsAsh <ash at itsash dot co dot uk>
 */

 
class TimeManagementComponent extends Component {
	
		
}

class TimeManagement extends DateTime {

    // Public Methods
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   
    /**
     * Calculate time difference between two dates
     * ...
     */
	
	public static function convert_seconds_into_diff_string($seconds) {

		if ($days = intval((floor($seconds / 86400))))
            $seconds %= 86400;
        if ($hours = intval((floor($seconds / 3600))))
            $seconds %= 3600;
        if ($minutes = intval((floor($seconds / 60))))
            $seconds %= 60;
               
        return array($days, $hours, $minutes, intval($seconds));
	}
   
    public static function StringSecondsDifference($seconds) {
        $i = array();
        list($d, $h, $m, $s) = (array) self::convert_seconds_into_diff_string($seconds);
       
        if ($d > 0)
            $i[] = sprintf('%d Days', $d);
        if ($h > 0)
            $i[] = sprintf('%d Hours', $h);
        if (($d == 0) && ($m > 0))
            $i[] = sprintf('%d Minutes', $m);
        if (($h == 0) && ($s > 0))
            $i[] = sprintf('%d Seconds', $s);
       
        return count($i) ? implode(' ', $i) : 'Just Now';
    }
    	
   // $date2 - $date1
    public static function TimeDifference($date1, $date2) {
        $date1 = is_int($date1) ? $date1 : strtotime($date1);
        $date2 = is_int($date2) ? $date2 : strtotime($date2);
       
        if (($date1 !== false) && ($date2 !== false)) {
            if ($date2 >= $date1) {
                $diff = ($date2 - $date1);
               
                if ($days = intval((floor($diff / 86400))))
                    $diff %= 86400;
                if ($hours = intval((floor($diff / 3600))))
                    $diff %= 3600;
                if ($minutes = intval((floor($diff / 60))))
                    $diff %= 60;
               
                return array($days, $hours, $minutes, intval($diff));
            }
        }
       
        return false;
    }
   
    public static function difference_in_seconds($date1, $date2) {
    	
    	$date1 = is_int($date1) ? $date1 : strtotime($date1);
        $date2 = is_int($date2) ? $date2 : strtotime($date2);
       
        if (($date1 !== false) && ($date2 !== false)) {
        	if ($date2 >= $date1) {
        		return ($date2 - $date1) ;
        	}
        }
        
        return false;
    }
    /**
     * Formatted time difference between two dates
     *
     * ...
     */
   
    public static function StringTimeDifference($date1, $date2) {
        $i = array();
        list($d, $h, $m, $s) = (array) self::TimeDifference($date1, $date2);
       
        if ($d > 0)
            $i[] = sprintf('%d Days', $d);
        if ($h > 0)
            $i[] = sprintf('%d Hours', $h);
        if (($d == 0) && ($m > 0))
            $i[] = sprintf('%d Minutes', $m);
        if (($h == 0) && ($s > 0))
            $i[] = sprintf('%d Seconds', $s);
       
        return count($i) ? implode(' ', $i) : 'Just Now';
    }
   
    /**
     * Calculate the date next month
     *
     * ...
     */
   
    public static function DateNextMonth($now, $date = 0) {
        $mdate = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        list($y, $m, $d) = explode('-', (is_int($now) ? strftime('%F', $now) : $now));
       
        if ($date)
            $d = $date;
       
        if (++$m == 2)
            $d = (($y % 4) === 0) ? (($d <= 29) ? $d : 29) : (($d <= 28) ? $d : 28);
        else
            $d = ($d <= $mdate[$m]) ? $d : $mdate[$m];
       
        return strftime('%F', mktime(0, 0, 0, $m, $d, $y));
    }
   
   public static function TSTomorrowSameTime()
   {
		$date = new DateTime();
		$date->modify('+1 day');
		return $date->format('Y-m-d H:i:s');
   }
   
   public static function TSTodayDate()
   {
		$date = new DateTime();
		return $date->format('Y-m-d');
   }
   
   public static function TSNowTime()
   {
		$date = new DateTime();
		return $date->format('Y-m-d H:i:s');
   }
   
   public static function TSGetTime( $difference_from_now )
   {
		$date = new DateTime();
		$date->modify( $difference_from_now ); // like '+1 day'
		return $date->format('Y-m-d H:i:s');
   }
   
   public static function DecodeDate($format, $date_string)
   {
		$old_error_reporting = error_reporting (0);
		$date = self::temp_date_parse_from_format_until_php53($format, $date_string);
		error_reporting($old_error_reporting);
		return $date;
   }
   
   // This is a temp function as a replacement for date_parse_from_format routine, starting from php 5.3
   private static function temp_date_parse_from_format_until_php53($format, $date) 
   {
		  $dMask = array(
			'H'=>'hour',
			'i'=>'minute',
			's'=>'second',
			'y'=>'year',
			'm'=>'month',
			'd'=>'day'
		  );
		  $format = preg_split('//', $format, -1, PREG_SPLIT_NO_EMPTY); 
		  $date = preg_split('//', $date, -1, PREG_SPLIT_NO_EMPTY); 
		  foreach ($date as $k => $v) {
			if ($dMask[$format[$k]]) $dt[$dMask[$format[$k]]] .= $v;
		  }
		  return $dt;
	}
	
} // class ends here
?>