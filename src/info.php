<!DOCTYPE html>
<html>
<body>
 <!--taken from the http://www.w3schools.com/html/tryit.asp?filename=tryhtml_layout_divs-->
 <div id = "header"><h1>Video Database</h1></div>
 <style>
 #header {
    background-color:#5270B0;
    color:white;
    text-align:center;
    padding:5px;
}
</style>

<form action="video_database.php" method="POST">
	<fieldset>
		<div><h3> ADD VIDEO </h3></div>
		<p> <b> Name: </b> <input type="text" name="video_Name" > 
		<b> Category:</b> <input type="text" name="video_Category" >  
		<b> Length: </b> <input type="number" name="length" min=0>
		</p>
		<input type="submit" value="submit" name="add_Video">
	</fieldset>
</form>
</div>
</body>
</html>

<?php
	//used as a reference: 
	//https://github.com/leonardbosu/assignment4-part2/blob/0df38884bd9c8f7261a33b1720d9cf4cd30e5233/upload.php
	//https://github.com/jurczakn/PHP-MySQL-Assignment/blob/master/videoInventory.php
	//https://github.com/wangxis/cs290-assignment4-part2/blob/48270176949bde41890832f6dfb08cb3b5f1dbbb/assignment4-part2.php
	ini_set('display_errors', 'On'); //The error_reporting() function sets the error_reporting directive at runtime. 
	//conecting to the database
	//$mysqli = new mysqli("localhost", "user", "password", "database");
	//http://php.net/manual/en/mysqli.quickstart.connections.php
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "vlaskint-db", "tb0NGWMdrkGhe2mA", "vlaskint-db");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	//delete video
	//http://stackoverflow.com/questions/6631364/when-to-close-prepared-statement
	//STAGE 1: Prepared statement, stage 1: prepare */
	//http://php.net/manual/en/mysqli.quickstart.prepared-statements.php
	//$stmt->bind_param("s", $_POST['n']);
	//http://www.mustbebuilt.co.uk/php/insert-update-and-delete-with-mysqli/
	//deletiong will be done based on the unique ID
	if(isset($_POST['delete_Video'])){
		if (!($stmt = $mysqli->prepare("DELETE FROM video_inventory WHERE id=(?)"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->bind_param("s", $_POST['id_title'])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$stmt->close();
	}
	//update availability
	//this is similar to the delete, but 
	if(isset($_POST['availabilityUpdate'])){
		if(isset($_POST['checkInOut'])) {
			//if the preparation failed
			//http://stackoverflow.com/questions/18316501/php-update-prepared-statement
			//update is done based on the unique ID
			if(!($stmt = $mysqli->prepare("UPDATE video_inventory SET rented = (?) WHERE id=(?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			//if the movie is available for the checkout
			if($_POST['checkInOut'] == 'in') {
				$inOut = false;
			} 
			//if the movie is not available for checkout
			else if ($_POST['checkInOut'] == 'out') {
				$inOut = true;
			}
			
			//if binding did not work dispay an error
			if (!$stmt->bind_param("is", $inOut, $_POST['checkInOutItem'])) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//if the execution failed, display an error
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			$stmt->close();// close the statement
		}
	}
	
	//input, most of the code is taken from teh following websites
	//http://stackoverflow.com/questions/6631364/when-to-close-prepared-statement
	//http://php.net/manual/en/mysqli.quickstart.prepared-statements.php
	if(isset($_POST['add_Video'])){
		if(empty($_POST['video_Name'])){// if there is no entry for username, http://php.net/manual/en/reserved.variables.post.php
			echo "<font color='red'> WARNING: Video name field cannot be empty <br> </font>";
		} 
		else {
			if (!($stmt = $mysqli->prepare("SELECT name FROM video_inventory"))) {
				echo "<font color='red'>Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error. "</font>";
			}
			if (!$stmt->execute()) {
				echo "<font color='red'>Execute failed: (" . $mysqli->errno . ") " . $mysqli->error. "</font>";
			}
			$name_in_Database=NULL;
			$same_Name=false; //boolean that will toggle between false and true depending on the status if the entered name is dublicate
			if (!$stmt->bind_result($name_in_Database)) {
				echo "<font color='red'>Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error. "</font>";
			}
			// Fetch results from a prepared statement into the bound variables
			//http://php.net/manual/en/mysqli-stmt.fetch.php
			while ($stmt->fetch()){
				if($name_in_Database == $_POST['video_Name']){// check if the name that was just entered is already in the database
					echo "<font color='red'>". $_POST['video_Name'] . " is already in the database<br>" . "</font>";
					$same_Name=true; // if the mane is the same
					break;
				}
			}
			$stmt->close();// close the statement
			//if the name that was enteres is not in the database already,
			if(!$same_Name){
				$name = $_POST['video_Name'];
			}
		}
		//if catergory was not entered, this is acceptable unless I missread the assignment description
		if (empty($_POST['video_Category'])){
			$category_in_Database="Not indicated";
		} 
		//if category was chosen
		else {
			$category_in_Database=$_POST['video_Category'];
		}
		//if length was not indicated
		if($_POST['length'] <= 0){
			echo "<font color='red'> The length has to be a positive integer<br> </font>";
		}
		//if length was indicated
		else {
			$length_in_Database=$_POST['length'];
		}
		//if all the entries are acceptable, we can go ahead and add the entry in the table
		//code is modified from http://www.mustbebuilt.co.uk/php/insert-update-and-delete-with-mysqli/
		if(isset($name, $category_in_Database, $length_in_Database)){
			//if error during preparation
			if (!($stmt = $mysqli->prepare("INSERT INTO video_inventory (name, category, length) VALUES (?, ?, ?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			//if there is an error during binding
			if (!$stmt->bind_param("ssi", $name, $category_in_Database, $length_in_Database)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//if error during execution
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			$stmt->close();//closing 
		}
	}
	
	//Display by category
	if(isset($_POST['display_by_Category'])){
		//if user wants to see all movies
		if ($_POST['choice'] == 'all_Movies') {
			//if preparation failed
			if (!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_inventory"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
		} 
		//if user want to see certain category
		else {
			//if preparation failed
			if (!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_inventory WHERE category=(?)"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			//if binding failed
			if (!$stmt->bind_param("s", $_POST['choice'])) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}
	}
	
	//delete all videos
	//http://stackoverflow.com/questions/3000917/delete-all-from-table
	//code is essentially copy and paste from the add, delete, etc sections
	//we are making sure that the statement was prepared, binded and executed
	
	if(isset($_POST['delete_All_Videos'])){
		if (!($stmt = $mysqli->prepare("DELETE FROM video_inventory"))) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$stmt->close();
		echo '<h3>' . "VIDEO LIST" . '</h3>';
		echo '<table border="1">';
		echo '<tr><td><b>ID</b></td<td><b>Name</b></td><td><b>Category</b></td><td><b>Length</b></td><td><b>Availability</b></td><td><b>Transaction</b></td><td><b>Delete from the inventory</b></td></tr>';
		echo '</table>';
	} 
	else {
		//display list
		if(!isset($_POST['display_by_Category']) && !isset($_POST['delete_All_Videos'])){
			if (!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_inventory"))) {
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
		}
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$name_in_Database = NULL;
		$category_in_Database = NULL;
		$length_in_Database =NULL;
		$rental_Status= NULL;
		//http://stackoverflow.com/questions/18753262/example-of-how-to-use-bind-result-vs-get-result
		if (!$stmt->bind_result($id, $name_in_Database, $category_in_Database, $length_in_Database, $rental_Status)) {
			echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		echo '<fieldset>' . '<h3>' . "VIDEO LIST" . '</h3>';
		echo '<table border="1">';
		echo '<tr><td><b>ID</b></td><td><b>Name</b></td><td><b>Category</b></td><td><b>Length (minutes)</b></td><td><b>Availability</b></td><td><b>Transaction</b></td><td><b>Delete from the inventory</b></td></tr>';
		while ($stmt->fetch()) {//Fetch results from a prepared statement into the bound variables
			if ($rental_Status == 'false') {
				$rental_Status = 'Available';
			}
			else {//if the video is checked out
				$rental_Status = 'Checked out';
			}
			echo '<tr><td>' .$id . '<td>' . $name_in_Database . '<td>' . $category_in_Database . '<td>' . $length_in_Database . '<td>' . $rental_Status;
			echo '<td><form method="POST" action="video_database.php">';
			//lets the user to switch between the checkout status and checked in status
			echo "Check-" . "in" . '<input type="radio" name="checkInOut" value=' . "in" . '>';
			echo "out" . '<input type="radio" name="checkInOut" value=' . "out" . '>';
			echo '<input type="hidden" name="checkInOutItem" value=' . $id . '>';
			//button that updates the status once the check-in or check-out is selected
			echo '<input type="submit" value="Update" name=' . "availabilityUpdate" . '></form></td>';
			echo '<td><form method="POST" action="video_database.php">';
			//delete video button
			echo '<input type="hidden" name="id_title" value=' . $id;
			echo '><input type="submit" value="Delete Video" name =' . "delete_Video" . '></form></td>';
		}
		echo '</table>'. '</fieldset>';
		$stmt->close();
	}
	//display category
	//https://github.com/salbahra/Facebook-Group-Search/blob/master/import.php
	if (!($stmt = $mysqli->prepare("SELECT DISTINCT category FROM video_inventory"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	$category_in_Databaseegory=NULL;
	if (!$stmt->bind_result($category_in_Databaseegory)) {
		echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	echo '<fieldset>' . '<h3>' . "DISPLAY VIDEOS BY CATEGORY" . '</h3>';
	echo '<form method="POST" action="video_database.php">';
	echo '<select name=' . "choice" . '>';
	while ($stmt->fetch()){
		echo '<option value=' . $category_in_Databaseegory . '>' . $category_in_Databaseegory . '</option>';
	}
	echo '<option selected value=' . "all_Movies" . '>' . "all movies" . '</option>';
	echo '<input type="submit" value="submit" name=' . "display_by_Category" . '>';
	echo '</select></form>'. '</fieldset>';
	
	//delete button that deletes everything from the database
	echo '<form method="POST" action="video_database.php">';
	echo '<br>';
	echo '<input type="submit" value="Delete All Videos" name=' . "delete_All_Videos" . '>';
	echo '</form>';
?>