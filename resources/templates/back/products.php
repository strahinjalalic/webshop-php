<div class="col-md-12">
<div class="row">
<h1 class="page-header">
   All Products
</h1>
<p class="bg bg-success"><?php display_message(); ?></p>
<table class="table table-hover">
    <thead>
      <tr>
           <th>ID</th>
           <th>Title</th>
           <th>Category</th>
           <th>Brand</th>
           <th>Price</th>
           <th>Quantity</th>
           <th>Edit</th>
           <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php display_products_in_admin(); ?>
    </tbody>
</table>
</div>
</div>