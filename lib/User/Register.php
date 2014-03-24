<?php
	// With love. http://webdevrefinery.com/forums/topic/3280-how-to-make-a-user-account-system/
	// Assume we have 5 tables: users, autologin, groups, forced_group_ids, and banned_emails
	class Register {
		protected $connection, $username, $password, $salt, $email, $passHash, $ipAddress, $validation, $dateTime;

		function __construct($username, $password, $email) {
			require_once('../General/Connection.php');
			$this->connection = new Connection();

			$this->username = $username;
			$this->password = $password;
			$this->email = $email;
		}

		// Check if the username or email already exists
		public function exists() {
			$query = 'SELECT * FROM users WHERE username = :username OR email=:email';
			$params = array(
				':username'	=> $this->username,
				':email'	=> $this->email
			);

			$result = $this->connection->runUserQuery($query, $params);

			return (sizeof($result) > 0);
		}

		// Validility check
		public function validate() {
			$this->validation = true;

			if (strlen($this->username) < 5 || strlen($this->username) > 20) {
				$this->validation = false;
				return array(
					'success' => false,
					'message' => 'Invalid length for username. Should be 5 ~ 20 characters in length'
				);
			}

			if (strlen($this->password) < 5 || strlen($this->password) > 20) {
				$this->validation = false;
				return array(
					'success' => false,
					'message' => 'Invalid length for password. Should be 5 ~ 20 characters in length'
				);
			}

			// TODO: Validate email address

			return array(
				'success' => true
			);
		}

		// Create a new user account
		public function register() {
			// Get a $salt from the username because I am lazy lolz
			$this->salt = substr(str_shuffle($this->username), 0, 5);
			$this->passHash = md5(md5($this->salt) . md5($this->password));
			$this->ipAddress = ip2long($_SERVER['REMOTE_ADDR']);
			$this->dateTime = date('m/d/Y h:i:s a', time());

			// Create entry in the database
			$query = 'INSERT INTO users (username, email, group_id, salt, passhash, perm_override_remove, perm_override_add, reg_date, reg_ip)
						VALUES (:username, :email, 1, :salt, :passhash, 0, 0, :reg_date, :reg_ip)';
			$params = array(
				':username'	=> $this->username,
				':email'	=> $this->email,
				':salt'		=> $this->salt,
				':passhash'	=> $this->passHash,
				':reg_date'	=> $this->dateTime,
				':reg_ip'	=> $this->ipAddress
			);

			$this->connection->runUserQuery($query, $params);

			return array(
				'username'	=> $this->username;
			);
		}
	}
?>