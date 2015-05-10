<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
include 'storedInfo.php';

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "gallinaj-db", $myPassword, "gallinaj-db");

if($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Add Videos</title>
	</head>
	<body>
		<div id="added">
			<form action="videos.php">
				<?php
				if($_POST['name'] == NULL) {
					echo "The video name is a required field.";
				}
				elseif(!is_numeric($_POST['length']) || $_POST['length'] < 0) {
					echo "The value of length must be a positive integer.";
				}
				else {
					if(!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length) VALUES(?,?,?)"))) {
						echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
					}
					if(!$stmt->bind_param("ssi", $_POST['name'], $_POST['category'], $_POST['length'])) {
						echo "Bind failed: "  . $mysqli->errno . " " . $mysqli->error;
					}
					if(!$stmt->execute()) {
						echo "Execute failed: "  . $mysqli->errno . " " . $mysqli->error;
					}
					else {
						echo "Added " . $stmt->affected_rows . " row to videos.";
					}
				}
				
				?>
				<p><input type="submit" value="OK"></p>
			</form>	
		</div>
	</body>
</html>