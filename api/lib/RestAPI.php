<?php
    abstract class RestAPI {
        /*
         *  HTTP Method used
        */
        protected $method = '';

        /*
         *  End Point
         *  The module required
        */
        protected $endpoint = '';

        /*
         *  Verb
         *  Action that follows an endpoint
        */
        protected $verb = '';

        /*
         *  Args
        */
        protected $args = array();

        /*
         *  File
         *  File for PUT methods
        */
        protected $file = '';

        /*
         *  Constructor
        */
        public function __construct($request) {
            header("Access-Control-Allow-Orgin: *");
            header("Access-Control-Allow-Methods: *");
            header("Content-Type: application/json");

            // Process args
            $this->args = explode('/', rtrim($request, '/'));
            $this->endpoint = array_shift($this->args);
            if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
                $this->verb = array_shift($this->args);
            }

            // Process HTTP Methods
            $this->method = $_SERVER['REQUEST_METHOD'];
            if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
                if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                    $this->method = 'DELETE';
                } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                    $this->method = 'PUT';
                } else {
                    throw new Exception("Unexpected Header");
                }
            }

            // Data is stored in $this->request property
            switch ($this->method) {
                case "DELETE" :
                    break;
                case "POST" :
                    // $_POST is always empty and I need to get the data this way
                    // Need to look further into it
                    // TODO
                    $rest_json = file_get_contents("php://input");
                    $_POST = json_decode($rest_json, true);
                    
                    $this->request = $this->_cleanInputs($_POST);
                    break;
                case "GET":
                    $this->request = $this->_cleanInputs($_GET);
                    break;
                case "PUT":
                    $this->request = $this->_cleanInputs($_GET);
                    $this->file = file_get_contents("php://input");
                    break;
                default:
                    print_r("default");
                    $this->_response('Invalid Method', 405);
                break;
            }
        }

        private function _cleanInputs($data) {
            $cleanInput = array();

            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $cleanInput[$key] = $value;
                }
            } else {
                $cleanInput = trim(strip_tags($data));
            }

            return $cleanInput;
        }

        public function processAPI() {
            // Check if we have a correctly parsed HTTP method
            if ((int)method_exists($this, $this->endpoint) > 0) {
                return $this->_response($this->{$this->endpoint}($this->args));
            }

            return $this->_response('No Endpoint: $this->endpoint, 404');
        }

        public function sendResponse($data, $content_type = 'text/html', $status = 200) {
            header('Content-type: ' . $contentType);
            if ($status === 200) {
                echo $data;
            }
        }

        private function _response($data, $status = 200) {
            header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
            return $data;
        }

        private function _requestStatus($code) {
            $status = array(  
                200 => 'OK',
                404 => 'Not Found',   
                405 => 'Method Not Allowed',
                500 => 'Internal Server Error',
            ); 
            return ($status[$code])?$status[$code]:$status[500];
        }
    }
?>