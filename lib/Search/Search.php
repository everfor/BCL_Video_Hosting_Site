<?php

    Class Search {
        protected $connection;

        public function __construct() {
            require_once(dirname(__FILE__) . '/../General/Connection.php');
            $this->connection = new Connection();
        }

        public function searchVideoByKeywords($sentence) {
            session_start();
            // Check if the user is logged in
            // If not, do not do the search and return an error
            if (!isset($_SESSION['UID'])) {
                return array(
                    'success' => false,
                    'message' => 'Content only available for logged in users'
                );
            }

            // Split the query sentence into different keywords
            $keywords = explode(" ", $sentence);
            $query = "";
            $params = array();

            // Add all keywords search
            foreach ($keywords as $index => $keyword) {
                $placeholder = ":" . md5($keyword);
                if ($index === 0) {
                    $query = $query . "SELECT * FROM videos WHERE title LIKE {$placeholder}";
                } else {
                    if (!array_key_exists($placeholder, $params)) {
                        $query = $query . " AND title LIKE {$placeholder}";
                    }
                }

                $params[$placeholder] = "%" . $keyword . "%";
            }
            
            $result = $this->connection->runVideoQuery($query, $params);

            if (sizeof($result) <= 0) {
                return array(
                    'success'   =>  false,
                    'message'   =>  'No result found'
                );
            }

            // Get the thumbnail of all results
            foreach ($result as $index => $item) {
                $vimeo_response = json_decode(file_get_contents("http://vimeo.com/api/v2/video/" . $item['vimeo_id'] . ".json"));
                $result[$index]['thumbnail'] = $vimeo_response[0]->thumbnail_small;
                unset($result[$index]['vimeo_id']);
            }

            return array(
                'success'   =>  true,
                'result'    =>  $result
            );
        }

        public function searchVideoById($id) {
            session_start();
            // Check if the user is logged in
            // If not, do not do the search and return an error
            if (!isset($_SESSION['UID'])) {
                return array(
                    'success' => false,
                    'message' => 'Content only available for logged in users'
                );
            }
            
            $query = "SELECT * FROM videos WHERE id = {$id}";

            $result = $this->connection->runVideoQuery($query);

            if (sizeof($result) <= 0) {
                return array(
                    'success'   =>  false,
                    'message'   =>  'No result found'
                );
            }

            return array(
                'success'   =>  true,
                'result'    =>  $result
            );
        }

        /*
        * NOT TESTED
        function searchVideoByCategory($category) {
            // Get all video ids which belong to the requested category
            $query = 'SELECT video_id FROM categories WHERE category = :category';
            $params = array( ':category' => $category );
            $ids = $this->connection->runVideoQuery($query, $params);

            $key = ':id0';
            $query = 'SELECT * FROM videos WHERE id = {$key}';

            if (sizeof($ids) <= 0) {
                return array(
                    'success'   =>  false,
                    'message'   =>  'No videos under such category found'
                );
            }

            $params = array( $key => $ids[0]['video_id']);

            $ids = array_slice($ids, 1);
            foreach ($ids as $index => $id) {
                $key = ':id' . strval($index + 1);
                $query = $query . ' OR id = {$key}';
                $params[$key] = $id;
            }

            $result = $this->connection->runVideoQuery($query, $params);

            return array(
                'success'   =>  true,
                'result'    => $result
            );
        }
        */
    }
?>
