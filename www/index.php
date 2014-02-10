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
            <input type="text" name="movieYear" placeholder="2014" />
            <br />
            <input type="submit" />
        </form>
        <br />
        <?php

            // This bit of PHP gets all the database entries 
            // for all movies stored in the UMDB.
            
            // include_once "lib/secrets.php";
            include_once "lib/timeformatter.php";
            include_once "lib/dbTool.php";
            include_once "lib/movie.php";

            // $secrets = new Secrets;
            $dbt = new DBTools;

            $result = $dbt->homepageQuery();
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
        ?>

        </table>
    </body>
</html>
<?php
    // This code block takes input from the post form and submits it to the UMDB

    $valid = false;

    $movie_title = null;
    $movie_description = null;
    $movie_link = null;
    $running_time = null;
    $movie_year = null;

    if (isset( $_POST['movieID'] ) ) {$movie_title = pg_escape_string( $_POST['movieID'] );}                            // get all movie information from the POST    
    if (isset( $_POST['movieDescription'] ) ) {$movie_description = pg_escape_string( $_POST['movieDescription'] );}    // all information is escaped using "pg_escape_string()"
    if (isset( $_POST['movieLink'] ) ) {$movie_link = pg_escape_string( $_POST['movieLink'] );}
    if (isset( $_POST['runningTime'] ) ) {$running_time = $tf->timeToSec(pg_escape_string( $_POST['runningTime'] ));}
    if (isset( $_POST['movieYear'] ) ) {$movie_year = pg_escape_string( $_POST['movieYear'] );}

    if ($movie_title and $movie_description and $movie_link and $running_time and $movie_year) {                // checks to see if all information is valid 
        
        $valid = true;                                                                          // (TODO: put all validity information into 
                                                                                                // another class)
        $curMovie = new Movie($movie_title, $movie_description, $movie_link, $running_time, $movie_year);
    }

    if ($valid) {
        
        $dbt->submitMovie($curMovie);

        echo '<meta http-equiv="refresh" content="0">'; // Refreshes the page to show 
                                                        // updated table that includes 
                                                        // all the information just submitted
    }

?>