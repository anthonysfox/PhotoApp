<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- getting information from the profile function in Posts.php controller -->
    <?php flash('post_message'); ?>
    <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
    <?php if($data['user']->user_id == $_SESSION['user_id']): ?>
        <div class="row"> 
        <div class="col-md-6 mb-3">
            <h1>Your Profile</h1>
        </div>
        <div class="col-md-6">
            <form class="pull-right" action="<?php echo URLROOT; ?>/posts/deleteUser/<?php echo $_SESSION['user_id']; ?>" method="post">
                <input type="submit" value="Delete Your Account" class="btn btn-danger">
            </form>
            <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
                <i class="fa fa-pencil"></i> Add Post
            </a>
        </div>
        </div> 
    <?php else: ?>
        <h1>Welcome to  <?php echo $data['user']->username; ?>'s profile!</h1>
    <?php endif; ?>
    <?php foreach($data['posts'] as $posts): ?>
        <div class="card card-body mb-3">
        <img class="img-fluid" src="http://elvis.rowan.edu/~foxa3/webdev/project-test2/public/UPLOAD/archive/<?php echo  $posts->user_id; ?>/<?php echo $posts->filename ; ?>"><br>
            <h3 class="mb-3 mt-3"><?php echo $posts->caption; ?></h3><br>
            <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $posts->photo_id; ?>" class="btn btn-dark">View Post</a>
        </div>
    <?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>