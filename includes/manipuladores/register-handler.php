<?php 

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);
	return $inputText;
}

function sanitizeFormUsername($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));
	return $inputText;
}

if(isset($_POST['registerButton'])) {

	//Funcao Quando O Botao de Registar Conta For pressionado
	
	$username = sanitizeFormUsername($_POST['username']);
	$primeiroNome = sanitizeFormString($_POST['primeiroNome']);
	$ultimoNome = sanitizeFormString($_POST['ultimoNome']);
	$email = sanitizeFormString($_POST['email']);
	$email2 = sanitizeFormString($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);

	$wasSuccessful = $Conta->register($username, $primeiroNome, $ultimoNome, $email, $email2, $password, $password2);
		if($wasSuccessful == true) {
			$_SESSION['userLoggedIn'] = $username;
			header ("Location: index.php");
		}	
}

?>