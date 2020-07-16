<?php include('../resources/config.php'); ?>
<?php include(TEMPLATE_FRONT . '/header.php'); ?>
<?php if(isset($_GET['search'])) {
    $search = $_GET['search'];
} else {
    $search = "";
} ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3><?php if(isset($_GET['search'])) echo "All available results for: " . $_GET['search'] ?></h3>
        </div>
    </div>
        <?php 
            if($search == "") {
                echo "Enter something in search box.";
            } else {
                $find_product_query = query("SELECT * FROM products WHERE product_title LIKE '%{$search}%'");;
            }

            if(mysqli_num_rows($find_product_query) == 0) {
                echo "We can't find product of name {$search}";
            } else {
                echo mysqli_num_rows($find_product_query) . " results found.";
            }
                ?>
            <div class="row text-center">
                <?php
                    while($row = fetch_array($find_product_query)) {
                    $path = display_image($row['product_image']); 
                ?>
                 <div class='col-md-3 col-sm-6 hero-feature'>
                    <div class='thumbnail'>
                        <img src="<?php echo '../resources/uploads/'.$row['product_image'] ?>">
                        <div class='caption'>
                            <h3 style='font-size:18px;'><b><?php echo $row['product_title'] ?></b></h3>
                            <p><?php echo $row['short_desc'] ?></p>
                            <p>
                            <?php if(isset($username)) {
                                echo "<a href='../resources/cart.php?add={$row3['product_id']}' class='btn btn-primary'>Buy Now!</a>"; 
                            }    ?>
                                <a href="item.php?id=<?php echo $row['product_id'] ?>" class='btn btn-default'>More Info</a>
                            </p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
           