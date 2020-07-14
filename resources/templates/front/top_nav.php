<?php include("./handle_register_login.php");
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = null;
} ?>
<div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="nav_li">
                    <li>
                        <a href="contact.php" class="nav_a">Contact</a>
                    </li>
                    <?php if(is_admin($username)) {?>
                    <li>
                        <a href="admin" class="nav_a">Admin</a>
                    </li>
                    </ul>
                    
                    <?php } if(isset($username)) { ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="checkout.php" id="cart"><img src="../resources/uploads/cart.png" width="23px" height="23px" alt=""></a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#" class="dropdown-toggle" id="front_logout" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $username; ?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/e-com-master/public/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <?php } else { ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <button id='popup'>Login</button>
                            </li>
                            <li>
                                <a href="register.php">Register</a>
                            </li>
                        </ul>
                        <?php } ?>
            </div>
        </div>
        <div id="body" style="overflow:hidden;">
            <div id="abc">
                <div id="popupLogin">
                    <form action="" id="form_login" method="POST" name="form">
                        <img id="close" src="http://icon-library.com/images/close-icon/close-icon-5.jpg" height="22" width="22">
                        <h2 id="login_h2">Login form</h2>
                        <hr id='popup_form'>
                        <input id="username" name="username" placeholder="Username or Email" type="text" value="<?php echo isset($username_email) ? $username_email : ""; ?>"><span id="email_icon"><i class="fas fa-envelope"></i></span>
                        <input id="password" name="password" placeholder="Password" type="password"><span id="login_icons"><i class="fas fa-user-tie"></i></span>
                        <p id='display_login_msg'><?php echo in_array("Username or Password is incorrect.", $login_errors) ? "Username or Password is incorrect." : "";
                        echo in_array("Username or Password is incorrect!", $login_errors) ? "Username or Password is incorrect!" : "";
                        echo in_array("Enter password!", $login_errors) ? "Enter password!" : "";
                        echo in_array("Enter username or email!", $login_errors) ? "Enter username or email!" : ""; ?></p>
                        <button type="submit" id="submit_login" name="submit_login">Login</button>
                    </form>
                </div>
            </div>
        </div>