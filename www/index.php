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
            <input type="submit" />
        </form>
        <br />
        <?php

            $host = "localhost";
            $user = "feanor93";
            $db = "UMDB";
            $pass = "connolly";

            $con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");

            $query = "SELECT * FROM movie_id LEFT OUTER JOIN description ON (movie_id.movie_id = description.description_movie_id) ORDER BY movie_id ASC;";

            $result = pg_query($con, $query) or die ("Cannot execute query: $query\n");

            //echo $result;

            $numrows = pg_num_rows($result);
            
        ?>
                
        <table border="0">
        <tr>
            <th>ID</th>
            <th>Movie Title</th>
            <th>Description ID</th>
            <th>Description</th>
        </tr>

        <?
            
            for ($i=0; $i < $numrows; $i++) { 
                $row = pg_fetch_array($result, $i);
                echo "<tr>";
                echo "<td>", $row["movie_id"], "</td>";
                echo "<td>", $row["movie_title"], "</td>";
                echo "<td>", $row["description_id"], "</td>";
                echo "<td>", $row["description"], "</td>";
                echo "</td>";
            }

            echo "</ul>";

            pg_close($con);
        ?>

        </table>
    </body>
</html>
<?php

    $valid = false;

    $host = "localhost";
    $user = "feanor93";
    $db = "UMDB";
    $pass = "connolly";

    $con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");

    $movie_title = pg_escape_string( $_POST['movieID'] );
    $movie_description = pg_escape_string( $_POST['movieDescription'] );

    if ($movie_title and $movie_description) {
        $valid = true;
    }

    if ($valid) {
        $query = "INSERT INTO movie_id VALUES(DEFAULT, '" . $movie_title . "');";
        $result = pg_query($con, $query) or die ("Cannot execute query: $query\n");

        if (!$result) {
            $errormessage = pg_last_error();
            echo "Error with query: " . $errormessage;
            exit();
        }

        $query = "INSERT INTO description VALUES( DEFAULT, '" . $movie_description . "', currval('movie_id_movie_id_seq'::regclass) );";
        $result = pg_query($con, $query) or die ("Cannot execute query: $query\n");
        // $result = pg_query($con, $query) or die (pg_last_error());

        if (!$result) {
            $errormessage = pg_last_error();
            echo "Error with query: " . $errormessage;
            exit();
        }

        pg_close($con);
        echo '<meta http-equiv="refresh" content="0">';
    }

?>