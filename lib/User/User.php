<?php
    // With love. http://webdevrefinery.com/forums/topic/3280-how-to-make-a-user-account-system/
    // Assume we have 5 tables: users, autologin, groups, forced_group_ids, and banned_emails
    class User {
        protected $connection, $userObj, $username, $password, $salt, $email, $passHash, $validationMsg, $ipAddress, $dateTime;

        public function __construct($username = '', $password = '', $email = '') {
            require_once(dirname(__FILE__) . '/../General/Connection.php');
            $this->connection = new Connection();

            $this->username = $username;
            $this->password = $password;
            $this->email    = $email;
        }

        public function checkID($id) {
            $query = 'SELECT * FROM users WHERE id = :uid';
            $params = array( ':uid' => $id );

            $result = $this->connection->runUserQuery($query, $params);

            if (sizeof($result) <= 0) {
                return array(
                    'success' => false,
                    'message' => 'User id does not exist'
                );
            }

            return array(
                'success' => true,
                'user' => $result[0]
            );
        }

        // Check if the username or email already exists
        public function exists() {
            $query = 'SELECT * FROM users WHERE username = :username OR email LIKE :email';
            $params = array(
                ':username' => $this->username,
                ':email'    => $this->email
            );

            $result = $this->connection->runUserQuery($query, $params);

            // Store the result. Used for login
            $this->userObj = $result[0];

            return (sizeof($result) > 0);
        }

        // Validility check for registration
        public function validate() {

            if (strlen($this->username) < 5 || strlen($this->username) > 20) {
                $this->validationMsg = "Invalid length for username. Should be 5 ~ 20 characters in length";
                return false;
            }

            if (strlen($this->password) < 5 || strlen($this->password) > 20) {
                $this->validationMsg = "Invalid length for password. Should be 5 ~ 20 characters in length";
                return false;
            }

            // TODO: Validate email address

            return true;
        }

        // Create a new user account
        public function register() {
            // Check if the username already exists
            if ($this->exists()) {
                return array(
                    'success' => false,
                    'message' => 'Existing username or email address!'
                );
            }

            // Validate input information
            if (!$this->validate()) {
                return array(
                    'success' => false,
                    'message' => $this->validationMsg
                );
            }

            // Get a $salt from the username because I am lazy lolz
            $this->salt = substr(str_shuffle($this->username), 0, 5);
            $this->passHash = md5(md5($this->salt) . md5($this->password));
            $this->ipAddress = ip2long($_SERVER['REMOTE_ADDR']);
            $this->dateTime = date('Y-m-d H:i:s');

            // Create entry in the database
            $query = "INSERT INTO users (username, email, group_id, salt, passhash, perm_override_remove, perm_override_add, reg_date, reg_ip)
                        VALUES (:username, :email, 1, :salt, :passhash, 0, 0, :reg_date, :reg_ip)";
            $params = array(
                ':username' => $this->username,
                ':email'    => $this->email,
                ':salt'     => $this->salt,
                ':passhash' => $this->passHash,
                ':reg_date' => $this->dateTime,
                ':reg_ip'   => $this->ipAddress
            );

            $this->connection->runUserQuery($query, $params);

            return array(
                'success'   => true,
                'username'  => $this->username
            );
        }

        
        // Log in
        // Also checks if "remember me" is checked
        public function login($remember) {
            if (!$this->exists()) {
                return array(
                    'success'   =>  false,
                    'message'   =>  'The user does not exist'
                );
            }

            // Check password
            if ($this->userObj['passhash'] != md5(md5($this->userObj['salt']) . md5($this->password))) {
                return array(
                    'success'   =>  false,
                    'message'   =>  'Incorrect password'
                );
            }

            // Do not forget to start the session here!
            session_start();
            // Set a user id in session variable
            $_SESSION['UID'] = $this->userObj['id'];
            $_SESSION['UAGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

            // Update database with last logged in ip etc.
            $updateQuery = 'UPDATE users
                            SET last_login_date = :date_now, last_login_ip = :ip
                            WHERE id = :user_id';
            $updateArray = array(
                ':date_now' =>  date('Y-m-d H:i:s'),
                ':ip'       =>  ip2long($_SERVER['REMOTE_ADDR']),
                ':user_id'  =>  $this->userObj['id']
            );

            $this->connection->runUserQuery($updateQuery, $updateArray);

            // Check autologin
            if ($remember) {
                // Delete old cookie entry for the user
                $delCookieQuery = 'DELETE FROM autologin WHERE uid = :user_id';
                $delCookieArray = array( ':user_id' => $this->userObj['id'] );
                $this->connection->runUserQuery($delCookieQuery, $delCookieArray);

                $publicKey = md5($this->userObj['username'] . date('Y-m-d H:i:s'));
                $privateKey = md5($this->userObj['salt'] . $_SERVER['HTTP_USER_AGENT']);
                
                // Make the cookie avaible to entire domain
                // Note that setting cookies is tricky for localhost
                // For real domains
                // setcookie('public_key', $publicKey, time() + 3600 * 24 * 30, '/', $domainName);
                // For localhsot
                setcookie('public_key', $publicKey, time() + 3600 * 24 * 30, '/', false);

                // Create new cookie entry
                $createCookieQuery = 'INSERT INTO autologin (uid, public_key, private_key, created_on, last_used_on, last_used_ip)
                                        VALUES (:user_id, :public_key, :private_key, :date_now, NULL, NULL)';
                $createCookieArray = array(
                    ':user_id'      =>  $this->userObj['id'],
                    ':public_key'   =>  $publicKey,
                    ':private_key'  =>  $privateKey,
                    ':date_now'     =>  date('Y-m-d H:i:s')
                );
                $this->connection->runUserQuery($createCookieQuery, $createCookieArray);
            }

            return array(
                'success'   =>  true,
                'user'      =>  $this->userObj['username']
            );
        }

        // Log out. Remove all sessions and cookies
        public function logout() {
            session_start();

            if (isset($_SESSION['UID'])) {
                unset($_SESSION['UID']);
            }

            if (isset($_SESSION['UAGENT'])) {
                unset($_SESSION['UAGENT']);
            }

            if (isset($_COOKIE['public_key'])) {
                setcookie('public_key', null, -1, '/');
            }
        }

        // Use public key to detect if auto login is valid
        public function autoLogin() {
            if (isset($_COOKIE['public_key']) && strlen($_COOKIE['public_key']) >=32) {
                // Check auto login data entries in database
                $query = 'SELECT * FROM autologin WHERE public_key = :public_key';
                $params = array( ':public_key' => $_COOKIE['public_key'] );
                $auto = $this->connection->runUserQuery($query, $params);

                if (sizeof($auto) > 0) {
                    $auto = $auto[0];
                    $query = 'SELECT * FROM users WHERE id = :id';
                    $params = array( ':id' => $auto['uid'] );
                    $this->userObj = $this->connection->runUserQuery($query, $params);

                    if (sizeof($this->userObj) > 0) {
                        $this->userObj = $this->userObj[0];
                        // Check private key
                        if ($auto['private_key'] == md5($this->userObj['salt'] . $_SERVER['HTTP_USER_AGENT'])) {
                            // Log in!
                            session_start();
                            $_SESSION['UID'] = $this->userObj['id'];
                            $_SESSION['UAGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

                            // Update last login data
                            $query = 'UPDATE users
                                        SET last_login_date = :date_now, last_login_ip = :ip
                                        WHERE id = :user_id';
                            $params = array(
                                ':date_now' =>  date('Y-m-d H:i:s'),
                                ':ip'       =>  ip2long($_SERVER['REMOTE_ADDR']),
                                ':user_id'  =>  $this->userObj['id']
                            );
                            $this->connection->runUserQuery($query, $params);

                            $query = 'UPDATE autologin
                                        SET last_used_on = :date_now, last_used_ip = :ip
                                        WHERE uid = :user_id';
                            $this->connection->runUserQuery($query, $params);

                            return array(
                                'success'   =>  true
                            );
                        }
                    }
                }
            }

            return array(
                'success'   =>  false,
                'message'   =>  'No cookie detected'
            );
        }

    }
?>