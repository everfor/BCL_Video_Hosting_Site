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
                'result'    =>  $result[0],
                'recommendations' => $this->getSimilarVideos($id)
            );
        }

        public function getSimilarVideos($id) {
            $ids = $this->getSimilarVideoIds($id);

            $query = "";
            $params = array();
            foreach ($ids as $index => $id) {
                if ($index === 0) {
                    $placeholder = ":" . md5($id);
                    $query = $query . "SELECT * FROM videos WHERE id = {$placeholder}";
                    $params[$placeholder] = $id;
                } else {
                    $placeholder = ":" . md5($id);
                    $query = $query . " OR id = {$placeholder}";
                    $params[$placeholder] = $id;
                }
            }

            $videos = $this->connection->runVideoQuery($query, $params);

            // Get the thumbnail of all results
            foreach ($videos as $index => $item) {
                $vimeo_response = json_decode(file_get_contents("http://vimeo.com/api/v2/video/" . $item['vimeo_id'] . ".json"));
                $videos[$index]['thumbnail'] = $vimeo_response[0]->thumbnail_medium;
                unset($videos[$index]['vimeo_id']);
            }

            return $videos;
        }

        public function getSimilarVideoIds($id) {
            $categories = $this->getCategories($id);

            if ($categories == null) {
                return null;
            }

            $query = "";
            $params = array();
            foreach ($categories as $index => $cid) {
                if ($index === 0) {
                    $placeholder = ":" . md5($cid);
                    $query = $query . "SELECT DISTINCT vid FROM video_category_map WHERE (cid = {$placeholder}";
                    $params[$placeholder] = $cid;
                } else {
                    $placeholder = ":" . md5($cid);
                    $query = $query . " OR cid = {$placeholder}";
                    $params[$placeholder] = $cid;
                }
            }

            // Limit 5 similar videos only for now
            $query = $query . ") AND vid <> :self LIMIT 5";
            $params[':self'] = $id;

            $similarVideoIds = $this->connection->runVideoQuery($query, $params);

            // Parse the array
            foreach ($similarVideoIds as $index => $vid) {
                $similarVideoIds[$index] = $vid['vid'];
            }

            return $similarVideoIds;
        }

        public function getCategories($id) {
            $query = "SELECT cid FROM video_category_map WHERE vid = :id";
            $params = array( ":id" => $id );

            $result = $this->connection->runVideoQuery($query, $params);

            if (sizeof($result) <= 0) {
                return null;
            }

            // Parse cid array
            foreach ($result as $index => $cid) {
                $result[$index] = $cid['cid'];
            }

            return $result;
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
