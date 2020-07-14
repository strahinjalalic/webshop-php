<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");

if(isset($_POST['save']) && $_POST['save'] == 1) {
    $user_id = $_POST['userID'];
    $product_id = $_POST['productId'];
    $rated_index = $_POST['ratedIndex'];
    $date_time = date('Y-m-d H:i:s');

    if(!$user_id) {
        $query = query("INSERT INTO ratings(user_id, product_id, rating, date_time ) VALUES({$_SESSION['u_id']}, {$product_id}, {$rated_index}, '{$date_time}')");
        $last_id = query("SELECT user_id FROM ratings ORDER BY id DESC LIMIT 1");
        $user_data = fetch_array($last_id);
        $user_id = $user_data['user_id'];
    } else {
        $query = query("UPDATE ratings SET ratedIndex = {$rated_index} WHERE user_id = {$user_id}");
    }
    exit(json_encode(array('user_id' => $user_id)));
}
?>

<div class="container">
    <?php require_once(TEMPLATE_FRONT . DS . 'side_nav.php'); ?>
<div class="col-md-9">
    <?php 
        $sql = "SELECT * FROM products WHERE product_id = " . mysqli_real_escape_string($connection, $_GET['id']);
        $query = query($sql);
        while($row = fetch_array($query)):
            $path = display_image($row['product_image']);
    ?>
<div class="row">

    <div class="col-md-7">
       <img class="img-responsive" src="<?php echo "../resources/{$path}" ?>" alt="">
    </div>
    <div class="col-md-5">
        <div class="thumbnail">
            <div class="caption-full">
                <h4><a href="#"><?php echo $row['product_title']; ?></a>  </h4><?php echo "Price of product: ". "<b>$".$row['product_price'] . "</b>"; ?>    
                <hr>
                <div class="ratings">
                <?php
                      $count_all_query = query("SELECT id FROM ratings WHERE product_id = {$row['product_id']}");
                      $count_all = mysqli_num_rows($count_all_query);
                      $query = query("SELECT SUM(rating) AS total FROM ratings WHERE product_id = {$row['product_id']}");
                      $fetch = fetch_array($query);
                      $count_rating = $fetch['total'];
                      ($count_all > 0) ? $avg = $count_rating / $count_all : '';   ?>
                    <p>
                        <i class="fa fa-star" aria-hidden="true" data-index="1"></i>
                        <i class="fa fa-star" aria-hidden="true" data-index="2"></i>
                        <i class="fa fa-star" aria-hidden="true" data-index="3"></i>
                        <i class="fa fa-star" aria-hidden="true" data-index="4"></i>
                        <i class="fa fa-star" aria-hidden="true" data-index="5"></i>
                        <input type="hidden" name="product_id" id="product_id" value="<?php echo $row['product_id']; ?>">
                        <?php echo ($avg > 0) ?  round($avg, 1) . " stars" : "0 stars" ?>
                    </p>
                </div>
                <form action="">
                    <div class="form-group">
                        <?php if(isset($username)) { ?>
                            <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add=<?php echo $row['product_id'] ?>">Add To Cart</a>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        <hr>

<div class="row">
    <div role="tabpanel">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <p></p> 
            <p><?php echo $row['product_description']; ?></p>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
        <div class="col-md-6">
            <?php get_all_ratings(); ?>
        </div>
        <div class="col-md-6">
            <form action="" class="form-inline">
                <h3>Your Rating</h3><br>
                <?php if(!isset($_SESSION['username'])) {
                    echo "<div class='form-group'>
                            <textarea name='' id='' cols='60' rows='10' class='form-control' disabled></textarea>
                          </div>
                          <div class='form-group'>
                            <input type='submit' class='btn btn-primary' value='SUBMIT' disabled>
                          </div>";
                        } else { ?>
                <div class="form-group">
                    <textarea name="" id="" cols="60" rows="10" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="SUBMIT">
                </div> <?php  }?>
            </form>
        </div>
        </div>
    </div>
  </div>
</div>
</div>
    <?php endwhile; ?>
</div>
</div>

<?php require_once(TEMPLATE_FRONT . DS . "footer.php"); ?>