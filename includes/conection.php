<?php
	ob_start();
	session_start();

	$timezone = date_default_timezone_set("Africa/Maputo");

	$con = mysqli_connect("localhost", "root", "", "spotify");

	if(mysqli_connect_errno()) {
		echo "Falha ao Conectar-se: " . mysqli_connect_errno();
	}
?>