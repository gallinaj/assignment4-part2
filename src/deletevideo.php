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
		<title>Delete Videos</title>
	</head>
	<body>
		<div id="added">
			<form action="videos.php">
				<?php
		if(isset($_POST['delete_Video'])){
			if (!($stmt = $mysqli->prepare("DELETE FROM videos WHERE id=(?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			if (!$stmt->bind_param("i", $_POST['id'])) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			$stmt->close();
		}
				
				/*				if(!($stmt = $mysqli->prepare("DELETE FROM videos WHERE id=?"))) {
					echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				if(!$stmt->bind_param("i", $_POST['list'])) {
					echo "Bind failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}
				if(!$stmt->execute()) {
					echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
				}*/
/*				if(!$stmt->bind_result($id)) {
					echo "Bind failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				while($stmt->fetch()){
					echo "Do you really want to delete " . $name . "?";				
				}*/
				echo "Row " . $_POST['id'] . " deleted.";
				
				
				/*if(!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length) VALUES(?,?,?)"))) {
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				if(!$stmt->bind_param("sss", $_POST['name'], $_POST['category'], $_POST['length'])) {
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				}
				if(!$stmt->execute()) {
					echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
				}
				else {
					echo "Added " . $stmt->affected_rows . " rows to videos.";
				}*/				
				?>
			
				<p><input type="submit" value="OK"></p>
			</form>	
		</div>
	</body>
</html>