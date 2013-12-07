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

        <form name="add_movie" action="db_addMovie.php" method="post">
            <input type="text" name="movieID" placeholder="Movie Name" />
            <br />
            <input type="submit" />
            <br />
        </form>
    </body>
</html>
