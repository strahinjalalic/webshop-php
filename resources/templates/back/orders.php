<div class="col-md-12">
    <div class="row">
        <h1 class="page-header">
            All Orders
        </h1>
        <p class="bg bg-success"> <?php display_message(); ?> </p>
    </div>
    <div class="row">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Order Amount</th>
                <th>Order Transaction</th>
                <th>Order Currency</th>
                <th>Order Status</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
                <?php display_orders(); ?>
            </tbody>
        </table>
    </div>
</div>