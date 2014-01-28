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
}
 ?>