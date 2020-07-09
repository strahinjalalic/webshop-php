<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");
?>

<script src="https://js.stripe.com/v3/"></script>
<div class="container">
    <div class="row">
      <h1>Cart</h1>
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
      <?php if(isset($_SESSION['total_items']) && $_SESSION['total_items'] >= 1) { ?>
        <input type="image" name="checkout" id="checkout-button" src="../resources/uploads/buy_now.png" style="height: 70px;" />
      <?php }  $session = stripe_checkout(); ?> 
        <script>
          var checkoutButton = document.getElementById('checkout-button');
          var stripe = Stripe('pk_test_3U9OiFBxvIT0pYAFdOQ8WNTr008zbgMl80');

          checkoutButton.addEventListener('click', function() {
            stripe.redirectToCheckout({
            sessionId: "<?php echo $session['id']; ?>"
              }).then(function(result) {
                console.log(result.error.message);
              });
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