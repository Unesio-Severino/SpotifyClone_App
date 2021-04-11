<?php
	class Artist {

		private $con;
        private $id;

		public function __construct($con, $id) {
			$this->con = $con;
			$this->id = $id;
        }

		public function getId() {
			return $this->id;
		}

        public function getName() {
            $artistaQuery = mysqli_query($this->con, "SELECT name FROM artists WHERE id='$this->id'");
           
            $artista = mysqli_fetch_array($artistaQuery);

            return $artista['name'];
        }

        public function getSongIds() {

			$query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays ASC");

			$array = array();

			while($row = mysqli_fetch_array($query)) {
				array_push($array, $row['id']);
			}

			return $array;

		}
    }
?>