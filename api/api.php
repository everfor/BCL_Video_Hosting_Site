<?php
    require_once('lib/RestAPI.php');
    class API extends RestAPI {
        
        public function __construct($request, $origin) {
            parent::__construct($request);
        }

        // This method is used for testing purposes
        protected function example($args) {
            $result =  array(
                'name'  =>  $args[0],
                'value' =>  $args[1]
            );

            return $result['name'];
        }

        // User account regesitration
        protected function register($args) {
            if ($this->method == 'POST') {
                // Assuming $_POST contains keys 'username', 'password', 'email'
                require_once(dirname(__FILE__) . '/../lib/User/User.php');
                $registerAgent = new User($this->request['username'], $this->request['password'], $this->request['email']);
                
                // Check if the username/email already exists in database
                if ($registerAgent->exists()) {
                    return array(
                        'success' => false,
                        'message' => 'Existing username or email address!'
                    );
                }

                // Validation
                $validation = $registerAgent->validate();
                if ($validation['success'] === false) {
                    return $validation;
                }

                // If both username and email address are unique, create the user
                $userInfo = $registerAgent->register();
                return array(
                    'success'   => true,
                    'message'   => '',
                    'username'  => $userInfo['username']
                );
            }

            return array(
                'success' => false,
                'message' => 'An error occurred from the server'
            );
        }

        // User login
        protected function login($args) {
            if ($this->method == 'POST') {
                // $POST contains username and password
                require_once(dirname(__FILE__) . '/../lib/User/User.php');
                $user = new User($this->request['username'], $this->request['password']);

                // Assume no autologin for now
                return $user->login(false);
            }
        }

        // Searching function
        // Designen to realize the search of videos by both keywords and categories
        // And the searching of users
        protected function search($args) {
            if ($this->method == 'POST') {
                // Search for videos
                // Will also implement the searching for users
                if ($this->verb === 'video') {
                    require_once(dirname(__FILE__) . '/../lib/Video/Video.php');
                    $video = new Video();

                    // Determine if the search is by keyword or by category, or by id
                    if ($args[0] === 'keyword') {
                        // Return a json object
                        return json_encode($video->getVideosByKeywords($this->request['keyword']));
                    } else if ($args[0] === 'category') {
                        // TODO
                        // NOT TESTED
                        return json_encode($video->getVidesByCategory($this->request['category']));
                    } else if ($args[0] === 'id') {
                        // Fetch video by id
                        return json_encode($video->getVideoById($this->request['id']));
                    }
                }
            }
        }
    }
?>