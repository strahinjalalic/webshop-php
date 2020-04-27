<div class="col-md-12">
<div class="row">
<h1 class="page-header">
  Product Brands
</h1>
</div>
<div class="col-md-5">  
<p class="bg bg-info"><?php display_message(); ?></p>
    <form action="" method="post">    
        <div class="form-group">
            <label for="brand_name">Brand Name</label>
            <input type="text" name="brand_name" class="form-control">
        </div>
        <div class="form-group">  
            <input type="submit" name="submit" class="btn btn-primary" value="Add Brand">
        </div>  
        <?php create_brand(); ?>    
    </form>
</div>
<div class="col-md-7">
    <table class="table">
            <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Delete</th>
        </tr>
            </thead>
    <tbody>
       <?php display_brands(); ?>
    </tbody>
        </table>
</div>
</div>