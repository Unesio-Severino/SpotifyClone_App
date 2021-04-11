<div id="navBarContainer">
   <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')"  class="logotipo">
            <img src="assets/imagens/icones/logotipo.png">
        </span>

            <div class="group">
                    <div class="navItem">
                        <span role='link' tabindex='0' onclick='openPage("procurar.php")' class="navItemLink"> 
                             Procurar
                            <img src="assets/imagens/icones/search.png" class="icon" alt="procurar">
                        </span>
                    </div>
            </div>               

            <div class="group">
                    <div class="navItem">
                    <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Navegue</span>
                    </div>

                    <div class="navItem">
                    <span role="link" tabindex="0" onclick="openPage('suaMusica.php')" class="navItemLink">Sua Musica</span>
                    </div>

                    <div class="navItem">
                    <span role="link" tabindex="0" onclick="openPage('definicoes.php')" class="navItemLink"><?php echo $userLoggedIn->getFirstAndLastName(); ?></span>
                    </div>
            </div>  

    </nav>
</div>