<?php 
include("includes/includedFiles.php");

if(isset($_GET['id'])) {
	$artistId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$artist = new Artist($con, $artistId);

?>


<div class="entityInfo borderBottom">

    <div class="centroSection">

        <div class="artistInfo">

            <h1 class="artistNome"><?php echo $artist->getName(); ?></h1>
        
            <div class="CabecalhoButtons">
                <button class="button green" onclick="playFirtSong()">TOCAR</button>
            </div>
        </div>
    
    </div>

</div>

<div class="listaMusicaContainer borderBottom">
        <h2>MUSICAS</h2> 
        <ul class="listaMusicas">
        
            <?php
                $songIdArray = $artist->getSongIds();

                $i = 1;
                foreach($songIdArray as $songId) {

                    if ($i > 5) {
                        break;
                    }

                    $albumSong = new Song($con, $songId);
                    $albumArtist = $albumSong->getArtist();

                        echo "<li class='musicalistRow'>
                                <div class='musicaCount'>
                                    <img class='play' src='assets/imagens/icones/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", temporaryPlaylist, true)'>
                                    <span class='musicaNumero'>$i</span>
                                </div>

                                <div class='musicaInfo'>
                                    <span class='musicaNome'>" . $albumSong->getTitle() . "</span>
                                    <span class='artistaNome'>" . $albumArtist->getName() . "</span>
                                </div>

                                <div class='musicaOptions'>
                                    <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                                    <img class='optionsButton' src='assets/imagens/icones/more.png' onclick='showOptionsMenu(this)'>
                                </div>

                                <div class='musicaDuration'>
                                    <span class='duration'>" . $albumSong->getDuration() . "</span>
                                </div>

                              </li>";

                $i = $i + 1;
                        }

            ?>
            
        <script>

            //Funcao para fazer Reproduxao das musicas diretamente da lista de reproduxao
			var temporarySongIds = '<?php echo json_encode($songIdArray); ?>';
			temporaryPlaylist = JSON.parse(temporarySongIds);
			
		</script>

        </ul>
</div>

<div class="gridViewContainer">
         <h2>ALBUMS</h2>               
        <?php
            $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");
                 
                while($row = mysqli_fetch_array($albumQuery)) {
                     

              echo "<div class='gridViewItem'>
                        <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                           <img src='" . $row['artworkPath'] . "'>

                           <div class='gridViewInfo'>"
                            . $row['title'] .
                           "</div>
                        </span>
                        
                    </div>";
            }
        ?>
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>