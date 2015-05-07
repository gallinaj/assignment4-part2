<?php
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
	echo "Connection worked!<br />";
	
	/***Code assistance from http://www.phpro.org/tutorials/Introduction-to-PHP-and-MySQL.html#6.2***/
	
    /*** sql to create a new table ***/
    $sql = "CREATE TABLE videos (
	id INT(3) NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL UNIQUE,
	category VARCHAR(255),
	length INT(8) UNSIGNED,
	rented TINYINT NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
	)";

    if($mysqli->query($sql) === TRUE)
    {
        echo 'New table created successfully<br />';
    }
    else
    {
        echo $sql.'<br />' . $mysqli->error;
    }
	
	/*** sql to INSERT a new record ***/
    $sql = "INSERT INTO videos (name, category, length, rented)
    VALUES (\"The Avengers\", \"Action\", 120, 1)";
    $sql = "INSERT INTO videos (name, category, length)
    VALUES (\"The Avengers 2\", \"Action\", 130)";
    if($mysqli->query($sql) === TRUE)
    {
        echo 'New record created successfully<br />';
    }
    else
    {
        echo $sql.'<br />' . $mysqli->error;
    }

    /*** close connection ***/
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Video Rentals</title>
	</head>
	<body>
		<?php
		echo "<p>Welcome to Videos 4 U, the outdated video service!<br />";
		echo "Please enter a description of your movie.</p>";
			
		echo "<form id=\"video\" method=\"GET\" action=\"testing.php\"><br />";
			echo "<span>Movie Title </span><input type=\"text\" name=\"title\"><br />";
			echo "<span>Category (Genre) </span><input type=\"text\" name=\"genre\"><br />";
			echo "<span>Length </span><input type=\"number\" name=\"length\"><br />";
			echo "<input type=\"submit\" value=\"Add\"><br />";
		echo "</form>";
	
		

		?>	
	</body>
</html>