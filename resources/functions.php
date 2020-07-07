<?php 

use Mailgun\Mailgun;
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

function count_products() {
    $query = query("SELECT * FROM products");
    return mysqli_num_rows($query);
}

function clean($string) {
    return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
 }

function get_products() {
    global $username;

    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $per_page = 9;
    $start_from = ($page-1) * $per_page;

    $sql = "SELECT * FROM products LIMIT {$start_from}, {$per_page}";
    $query = query($sql);
    $product = "<div class='row'>";
    while($row = fetch_array($query)) {
        $path = display_image($row['product_image']);
        $description = substr($row['product_description'], 0, 80) . "...";
        $product .= "
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
    }
    $product .= "</div>";
    echo $product; 
    
    echo "<div class='pagination'>";
    $count = count_products();
    $num_pages = ceil($count / $per_page);

    if($page > 1) {
        echo "<a href='index.php?page=".($page-1)."' class='next_prev'>Previous</a>";
    } else {
        echo "Page";
    }

    $tmp = array();
    for($i=1; $i<=$num_pages; $i++) {
        if($page == $i) {
            $tmp[] = "<b id='bolded'>{$i}</b>";
        } else {
            $tmp[] = "<a href='index.php?page=".$i."' class='pagination_btn'>{$i}</a>";
        }
    }

    $lastlink = 0;
    foreach($tmp as $i => $link) {
      if($i > $lastlink + 1) {
        echo " ... ";
      } elseif($i) {
        echo " | ";
      }
      echo $link;
      $lastlink = $i;
    }

    if($num_pages > $page) {
        echo "<a href='index.php?page=".($page+1)."' class='next_prev'>Next</a>";
    }
    echo "</div>";
}

function get_categories_man() {
    $select_category = query("SELECT * FROM warderobes WHERE gender_id = 1");
    $categories = "";
    while($row = mysqli_fetch_assoc($select_category)) {
        $categories .= "<ul class='list-group cat_man'>{$row['title']}</a>";
        $select_brand_name = query("SELECT brand_name FROM brands WHERE warderobe_id={$row['id']}");
        while($row2 = mysqli_fetch_array($select_brand_name)){
            $categories .= "<li class='brand_man'><a class='brand_link' href='shop.php?brand={$row2['brand_name']}&category={$row['title']}&gender=1'>{$row2['brand_name']}</a></li>";
        }
        $categories .= "</ul>";
    }
    echo $categories;
}

function get_categories_woman() {
    $select_category = query("SELECT * FROM warderobes WHERE gender_id = 2");
    $categories = "";
    while($row = fetch_array($select_category)) {
        $categories .= "<ul class='list-group cat_man'>{$row['title']}";
        $select_brand_name = query("SELECT brand_name FROM brands WHERE warderobe_id = {$row['id']}");
        while($row2 = fetch_array($select_brand_name)) {
            $categories .= "<li class='brand_man'><a class='brand_link' href='shop.php?brand={$row2['brand_name']}&category={$row['title']}&gender=2'>{$row2['brand_name']}</a></li>";
        }
         $categories .= "</ul>";
    }
    echo $categories;
}

