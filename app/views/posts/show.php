<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- getting information from the show function in Posts.php controller -->
<a href="<?php echo URLROOT; ?>/posts/profile/<?php echo $data['post']->user_id; ?>" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
<br>
<div class="card card-body mb-3">

    <div class="row">
        <div class="col-md-6">
            <strong>@<?php echo $data['user']->username ?></strong>
        </div>
        <div class="col-md-6 mb-3">
            <?php if($data['post']->user_id == $_SESSION['user_id']) : ?>
                <form action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post']->photo_id; ?>/<?php echo $data['post']->user_id; ?>" method="post">
                    <input type="submit" value="Delete" class="btn btn-danger pull-right">
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if(!empty($data['post']->filename)): ?>
    <img class="img-fluid" src="http://elvis.rowan.edu/~foxa3/webdev/project-test2/UPLOAD/archive/<?php echo  $data['post']->user_id; ?>/<?php echo $data['post']->filename ; ?>"><br>
    <?php endif; ?>

    <h3 class="mb-3 mt-3"><?php echo $data['post']->caption; ?></h3>
    <?php foreach($data['comment'] as $comments) : ?>
        <div class="card card-body">

            <p><strong><?php echo $comments->username; ?>:</strong> <?php echo $comments->text; ?></p>

            <?php if($comments->user_id == $_SESSION['user_id'] || $data['post']->user_id == $_SESSION['user_id'] ) : ?>
                <form class="pull-right" action="<?php echo URLROOT; ?>/posts/deleteComment/<?php echo $comments->comment_id; ?>/<?php echo $data['post']->photo_id; ?>" method="post">
                    <input type="submit" value="Delete" class="btn btn-danger">
                </form>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

    <form action="<?php echo URLROOT; ?>/posts/comment/<?php echo $data['post']->photo_id; ?>" method="post">
        <div class="form-group">
            <textarea name="text" class="form-control form-control-lg"  placeholder="Say something about the image..."></textarea>
            <input type="submit" value="Submit" class="btn btn-success">
        </div>
    </form>

</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>