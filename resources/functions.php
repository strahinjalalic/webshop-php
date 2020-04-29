<?php 
require 'C:\xampp\htdocs\e-com-master\vendor\autoload.php';

// $dotenv = Dotenv\Dotenv::create(ENV);
// $dotenv->load(); 

// require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/e-com-master/');
$dotenv->load();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$uploads = "uploads";

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = null;
} 


function redirect($location) {
    return header("Location: {$location}");
}

function last_id() {
    global $connection;

    return mysqli_insert_id($connection);
}

function query($sql) {
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($query) {
    global $connection;
    if(!$query) {
        die('Query Failed!' . mysqli_error($connection));
    }
}

function fetch_array($query) {
    return mysqli_fetch_array($query);
}

function display_image($image) {
    global $uploads;
    return $uploads.DS.$image;
}

function get_products() {
    global $username;
    $sql = "SELECT * FROM products";
    $query = query($sql);
    while($row = fetch_array($query)) {
        $path = display_image($row['product_image']);
        $description = substr($row['product_description'], 0, 80) . "...";
        $product = "
        <div class='col-sm-4 col-lg-4 col-md-4'>
         <div class='thumbnail' id='lower_height'>
            <img src='../resources/{$path}' width='50px' height='50px' alt=''>
            <div class='caption'>
                <h4 class='pull-right'>$ {$row['product_price']} </h4>
                <h4><a href='item.php?id={$row['product_id']}'> {$row['product_title']} </a>
                </h4>
                <p> {$description} </p>";
                if(isset($username)){
                    $product .= "<a class='btn btn-primary' target='_blank' href='../resources/cart.php?add={$row['product_id']}'>Add To Cart</a>";
                }
                $product .= "
            </div>
         </div>
        </div>";
        echo $product;    
    }
}

function get_categories() {
    $sql = "SELECT * FROM categories";
    $query = query($sql);
    confirm($query);
    $categories = "";
    
    while($row = mysqli_fetch_assoc($query)) {
        // $categories .= "<ul class='list-group'>{$row['cat_category']}";
        //     $categories .= "<ul class='list-group'>{$row['cat_subcategory1']}";
        //         $categories .= "<li class='list-group-item'>{$row['cat_brand']}</li>";
        //     $categories .= "</ul>";
        // $categories .= "</ul>";
    }
    echo $categories;
}

function get_category_products() {
    global $connection;
    $sql = "SELECT * FROM products WHERE product_category_id = " . mysqli_real_escape_string($connection, $_GET['id']);
    $query = query($sql);
    confirm($query);
    while($row = fetch_array($query)) {
        $path = display_image($row['product_image']);
        $product = <<<DELIMETER
        <div class="col-md-3 col-sm-6 hero-feature">
        <div class="thumbnail">
            <img src="../resources/{$path}" alt="">
            <div class="caption">
                <h3>{$row['product_title']}</h3>
                <p>{$row['short_desc']}</p>
                <p>
                    <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                </p>
            </div>
        </div>
        </div>
        DELIMETER;
    echo $product;    
    }
}

function get_all_products() {
    $sql = "SELECT * FROM products";
    $query = query($sql);
    confirm($query);
    while($row = fetch_array($query)) {
        $path = display_image($row['product_image']);
        $product = "
        <div class='col-md-3 col-sm-6 hero-feature'>
        <div class='thumbnail'>
            <img src='../resources/{$path}' alt=''>
            <div class='caption'>
                <h3>{$row['product_title']}</h3>
                <p>{$row['short_desc']}</p>
                <p>";
                if(isset($username)) {
                    $products .= "<a href='../resources/cart.php?add={$row['product_id']}' class='btn btn-primary'>Buy Now!</a>"; 
                }
                $product .= "    
                    <a href='item.php?id={$row['product_id']}' class='btn btn-default'>More Info</a>
                </p>
            </div>
        </div>
        </div>";
    echo $product;    
    }
}

function set_message($msg) {
    if(!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = '';
    }
}

function display_message() {
    if(!empty($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    } else {
        return false;
    }
}

function is_admin($username) {
    $sql = "SELECT is_admin FROM users WHERE username = '{$username}'";
    $query = query($sql);
    confirm($query);
    
    $row = fetch_array($query);
    if($row['is_admin'] == 'yes') {
        return true;
    } 
    return false;
}

function contact_send_message() {
    if(isset($_POST['submit'])) {
        $mail = new PHPMailer(true);
        $from_name = trim($_POST['name']);
        $from_mail = trim($_POST['email']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);

        $mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host = getenv('APP_SMTP');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('APP_KEY');
        $mail->Password = getenv('APP_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($from_mail, $from_name);
        $mail->addAddress(getenv('APP_MAIL'));
        $mail->Subject = $subject;
        $mail->Body = $message;
        if($mail->send()) {
            set_message('Message sent successfully!');
            redirect('contact.php');
        } else {
            set_message('Message not sent! Try again!');
            redirect('contact.php');
        }
    }
}

function num_orders() {
    $query = query("SELECT * FROM orders");
    return mysqli_num_rows($query);
}

function num_products() {
    $query = query("SELECT * FROM products");
    return mysqli_num_rows($query);
}

function num_categories() {
    $query = query("SELECT * FROM categories");
    return mysqli_num_rows($query);
}

function display_orders() {
    $query = query("SELECT * FROM orders");
    confirm($query);
    while($row = fetch_array($query)) {
        $order = <<<DELIMETER
        <tr>
            <td>{$row['order_id']}</td>
            <td>{$row['order_amount']}</td>
            <td>{$row['order_transaction']}</td>
            <td>{$row['order_currency']}</td>
            <td>{$row['order_status']}</td>
            <td><a class='btn btn-danger' href='../../resources/templates/back/delete_orders.php?id={$row['order_id']}'><span class='glyphicon glyphicon-remove-sign'></span></a></td>
        </tr>
        DELIMETER;
        echo $order;
    }
}

function display_products_in_admin() {
    $query = query("SELECT * FROM products");
    confirm($query);
    while($row = fetch_array($query)) {
        $category_title = display_product_category_title($row['product_category_id']);
        $brand_name = display_brand_title($row['product_brand_id']);
        $product = <<<DELIMETER
        <tr>
            <td>{$row['product_id']}</td>
            <td><b>{$row['product_title']}</b><br>
                <img src=../../resources/uploads/{$row['product_image']} width="100" alt= /> </td>
            <td>{$category_title}</td>
            <td>{$brand_name}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class='btn btn-warning' href='index.php?edit_product&id={$row['product_id']}'>Edit</a></td>
            <td><a class='btn btn-danger' href='../../resources/templates/back/delete_products.php?id={$row['product_id']}'>Delete</a></td>
        </tr>
        DELIMETER;
        echo $product;
    }
}

function get_add_product_categories() {
    $query = query("SELECT * FROM categories");
    confirm($query);
    while($row = fetch_array($query)) {
        $category = <<<DELIMETER
        <option value={$row['cat_id']}>{$row['cat_title']}</option>
        DELIMETER;
        echo $category;
    }
}

function get_edit_product_categories($category_id) {
    $query = query("SELECT * FROM categories WHERE cat_id != {$category_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        echo "<option value={$row['cat_id']}>{$row['cat_title']}</option>";
    }
}

function get_add_product_brands() {
    $query = query("SELECT * FROM brands");
    confirm($query);
    while($row = fetch_array($query)) {
        $brand = "<option value={$row['id']}>{$row['brand_name']}</option>";
        echo $brand;
    }
}

function get_edit_product_brands($brand_id) {
    $query = query("SELECT * FROM brands WHERE id != {$brand_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        echo "<option value={$row['id']}>{$row['brand_name']}</option>";
    }
}

function add_product() {
    $products = array();
    if(isset($_POST['publish'])) {
        $products[] = $_POST;
        foreach($products as $product) {
        $short_desc = substr($product['product_description'], 0, 50);
       
        $product_image = $_FILES['file']['name'];
        $image_tmp_location = $_FILES['file']['tmp_name'];

        move_uploaded_file($image_tmp_location, UPLOAD_DIRECTORY.DS.$product_image);

    $query = query("INSERT INTO products(product_title, product_category_id, product_brand_id, product_price, product_quantity, product_description, short_desc, product_image, product_keywords) VALUES('{$product['product_title']}', {$product['product_category']}, {$product['product_brand']}, {$product['product_price']}, {$product['product_quantity']}, '{$product['product_description']}', '{$short_desc}', '{$product_image}', '{$product['product_keywords']}')");
    $last_id = last_id();
    confirm($query);
    set_message("New Product with ID of {$last_id} was just added!");
    redirect("index.php?products");
        }
    }
}

function edit_product() {
    $products = array();
    if(isset($_POST['update'])) {
        $products[] = $_POST;
        foreach($products as $product) {
         $short_desc = substr($product['product_description'], 0, 50);
         $product_image = $_FILES['file']['name'];
         $image_tmp_location = $_FILES['file']['tmp_name'];
        
        if(empty($product_image)) {
            $query = query("SELECT product_image FROM products WHERE product_id = " . $_GET['id']);
            confirm($query);
            $row = fetch_array($query);
            $product_image = $row['product_image'];
        } 
    
        move_uploaded_file($image_tmp_location, UPLOAD_DIRECTORY.DS.$product_image);

    $query = query("UPDATE products SET product_title = '{$product['product_title']}', product_category_id = {$product['product_category']}, product_brand_id = {$product['product_brand']}, product_description = '{$product['product_description']}', product_price = {$product['product_price']}, product_quantity = {$product['product_quantity']}, short_desc = '{$short_desc}', product_image = '{$product_image}', product_keywords = '{$product['product_keywords']}' WHERE product_id = " . $_GET['id']);
    confirm($query);

    set_message("Product has been updated.");
    redirect("index.php?products");
        }
    }
}

function edit_user_admin() {
    $users = array();
    if(isset($_POST['update'])) {
        $users[] = $_POST;
        foreach($users as $user) {
            $query = query("UPDATE users SET is_admin = '{$user['is_admin']}' WHERE user_id = '{$_GET['id']}'");
            confirm($query);

            set_message("User role has been updated.");
            redirect("index.php?users");
        }
    }
}

function display_product_category_title($product_category_id) {
    $query = query("SELECT * FROM categories WHERE cat_id = {$product_category_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        return $row['cat_title'];
    }
}

function display_brand_title($brand_id) {
    $query = query("SELECT * FROM brands WHERE id = {$brand_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        return $row['brand_name'];
    }
}

function display_categories() {
    $query = query("SELECT * FROM categories");

    confirm($query);
    while($row = fetch_array($query)) {
        $query2 = query("SELECT brand_name FROM brands WHERE id = {$row['brand_id']}");
        while($brand = fetch_array($query2)) {
            $category = <<<DELIMETER
            <tr>
                <td>{$row['cat_id']}</td>
                <td>{$row['cat_title']}</td>
                <td>{$row['cat_subcategory1']}</td>
                <td>{$brand['brand_name']}</td>
                <td><a class='btn btn-danger' href='../../resources/templates/back/delete_category.php?id={$row['cat_id']}'>Delete</a></td>
            </tr>
            DELIMETER;
            echo $category;
        }
    }
}

function display_brands() {
    $query = query("SELECT * FROM brands");
    confirm($query);
    while($row = fetch_array($query)) {
        $brand = "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['brand_name']}</td>
            <td><a class='btn btn-danger' href='../../resources/templates/back/delete_brand.php?id={$row['id']}'>Delete</a></td>
        </tr>";
        echo $brand;
    }
}

function create_category() {
    if(isset($_POST['submit'])) {
        if(!empty($_POST['title']) && !empty($_POST['male_female']) && !empty($_POST['brand_name'])) {
            $title = $_POST['title'];
            $gender = $_POST['male_female'];
            $brand = $_POST['brand_name'];
            $query = query("INSERT INTO categories(cat_title, cat_subcategory1, brand_id) VALUE('{$title}', '{$gender}', {$brand})");
            confirm($query);
            redirect("index.php?categories");
            set_message("New category is created!");
        } else {
            set_message("Enter title name!");
        }
    }
}

function create_brand() {
    if(isset($_POST['submit'])) {
        if(!empty($_POST['brand_name'])) {
            $name = $_POST['brand_name'];
            $query = query("INSERT INTO brands(brand_name) VALUE('{$name}')");
            confirm($query);
            redirect("index.php?brands");
            set_message("New brand is created!");
        } else {
            set_message("Enter brand name!");
        }
    }
}

function display_users() {
    global $uploads;
    $query = query("SELECT * FROM users");
    confirm($query);
    while($row = fetch_array($query)) {
        $path = $uploads.DS."user_placeholder".DS.$row['user_image'];
        $user = <<<DELIMETER
        <tr>
            <td>{$row['user_id']}</td>
            <td><img class="admin-user-thumbnail user_image" width='75px' height='75px' src="../../resources/{$path}" alt=""></td>
            <td>{$row['username']}
                    <div class="action_links">
                    <a href="../../resources/templates/back/delete_user.php?id={$row['user_id']}">Delete</a>
                    <a href="index.php?edit_user&id={$row['user_id']}">Edit</a>
                </div>
            </td>
            <td>{$row['email']}</td>
            <td>{$row['first_name']}</td>
            <td>{$row['last_name']}</td>
            <td>{$row['is_admin']}</td>
        </tr>
        DELIMETER;
        echo $user;
    }
}

function add_user() {
    if(isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $password = $_POST['password'];
        $image = $_FILES['file']['name'];
        $image_tmp = $_FILES['file']['tmp_name'];

        if(!empty($username) || !empty($email) || !empty($firstname) || !empty($lastname) || !empty($password)) {
            $query = query("INSERT INTO users(username, first_name, last_name, email, password, user_image) VALUES('{$username}', '{$firstname}', '{$lastname}', '{$email}', '{$password}', '{$image}')");
            confirm($query);

            move_uploaded_file($image_tmp, UPLOAD_DIRECTORY.DS.$image);

            redirect("index.php?users");
        } else {
            set_message("All fields must be filled!");
        }
    }
}

function display_reports() {
    $query = query("SELECT * FROM reports");
    confirm($query);
    while($row = fetch_array($query)) {
        $report = <<<DELIMETER
        <tr>
            <td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['order_id']}</td>
            <td>$ {$row['product_price']}</td>
            <td>{$row['product_title']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class='btn btn-danger' href='../../resources/templates/back/delete_report.php?id={$row['report_id']}'>Delete</a></td>
        </tr>
        DELIMETER;
        echo $report;
    }
}

function get_slides() {
    $query = query("SELECT * FROM slides");
    confirm($query);
    while($row = fetch_array($query)) {
        $path = display_image($row['slide_image']);
        $slide = <<<DELIMETER
        <div class="item">
            <img class="slide-image" src="../resources/{$path}" width="800px" height="30px" alt="">
        </div>
        DELIMETER;
        echo $slide;
    }
}

function get_active_slide() {
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while($row = fetch_array($query)) {
        $path = display_image($row['slide_image']);
        $slide = <<<DELIMETER
        <div class="item active">
            <img class="slide-image" src="../resources/{$path}" width="800px" height="300px" alt="">
        </div>
        DELIMETER;
        echo $slide;
    }
}

function add_slides() {
    if(isset($_POST['add_slide'])) {
        $slide_title = $_POST['slide_title'];
        $slide_image = $_FILES['file']['name'];
        $slide_image_tmp = $_FILES['file']['tmp_name'];

        if(empty($slide_title) || empty($slide_image)) {
            echo "<p class='bg-danger'>This field must be filled!</p>";
        } else {
            move_uploaded_file($slide_image_tmp, UPLOAD_DIRECTORY.DS.$slide_image);
            $query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}', '{$slide_image}')");
            confirm($query);
            set_message("Slide added");
            redirect("index.php?slides");
        }
    }
}

function display_slide_admin() {
   $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
   confirm($query);
   while($row = fetch_array($query)) {
       $path = display_image($row['slide_image']);
       $image = <<<DELIMETER
        <img class='img-responsive' src='../../resources/{$path}' alt=''>
       DELIMETER;
       echo $image;
   }
}

function get_slide_thumbnails() {
    $query = query("SELECT * FROM slides ORDER BY slide_id ASC");
    confirm($query);
    while($row = fetch_array($query)) {
        $path = display_image($row['slide_image']);
        $image_thumb = <<<DELIMETER
         <div class='col-xs-6 col-md-3 thumbnail_target'>
            <a href='../../resources/templates/back/delete_slides.php?id={$row['slide_id']}'>
                <img class='img-responsive slide_image' src='../../resources/{$path}'>
            </a>
         </div>
        DELIMETER;
        echo $image_thumb;
    }
}
?>