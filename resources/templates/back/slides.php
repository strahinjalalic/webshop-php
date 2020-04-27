<div class="row">
    <h3 class="bg-success"><?php display_message(); ?></h3>
    <div class="col-xs-4">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="file" name="file">
        </div>

        <div class="form-group">
        <label for="title">Slide Title</label>
            <input type="text" name="slide_title" class="form-control">
        </div>

        <div class="form-group">
            <input class="btn btn-primary" type="submit" name="add_slide">
        </div>
    </form>
    <?php add_slides(); ?>
</div>


</div>

<hr style="border: 0.3px solid black;">

<h1>All Slides <small>Tap To Delete</small></h1>

<div class="row">
    <?php get_slide_thumbnails(); ?>
</div>