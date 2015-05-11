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
		<title>Filter By Category</title>
	</head>
	<body>
		<div id="Table">
			<?php
			echo "<table border=\"1px\">";
//				echo "<caption>Videos Filtered by " . $_POST['catPick'] . "</caption>";
				echo "<thead>";
					echo "<th>Movie Title</th>";
					echo "<th>Category</th>";
					echo "<th>Length (min)</th>";
					echo "<th>Availability</th>";
					echo "<th>Remove from Inventory?</th>";
					echo "<th>CheckIn/CheckOut</th>";
				echo "</thead>";
				echo "<tbody>";

					if(!($stmt = $mysqli->prepare("SELECT name, category, length, rented FROM videos WHERE category=?"))) {
						echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
					}
					if(!$stmt->bind_param("s", $_POST['catPick'])) {
						echo "Bind failed: (" . $mysqli->errno . ") " . $mysqli->error;
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

							echo "<form id=\"list\" method=\"POST\" action=\"deleting.php\">";
								echo "<td><input type=\"hidden\" name=\"name\" value=\"" . $name . "\">";
								echo "<input type=\"submit\" value=\"Remove\" name=\"deleteVideo\"></td>";
							echo "</form>";

							echo "<form id=\"update\" method=\"POST\" action=\"updatevideo.php\">";
								if($rented == 1) {
									echo "<td><input type=\"submit\" value=\"Check In\"></td>";
								}
								else {
									echo "<td><input type=\"submit\" value=\"Check Out\"></td>";
								}
							echo "</tr>"; 
						echo "</form>";
					}					
					?>
				</tbody>
			</table>
		<form action="videos.php">
			<p><input type="submit" value="Return to Full Listing"></p>
		</form>
		</div>

	</body>
</html>