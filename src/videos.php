<?php
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
	echo "Connection worked!<br />";
	
	/***Code assistance from http://www.phpro.org/tutorials/Introduction-to-PHP-and-MySQL.html#6.2
	and CS340 video on PHP and MySQL***/
	
	
    /*** sql to create a new table ***/
    $table = "CREATE TABLE IF NOT EXISTS videos (
	id INT(3) NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL UNIQUE,
	category VARCHAR(255),
	length INT(8) UNSIGNED,
	rented TINYINT NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
	)";

    if(!($mysqli->query($table))) {
        echo $table.'<br />' . $mysqli->error;
    }
	
	/*** sql to INSERT a new record ***/
/*    $stmt = "INSERT INTO videos (name, category, length, rented)
    VALUES (\"The Avengers\", \"Action\", 120, 1)";
    $stmt = "INSERT INTO videos (name, category, length)
    VALUES (\"The Avengers 2\", \"Action\", 130)";
    if($mysqli->query($stmt) === TRUE)
    {
        echo 'New record created successfully<br />';
    }
    else
    {
        echo $stmt.'<br />' . $mysqli->error;
    }*/

    /*** close connection ***/
  //  $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Video Rentals</title>
	</head>
	<body>
		<div id="intro">
			<p><h2>Welcome to Videos 4 U, the outdated video service!</h2></p>
			<p><h3>Please enter the details of your movie.</h3></p>
		</div>
		<div id="videoForm">
			<?php
				
			echo "<form id=\"video\" method=\"POST\" action=\"addvideo.php\"><br />";
				echo "<span>Movie Title </span><input type=\"text\" name=\"name\"><br />";
				echo "<span>Category (Genre) </span><input type=\"text\" name=\"category\"><br />";
				echo "<span>Length </span><input type=\"text\" name=\"length\"><br />";
				echo "<input type=\"submit\" value=\"Add\"><br />";
			echo "</form>";
			?>			
		</div>
	
		<div id="videoTable">
			<table border="1px">
				<caption>Video Inventory</caption>
				<thead>
					<th>Movie Title</th>
					<th>Category</th>
					<th>Length (min)</th>
					<th>Availability (binary)</th>	
					<th>Remove from Inventory?</th>	
					<th></th>
				</thead>
				<tbody>
					<?php
					if(!($stmt = $mysqli->prepare("SELECT name, category, length, rented FROM videos"))) {
						echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
					}
					if(!$stmt->execute()) {
						echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
					}
					if(!$stmt->bind_result($name, $category, $length, $rented)) {
						echo "Bind failed: (" . $stmt->errno . ") " . $stmt->error;
					}
					
					while($stmt->fetch()) {
						echo "<form id=\"list\" method=\"POST\" action=\"deletevideo.php\">";
							echo "<tr>";
							echo "<td>" . $name . "</td>";
							echo "<td>" . $category . "</td>";
							echo "<td>" . $length . "</td>";
							if($rented == 1) {
								$rentable = "Checked Out"; 
							}
							else {
								$rentable = "Available";
							}							
							echo "<td>" . $rentable . "</td>";

							
							/*if(!($stmt = $mysqli->prepare("SELECT id, name FROM videos"))) {
								echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
								
							}
							if(!$stmt->execute()) {
								echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
							}
							if(!$stmt->bind_result($id, $name)) {
								echo "Bind failed: (" . $stmt->errno . ") " . $stmt->error;
							}
							while($stmt->fetch()){
								echo "Fetching";
							}*/
							
							echo "<td><input type=\"submit\" value=\"Remove\"></td>";
							
							if($rented == 1) {
								echo "<td><input type=\"submit\" value=\"Check In\"></td>";
							}
							else {
								echo "<td><input type=\"submit\" value=\"Check Out\"></td>";
							}
							
							
							echo "</tr>"; 
						echo "</form>";
					}
					$stmt->close();
					?>
				</tbody>
		</div>
		
		<div id="clearTable">
			<form id="clearTable" method="POST" action="clearvideo.php">
				<p><input type="submit" value="Clear Table"></p>
			</form>
		</div>

	
	</body>
</html>