<div class="col-md-12">
<div class="row">
<h1 class="page-header">
  Product Categories
</h1>
</div>
<div class="col-md-5">  
<p class="bg bg-info"><?php display_message(); ?></p>
    <form action="" method="post">    
        <div class="form-group">
            <label for="title">Category</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="custom-control custom-radio">
            <label for="male_female">Select Gender</label><br>
            <input type="radio" name="male_female" value="Muskarci" id="muskarci" class="custom-control-input">Muskarci <br>
            <input type="radio" name="male_female" value="Zene" id="zene" class="custom-control-input">Zene
        </div>
        <br>
        <div class="form-group">
            <label for="brand_name">Brand</label>
            <select name="brand_name" class="form-control" id="">
                <?php get_add_product_brands(); ?>
            </select>
        </div>
        <div class="form-group">  
            <input type="submit" name="submit" class="btn btn-primary" value="Add Category">
        </div>  
        <?php create_category(); ?>    
    </form>
</div>
<div class="col-md-7">
    <table class="table">
            <thead>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Male/Female</th>
            <th>Brand</th>
            <th>Delete</th>
        </tr>
            </thead>
    <tbody>
       <?php display_categories(); ?>
    </tbody>
        </table>
</div>
</div>