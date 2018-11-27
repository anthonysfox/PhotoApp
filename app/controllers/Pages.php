<?php
    class Pages extends Controller {
        public function __construct(){
            $this->postModel = $this->model('Post');
        }

        //  function to display information on the title page 
        public function index(){
            if(isLoggedIn()){
                redirect('posts');
            }

            // taking in data that can be viewed in the index
            // file in views/pages/index
            $data = [
                'title' => 'PhotoApp',
                'description' => 'Simple Image Sharing Site',
            ];

            // this is the page to be displayed when coming to
            // the website and you are not logged in 
            $this->view('pages/index', $data);
        }

        // Regular about page 
        public function about(){
            $data = [
                'title' => 'About Us',
                'description' => 'This is a website that allows you to share posts with other users.<br>
                It was created by Anthony Fox for a Final Project at Rowan Univeristy in Intro to Web Development.'
            ];
            $this->view('pages/about', $data);
        }
    }
?>