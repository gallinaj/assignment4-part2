<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {


	if(!$stmt = $mysqli->prepare("DELETE FROM videos")) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if(!$stmt->execute()) {
		echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	while($stmt->fetch()){
		echo "Clearing table.";				
		}
	echo "<form action=\"videos.php\">";
		echo "Table cleared.";
		echo "<p><input type=\"submit\" value=\"OK\"></p>";
	echo "</form>";

}
?>