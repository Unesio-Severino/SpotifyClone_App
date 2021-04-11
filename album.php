<?php include("includes/includedFiles.php"); 

if(isset($_GET['id'])) {
	$albumId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$album = new Album($con, $albumId);
$artist = $album->getArtist();

?>

<div class="entidadeInfo">

        <div class="sectionEsquerda">
            <img src="<?php echo $album->getArtworkPath(); ?>">
        </div>

        <div class="sectionDireita">
            <h2><?php echo $album->getTitle(); ?></h2>
            <p role="link" tabindex="0" onclick="openPage('artist.php?id=$artistId')">De: <?php echo $artist->getName(); ?></p>
            <p><?php echo $album->getNumberOfSongs(); ?> musicas</p>
        </div>

</div>


<div class="listaMusicaContainer">
        <ul class="listaMusicas">
        
            <?php
                $songIdArray = $album->getSongIds();

                $i = 1;
                foreach($songIdArray as $songId) {

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

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>
