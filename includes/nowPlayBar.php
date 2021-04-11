<?php
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();

while($row = mysqli_fetch_array($songQuery)) {
	array_push($resultArray, $row['id']);
}
$jsonArray = json_encode($resultArray);
?>

<script>

//Funcao e Script para mover o progresso das musicas com clicks ou arrastar

$(document).ready(function() {
	var newPlaylist = <?php echo $jsonArray; ?>;
	audioElement = new Audio();
    setTrack(newPlaylist[0], newPlaylist, false);
    updateVolumeProgressBar(audioElement.audio);

    $("#nowPlayBarContainer").on("mousedown touchstart mouse touchmove", function(e) {
        e.preventDefault();
    });
        
    $(".playbackBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".playbackBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
            timeFromOffset(e, this);
        }
    });

    $(".playbackBar .progressBar").mouseup(function(e) {
        timeFromOffset(e, this);
    });


    $(".volumeBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".volumeBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
        
        var percentage = e.offsetX / $(this).width();

             if(percentage >= 0 && percentage <= 1) {
             audioElement.audio.volume = percentage;
        }
    }
            
    });

    $(".volumeBar .progressBar").mouseup(function(e) {
        var percentage = e.offsetX / $(this).width();
            
            if(percentage >= 0 && percentage <= 1) {
             audioElement.audio.volume = percentage;
        }
    });

    $(document).mouseup(function() {
        mouseDown = false;
    });  

    });

//Funcao que faz calculo das direccoes dos clicks na barra de progresso do play

function timeFromOffset(mouse, progressBar) {
	var percentage = mouse.offsetX / $(progressBar).width() * 100;
	var seconds = audioElement.audio.duration * (percentage / 100);
	audioElement.setTime(seconds);
}

function previewSong() {
    if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
        audioElement.setTime(0); 
    }
    else {
        currentIndex = currentIndex - 1;
        setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }
}

function nextSong() {
    if(repeat == true) {
        audioElement.setTime(0);
        playSong();
        return;
    }

    if(currentIndex == currentPlaylist.length - 1) {
        currentIndex = 0;
    }
    else {
        currentIndex++;
    }

    var trackToPlay = aleatorio ? aleatorioPlaylist[currentIndex] : currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true);
}

function setRepeat() {
	repeat = !repeat;
	var imageName = repeat ? "repeat-active.png" : "repeat.png";
	$(".controlButton.repetir img").attr("src", "assets/imagens/icones/" + imageName);
}

function setMute() {
    audioElement.audio.muted = !audioElement.audio.muted;
	var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
	$(".controlButton.volume img").attr("src", "assets/imagens/icones/" + imageName);
}

function setAleatorio() {
    aleatorio = !aleatorio;
	var imageName = aleatorio ? "shuffle-active.png" : "shuffle.png";
    $(".controlButton.aleatorio img").attr("src", "assets/imagens/icones/" + imageName);
    
    if(aleatorio == true) {
		//Randomize playlist
		aleatorioArray(aleatorioPlaylist);
		currentIndex = aleatorioPlaylist.indexOf(audioElement.currentlyPlaying.id);
	}
	else {
		//aleatorio foi reativado
		//Voltar para regular playlist
		currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
	}
}

function aleatorioArray(a) {
    var j, x, i;
    for (i = a.length; i; i--) {
        j = Math.floor(Math.random() * i);
        x = a[i - 1];
        a[i - 1] = a[j];
        a[j] = x;
    }
}


//funcao que faz a adicao das musicas no canto inferior esquerdo do nowplayingBar//

function setTrack(trackId, newPlaylist, play) {
    
    if(newPlaylist != currentPlaylist) {
		currentPlaylist = newPlaylist;
		aleatorioPlaylist = currentPlaylist.slice();
		aleatorioArray(aleatorioPlaylist);
	}

	if(aleatorio == true) {
		currentIndex = aleatorioPlaylist.indexOf(trackId);
	}
	else {
		currentIndex = currentPlaylist.indexOf(trackId);
	}
	pauseSong();

	$.post("includes/manipuladores/ajax/getSongJson.php", { songId: trackId }, function(data) {

		var track = JSON.parse(data);
		$(".trackName span").text(track.title);

		$.post("includes/manipuladores/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
			var artist = JSON.parse(data);
            $(".trackInfo .artistName span").text(artist.name);
            $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
		});

		$.post("includes/manipuladores/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
			var album = JSON.parse(data);
            $(".content .albumLink img").attr("src", album.artworkPath);
            $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            $(".content .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
		});

		audioElement.setTrack(track);
            if(play == true) {
                playSong();
	    }
	   });    
}

//Funcao para o evento de Click nos botoes play e pause
function playSong() {

	if(audioElement.audio.currentTime == 0) {
	$.post("includes/manipuladores/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
	}

	$(".controlButton.play").hide();
	$(".controlButton.pausa").show();
	audioElement.play();
}

function pauseSong() {
	$(".controlButton.play").show();
	$(".controlButton.pausa").hide();
	audioElement.pause();
}

</script>


<div id="nowPlayBarContainer">

             <div id="nowPlayBar">

                <div id="nowPlayEsquerdo">
                    <div class="content">
                    <span class="albumLink">
                        <img role="link" tabindex="0" src="" class="albumArtwork">
                    </span>

                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0"></span>
                    </span>

                    <span class="artistName">
                        <span role="link" tabindex="0"></span>
                    </span>
                </div>
                </div> 
                </div>


                <div id="nowPlayCentro">
                <div class="content playerControls">

                <div class="buttons">
                <button class="controlButton aleatorio" title="Aleatorio" onclick="setAleatorio()">
                <img src="assets/imagens/icones/shuffle.png" alt="Aleatorio">
                </button>

                <button class="controlButton anterior" title="Anterior" onclick="previewSong()">
                <img src="assets/imagens/icones/previous.png" alt="Anterior">
                </button>

                <button class="controlButton play" title="Play" onclick="playSong()">
                <img src="assets/imagens/icones/play.png" alt="Play">
                </button>

                <button class="controlButton pausa" title="Pausa" style="display: none;" onclick="pauseSong()">
                <img src="assets/imagens/icones/pause.png" alt="Pausa">
                </button>

                <button class="controlButton proximo" title="Proximo" onclick="nextSong()">
                <img src="assets/imagens/icones/next.png" alt="Proximo">
                </button>

                <button class="controlButton repetir" title="Repetir" onclick="setRepeat()">
                <img src="assets/imagens/icones/repeat.png" alt="Repetir">
                </button>
                </div>

                <div class="playbackBar">

                <span class="progressTime atual">0.00</span>

                <div class="progressBar">
                <div class="progressBarBackgroung">
                    <div class="progress"></div>
                </div>
                </div>
                <span class="progressTime remaining">0.00</span>
                </div>
                </div>
                </div>


                <div id="nowPlayDireito">
                <div class="volumeBar">
                <button class="controlButton volume" title="Volume" onclick="setMute()">
                <img src="assets/imagens/icones/volume.png" alt="Volume">
                </button>

                <div class="progressBar">
                <div class="progressBarBackgroung">
                <div class="progress">
                </div>
                </div>
                </div>
                </div>   
                </div>
        </div>
</div>