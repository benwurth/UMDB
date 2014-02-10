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
		if (!$year)
		{
			return false;
		}
		$dbt = new DBTools;
		$query = "SELECT year_id FROM year_table WHERE year = " . $year . ";";
		$con = $dbt->connect();
		$result = $dbt->queryDB($con, $query);

		// error_log(pg_fetch_row($result)[0]);

		if (!pg_num_rows($result))
		{
			return false;
		}
		else
		{
			$n = pg_fetch_row($result);
			error_log($n[0]);
			return $n[0];
		}
	}

	/**
	 * insertMovieYearRel simply takes a year and determines if there is already an entry
	 * for it in the year_table. If there is, it simply creates a relation in the 
	 * year_rel_table. If there isn't already an entry for that year in year_table, it
	 * creates one and then creates a relationship with year_rel_table.
	 * @param  integer $movie_id the movie_id from the movie object (or the year object)
	 * @param  integer $year     The year from the movie object
	 * @param  integer $year_id  the year_id from the movie object. For creating relational
	 *                           tables
	 * @return Boolean           Returns True if the query was successful. Returns False if
	 *                           unsuccessful.
	 */
	public function insertMovieYearRel($movie_id, $year, $year_id)
	{
		if (!($movie_id and $year and $year_id)) {
			error_log("insertMovieYearRel is missing parameter(s).");
			return -1;
		}

		if ($this->checkIfYearExists($year)) {
			$year_id = $this->addMovieYear($year);
		}
	}

	/**
	 * addMovieYear inserts a year as an integer into year_table
	 * @param integer $year Year from the movie object
	 *
	 * @return integer 		Returns the value for the year_id column
	 */
	public function addMovieYear($year)
	{
		$dbt = new DBTools;
		$query = "INSERT INTO year_table VALUES(DEFAULT, " . $year . "); SELECT currval('year_table_year_id_seq'::regclass);";
		$con = $dbt->connect();
		$result = $dbt->queryDB($con, $query);
		if (!$result) {                                 // If there's no result,
            $errormessage = pg_last_error();            // get the error message,
            echo "Error with query: " . $errormessage;  // echo it,
            return -1;                                     // then exit program
        }

		return pg_fetch_result($result,0,0);
	}
}
 ?>