<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {

	if(isset($_POST)) {
		
		if(($_POST['rented']) == '1') {
			if(!($stmt = $mysqli->prepare("UPDATE videos SET rented = '0' WHERE id=?"))) {
				echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
			}
			if(!$stmt->bind_param("i", $_POST['id'])) {
				echo "Bind failed: "  . $mysqli->errno . " " . $mysqli->error;
			}
			if(!$stmt->execute()) {
				echo "Execute failed: "  . $mysqli->errno . " " . $mysqli->error;
			}
			else {
				echo "Updated " . $stmt->affected_rows . " row to videos.";
			}
		}
	}
	else {
		echo "Didn't pass info";
	}
	
	
/*

	if(!$stmt = $mysqli->prepare("UPDATE videos SET ")) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if(!$stmt->execute()) {
		echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	while($stmt->fetch()){
		echo "Clearing table.";				
	}
	echo "<form>";
		echo "Table cleared.";
		echo "<p><input type=\"submit\" value=\"OK\"></p>";
	echo "</form>";*/

}
?>