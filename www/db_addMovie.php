<html>
<body>
	<?php

        $host = "localhost";
        $user = "feanor93";
        $db = "UMDB";
        $pass = "connolly";

        $con = pg_connect("host=$host dbname=$db user=$user password=$pass") or die ("Could not connect to server\n");

        $movie_title = $_POST['movieID'];

        $query = "INSERT INTO movie_id VALUES(DEFAULT, '" . $movie_title . "')";

        if ($movie_title) {
        	$result = pg_query($con, $query) or die ("Cannot execute query: $query\n");
    	}

        if (!$result) {
        	$errormessage = pg_last_error();
        	echo "Error with query: " . $errormessage;
        	exit();
        }

        pg_close($con);

        flush();     
         
        printf("<script>location.href='index.php'</script>");
    ?>
</body>
</html>