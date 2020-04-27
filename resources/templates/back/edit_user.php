<?php //include("../../config.php"); 
$users = array();
if(isset($_GET['id'])) {
    $query = query("SELECT * FROM users WHERE user_id = {$_GET['id']}");
    confirm($query);
    $users[] = $row = fetch_array($query);
    foreach($users as $user) {
        $path = "uploads/user_placeholder/" . $user['user_image'];
 ?>

<div class="col-md-12">
<div class="row">
<h1 class="page-header">
   Edit User
</h1>
</div>
<form action="" method="post" enctype="multipart/form-data">
    <div class="col-md-8">
        <div class="form-group">
            <label for="first_name">First Name </label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" disabled>
        </div>
        <div class="form-group">
           <label for="last_name">Last Name</label>
           <input type="text" name="last_name" id="" class="form-control" value="<?php echo $user['last_name']; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" disabled>
        </div>
    </div>

<aside id="admin_sidebar" class="col-md-4">
     <div class="form-group">
        <input type="submit" name="update" class="btn btn-primary btn-lg" value="Update">
    </div>
    <div class="form-group">
         <label for="is_admin">Admin</label>
         <select name="is_admin" id="" class="form-control">
            <option value="<?php echo $user['is_admin']; ?>"><?php echo $user['is_admin']; ?></option>
            <?php if($user['is_admin'] == 'yes') { ?>
                <option value="no"> no </option>
            <?php  } else { ?>
                <option value="yes"> yes </option>
        <?php  } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="product-title">User Image</label>
        <input type="file" name="file" disabled>
        <img src="<?php echo "../../resources/{$path}" ?>" width="200px" alt="">
    </div>
</aside>  
</form>
<?php  edit_user_admin(); } }?>
</div>