function get_category_products() {
    global $connection;
    $sql = "SELECT * FROM products WHERE product_warderobe_id = " . mysqli_real_escape_string($connection, $_GET['id']);
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
    global $username;

    if(isset($_GET['brand']) && isset($_GET['category'])) {
        if($_GET['gender'] == 1) {
            $select_warderobe = query("SELECT id FROM warderobes WHERE title = '{$_GET['category']}' AND gender_id = 1");
            $row = fetch_array($select_warderobe);
            $warderobe_id = $row['id'];
            $select_brand = query("SELECT id FROM brands WHERE brand_name = '{$_GET['brand']}' AND warderobe_id = {$warderobe_id}");
            $row2 = fetch_array($select_brand);
            $brand_id = $row2['id'];
        } else {
            $select_warderobe = query("SELECT id FROM warderobes WHERE title = '{$_GET['category']}' AND gender_id = 2");
            $row = fetch_array($select_warderobe);
            $warderobe_id = $row['id'];
            $select_brand = query("SELECT id FROM brands WHERE brand_name = '{$_GET['brand']}' AND warderobe_id = {$warderobe_id}");
            $row2 = fetch_array($select_brand);
            $brand_id = $row2['id'];
        }
    } else {
        echo "<h2>Nothing Found!</h2>";
        return;
    }
    $select_products = query("SELECT * FROM products WHERE product_brand_id = {$brand_id}");
    while($row3 = fetch_array($select_products)){
        $path = display_image($row3['product_image']);
        $product = "
        <div class='col-md-3 col-sm-6 hero-feature'>
        <div class='thumbnail'>
            <img src='../resources/{$path}' alt=''>
            <div class='caption'>
                <h3 style='font-size:18px;'><b>{$row3['product_title']}</b></h3>
                <p>{$row3['short_desc']}</p>
                <p>";
                if(isset($username)) {
                    $product .= "<a href='../resources/cart.php?add={$row3['product_id']}' class='btn btn-primary'>Buy Now!</a>"; 
                }
                $product .= "    
                    <a href='item.php?id={$row3['product_id']}' class='btn btn-default'>More Info</a>
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
        $mail->Username = getenv('APP_USER');
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
    $query = query("SELECT * FROM warderobes");
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
            <td>{$row['order_currency']}</td>
            <td>{$row['order_status']}</td>
            <td><a class='btn btn-danger' href='index.php?delete_order&id={$row['order_id']}'><span class='glyphicon glyphicon-remove-sign'></span></a></td>
        </tr>
        DELIMETER;
        echo $order;
    }
}

function display_products_in_admin() {
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $per_page = 5;
    $start_from = ($page-1) * $per_page;
    $query = query("SELECT * FROM products LIMIT {$start_from}, {$per_page}");
    confirm($query);
    while($row = fetch_array($query)) {
        $category_title = display_product_category_title($row['product_brand_id']);
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
            <td><a class='btn btn-danger' href='index.php?delete_product&id={$row['product_id']}'>Delete</a></td>
        </tr>
        DELIMETER;
        echo $product;
    }
    echo "<div class='pagination'>";
        $count = count_products();
        $num_pages = ceil($count / $per_page);

        if($page > 1) {
            echo "<a href='index.php?products&page=".($page-1)."' class='next_prev'>Previous</a>";
        } else {
            echo "Page";
        }

        $tmp = array();
        for($i=1; $i<=$num_pages; $i++) {
            if($page == $i) {
                $tmp[] = "<b id='bolded'>{$i}</b>";
            } else {
                $tmp[] = "<a href='index.php?products&page=".$i."' class='pagination_btn'>{$i}</a>";
            }
        }

        $lastlink = 0;
        foreach($tmp as $i => $link) {
        if($i > $lastlink + 1) {
            echo " ... ";
        } elseif($i) {
            echo " | ";
        }
        echo $link;
        $lastlink = $i;
        }

        if($num_pages > $page) {
            echo "<a href='index.php?products&page=".($page+1)."' class='next_prev'>Next</a>";
        }
    echo "</div>";
}

function get_add_product_warderobes() {
    $query = query("SELECT * FROM warderobes");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_gender = query("SELECT * FROM genders WHERE id = {$row['gender_id']}");
        confirm($get_gender);
        while($row2 = fetch_array($get_gender)) {
            $category = <<<DELIMETER
            <option value={$row['id']}>{$row['title']}, {$row2['gender']}</option>
            DELIMETER;
        }
        echo $category;
    }
}

function get_edit_product_categories($id) {
    $query = query("SELECT * FROM warderobes WHERE id != {$id}");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_edit_gender = query("SELECT * FROM genders WHERE id = {$row['gender_id']}");
        confirm($get_edit_gender);
        while($row2 = fetch_array($get_edit_gender)) {
            $gender = $row2['gender'];
        }
        echo "<option value={$row['id']}>{$row['title']}, {$gender}</option>";
    }
}

