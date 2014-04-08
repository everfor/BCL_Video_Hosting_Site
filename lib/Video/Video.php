<?php
    Class Video {
        // For video databases, assume the tables are
        // videos, categories
        public function __construct() {
        }
        
        public function getAll() {
            // TODO: fetch all videos
        }

        public function getVidesByCategory($category) {
            require_once(dirname(__FILE__)  . '/../Search/Search.php');
            $search = new Search();
            return $search->searchVideoByCategory($category);
        }

        public function getVideosByKeywords($keywords) {
            require_once(dirname(__FILE__) . '/../Search/Search.php');
            $search = new Search();
            return $search->searchVideoByKeywords($keywords);
        }
    }
?>