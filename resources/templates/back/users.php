<div class="col-lg-12">
    <h1 class="page-header">
        Users
    </h1>
        <p class="bg-success" style="width: 13%;">
        <?php display_message(); ?>
    </p>
    <a href="index.php?add_user" class="btn btn-primary">Add User</a>
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Photo</th>
                    <th>Username</th>
                    <th>E-mail</th>
                    <th>First Name</th>
                    <th>Last Name </th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php display_users(); ?>
            </tbody>
        </table> 
    </div>
</div>