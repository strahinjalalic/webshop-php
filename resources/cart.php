<?php require_once('config.php');

\Stripe\Stripe::setApiKey(getenv('STRIPE_SK'));
\Stripe\Stripe::setVerifySslCerts(false);

if(isset($_GET['add'])) {
    $query = query('SELECT * FROM products WHERE product_id = ' . $_GET['add']);
    confirm($query);
    while($row = fetch_array($query)) {
        if($row['product_quantity'] > $_SESSION['product_' . $_GET['add']]) {
            $_SESSION['product_' . $_GET['add']] += 1;
            redirect('../public/checkout.php');
        } else {
            set_message('We have ' . $row['product_quantity'] . ' ' . $row['product_title'] . ' available!');
            redirect('../public/checkout.php');
        }
    }
}

if(isset($_GET['remove'])) {
    $_SESSION['product_' . $_GET['remove']] --;
    redirect('../public/checkout.php');
    if($_SESSION['product_' . $_GET['remove']] < 1) {
        $_SESSION['product_' . $_GET['remove']] = '0';
        unset($_SESSION['total_amount']);
        unset($_SESSION['total_items']);
        redirect('../public/checkout.php');
    } 
}

if(isset($_GET['delete'])) {
    $_SESSION['product_' . $_GET['delete']] = '0';
    unset($_SESSION['total_amount']);
    unset($_SESSION['total_items']);
    redirect('../public/checkout.php');
}

function cart() {
  $total = 0;
  $total_items = 0;
  $item_name = 1;
  $item_number = 1;
  $amount = 1;
  $quantity = 1;
  foreach($_SESSION as $name => $value) {
     if($value > 0) {
        if(substr($name, 0, 8) == 'product_') {
            $length = strlen($name); 
            $id = substr($name, 8, $length);
            $query = query('SELECT * FROM products WHERE product_id = '. $id);
            confirm($query);
            while($row = fetch_array($query)) {
                $path = display_image($row['product_image']);
                $sub_total = $row['product_price'] * $value;
                $product = <<<DELIMETER
                  <tr>
                    <td><b>{$row['product_title']}</b><br>
                      <img src='../resources/{$path}' width='100' />
                    <td>$ {$row['product_price']}</td>
                    <td>{$value}</td>
                    <td>$ {$sub_total}</td>
                    <td><a class='btn btn-warning' href="../resources/cart.php?remove={$row['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>  <a class='btn btn-success' href="../resources/cart.php?add={$row['product_id']}"><span class='glyphicon glyphicon-plus'></span></a>  <a class='btn btn-danger' href="../resources/cart.php?delete={$row['product_id']}"><span class='glyphicon glyphicon-remove'></span></a></td>
                  </tr>
                  <input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}"> 
                  <input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
                  <input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
                  <input type="hidden" name="quantity_{$quantity}" value="{$value}">
                DELIMETER;
                echo $product;

                $item_name++;
                $item_number++;
                $amount++;
                $quantity++;
            }
            $_SESSION['total_amount'] = $total += $sub_total; //ceo racun
            $_SESSION['total_items'] = $total_items += $value; //ukupno item-a iz cart-a
        }
    } 
  }
}

function stripe() {
    $paypal_button = <<<DELIMETER
    <input type="image" name="upload" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
   DELIMETER;

  if(isset($_SESSION['total_items']) && $_SESSION['total_items'] >= 1)   {
    echo $paypal_button;
    foreach($_SESSION as $product => $value) {
        if(substr($product, 0, 8) == 'product_') {
          $length = strlen($product); 
          $id = substr($product, 8, $length);
          $query = query("SELECT * FROM products WHERE product_id = {$id}");
          while($row = fetch_array($query)) {
              $product = \Stripe\Product::create([
                'name' => "{$row['product_title']}",
              ]);
        
              $price = \Stripe\Price::create([
                'product' => "{$product['id']}",
                'unit_amount' => "{$row['product_price']}",
                'currency' => 'eur'
              ]);
        
              $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                  'price' => "{$price['id']}",
                  'quantity' => "{$value}",
                ]],
                'mode' => 'payment',
                'success_url' => "http://localhost/e-com-master/public/thank_you.php",
                'cancel_url' => 'http://localhost/e-com-master/public/index.php',
              ]);
              
              return $session['id'];
          }
        }
    }
  }  
}

function report() {
  if(isset($_GET['tx'])) { //paypal get parametri
    $amount = $_GET['am'];
    $status = $_GET['st'];
    $currency = $_GET['cc'];
    $transaction = $_GET['tx'];

  $total = 0;
  $total_items = 0;
  foreach($_SESSION as $name => $value) {
     if($value > 0) {
        if(substr($name, 0, 8) == 'product_') {
            $length = strlen($name - 8);
            $id = substr($name, 8, $length);

            $insert_order = query("INSERT INTO orders(order_amount, order_transaction, order_currency, order_status) VALUES('{$amount}', '{$transaction}', '{$currency}', '{$status}')");
            $last_id = last_id();
            confirm($insert_order);

            $query = query('SELECT * FROM products WHERE product_id = '. $id);
            confirm($query);
            while($row = fetch_array($query)) {
                $product_price = $row['product_price'];
                $product_title = $row['product_title'];
                $sub_total = $product_price * $value;
                $total_items += $value;

                $insert_report = query("INSERT INTO reports(order_id, product_id, product_title, product_price, product_quantity) VALUES('{$last_id}', '{$id}', '{$product_title}', '{$product_price}', '{$value}')");
                confirm($insert_report);
            }
            $total += $sub_total;
            // echo $total_items;
        }
    } 
  }
  // session_destroy(); => naci bolji nacin za destroy samo product_ sessiona
} else {
  redirect("index.php");
}
}

?>