//Efeito de javascript para ao clicar no Hidelogin salta para registar conta

$(document).ready(function() {

	$('#hideLogin').click(function() {
		$('#loginForm').hide();
		$('#registerForm').show();
	});

	$('#hideRegister').click(function() {
		$('#loginForm').show();
		$('#registerForm').hide();
	});
});