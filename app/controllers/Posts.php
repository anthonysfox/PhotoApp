<?php
    class Posts extends Controller {
      public function __construct(){
        // Saying if you arent logged in person is redirected to 
        // login page 
        if(!isLoggedIn()){
          redirect('users/login');
        }

        // loading in Post and User models 
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
      }

      // When user is logged in they will be directed to page 
      // that loads the below function 
      public function index(){
        // Get posts
        $posts = $this->postModel->getPosts();

        $data = [
          'posts' => $posts
        ];

        $this->view('posts/index', $data);
      }

      // function below is to add a post 
      // sends information in data array to Post controller 
      // to be added to database and also upload image 
      // but checks to see if the field is empty first
      public function add(){
          if($_SERVER['REQUEST_METHOD'] == 'POST') {
              // Sanitize Post
              $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
              $data = [
                  'user_id' => $_SESSION['user_id'],
                  'filename' => $_FILES["filename"]["name"],
                  'caption' => trim($_POST['caption']),
                  'filename_err' => ''
              ];

              if(empty($data['filename'])){
                $data['filename_err'] = 'Please enter a file';
              } else {
                mkdir( '/home/foxa3/public_html/webdev/project-test2/public/UPLOAD/archive/' . $_SESSION['user_id'], 0777);
                chmod( '/home/foxa3/public_html/webdev/project-test2/public/UPLOAD/archive/' . $_SESSION['user_id'], 0777);
                // bug in mkdir() requires you to chmod()

                $targetname = '/home/foxa3/public_html/webdev/project-test2/public/UPLOAD/archive/' . $_SESSION['user_id'] . '/' . $data['filename'];
              
                // below is to make add number to image if it has already been uploaded
              if (file_exists($targetname)) {

                $count = 1;
            
                $path_parts = pathinfo( $targetname );
            
                while ( file_exists($targetname) ) {
            
                    $targetname = $path_parts['dirname'] . '/' .
                                  $path_parts['filename'] . '_' . $count . '.' .
                                  $path_parts['extension'];
            
                    $count += 1;
                }
            
                echo "<p>Already uploaded one with that name.<br />" .
                     " I changed it to $targetname .</p>";
              }
              // below copies file into the folder and can then be displayed 
              if ( copy($_FILES['filename']['tmp_name'], $targetname) ) {
                // if we don't do this, the file will be mode 600,
                // owned by user "apache", and so we won't be able to
                // read it ourselves or do anything with it
                chmod($targetname, 0444);
                // but we can't upload another with the same name on top,
                // because it's now read-only
              } else {
                // die if the picture file size is too big 
                // Could not figure out how to change the allowed file upload size. 
                // Also not sure if its only allowing certain sized things because of how elvis is set up. 
                die("Error copying ". $data['filename'] . " because it is too big, please go back and upload a picture thats less then 1MB");
              }
            }
            

              // Make sure no errors and then post to database
              if(empty($data['filename_err'])){
                if($this->postModel->addPost($data)){
                  flash('post_message', 'Post added');
                  redirect('posts');
                } else {
                  die('Something went wrong');
                }
              
              } else {
                // Load view with errors
                $this->view('posts/add', $data);
              }
            

          } else {

          }
    
          $this->view('posts/add', $data);
      }

      // below function is to add a comment to the picture 
      // takes in photo_id of photo being commented on 
      // takes in user_id, text, photo_id 
      // sends it to Post model
      public function comment($photo_id){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize Post
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'text' => trim($_POST['text']), 
                'photo_id' => $photo_id,
                'error' => ''
            ];

            // Make sure no errors and then post to database
            if(empty($data['error'])){
              if($this->postModel->addComment($data)){
                redirect('posts/show/' . $photo_id);
              } else {
                die('Something went wrong');
              }
            } else {
              // Load view with errors
              $this->view('posts/show', $data);
            }
        }
      }

      // below function is to display profile of user 
      // takes in user_id to be displayed 
      public function profile($user_id){
        // making a variable to store information from methods being called
        $posts = $this->postModel->getUserPosts($user_id);
        $user = $this->userModel->getUserById($user_id);

        $data = [
          'posts' => $posts,
          'user' => $user
        ];

        $this->view('posts/profile', $data);
      }

      // below is function called when viewing picture from user 
      // shows the name, time, picture, caption, and any comments  
      public function show($photo_id){
        $post = $this->postModel->getPostById($photo_id);
        $comment = $this->postModel->getComments($photo_id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
          'post' => $post,
          'comment' => $comment,
          'user' => $user
        ];
        $this->view('posts/show', $data);
      }

      // function to delete a post
      public function delete($photo_id, $user_id){
        echo $photo_id;
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          if($this->postModel->deletePost($photo_id)){
            flash('post_message', 'Post Removed');
            // once post is deleted it redirects to users profile
            redirect('posts/profile/' . $user_id);
          }else {
            die('Something went wrong');
          }

          // Delete just photo if no comments 
          if($this->postModel->deletePicture($photo_id)){
            flash('post_message', 'Post Removed');
            // once post is deleted it redirects to users profile
            redirect('posts/profile/' . $user_id);
          } else {
            die('Something went wrong');
          }
        } else {
          redirect('posts');
        }
      }

      // function to delete a user 
      // deletes if they have entered a picture and comment
      // cannot figure out how to delete if one of those is missing
      public function deleteUser($user_id){ 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          // if statements below handle the different situations like 
          // if user has no post or comments 
          // if user has posts but no comments 
          // if user has no posts but comments 
          if($this->userModel->eraseUserAndComments($user_id)){
            session_destroy();
            flash('post_message', 'User Removed Successfully');
            redirect('pages/index');
          }
          if($this->userModel->eraseUserAndPhotos($user_id)){
            session_destroy();
            flash('post_message', 'User Removed Successfully');
            redirect('pages/index');
          } 
          if($this->userModel->eraseUserAll($user_id)){
            session_destroy();
            flash('post_message', 'User Removed Successfully');
            redirect('pages/index');
          }
          if($this->userModel->eraseUser($user_id)){
            session_destroy();
            flash('post_message', 'User Removed Successfully');
            redirect('pages/index');
          } else {
            die('Something went wrong');
          }

        } else {
          redirect('posts');
        }
      }

      // function to delete a comment 
      // takes in the comment+id of the comment that is to be deleted 
      // takes in photo_id that comment is on so it redirects back
      // to it
      public function deleteComment($comment_id, $photo_id){ 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          if($this->postModel->deleteComment($comment_id)){
            redirect('posts/show/' . $photo_id);
          } else {
            die('Something went wrong');
          }
        } else {
          redirect('posts');
        }
      }
    }
?>