function get_add_product_brands() {
    $query = query("SELECT * FROM brands");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_warderobes = query("SELECT * FROM warderobes WHERE id = {$row['warderobe_id']}");
        confirm($get_warderobes);
        while($row2 = fetch_array($get_warderobes)) {
            $title = $row2['title'];
            $get_gender = query("SELECT * FROM genders WHERE id = {$row2['gender_id']}");
            confirm($get_gender);
            while($row3 = fetch_array($get_gender)) {
                $gender = $row3['gender'];
            }
        }
        $brand = "<option value={$row['id']}> {$row['brand_name']}, {$title}, {$gender} </option>";
        echo $brand;
    }
}

function get_edit_product_brands($brand_id) {
    $query = query("SELECT * FROM brands WHERE id != {$brand_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_warderobes = query("SELECT * FROM warderobes WHERE id = {$row['warderobe_id']}");
        confirm($get_warderobes);
        while($row2 = fetch_array($get_warderobes)) {
            $title = $row2['title'];
            $get_gender = query("SELECT * FROM genders WHERE id = {$row2['gender_id']}");
            confirm($get_gender);
            while($row3 = fetch_array($get_gender)) {
                $gender = $row3['gender'];
            }
        }
        $brand = "<option value={$row['id']}> {$row['brand_name']}, {$title}, {$gender} </option>";
        echo $brand;
    }
}

