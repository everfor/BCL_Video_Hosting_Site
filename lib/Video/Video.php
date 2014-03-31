<?php
    Class Video {
        // For video databases, assume the tables are
        // videos, categories
        function __construct() {
        }
        
        function getAll() {
            // TODO: fetch all videos
        }

        function getVidesByCategory($category) {
            require_once('/lib/Search/Search.php');
            $search = new Search();
            return $search->searchVideoByCategory($category);
        }

        function getVideosByKeywords($keywords) {
            require_once('/lib/Search/Search.php');
            $search = new Search();
            return $search->searchVideoByKeywords($keywords);
        }
    }
?>