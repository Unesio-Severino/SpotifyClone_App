<?php 
include("includes/includedFiles.php"); 

if(isset($_GET['id'])) {
	$playlistId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());
?>

<div class="entidadeInfo">

	<div class="sectionEsquerda">
		<div class="playlistImage">
			<img src="assets/imagens/icones/playlist.png">
		</div>
	</div>

	<div class="sectionDireita">
		<h2><?php echo $playlist->getName(); ?></h2>
		<p>By <?php echo $playlist->getOwner(); ?></p>
		<p><?php echo $playlist->getNumberOfSongs(); ?> songs</p>
		<button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">DELETE PLAYLIST</button>

	</div>

</div>


<div class="musicalistaContainer">
	<ul class="listaMusicas">
		
		<?php
		$songIdArray = $playlist->getSongIds();

		$i = 1;
		foreach($songIdArray as $songId) {

			$playlistSong = new Song($con, $songId);
			$songArtist = $playlistSong->getArtist();

			echo "<li class='musicalistRow'>
					<div class='musicaCount'>
						<img class='play' src='assets/imagens/icones/play-white.png' onclick='setTrack(\"" . $playlistSong->getId() . "\", temporaryPlaylist, true)'>
						<span class='musicaNumero'>$i</span>
					</div>


					<div class='musicaInfo'>
						<span class='musicaName'>" . $playlistSong->getTitle() . "</span>
						<span class='artistaName'>" . $songArtist->getName() . "</span>
					</div>

					<div class='musicaOptions'>
                        <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                        <img class='optionsButton' src='assets/imagens/icones/more.png' onclick='showOptionsMenu(this)'>
                    </div>

					<div class='musicaDuration'>
						<span class='duration'>" . $playlistSong->getDuration() . "</span>
					</div>


				</li>";

			$i = $i + 1;
		}

		?>

		<script>
			var temporarySongIds = '<?php echo json_encode($songIdArray); ?>';
			temporaryPlaylist = JSON.parse(temporarySongIds);
		</script>

	</ul>
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>

    <div class="item" onclick="removerFromPlaylist(this, '<?php echo $playlistId; ?>')">Remover da lista</div>
</nav>