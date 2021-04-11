<?php

//Funcoes que indicam se a pagina foi carregada com AJAX ou nao!

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	include("includes/conection.php");
	include("includes/classes/User.php");
	include("includes/classes/Artists.php");
	include("includes/classes/Album.php");
	include("includes/classes/Song.php");
	include("includes/classes/Playlist.php");

	if(isset($_GET['userLoggedIn'])) {
		$userLoggedIn = new User($con, $_GET['userLoggedIn']);
	}
	else {
		echo "A variavel Nome de usuario nao foi passada na pagina. Verifique a openPage JS function";
		exit();
	}
}
else {
	include("includes/header.php");
	include("includes/footer.php");

	$url = $_SERVER['REQUEST_URI'];
	echo "<script>openPage('$url')</script>";
	exit();
}

?>