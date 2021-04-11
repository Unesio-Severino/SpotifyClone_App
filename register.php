<?php
    include("includes/conection.php");
    include("includes/classes/Conta.php");
    include("includes/classes/Constantes.php");

    $Conta = new Conta($con);
    
    include("includes/manipuladores/register-handler.php");
    include("includes/manipuladores/login-handler.php");

    function getInputValue($name) {
		if(isset($_POST[$name])) {
			echo $_POST[$name];
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bem vindo ao Spotify Clone</title>
    
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/register.js"></script>
    
</head>
<body>

    <?php
            if(isset($_POST['registerButton'])) {
                echo '<script>
                        $(document).ready(function() {
                            $("#loginForm").hide();
                            $("#registerForm").show();
                        });
                    </script>';
            }
            else {
                echo '<script>
                        $(document).ready(function() {
                            $("#loginForm").show();
                            $("#registerForm").hide();
                        });
                    </script>';
            }
    ?>

<div id="background">  
    
    <div id="loginContainer">
        <div id="inputContainer">
            <form id="loginForm" action="register.php" method="POST">
                <h2>Entre com sua Conta</h2>
                <p>
                <?php echo $Conta->getError(Constantes::$loginFailed); ?>
                <label for="loginUsername">Usuario</label>
                <input id="loginUsername" name="loginUsername" type="text" placeholder="ex. Unesio_charger" value="<?php getInputValue('loginUsername') ?>" required>
                </p>
                <p>
                <label for="loginPassword">Palavra-passe</label>
                <input id="loginPassword" name="loginPassword" type="password" placeholder="Sua Palavra Passe" required>
                </p>
                <button type="submit" name="loginButton">Entrar</button>

                <div class="ContaText">
                    <span id="hideLogin">Ainda Nao tem uma conta? Inscreva-se aqui.</span>
                </div>
            </form>


            <form id="registerForm" action="register.php" method="POST">
                <h2>Crie a sua Conta</h2>

                <p>
                <?php echo $Conta->getError(Constantes::$usernameCharacters); ?>
                <?php echo $Conta->getError(Constantes::$usernameTaken); ?>
                <label for="username">Usuario</label>
                <input id="username" name="username" type="text" placeholder="ex. Unesio_Windforce" value="<?php getInputValue('username') ?>" required>
                </p>

                <p>
                <?php echo $Conta->getError(Constantes::$primeiroNomeCharacters); ?>
                <label for="primeiroNome">Primeiro Nome</label>
                <input id="primeiroNome" name="primeiroNome" type="text" placeholder="ex. Unesio" value="<?php getInputValue('primeiroNome') ?>" required>
                </p>

                <p>
                <?php echo $Conta->getError(Constantes::$ultimoNomeCharacters); ?>
                <label for="ultimoNome">Ultimo Nome</label>
                <input id="ultimoNome" name="ultimoNome" type="text" placeholder="ex: Windforce" value="<?php getInputValue('ultimoNome') ?>" required>
                </p>

                <p>
                <?php echo $Conta->getError(Constantes::$emailsNaoCorresponde); ?>
                <?php echo $Conta->getError(Constantes::$emailInvalido); ?>
                <?php echo $Conta->getError(Constantes::$emailTaken); ?>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="ex. conta@Gmail.com" value="<?php getInputValue('email') ?>" required>
                </p>

                <p>
                <label for="email2">Confirma seu Email</label>
                <input id="email2" name="email2" type="email" placeholder="ex. conta@Gmail.com" value="<?php getInputValue('email2') ?>" required>
                </p>

                <p>
                <?php echo $Conta->getError(Constantes::$passwordsNaoCorresponde); ?>
                <?php echo $Conta->getError(Constantes::$passwordNaoAlphanumerico); ?>
                <?php echo $Conta->getError(Constantes::$passwordCharacters); ?>
                <label for="password">Palavra-passe</label>
                <input id="password" name="password" type="password" placeholder="Sua Palavra Passe" required>
                </p>

                <p>
                <label for="password2">Confirme a Palavra-passe</label>
                <input id="password2" name="password2" type="password" placeholder="Sua Palavra Passe" required>
                </p>

                <button type="submit" name="registerButton">Criar Conta</button>
                
                <div class="ContaText">
                    <span id="hideRegister">Ja possui uma conta? Faça o Login aqui.</span>
                </div>

            </form>
        </div>

            <div id="LoginTexto">
                <h1>Obtenha excelentes musicas</h1>
                <h2>Ouça boa musicas Gratuitamente</h2>
                <ul>
                    <li>Descubra Musicas que Farão se apaixonar</li>
                    <li>Crie a sua Lista de Reprodução</li>
                    <li>Fique atualizado</li>
                </ul>

            </div>

    </div>
</div> 


</body>
</html>