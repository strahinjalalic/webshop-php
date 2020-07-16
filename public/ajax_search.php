<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>WebShop</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/shop-homepage.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
    </head>
    <body>
<?php include('../resources/config.php'); ?>
<?php
$query = $_POST['query'];
$find_product_query = query("SELECT * FROM products WHERE product_keywords LIKE '%{$query}%' LIMIT 5");;

if($query != "") {
    while($row = fetch_array($find_product_query)) {
        echo "<div class='display_product' style='background-color: aliceblue;'>
                    <a href='item.php?id={$row['product_id']}' style='color:#000;'>
                      <div class='live_search_img'>
                        <img src=../resources/uploads/{$row['product_image']}>
                      </div>
                      <div class='live_search_text'>
                        " . $row['product_title'] . "  ". "<b>" . "$" . $row['product_price'] . "</b>" . "
                        <p id='grey' style='padding-top:6px;'> " . substr($row['short_desc'], 0, 50) . "..." . "</p>
                      </div>
                    </a>  
                 </div>";
    }
}

?>
<script src="https://kit.fontawesome.com/03abbca0a8.js" crossorigin="anonymous"></script>
<script src="js/jquery.js"></script>
<script src="js/script.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
