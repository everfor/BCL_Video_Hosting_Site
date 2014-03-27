<?php
	Class Search {
		$connection;

		function __construct() {
			require_once('/lib/General/Connection.php');
			$this->connection = new Connection();
		}

		function searchVideoByKeywords($sentence) {
			// Split the query sentence into different keywords
			$keywords = explode(" ", $sentence);
			$query = 'SELECT * FROM videos WHERE title LIKE %:key0%';
			$params = array( ':key1' => $keywords[0] );

			$keywords = array_slice($keywords, 1);

			// Add all keywords search using union
			foreach ($keywords as $index => $keyword) {
				$key = ':key' . strval($index);
				$query = $query . ' UNION
									SELECT id, vimeo_id FROM videos WHERE title LIKE %{$key}%';
				$params[$key] = $keyWord;
			}

			$result = $this->connection->runVideoQuery($query, $params);

			if (sizeof($result) <= 0) {
				return {
					'success'	=>	false,
					'message'	=>	'No result found'
				};
			}

			return {
				'success'	=>	true,
				'result'	=>	$result
			};
		}

		function searchVideoByCategory($category) {
			// Get all video ids which belong to the requested category
			$query = 'SELECT video_id FROM categories WHERE category = :category';
			$params = array( ':category' => $category );
			$ids = $this->connection->runVideoQuery($query, $params);

			$key = ':id0';
			$query = 'SELECT * FROM videos WHERE id = {$key}';

			if (sizeof($ids) <= 0) {
				return array(
					'success'	=>	false,
					'message'	=>	'No videos under such category found'
				);
			}

			$params = array( $key => $ids[0]['video_id']);

			$ids = array_slice($ids, 1);
			foreach ($ids as $index => $id) {
				$key = ':id' . strval($index);
				$query = $query . ' UNION
									SELECT * FROM videos WHERE id = {$key}';
				$params[$key] = $id;
			}

			$result = $this->connection->runVideoQuery($query, $params);

			return array(
				'success'	=>	true,
				'result'	=> $result
			);
		}
	}
?>
