<?php
  class Post {
    private $db;

    public function __construct(){
      // creating a database instance 
      $this->db = new Database;
    }

    public function getPosts(){ // getting all posts and users associated with them
      // query is function in database library 
      // prepares the statement to be executed 
      $this->db->query('SELECT *,
                        photos.photo_id as photoId,
                        users.user_id as userId
                        FROM photos
                        INNER JOIN users
                        ON photos.user_id = users.user_id
                        ORDER BY photos.time DESC
                        ');

      // below gets all of entries  by calling database variable in constructor 
      $results = $this->db->resultSet();

      // returning the data 
      return $results;
    }

    // getting posts made by a certain user 
    // passing in a user_id
    public function getUserPosts($user_id){
      $this->db->query('SELECT * 
                        FROM photos 
                        WHERE user_id = :user_id ORDER BY time DESC');

      $this->db->bind(':user_id', $user_id);

      $results = $this->db->resultSet();

      return $results;
    }

    // gets comments on a picture being viewed 
    // pass in photo_id of picture being viewed 
    public function getComments($photo_id){
      $this->db->query('SELECT *
                        FROM comments
                        INNER JOIN users
                        ON comments.user_id = users.user_id
                        WHERE comments.photo_id = :photo_id
                        ');

      $this->db->bind(':photo_id', $photo_id);

      $results = $this->db->resultSet();

      // if there is a recorded comment then it'll return it 
      // rowcount is function in Database library
      if($this->db->rowCount() > 0){
        return $results;
      }

    }

    // function to add post and insert into database 
    // getting information from Posts controller 
    public function addPost($data){
      $this->db->query('INSERT INTO photos (filename, user_id, caption) VALUES(:filename, :user_id, :caption)');

      // bind is function defined in database library  
      $this->db->bind(':filename', $data['filename']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':caption', $data['caption']);

      // Execute
      if($this->db->execute()){
          return true;
      } else {
          return false;
      }
    }

    // function to add a comment
    // taking in data from Posts controller
    public function addComment($data){
      $this->db->query('INSERT INTO comments (user_id, text, photo_id) VALUES(:user_id, :text, :photo_id)');

      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':text', $data['text']);
      $this->db->bind(':photo_id', $data['photo_id']);

      // Execute
      if($this->db->execute()){
          return true;
      } else {
          return false;
      }
    }

    // function to get comment by the id 
    public function getCommentById($user_id) {
      $this->db->query('SELECT *
                        FROM users
                        LEFT JOIN comments
                        ON users.user_id = comments.user_id
                        WHERE users.user_id = :user_id
                        ORDER BY time ASC');

      $this->db->bind('user_id', $user_id);

      $row = $this->db->resultSet();

      // Check row
      return $row;
    }

    // function to get posts by id
    public function getPostById($photo_id){
      $this->db->query('SELECT * FROM photos WHERE photo_id = :photo_id ORDER BY time DESC');
      $this->db->bind(':photo_id', $photo_id);

      // returning a single row from database 
      $result = $this->db->single();

      return $result;
    }

    // function to get a random image but not sure how to implement 
    public function getRandomPhoto(){
      $this->db->query('SELECT * FROM photos
                        ORDER BY RANDOM()
                        LIMIT 1');
    }

    // delete function for photos if no comments are on it 
    public function deletePicture($photo_id){
      $this->db->query('DELETE FROM photos WHERE photo_id = :photo_id');

      $this->db->bind(':photo_id', $photo_id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // function to delete post with any comments on it
    public function deletePost($photo_id){ // Deletes post and any comments on that post 
      $this->db->query('DELETE photos, comments FROM photos
                       INNER JOIN comments ON comments.photo_id = photos.photo_id 
                       WHERE photos.photo_id = :photo_id');

      $this->db->bind(':photo_id', $photo_id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // function to delete comment from comments 
    // taking in info from Posts controller 
    public function deleteComment($comment_id){
      $this->db->query('DELETE FROM comments WHERE comment_id = :comment_id');

      $this->db->bind(':comment_id', $comment_id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
?>