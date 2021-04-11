<?php  
include("includes/includedFiles.php");
?>

<div class="entidadeInfo">

	<div class="centroSection">
		<div class="userInfo">
			<h1><?php echo $userLoggedIn->getFirstAndLastName(); ?></h1>
		</div>
	</div>

	<div class="buttonItems">
		<button class="button" onclick="openPage('updateDetails.php')">DETALHES DE USUARIO</button>
		<button class="button" onclick="logout()">SAIR</button>
	</div>


</div>