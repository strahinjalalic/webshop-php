<?php
include("../vendor/autoload.php");

$options = array(
    'cluster' => 'eu',
    'useTLS' => true
);

$pusher = new Pusher\Pusher(
    '167c9c214517f32c90ce',
    '296a6866df24ff130298',
    '988007',
    $options
  );

$reg_errors = array();
if(isset($_POST['register_user'])) {
    global $connection;

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['password_repeat'];

    $user_image = "user_default.png";

    if(empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($repeat_password)) {
        array_push($reg_errors, "Please fill out all fields.");
    }

    if(strlen($first_name) < 2) {
        array_push($reg_errors, "Please enter valid name.");
    }

    if(strlen($last_name) < 2) {
        array_push($reg_errors, "Please enter valid last name.");
    }

    if(strlen($username) < 3 && strlen($username) > 20) {
        array_push($reg_errors, "Username can contain between 3 and 20 standard characters.");
    }

    $check_username_exists = query("SELECT username FROM users WHERE username = '{$username}'");
    if(mysqli_num_rows($check_username_exists) == 1) {
        array_push($reg_errors, "Username already exists.");
    }

    if(ctype_alnum($username)) {
        $username = preg_replace('/[^A-Za-z0-9\-]/', '', $username);
    } else {
        array_push($reg_errors, "Username need to contain only standard set of characters.");
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($reg_errors, "Enter valid e-mail address.");
    }

    $check_email_exist_query = query("SELECT email FROM users WHERE email = '{$email}'");
    if(mysqli_num_rows($check_email_exist_query) == 1) {
        array_push($reg_errors, "E-mail already exists.");
    }

    if(strlen($password) < 8) {
        array_push($reg_errors, "Password need to have at least 8 characters.");
    }

    if($password != $repeat_password) {
        array_push($reg_errors, "Passwords needs to match.");
    } 

    if(empty($reg_errors)) {
        $hashed_pasword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        $insert_user = query("INSERT INTO users(username, first_name, last_name, email, password, user_image) VALUES('{$username}', '{$first_name}', '{$last_name}', '{$email}', '{$hashed_pasword}', '{$user_image}')");
        $data['message'] = $username;
        $pusher->trigger('notifications', 'new_user', $data);
        set_message("Registration complete! You can log in now.");
        redirect("index.php");
    }
}

$login_errors = array();
if(isset($_POST['submit_login'])){
        if(!empty($_POST['username_email']) && !empty($_POST['password'])) {
        $username_email = trim($_POST['username_email']);
        $password = trim($_POST['password']);

        $sql = "SELECT * FROM users WHERE username = '{$username_email}' OR email = '{$username_email}'";
        $send_query = query($sql);
        confirm($send_query);
        if($row = fetch_array($send_query)) {
            $verify_pass = password_verify($password, $row['password']);
            if($verify_pass){
                $_SESSION['username'] = $row['username'];
                redirect('index.php');
            } else {
                array_push($login_errors, 'Username or Password is incorrect!');    
            }
        } else {
            array_push($login_errors, 'Username or Password is incorrect.');    
        } 
        } else if(!empty($_POST['username_email']) && empty($_POST['password'])) {
            $username_email = trim($_POST['username_email']);
            array_push($login_errors, 'Enter password!');
        } else {
        array_push($login_errors, 'Enter username or email!');
        }
} ?>