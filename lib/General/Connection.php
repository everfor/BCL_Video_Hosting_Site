<?php
    // Holds all connection methods to database
    class Connection {
        protected $host, $db_user, $db_pass, $video_db, $user_db, $videoConnection, $userConnection;

        function __construct() {
            require_once(dirname(__FILE__) . '/./Configurations.php');
            
            $this->host = Configurations::$host;
            $this->db_user = Configurations::$db_user;
            $this->db_pass = Configurations::$db_pass;
            $this->video_db = Configurations::$video_db;
            $this->user_db = Configurations::$user_db;
            $this->videoConnection = new PDO("mysql:host={$this->host};dbname={$this->video_db}", $this->db_user, $this->db_pass);
            $this->userConnection = new PDO("mysql:host={$this->host};dbname={$this->user_db}", $this->db_user, $this->db_pass);

            // http://stackoverflow.com/a/10438026
            // Because MySql shuts down query preparation by default
            // MySQL: Screw you! lol
            $this->videoConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->userConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        
        public function runVideoQuery($query, $array = null) {
            $prep = $this->videoConnection->prepare($query);
            
            // TODO here
            if ($array != null) {
                $prep->execute($array);
            } else {
                $prep->execute();
            }

            // Indices are column names
            $result = $prep->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

        public function runUserQuery($query, $array = null) {
            $prep = $this->userConnection->prepare($query);
            if ($array != null) {
                $prep->execute($array);
            } else {
                $prep->execute();
            }

            $result = $prep->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
    }
?>