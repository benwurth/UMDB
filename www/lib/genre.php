<?php 
	/**
	* genre.php is the class for a Genre. It congains code to create genres, 
	* access genres, and delete genres.
	*/

	include_once "dbTool.php";

	class Genre
	{
		private $movie_id = 0;
		private $genre_id = 0;
		private $genre_name = "";

		function __construct($movie_id = null, $genre_id = null, $genre_name = null)
		{
			$this->$movie_id = $movie_id;
			$this->$genre_id = $genre_id;
			$this->$genre_name = $genre_name;
		}

		/**
		 * Queries the database to see if there is an entry matching the genre
		 * name. If there is, it returns the genre_id. If not, it returns -1.
		 * @param  string $checkName The name of the genre to check.
		 * @return integer            Returns genre_id if there is already an entry
		 *                            with the same name in the database. 
		 *                            Returns -1 if there isn't.
		 */
		public function checkForGenre($checkName)
		{
			if (!$checkName)
			{
				return false;
			}
			$dbt = new DBTools;
			$query = "SELECT genre_id FROM genres WHERE genre_name = " . $checkName . ";";
			$con = $dbt->connect();
			$result = $dbt->queryDB($con, $query);

			if (!pg_num_rows($result)) 
			{
				return -1;
			}
			else
			{
				$n = pg_fetch_row($result);
				return $n[0];
			}
		}

		/**
		 * addGenre simply adds an entry for a genre in the genres database.
		 * If it is successful, it returns the genre_id of the entry.
		 * @param 	string $name 	The name of the Genre (should be capitalized!)
		 * @return 	integer 		If the query is successful, it returns the genre_id
		 *                       	of the entry. Otherwise, it returns -1.
		 */
		public function addGenre($name)
		{
			$dbt = new DBTools;
			$query = "INSERT INTO genres VALUES(DEFAULT, " . $name . "); SELECT currval('genres_genre_id_seq'::regclass);";
			$con = $dbt->connect();
			$result = $dbt->queryDB($con, $query);
			if (!$result)
			{
				$errormessage = pg_last_error();
				echo "Error with query: " . $errormessage;
				return -1;
			}

			return pg_fetch_result($result, 0, 0);
		}
	}
 ?>