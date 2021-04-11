<?php
include("../../conection.php");

if(!isset($_POST['username'])) {
	echo "ERROR: Nao conseguiu definir nome de usuario";
	exit();
}

if(isset($_POST['email']) && $_POST['email'] != "") {

	$username = $_POST['username'];
	$email = $_POST['email'];

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "Email e invalido";
		exit();
	}

	$emailCheck = mysqli_query($con, "SELECT email FROM users WHERE email='$email' AND username != '$username'");
	if(mysqli_num_rows($emailCheck) > 0) {
		echo "Email ja esta sendo usado";
		exit();
	}

	$updateQuery = mysqli_query($con, "UPDATE users SET email = '$email' WHERE username='$username'");
	echo "Atualizacao feita com Sucesso";

}
else {
	echo "Voce deve fornecer um email";
}

?>


