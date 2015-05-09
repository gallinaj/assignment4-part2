<?php
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
	echo "Connection worked!<br />";

	if(!$stmt = $mysqli->prepare("DELETE FROM videos")) {
		echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	if(!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	while($stmt->fetch()){
		echo "Clearing table.";				
		}
	echo "Table cleared.";
}
?>