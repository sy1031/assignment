<?php
include 'config/function.php';
$order_id = $_GET['order_id'];
require __DIR__ . "/vendor/autoload.php";

// Stripe Secret Key
$stripe_secret_key = "sk_test_51KA3yrDKdTwGg1g3k6jPrfkpvZ7P4QdfLoLQQrKbaHptXbr1mDIyTwt3eb9yHHPt6laaweaIQwTxrkTk3Mqex8al000djCqimv";

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Check if the cart data is available in the session
if (isset ($_SESSION['cart'])) {
    $cart_data = $_SESSION['cart'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                padding: 50px 0;
            }


            .card {
                border: none;
                border-radius: 10px;
                box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
                padding: 20px;
                background-color: #fff;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table th,
            .table td {
                padding: 10px;
                border-bottom: 1px solid #dee2e6;
            }

            .table th {
                background-color: #f8f9fa;
            }

            .total {
                font-weight: bold;
                font-size: 18px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="card">
                <!-- Delivery Address Form -->
                <!-- <div class="delivery-address mt-5">
                    <h2>Delivery Address</h2>
                    <form action="checkout.php" method="post">
                        <div class="form-group">
                            <label for="delivery_address">Delivery Address</label>
                            <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3"
                                placeholder="Enter your delivery address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit_address">Save Address</button>
                    </form>
                </div> -->

                <br>
                <h1 class="mb-4">Order Summary</h1>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_amount = 0;
                            foreach ($cart_data as $item) {
                                $total = $item['productPrice'] * $item['quantity'];
                                $total_amount += $total;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $item['productName']; ?>
                                    </td>
                                    <td>
                                        <img src="images/<?php echo $item['productImage'] ?>"
                                            style="width: 200px; height: auto;">
                                    </td>
                                    <td>RM
                                        <?php echo $item['productPrice']; ?>
                                    </td>
                                    <td>
                                        <?php echo $item['quantity']; ?>
                                    </td>
                                    <td>RM
                                        <?php echo number_format($total, 2); ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="total mt-4">
                    <span>Total Amount: RM
                        <?php echo number_format($total_amount, 2); ?>
                    </span>
                </div>

                <!-- Payment options -->
                <h2 class="mt-5">Payment Options</h2>
                <div id="payment-section">
                    <!-- Stripe checkout button -->
                    <a href="checkout_stripe.php?order_id=<?php echo $order_id; ?>">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </body>

    </html>

    <script>
    document.getElementById('submit-stripe').addEventListener('click', function() {
        // Redirect to the checkout page
        window.location.href = 'checkout_stripe.php';
    });
</script>

    <?php
    // // Create a Stripe Checkout Session
    // $checkout_session = \Stripe\Checkout\Session::create([
    //     "mode" => "payment",
    //     "success_url" => "http://localhost:8080/Assignment_server-side/assignment/success.php?order_id=$order_id",
    //     "cancel_url" => "http://localhost:8080/Assignment_server-side/assignment",
    //     "line_items" => [
    //         [
    //             "quantity" => 1,
    //             "price_data" => [
    //                 "currency" => 'MYR',
    //                 "unit_amount" => $total_amount * 100, // Assuming $total_amount is in MYR
    //                 "product_data" => [
    //                     "name" => "Order #$order_id", // Change this to dynamically fetch the order name
    //                 ]
    //             ]
    //         ]
    //     ]
    // ]);

    // Create a Stripe Checkout Session
    // $checkout_session = \Stripe\Checkout\Session::create([
    //     "payment_method_types" => ["card"],
    //     "line_items" => [
    //         [
    //             "price_data" => [
    //                 "currency" => "MYR",
    //                 "unit_amount" => $total_amount * 100, // Stripe requires the amount in cents
    //                 "product_data" => [
    //                     "name" => "Your Product Name", // Change this to dynamically fetch product name
    //                 ],
    //             ],
    //             "quantity" => 1,
    //         ],
    //     ],
    //     "mode" => "payment",
    //     "success_url" => "http://localhost:8080/assignment_paymentmodule/success.php?order_id={$order_id}",
    //     "cancel_url" => "http://localhost:8080/assignment_paymentmodule/index.php",
    // ]);

    // Redirect the user to the checkout page URL
    // http_response_code(303);
    // header("Location:" . $checkout_session->url);
    // exit; // Ensure script execution stops after redirection

    // Store the session ID in a variable
    //$session_id = $checkout_session->id;

    // $checkout_session = \Stripe\Checkout\Session::create([
    //     "mode" => "payment",
    //     "success_url" => "http://localhost:8080/assignment_paymentmodule/success.php",
    //     "cancel_url" => "http://localhost:8080/assignment_paymentmodule/index.php",
    //     "line_items" => [
    //         [
    //             "quantity" => 1,
    //             "price_data" => [
    //                 "currency" => 'MYR',
    //                 "unit_amount" => 2000,
    //                 "product_data" => [
    //                     "name" => "T-shirt"
    //                 ]
    //             ]
    //         ]

    //     ]

    // ]);

} else {
    // If cart data is not available, display a message
    echo "Cart is empty.";
}
?>