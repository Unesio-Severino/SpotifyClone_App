<?php
	class Conta {

		private $con;
    	private $errorArray;

		public function __construct($con) {
			$this->con = $con;
			$this->errorArray = array();
		}
		//FUNCAO PARA VERICAR DADOS DE LOGIN
		public function login($un, $pw) {
			$pw = md5($pw);

			$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

			if(mysqli_num_rows($query) == 1) {
				return true;
			}
			else {
				array_push($this->errorArray, Constantes::$loginFailed);
				return false;
			}

		}

		public function register($un, $pn, $ln, $em, $em2, $pw, $pw2) {
	        $this->validateUsername($un);
            $this->validateprimeiroNome($pn);
            $this->validateultimoNome($ln);
            $this->validateEmails($em, $em2);
			$this->validatePasswords($pw, $pw2);

			if(empty($this->errorArray) == true) {
				//INSERIR NA BASE DE DADOS
				return $this->insertUsuarioDetalhes($un, $pn, $ln, $em, $pw);
			}
			else {
				return false;
			}
		}

		public function getError($error){
			if(!in_array($error, $this->errorArray)){
				$error ="";
			}
			return "<span class='errorMessage'>$error</span>";
		}

		private function insertUsuarioDetalhes($un, $pn, $ln, $em, $pw) {
			$encryptedPw = md5($pw);
			$profilePic = "assets/imagens/imagens_perfil/userPerfil.png";
			$date = date("d-m-Y");

			$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$pn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

			return $result;
		}

		private function validateUsername($un) {

			if(strlen($un) > 25 || strlen($un) < 5) {
				array_push($this->errorArray, Constantes::$usernameCharacters);
				return;
			}

			$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
			if(mysqli_num_rows($checkUsernameQuery) != 0) {
				array_push($this->errorArray, Constantes::$usernameTaken);
				return;
			}

		}
		//FAZ: VERIFICA SE O NOMES DE USUARIOS JA EXISTEM
		private function validateprimeiroNome($pn) {
			if(strlen($pn) > 25 || strlen($pn) < 2) {
				array_push($this->errorArray, Constantes::$primeiroNomeCharacters);
				return;
			}
		}

		private function validateultimoNome($ln) {
			if(strlen($ln) > 25 || strlen($ln) < 2) {
				array_push($this->errorArray, Constantes::$ultimoNomeCharacters);
				return;
			}
		}

		private function validateEmails($em, $em2) {
			if($em != $em2) {
				array_push($this->errorArray, Constantes::$emailsNaoCorresponde);
				return;
			}

			if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
				array_push($this->errorArray, Constantes::$emailInvalido);
				return;
			}

			$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM username WHERE email='$em'");
			if(mysqli_num_rows($checkEmailQuery) != 0) {
				array_push($this->errorArray, Constantes::$emailTaken);
				return;
			}
		}
			//Faz: verifica se o nome de usuario ja esta sendo usado
			private function validatePasswords($pw, $pw2) {
				if($pw != $pw2) {
				array_push($this->errorArray, Constantes::$passwordsNaoCorresponde);
				return;
			}

				if(preg_match('/[^A-Za-z0-9]/', $pw)) {
				array_push($this->errorArray, Constantes::$passwordNaoAlphanumerico);
				return;
			}

				if(strlen($pw) > 30 || strlen($pw) < 5) {
				array_push($this->errorArray, Constantes::$passwordCharacters);
				return;
			}

		}
	}
?>