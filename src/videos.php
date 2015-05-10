<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
//	echo "Connection worked!<br />";
	
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

		</div>
		<div id="videoForm">
			<fieldset>
				<legend>Please enter the details of your movie:</legend>
				<?php
				echo "<form id=\"video\" method=\"POST\" action=\"addvideo.php\"><br />";
					echo "<span>Movie Title </span><input type=\"text\" name=\"name\"><br />";
					echo "<span>Category (Genre) </span><input type=\"text\" name=\"category\"><br />";
					echo "<span>Length </span><input type=\"text\" name=\"length\"><br />";
					echo "<input type=\"submit\" value=\"Add\">";
				echo "</form>";
				?>
			</fieldset>
		</div>
		
		<div id="filterVideo">
			<form method="POST" action="filtervideo.php">
				<fieldset>
					<select name="pickCat">
						<?php
						$pick = array();
						
						if (!($stmt = $mysqli->prepare("SELECT category FROM videos"))) {
							echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
						}
						if (!$stmt->execute()) {
							echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
						}
						if (!$stmt->bind_result($category)) {
							echo "Bind failed: (" . $mysqli->errno . ") " . $mysqli->error;
						}						
						
						while($stmt->fetch()) {
							if(in_array($pick)) {
								break;
							}
							else {
								$pick.array_push($pick);
								echo "<option value=\" ". $category . " \"> " . $category . "</option>";
							}
						}
						?>
					</select>
					<input type="submit" value="Filter By Category" />
				</fieldset>
			</form>
		</div>
	
		<div id="videoTable">
			<table border="1px">
				<caption>Video Inventory</caption>
				<thead>
					<th>Movie Title</th>
					<th>Category</th>
					<th>Length (min)</th>
					<th>Availability</th>	
					<th>Remove from Inventory?</th>	
					<th>CheckIn/CheckOut</th>
				</thead>
				<tbody>
					<?php
					if(!($stmt = $mysqli->prepare("SELECT name, category, length, rented FROM videos"))) {
						echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					if(!$stmt->execute()) {
						echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					if(!$stmt->bind_result($name, $category, $length, $rented)) {
						echo "Bind failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					
					while($stmt->fetch()) {
						
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

/*						<form id="list" method="POST" action="deletevideo.php">
							<td><input type="submit" value="Remove"></td>
						</form>*/
						
						echo "<form id=\"list\" method=\"POST\" action=\"deletevideo.php\">";
							echo "<td><input type=\"submit\" value=\"Remove\"></td>";
						echo "</form>";
						
						
						echo "<form id=\"update\" method=\"POST\" action=\"updatevideo.php\">";
							if($rented == 1) {
								echo "<td><input type=\"submit\" value=\"Check In\"></td>";
							}
							else {
								echo "<td><input type=\"submit\" value=\"Check Out\"></td>";
							}
						echo "</form>";
							
							echo "</tr>"; 
					}
					$stmt->close();
					?>
				</tbody>
			</table>
		</div>
		
		<div id="clearTable">
			<fieldset>
				<form id="clearTable" method="POST" action="clearvideo.php">
					Click this button to clear the table.<br />
					Warning! Cannot be undone!<br />
					<input type="submit" value="Delete All Videos">
				</form>
			</fieldset>
		</div>

	
	</body>
</html>