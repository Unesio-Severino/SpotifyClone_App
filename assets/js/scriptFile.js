var currentPlaylist = [];
var aleatorioPlaylist = [];
var temporaryPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var aleatorio = false;
var userLoggedIn;
var timer;


//Funcao para esconder o menu (tres pontinhos) quando fazes scroll
$(document).click(function(click) {
	var target = $(click.target);

	if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
		hideOptionsMenu();
	}
});

$(window).scroll(function() {
	hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
	var select = $(this);
	var playlistId = select.val();
	var songId = select.prev(".songId").val();

	$.post("includes/manipuladores/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId})
	.done(function(error) {

		if(error != "") {
			alert(error);
			return;
		}

		hideOptionsMenu();
		select.val("");
	});
});

function updateEmail(emailClass) {
	var emailValue = $("." + emailClass).val();

	$.post("includes/manipuladores/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn})
	.done(function(response) {
		$("." + emailClass).nextAll(".message").text(response);
	})


}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPasswordClass1).val();
	var newPassword2 = $("." + newPasswordClass2).val();

	$.post("includes/manipuladores/ajax/updatePassword.php", 
		{ oldPassword: oldPassword,
		newPassword1: newPassword1,
		newPassword2: newPassword2, 
		username: userLoggedIn})

	.done(function(response) {
		$("." + oldPasswordClass).nextAll(".message").text(response);
	})


}

function logout() {
	$.post("includes/manipuladores/ajax/logout.php", function() {
		location.reload();
	});
}

//Funcao para mudar as paginas fazendo Load na mesma pagina
function openPage(url) {

	if(timer != null) {
		clearTimeout(timer);
	}

	if(url.indexOf("?") == -1) {
		url  = url + "?";
	}
	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	console.log(encodedUrl);
	$("#principalContent").load(encodedUrl);
	$("body").scrollTop(0);
	history.pushState(null, null, url);
}

function removerFromPlaylist(button, playlistId) {
	var songId = $(button).prevAll(".songId").val();

	$.post("includes/manipuladores/ajax/removerFromPlaylist.php", { playlistId: playlistId, songId: songId })
	.done(function(error) {

		if(error != "") {
			alert(error);
			return;
		}

		//do something when ajax returns
		openPage("playlist.php?id=" + playlistId);
	});
}

function createPlaylist() {
	var popup = prompt("Por favor insira uma nova lista de reproducao");

	if(popup != null) {

		$.post("includes/manipuladores/ajax/createPlaylist.php", { name: popup, username: userLoggedIn})
		.done(function(){

			if(error != "") {
				alert(error);
				return;
			}

			openPage("suaMusica.php");
		});
	}
}

function hideOptionsMenu() {
	var menu = $(".optionsMenu");
	if(menu.css("display") != "none") {
		menu.css("display", "none");
	}
}

function showOptionsMenu(button) {
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);

	var scrollTop = $(window).scrollTop(); //Distance from top of window to top of document
	var elementOffset = $(button).offset().top; //Distance from top of document

	var top = elementOffset - scrollTop;
	var left = $(button).position().left;

	menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" });

}


function deletePlaylist(playlistId) {
	var prompt = confirm("Tem certeza que deseja apagar a lista de reproducao?");

	if(prompt == true) {

		$.post("includes/manipuladores/ajax/deletePlaylist.php", { playlistId: playlistId })
		.done(function(error) {

			if(error != "") {
				alert(error);
				return;
			}

			//do something when ajax returns
			openPage("suaMusica.php");
		});


	}
}

function formatTime(seconds) {
	var time = Math.round(seconds);
	var minutes = Math.floor(time / 60); //Rounds down
	var seconds = time - (minutes * 60);

	var extraZero = (seconds < 10) ? "0" : "";

	return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
	$(".progressTime.atual").text(formatTime(audio.currentTime));
	$(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

	var progress = audio.currentTime / audio.duration * 100;
	$(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");
}

// Funcao para fazer tocar a musica no menu dos artistas
function playFirtSong() {
	setTrack(temporaryPlaylist[0], temporaryPlaylist, true);
}

function Audio() {

	this.currentlyPlaying;
  this.audio = document.createElement('audio');
  
  this.audio.addEventListener("ended", function(){
      nextSong();
  });

	this.audio.addEventListener("canplay", function() {

		//'this' refers to the object that the event was called on
	var duration = formatTime(this.duration);
    $(".progressTime.remaining").text(duration);
    updateVolumeProgressBar(this);

	});

	this.audio.addEventListener("timeupdate", function(){
		if(this.duration) {
			updateTimeProgressBar(this);
		}
  });
  
   this.audio.addEventListener("volumechange", function(){
      updateVolumeProgressBar(this);
   });

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function() {
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
  }
  
  this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}
}