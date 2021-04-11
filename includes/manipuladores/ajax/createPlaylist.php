<?php
include("../../conection.php");

if(isset($_POST['name']) && isset($_POST['username'])) {

	$name = $_POST['name'];
	$username = $_POST['username'];
	$date = date("Y-m-d");

	$query = mysqli_query($con, "INSERT INTO playlists VALUES('', '$name', '$username', '$date')");

}
else {
	echo "Nome e nome de usuario nao foram passados para lista";
}

?>