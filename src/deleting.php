<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
else {
	echo "Connection worked!";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Delete Videos</title>
	</head>
	<body>
		<div id="deleting">
			<form action="videos.php">
			<?php
				if (!($stmt = $mysqli->prepare("DELETE FROM videos WHERE name=?"))) {
					echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				if(!($stmt->bind_param("s", $_POST['name']))) {
					echo "Bind failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				if (!$stmt->execute()) {
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				else {
					echo "Row deleted.";	
				}
			?>
				<p><input type="submit" value="OK"></p>
			</form>
		</div>
	</body>
</html>
