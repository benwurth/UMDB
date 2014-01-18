<?php 
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
			# Takes a string with a formatted time value and returns the number 
			# of seconds as an integer
		}
	}
 ?>