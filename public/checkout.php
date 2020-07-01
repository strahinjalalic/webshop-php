<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");

?>
<script src="https://js.stripe.com/v3/"></script>
<div class="container">
    <div class="row">
      <h1>Checkout</h1>
    <p class='text-center bg-danger'> <?php display_message(); ?> </p>
      <table class="table table-striped">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Sub-total</th>
            </tr>
          </thead>
          <tbody>
            <?php cart(); ?>
          </tbody>
      </table>
        <?php $session = stripe();?> 
        <script>
          var stripe = Stripe(getenv('STRIPE_PK'));
          stripe.redirectToCheckout({
            sessionId: "<?php echo $session; ?>"
          }).then(function(result) {
            console.log(result.error.message);
          });
        </script>            
        <div class="col-xs-4 pull-right ">
          <h2>Total</h2>
            <table class="table table-bordered" cellspacing="0">
              <tr class="cart-subtotal">
                <th>Items:</th>
                <td><span class="amount"> <?php echo isset($_SESSION['total_items']) ? $_SESSION['total_items'] : $_SESSION['total_items'] = '0' ?> </span></td>
              </tr>
              <tr class="shipping">
                <th>Shipping and Handling</th>
                <td>Free Shipping</td>
              </tr>
              <tr class="order-total">
                <th>Order Total</th>
                <td><strong><span class="amount">$ <?php echo isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : $_SESSION['total_amount'] = '0' ?> </span></strong> </td>
              </tr>
            </table>
        </div>
    </div>
</div>
<?php require_once(TEMPLATE_FRONT . DS . 'footer.php'); ?>