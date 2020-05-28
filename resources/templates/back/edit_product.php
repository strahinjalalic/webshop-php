<?php 
$products = array();
if(isset($_GET['id'])) {
  $query = query("SELECT * FROM products WHERE product_id = " . $_GET['id']);
  confirm($query);
  $row = fetch_array($query);
  $products[] = $row;
  foreach($products as $product) {
    $path = display_image($product['product_image']);  ?>

<div class="col-md-12">
<div class="row">
<h1 class="page-header">
   Edit Product
</h1>
</div>
<form action="" method="POST" enctype="multipart/form-data">
<div class="col-md-8">
<div class="form-group">
    <label for="product-title">Product Title </label>
        <input type="text" name="product_title" class="form-control" value="<?php echo $product['product_title']; ?>">
    </div>
    <div class="form-group">
           <label for="product-title">Product Description</label>
      <textarea name="product_description" id="" cols="30" rows="10" class="form-control"><?php echo $product['product_description']; ?></textarea>
    </div>
    
    <div class="form-group row">
      <div class="col-xs-3">
        <label for="product-price">Product Price</label>
        <input type="number" name="product_price" class="form-control" size="60" value="<?php echo $product['product_price']; ?>">
      </div>
    </div>
</div>

<aside id="admin_sidebar" class="col-md-4">
     <div class="form-group">
        <input type="submit" name="update" class="btn btn-primary btn-lg" value="Update">
    </div>
    <div class="form-group">
         <label for="product_brand">Product Brand</label>
         <select name="product_brand" id="" class="form-control">
            <option value="<?php echo $product['product_brand_id']; ?>"><?php echo display_brand_title($product['product_brand_id']) ?></option>
            <?php
            get_edit_product_brands($product['product_brand_id']); ?>
         </select>
    </div>

    <div class="form-group">
      <label for="product-title">Product Quantity</label>
        <input type="number" name="product_quantity" class="form-control" size="60" value="<?php echo $product['product_quantity']; ?>">
    </div>

    <div class="form-group">
          <label for="product-title">Product Keywords</label>
        <input type="text" name="product_keywords" class="form-control" value="<?php echo $product['product_keywords']; ?>">
    </div>

    <div class="form-group">
        <label for="product-title">Product Image</label>
        <input type="file" name="file">
        <img src="<?php echo "../../resources/{$path}" ?>" width="200px" alt="">
    </div>
</aside>  
</form>
<?php edit_product(); } }?>
</div>