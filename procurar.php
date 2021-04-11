 <?php
include("includes/includedFiles.php");

if(isset($_GET['term'])) {
    $term = urldecode($_GET['term']);
}
else {
    $term = "";
}
?> 

<div class="procurarContainer">
    <h3>Procure por qualquer artista, album ou musica</h3>
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Procurar..." onfocus="this.value = this.value">
</div>


<script>
//Funcao para o tempo de espera quando fazes Search 
$(".searchInput").focus();

$(function() {
    
    $(".searchInput").keyup(function(){
        clearTimeout(timer);

        timer = setTimeout(function(){
           var val = $(".searchInput").val();
           openPage("procurar.php?term=" + val);
        }, 2000);
    })

})

</script>


<?php 

if($term == "") exit(); 
//Funcao para limpar a tela das musicas quando nao estivermos a pesquisar
?>


<div class="listaMusicaContainer borderBottom">
        <h2>MUSICAS</h2> 
        <ul class="listaMusicas">
        
            <?php
                $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");
                
                if(mysqli_num_rows($songsQuery) == 0) {
                    echo "<span class='noResults'>Nenhum resultado encontrado " . $term . "</span>";
                }

                $songIdArray = array();

                $i = 1;
                while($row = mysqli_fetch_array($songsQuery)) {

                    if($i > 15) {
                        break;
                    }

                    array_push($songIdArray, $row['id']);

                    $albumSong = new Song($con, $row['id']);
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

<div class="artistaContainer borderBottom">

    <h2>ARTISTAS</h2>
       <?php 
         $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");
                
         if(mysqli_num_rows($artistsQuery) == 0) {
             echo "<span class='noResults'>Nenhum artista encontrado " . $term . "</span>";
         }
         while($row = mysqli_fetch_array($artistsQuery)) {
             $artistFound = new Artist($con, $row['id']);

             echo "<div class='procurarResultsRow'>
                    <div class='artistName'>
                    
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" .$artistFound->getId() ."\")'>
                        "
                        . $artistFound->getName() .
                        "
                        </span>

                    </div>

                    </div>";
         }
       
       ?>                 

</div>

<div class="gridViewContainer">
	<h2>ALBUMS</h2>
	<?php
		$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

		if(mysqli_num_rows($albumQuery) == 0) {
			echo "<span class='noResults'>No albums found matching " . $term . "</span>";
		}

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