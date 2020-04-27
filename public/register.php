<?php include("../resources/config.php"); 
include("./handle_register_login.php");

?>

<?php include(TEMPLATE_FRONT . DS . "header.php");?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading">Register</h2>
                <h3 class="section-subheading text-muted"></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form name="sentMessage" id="contactForm" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" required data-validation-required-message="Please enter your name.">
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Please enter valid name.", $reg_errors) ? "Please enter valid name." : ""; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required data-validation-required-message="Please enter your last name.">
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Please enter valid last name.", $reg_errors) ? "Please enter valid last name." : ""; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?php echo isset($username) ? $username : '' ; ?>" required data-validation-required-message="Please enter your username.">
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Username can contain between 3 and 20 standard characters.", $reg_errors) ? "Username can contain between 3 and 20 standard characters." : "";
                                    echo in_array("Username need to contain only standard set of characters.", $reg_errors) ? "Username need to contain only standard set of characters." : "";
                                    echo in_array("Username already exists.", $reg_errors) ? "Username already exists." : ""; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter E-mail" value="<?php echo isset($email) ? $email : ''; ?>" required data-validation-required-message="Please enter your e-mail.">
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Enter valid e-mail address.", $reg_errors) ? "Enter valid e-mail address." : "";
                                    echo in_array("E-mail already exists.", $reg_errors) ? "E-mail already exists." : ""; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="passsword">Password</label><span id="suggest_password"><sub>Suggest password</sub></span>
                                <input type="password" name="password" id="password_field" class="form-control" placeholder="Enter Password" required data-validation-required-message="Please enter your password.">
                                <span class="field-icon"><i toggle="#password_field" class="far fa-eye"></i></span>
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Password need to have at least 8 characters.", $reg_errors) ? "Password need to have at least 8 characters." : ""; ?></p>
                            </div>
                            <div class="form-group">
                                <label for="pasword_repeat">Repeat password</label>
                                <input type="password" name="password_repeat" id="password_repeat_field" class="form-control" placeholder="Repeat Password" required data-validation-required-message="Please repeat your password.">
                                <span class="field-icon"><i toggle="#password_repeat_field" class="far fa-eye"></i></span>
                                <p style="color:red;" class="help-block text-danger"><?php echo in_array("Passwords needs to match.", $reg_errors) ? "Passwords needs to match." : "";  ?></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 text-center">
                            <div id="success"></div>
                            <button type="submit" name="register_user" class="btn btn-xl">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include(TEMPLATE_FRONT . DS . "footer.php");?>