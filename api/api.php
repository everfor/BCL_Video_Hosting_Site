<?php
	require_once('lib/RestAPI.php');
	class API extends RestAPI {
		
		public function __construct($request, $origin) {
			parent::__construct($request);
		}

		// This method is used for testing purposes
		protected function example($args) {
			return array(
				'name'	=>	$args[0],
				'value'	=>	$args[1]
			);
		}

		protected function register($args) {
			if ($this->method === 'POST') {
				// Assuming $_POST contains keys 'username', 'password', 'email'
				require_once('../lib/General/User/User.php');
				$registerAgent = new User($this->request['username'], $this->request['password'], $this->request['email']);
				
				// Check if the username/email already exists in database
				if ($registerAgent->exists()) {
					return array(
						'success' => false,
						'message' => 'Existing username or email address!'
					);
				}

				// Validation
				$validation = $register->validate();
				if ($validation['success'] === false) {
					return $validation;
				}

				// If both username and email address are unique, create the user
				$userInfo = $registerAgent->register();
				return array(
					'success'	=> true,
					'message'	=> '',
					'username'	=> $userInfo['username']
				);
			}

			return array(
				'success' => false,
				'message' => 'An error occurred from the server'
			);
		}

		protected function login($args) {

		}
	}
?>