function add_product() {
    $products = array();
    if(isset($_POST['publish'])) {
        $products[] = $_POST;
        foreach($products as $product) {
            $product_description = clean($product['product_description']);
            $short_desc = substr($product['product_description'], 0, 50);
            $trim_short_desc = clean($short_desc);
        
            $product_image = $_FILES['file']['name'];
            $image_tmp_location = $_FILES['file']['tmp_name'];

            move_uploaded_file($image_tmp_location, UPLOAD_DIRECTORY.DS.$product_image);

        $query = query("INSERT INTO products(product_title, product_brand_id, product_price, product_quantity, product_description, short_desc, product_image, product_keywords) VALUES('{$product['product_title']}', {$product['product_brand']}, {$product['product_price']}, {$product['product_quantity']}, '{$product_description}', '{$trim_short_desc}', '{$product_image}', '{$product['product_keywords']}')");
        confirm($query);
        $last_id = last_id();
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
         $product_description = clean($product['product_description']);
         $short_desc = substr($product['product_description'], 0, 50);
         $trim_short_desc = clean($short_desc);
         $product_image = $_FILES['file']['name'];
         $image_tmp_location = $_FILES['file']['tmp_name'];
        
        if(empty($product_image)) {
            $query = query("SELECT product_image FROM products WHERE product_id = " . $_GET['id']);
            confirm($query);
            $row = fetch_array($query);
            $product_image = $row['product_image'];
        } 
    
        move_uploaded_file($image_tmp_location, UPLOAD_DIRECTORY.DS.$product_image);

    $query = query("UPDATE products SET product_title = '{$product['product_title']}', product_brand_id = {$product['product_brand']}, product_description = '{$product_description}', product_price = {$product['product_price']}, product_quantity = {$product['product_quantity']}, short_desc = '{$trim_short_desc}', product_image = '{$product_image}', product_keywords = '{$product['product_keywords']}' WHERE product_id = " . $_GET['id']);
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
    $query = query("SELECT * FROM brands WHERE id = {$product_category_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_category = query("SELECT * FROM warderobes WHERE id = {$row['warderobe_id']}");
        confirm($get_category);
        while($row2 = fetch_array($get_category)) {
            $title = $row2['title'];
            $get_gender = query("SELECT * FROM genders WHERE id = {$row2['gender_id']}");
            confirm($query);
            while($row3 = fetch_array($get_gender)) {
                $gender = $row3['gender'];
            }
        }
        return $title . ", " . $gender;
    }
}

function display_brand_title($brand_id) {
    $query = query("SELECT * FROM brands WHERE id = {$brand_id}");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_warderobes = query("SELECT * FROM warderobes WHERE id = {$row['warderobe_id']}");
        confirm($get_warderobes);
        while($row2 = fetch_array($get_warderobes)) {
            $title = $row2['title'];
            $get_gender = query("SELECT * FROM genders WHERE id = {$row2['gender_id']}");
            confirm($get_gender);
            while($row3 = fetch_array($get_gender)) {
                $gender = $row3['gender'];
            }
        }
        return $row['brand_name'] . ", " . $title . ", " . $gender;
    }
}

function display_categories() {
    $query = query("SELECT * FROM warderobes");
    confirm($query);
    while($row = fetch_array($query)) {
            $category = "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>";
                if($row['gender_id'] == 1) {
                    $category .= "<td>Muske</td>";
                } else {
                    $category .= "<td>Zenske</td>";
                }
                $category .= "<td><a class='btn btn-danger' href='index.php?delete_category&id={$row['id']}'>Delete</a></td>
            </tr>";
            echo $category;
    }
}

function display_brands() {
    $query = query("SELECT * FROM brands");
    confirm($query);
    while($row = fetch_array($query)) {
        $get_category = query("SELECT * FROM warderobes WHERE id = {$row['warderobe_id']}");
        confirm($get_category);
        while($row2 = fetch_array($get_category)) {
            $title = $row2['title'];
            $get_gender = query("SELECT * FROM genders WHERE id = {$row2['gender_id']}");
            confirm($get_gender);
            while($row3 = fetch_array($get_gender)) {
                $gender = $row3['gender'];
            }
        }
        $brand = "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['brand_name']}</td>
            <td>{$title}, {$gender}</td>
            <td><a class='btn btn-danger' href='index.php?delete_brand&id={$row['id']}'>Delete</a></td>
        </tr>";
        echo $brand;
    }
}

function create_category() {
    if(isset($_POST['submit'])) {
        if(!empty($_POST['title'])) {
            $title = $_POST['title'];
            $gender = $_POST['gender_category'];
            $query = query("INSERT INTO warderobes(title, gender_id) VALUE('{$title}', {$gender})");
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
            $category = $_POST['brand_category'];
            $query = query("INSERT INTO brands(brand_name, warderobe_id) VALUE('{$name}', {$category})");
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
            <td>{$row['product_price']}</td>
            <td>{$row['product_title']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class='btn btn-danger' href='index.php?delete_report&id={$row['report_id']}'>Delete</a></td>
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
            <a href='index.php?delete_slide&id={$row['slide_id']}'>
                <img class='img-responsive slide_image' src='../../resources/{$path}'>
            </a>
         </div>
        DELIMETER;
        echo $image_thumb;
    }
}

function list_transactions() {
    $query = query("SELECT * FROM orders");
    confirm($query);
    while($row = fetch_array($query)) {
        $date = $row['order_time'];
        $split = explode(" ", $date);
        $orders = "<tr>
                      <td>{$row['order_id']}</td> 
                      <td>{$split[0]}</td>
                      <td>{$split[1]}</td>
                      <td>{$row['order_amount']}</td>
                      <td>{$row['order_status']}</td>
                   </tr>";

        echo $orders;
    }
}

function list_report_transactions() {
    $query = query("SELECT * FROM reports");
    confirm($query);
    while($row = fetch_array($query)) {
        $reports = "<tr>
                      <td>{$row['report_id']}</td>
                      <td>{$row['order_id']}</td>
                      <td>{$row['product_title']}</td>
                      <td>{$row['product_price']}</td>
                      <td>{$row['product_quantity']}</td>  
                    </tr>";

        echo $reports;
    }
}
?>