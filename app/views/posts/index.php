 <?php require APPROOT . '/views/inc/header.php'; ?>
 <!-- getting information from the index function in Posts.php controller -->
    <?php flash('post_message'); ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <h1>Posts</h1>
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
    <?php foreach($data['posts'] as $post) : ?>
        <div class="card card-body mb-3">
            <div class="p-2 mb-3">
                @<?php echo $post->username; ?> on <?php echo $post->time; ?><br>
            </div>
            <?php if(!empty($post->filename)): ?>
                <img class="img-fluid" src="http://elvis.rowan.edu/~foxa3/webdev/project-test2/public/UPLOAD/archive/<?php echo  $post->user_id; ?>/<?php echo $post->filename ; ?>"><br>
             <?php endif; ?>
            <h3 class="mb-3 mt-3"><?php echo $post->caption; ?></h3>
            <a href="<?php echo URLROOT; ?>/posts/profile/<?php echo $post->user_id; ?>" class="btn btn-dark">View Profile</a>
        </div>
    <?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>