<?php 
	
	include "validate.php";

	/**
	* TimeFormatter deals with formatting a number of seconds to and from regular time notation
	*/
	class TimeFormatter
	{
		
		// function __construct()
		// {
		// 	# code...
		// }

		public function secToTime($seconds)
		{
			# Takes an integer representing a number of seconds and returns a 
			# string with the format HH:MM:SS
			
			$timeString = "";
			$minutes = 0;
			$hours = 0;

			$hours = floor($seconds/3600);
			$seconds = $seconds%3600;

			$minutes = floor($seconds/60);
			$seconds = $seconds%60;

			// $output = $hours . ":" . $minutes . ":" . $seconds;
			$output = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
			return $output;
		}

		public function timeToSec($time)
		{
			# Takes a formatted time and returns the total number of seconds as 
			# an integer.
			$tv = new Validate;

			$time = $tv->validateTime($time);

			if ($time)
			{
				$hms = explode(":", $time);

				if (count($hms) == 1)
				{
					return $hms[0];
				} 
				else if (count($hms) == 2)
				{
					return $hms[0] * 60 + $hms[1];
				}
				else if (count($hms) == 3)
				{
					return $hms[0] * 3600 + $hms[1] * 60 + $hms[2];
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}
	}
 ?>