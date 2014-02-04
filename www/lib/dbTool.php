<?php 

include "secrets.php";

/**
* dbTool is a class for handling most of the processes that require access to the
* postgresql database.
*
* It gets the database information from 'www/lib/secrets.php', which is not included
* in the git repo. If you are running UMDB on a development machine, you'll need to 
* create your own version of 'secrets.php.'
*/
class DBTools
{
	private $host = "";
	private $user = "";
	private $db = "";
	private $pass = "";

	function __construct()
	{
		$s = new Secrets;
		$this->host = $s->getDatabaseHost();
		$this->user = $s->getDatabaseUser();
		$this->db = $s->getDatabaseName();
		$this->pass = $s->getDatabasePassword();	
	}

	public function connect()
	{
		/**
		 * This function simply returns a database connection
		 */
		
		$host = $this->host;
		$db = $this->db;
		$user = $this->user;
		$pass = $this->pass;
		
		$con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");
		return $con;
	}

	public function queryDB($con, $query)
	{
		// quits if something goes wrong
		$result = pg_query($con, $query) or die ("Cannot execute query: $query\n");
		return $result;
	}

	public function homepageQuery()
	{
		$con = $this->connect();
		$query = "SELECT * FROM movie_id 
            INNER JOIN description 
                ON (movie_id.movie_id = description.description_movie_id) 
            INNER JOIN watch_links
                ON (movie_id.movie_id = watch_links.fk_movie_id) 
            INNER JOIN running_time
                ON (movie_id.movie_id = running_time.fk_movie_id)
            ORDER BY movie_id ASC;";
        $result = $this->queryDB($con, $query);
        pg_close($con);
        return $result;
	}

	public function submitMovie($movie)
	{
		$con = $this->connect();
		$movie_title = $movie->getTitle();
		$description = $movie->getDescription();
		$watch_links = $movie->getWatchLinks();
		$running_time = $movie->getRunningTime();

		$query = "INSERT INTO movie_id VALUES(DEFAULT, '" . $movie_title . "'); SELECT currval('movie_id_movie_id_seq'::regclass);";
		$result = $this->queryDB($con, $query);

		if (!$result) {                                 // If there's no result,
            $errormessage = pg_last_error();            // get the error message,
            echo "Error with query: " . $errormessage;  // echo it,
            exit();                                     // then exit program
        }

		$movie_id = pg_fetch_result($result,0,0);
		
		$query = '';


		if ($description) {
			$query .= "INSERT INTO description VALUES( DEFAULT, '" . $description . "', " . $movie_id ." );";
		}

		if ($watch_links) {
			$query .= "INSERT INTO watch_links VALUES( DEFAULT, '" . $watch_links . "', " . $movie_id ." );";
		}

		if ($running_time) {
			$query .= "INSERT INTO running_time VALUES( DEFAULT, '" . $running_time . "', " . $movie_id ." );";
		}

		error_log("Initiated query: \"$query\"\nThe result was: \"$result\"", 3, "php.log");    // Logs the query for debugging 
                                                                                                // (can be removed in deployment version)

		$result = $this->queryDB($con, $query);

		if (!$result) {                                 // Checks to make sure the result was good
            $errormessage = pg_last_error();            // otherwise, it throws an error and exits
            echo "Error with query: " . $errormessage;
            exit();
        }

		pg_close($con);
		return $result;
	}
}
 ?>