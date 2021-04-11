<?php

include("includes/conection.php");
include("includes/classes/Artists.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");
include("includes/classes/User.php");
//session_destroy(); LOGOUT

if(isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
	$username = $userLoggedIn->getUsername();

// funcao para mostrar os usuarios logados na barra de navegacao
	echo "<script>userLoggedIn = '$username';</script>";

}
else {
	header("Location: register.php");
}

?>

<html>
<head>
	<title>Welcome to Slotify!</title>

	<link rel="stylesheet" type="text/css" href="assets/css/index.css">
	
	<script src="assets/js/jquery-3.2.1.min.js"></script>
	<script src="assets/js/scriptFile.js"></script>

</head>

<body>

	<div id="mainContainer">

		<div id="internalContainer">

			<?php include("includes/navBarContainer.php"); ?>

			<div id="mainViewContainer">
				<div id="principalContent">