<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <div class="card card-body bg-light mt-5 mb-3">
        <h2>Add Post</h2>
        <p>Create a post with this form</p>
        <!-- form using add function from Posts.php in controllers -->
        <form enctype="multipart/form-data" action="<?php echo URLROOT; ?>/posts/add" method="post">
            <div class="form-group">
                <input type="file" name="filename" class="form-control <?php echo (!empty($data['filename_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['filename']; ?>">
                <span class="invalid-feedback"><?php echo $data['filename_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="caption">Caption: <sup>*</sup></label>
                <textarea name="caption" class="form-control form-control-lg" placeholder="Say something about your image..."></textarea>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Submit" class="btn btn-success btn-block">
                </div>
            </div>
        </form>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>