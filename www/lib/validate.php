<?php 
	/**
	* Validate includes tools for determining the validity of different values.
	*/
	class Validate
	{

		const FORMATTED_TIME_REGEX = "/^\d{0,2}(:\d{0,2}){0,2}/";
		
		// public function __construct(argument) 
		// {
			
		// }

		public function validateTime($time)
		{
			$v = preg_match("/\d{1,2}(:\d{0,2}){0,2}/", $time, $matches[]);

			if ($v == 1) {
				return $matches[0][0];
			}
			else {
				return FALSE;
			}
		}
	}

 ?>