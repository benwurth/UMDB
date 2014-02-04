<?php 
/**
* Year is a class for storing information related to a movie's
* year and contains methods for dealing with information
* related to years
*/

include_once "dbTool.php";

class Year
{
	private $movie_id = 0;
	private $year = 0;
	private $year_id = 0;

	function __construct($m_id = null, $y = null, $y_id = null)
	{
		$this->movie_id = $m_id;
		$this->year = $y;
		$this->year_id = $y_id;
	}

	public function checkIfYearExists($year)
	{
		$dbt = new DBTools;
		$query = "SELECT * FROM year_table WHERE year = " . $year . ";";
		$con = $dbt->connect();
		$result = $dbt->queryDB($con, $query);

		if (!pg_num_rows($result))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function insertMovieYear($movie_id, $year, $year_id)
	{
		# code...
	}

	public function addMovieYear($year)
	{
		# code...
	}
}
 ?>