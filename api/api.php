<?php
	require_once('lib/RestAPI.php');
	class API extends RestAPI {
		
		public function __construct($request, $origin) {
			parent::__construct($request);
		}

		protected function example() {
			return 'Hello!';
		}
	}
?>