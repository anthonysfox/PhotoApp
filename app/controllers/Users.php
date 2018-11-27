<?php 

    class Users extends Controller {
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        // function below is to register a user
        public function register(){
            // Check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form
                
                // Sanitize post data 
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data 
                $data = [
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'username_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                // Validate email
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                } else {
                    // Check email 
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already being used';
                    }
                }

                // Validate username
                if(empty($data['username'])){
                    $data['username_err'] = 'Please enter username';
                } else {
                    // Check username
                    if($this->userModel->findUserByUsername($data['username'])){
                        $data['username_err'] = 'Username is already taken';
                    }
                }

                // Validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                } elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }

                // Validate confirm password
                if(empty($data['confirm_password'])){
                    $data['confirm_password_err'] = 'Please confirm password';
                } else {
                    if($data['password'] != $data['confirm_password']){
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }

                // Make sure errors are empty 
                if(empty($data['email_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                    // Validated 
                    
                    // Hash password which encripts it
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // Register User by sending info in data array to register in 
                    // user model 
                    if($this->userModel->register($data)){
                        flash('register_success', 'You are registered and can log in');
                        redirect('users/login');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // Load view with errors
                    $this->view('users/register', $data);
                }
            } else {
                // init data 
                $data = [
                    'username' => '',
                    'email' => '',
                    'password' => '',
                    'confirm password' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                // Load view 
                $this->view('users/register', $data);
            }
        }

        // function below is to login the user 
        public function login(){
            // Check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form
                // Sanitize post data 
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data 
                $data = [
                    'username' => trim($_POST['username']),
                    'password' => trim($_POST['password']),
                    'username_err' => '',
                    'password_err' => '',
                ];

                // Validate username
                if(empty($data['username'])){
                    $data['username_err'] = 'Please enter username';
                }  
                
                // Validate password
                if(empty($data['password'])){
                    $data['password_err'] = 'Please enter password';
                }

                // Check for user/username
                if($this->userModel->findUserByUsername($data['username'])){
                    // user found
                } else {
                    $data['username_err'] = 'No username found';
                }

                // Make sure errors are empty
                if(empty($data['username_err']) && empty($data['password_err'])){
                    // Validated 
                    // Check and set logged in user
                    $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                    if($loggedInUser){
                        // Create Session
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['password_err'] = 'Password incorrect';
                        $this->view('users/login', $data);
                    }
                } else {
                    // Load view with errors
                    $this->view('users/login', $data);
                }
                

            } else {
                // init data 
                $data = [
                    
                    'username' => '',
                    'password' => '',
                    'email_err' => '',
                    'username_err' => '',
                ];

                // Load view 
                $this->view('users/login', $data);
            }
        }

        // creating session for the user 
        public function createUserSession($user) {
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['username'] = $user->username;
            redirect('pages/index');
        }

        // logging out the user 
        public function logout() {
            unset($_SESSION['used_id']);
            unset($_SESSION['email']);
            unset($_SESSION['username']);
            session_destroy();
            redirect('users/login');
        }
    }

?>