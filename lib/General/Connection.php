<?php
	// Holds all connection methods to database
	class Connection {
		protected $host, $db_user, $db_pass, $video_db, $user_db, $videoConnection, $userConnection;

		function __construct() {
			require_once('/lib/General/Configurations.php');

			$this->host = Configurations::$host;
			$this->db_user = Configurations::$db_user;
			$this->db_pass = Configurations::$db_pass;
			$this->video_db = Configurations::$video_db;
			$this->user_db = Configurations::$user_db;
			$this->videoConnection = new PDO('mysql:host={$this->host};dbname={$this->video_db}', $this->db_user, $this->db_pass);
			$this->userConnection = new PDO('mysql:host={$this->host};dbname={$this->video_db}', $this->db_user, $this->db_pass);
		}

		public function runVideoQuery($query, $array = null) {
			$prep = $this->videoConnection->prepare($query);
			if ($array != null) {
				$prep->execute($array);
			} else {
				$prep->execute();
			}

			$result = $prep->fecthAll(PDO::FETCH_ASSOC);

			return $result;
		}

		public function runUserQuery($query, $array = null) {
			$prep = $this->userConnection->prepare($query);
			if ($array != null) {
				$prep->execute($array);
			} else {
				$prep->execute();
			}

			$result = $prep->fecthAll(PDO::FETCH_ASSOC);

			return $result;
		}
	}
?>