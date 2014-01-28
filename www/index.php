<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="homepage">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UMDB</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="author" href="humans.txt">
    </head>
    <body>
        <h1>University Movie Database</h1>

        <form name="add_movie" action="index.php" method="post">
            <input type="text" name="movieID" placeholder="Movie Name"/>
            <br />
            <textarea rows="4" cols="25" name="movieDescription" placeholder="Description"></textarea>
            <br />
            <input type="text" name="movieLink" placeholder="Movie Link"/>
            <br />
            <input type="text" name="runningTime" placeholder="HH:MM:SS"/>
            <br />
            <input type="submit" />
        </form>
        <br />
        <?php

            // This bit of PHP gets all the database entries 
            // for all movies stored in the UMDB.
            
            include "lib/secrets.php";

            $secrets = new Secrets;

            $host = $secrets->getDatabaseHost();                // storing database information
            $user = $secrets->getDatabaseUser();
            $db = $secrets->getDatabaseName();
            $pass = $secrets->getDatabasePassword();

            // connect to the database and store the connection
            $con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");

            // Create the query string to retrieve all the information for all movies.
            // Uses an inner join to get all the relevant information for each movie 
            // from the individual tables
            $query = "SELECT * FROM movie_id 
            INNER JOIN description 
                ON (movie_id.movie_id = description.description_movie_id) 
            INNER JOIN watch_links
                ON (movie_id.movie_id = watch_links.fk_movie_id) 
            INNER JOIN running_time
                ON (movie_id.movie_id = running_time.fk_movie_id)
            ORDER BY movie_id ASC;";

            // stores the result in "result" if possible
            // quits if something goes wrong
            $result = pg_query($con, $query) or die ("Cannot execute query: $query\n"); 

            $numrows = pg_num_rows($result);    // gets the number of rows (its "length") from the result
            
        ?>
                
        <table border="0">
        <tr>
            <th>ID</th>
            <th>Movie Title</th>
            <th>Description ID</th>
            <th>Description</th>
            <th>Watch Link</th>
            <th>Running Time</th>
        </tr>

        <?php

            include "lib/timeformatter.php";

            $tf = new TimeFormatter;

            // This PHP fills the table that was created above with information 
            // gathered in the previous block
            
            for ($i=0; $i < $numrows; $i++) {                   // numrows is the length of the query result
                $row = pg_fetch_array($result, $i);             // turns the current row into an array
                echo "<tr>";
                echo "<td>", $row["movie_id"], "</td>";         // echos the html to put movie_id into the table
                echo "<td>", $row["movie_title"], "</td>";      // same for all the other pieces of information
                echo "<td>", $row["description_id"], "</td>";
                echo "<td>", $row["description"], "</td>";
                echo "<td>", $row["watch_link"], "</td>";
                echo "<td>", $tf->secToTime($row["running_time"]), "</td>";
                echo "</tr>";
            }
            

            echo "</ul>";

            pg_close($con);                     // closes the connection
        ?>

        </table>
    </body>
</html>
<?php
    // This code block takes input from the post form and submits it to the UMDB
    
    // include "lib/timeformatter.php";

    // $tf = new TimeFormatter;

    $valid = false;

    $host = "localhost";                        // store the database information
    $user = "feanor93";                         // (TODO: delete this instance of this 
    $db = "UMDB";                               // information because it already 
    $pass = "connolly";                         // exists in a previous PHP block)

    $con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");  // connect to the server or die

    $movie_title = pg_escape_string( $_POST['movieID'] );                   // get all movie information from the POST
    $movie_description = pg_escape_string( $_POST['movieDescription'] );    // all information is escaped using "pg_escape_string()"
    $movie_link = pg_escape_string( $_POST['movieLink'] );
    $running_time = $tf->timeToSec(pg_escape_string( $_POST['runningTime'] ));

    error_log($running_time);

    if ($movie_title and $movie_description and $movie_link and $running_time) {              // checks to see if all information is valid 
        $valid = true;                                                      // (TODO: put all validity information into 
    }                                                                       // another class)

    if ($valid) {
        $query = "INSERT INTO movie_id VALUES(DEFAULT, '" . $movie_title . "');";   // creates initial query to insert the movie title into the table: 
                                                                                    // "movie_id"
        $result = pg_query($con, $query) or die ("Cannot execute query: $query\n"); // stores the result of the query or dies

        if (!$result) {                                 // If there's no result,
            $errormessage = pg_last_error();            // get the error message,
            echo "Error with query: " . $errormessage;  // echo it,
            exit();                                     // then exit program
        }

        // This next block of code simply constructs the pgsql query one line at a time 
        // by adding them together while keeping them separated with semicolons
        $query = "INSERT INTO description VALUES( DEFAULT, '" . $movie_description . "', currval('movie_id_movie_id_seq'::regclass) );";
        $query .= "INSERT INTO watch_links VALUES( DEFAULT, '" . $movie_link . "', currval('movie_id_movie_id_seq'::regclass) );";
        $query .= "INSERT INTO running_time VALUES( DEFAULT, '" . $running_time . "', currval('movie_id_movie_id_seq'::regclass) );";
        $result = pg_send_query($con, $query) or die ("Cannot execute query: $query\n");
        error_log("Initiated query: \"$query\"\nThe result was: \"$result\"", 3, "php.log");    // Logs the query for debugging 
                                                                                                // (can be removed in deployment version)

        if (!$result) {                                 // Checks to make sure the result was good
            $errormessage = pg_last_error();            // otherwise, it throws an error and exits
            echo "Error with query: " . $errormessage;
            exit();
        }

        pg_close($con);                                 // Closes the connection
        
        echo '<meta http-equiv="refresh" content="0">'; // Refreshes the page to show 
                                                        // updated table that includes 
                                                        // all the information just submitted
    }

?>