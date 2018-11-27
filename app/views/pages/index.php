<?php require APPROOT . '/views/inc/header.php'; ?>
    <!-- getting info from Pages controller/ index method -->
    <?php flash('post_message'); ?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <h1 class="display-3"><?php echo $data['title']; ?></h1>
            <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>