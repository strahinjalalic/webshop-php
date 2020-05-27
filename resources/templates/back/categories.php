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
        <div class="form-group">
            <?php $query = query("SELECT * FROM genders");
                  while($row = fetch_array($query)) {
                      echo "<input type='radio' name='gender_category' value='" . $row['id'] . "'>". $row['gender'] . "<br>";
                  }          
            ?>
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
            <th>Gender</th>
            <th>Delete</th>
        </tr>
            </thead>
    <tbody>
       <?php display_categories(); ?>
    </tbody>
        </table>
</div>
</div>