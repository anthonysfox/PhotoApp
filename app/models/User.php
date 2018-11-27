<?php 

    class User {
        private $db;

        public function __construct() {
            $this->db = new Database;
        }

        // Register user
        public function register($data) {
            $this->db->query('INSERT INTO users (username, email, password) VALUES(:username, :email, :password)');

            // Bind the values 
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);

            // Execute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        // login in user 
        public function login($username, $password){
            $this->db->query('SELECT * FROM users WHERE username = :username');
            $this->db->bind(':username', $username);

            $row = $this->db->single();

            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            } else {
                return false;
            }
        }

        // Find user by username to see if it exists already 
        public function findUserByUsername($username) {
            $this->db->query('SELECT * FROM users WHERE username = :username');

            // Bind value
            $this->db->bind(':username', $username);

            $row = $this->db->single();

            // Check row
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        // Find user by email to see if it exists
        public function findUserByEmail($email){
            $this->db->query('SELECT * FROM users WHERE email = :email');

            // Bind value
            $this->db->bind(':email', $email);

            $row = $this->db->single();

            // Check row
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        // finds user with the user_id being passed in 
        public function getUserById($user_id) {
            $this->db->query('SELECT * FROM users WHERE user_id = :user_id');

            // Bind value
            $this->db->bind(':user_id', $user_id);

            $row = $this->db->single();

            // Check row
            return $row;
        }

        // deletes user that doesnt have any posts or comments 
        public function eraseUser($user_id){
            $this->db->query('DELETE FROM users WHERE user_id = :user_id');

            $this->db->bind(':user_id', $user_id);

            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        // deletes user that has photos only 
        public function eraseUserAndPhotos($user_id){
            // Below deletes user when they have posts and comments 
            $this->db->query('DELETE users, photos
                            FROM users 
                            INNER JOIN photos 
                            ON photos.user_id = users.user_id
                            WHERE users.user_id = :user_id');
            
            $this->db->bind(':user_id', $user_id); 

               if($this->db->execute()){
                    return true;
                } else {
                    return false;
                }   
        }

        // deletes user that has comments only 
        public function eraseUserAndComments($user_id){
            // Below deletes user when they have posts and comments 
            $this->db->query('DELETE users, comments
                            FROM users 
                            INNER JOIN comments 
                            ON comments.user_id = users.user_id 
                            WHERE users.user_id = :user_id');
            
            $this->db->bind(':user_id', $user_id); 

               if($this->db->execute()){
                    return true;
                } else {
                    return false;
                }   
        }

        // deletes user that has posts and comments 
        public function eraseUserAll($user_id){
            // Below deletes user when they have posts and comments 
            $this->db->query('DELETE users, photos, comments 
                            FROM users 
                            INNER JOIN photos 
                            ON photos.user_id = users.user_id
                            INNER JOIN comments 
                            ON comments.user_id = users.user_id 
                            WHERE users.user_id = :user_id');
            
            $this->db->bind(':user_id', $user_id); 

               if($this->db->execute()){
                    return true;
                } else {
                    return false;
                }   
        }
    }
?>
  
   