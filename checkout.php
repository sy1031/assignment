<?php
include 'config/function.php';
$order_id = $_GET['order_id'];
require __DIR__ . "/vendor/autoload.php";

unset($_SESSION['discounted_total_amount']);
$_SESSION['promotional_code_applied'] = false;

// Initialize promotional code applied flag in session if not set
if (!isset ($_SESSION['promotional_code_applied'])) {
    $_SESSION['promotional_code_applied'] = false;
}

// Initialize total amount if not set or if needed
if (!isset ($_SESSION['total_amount']) || empty ($_SESSION['total_amount'])) {
    $_SESSION['total_amount'] = calculateTotalAmount($_SESSION['cart']); // Define a function to calculate total amount
}

// Stripe Secret Key
$stripe_secret_key = "sk_test_51KA3yrDKdTwGg1g3k6jPrfkpvZ7P4QdfLoLQQrKbaHptXbr1mDIyTwt3eb9yHHPt6laaweaIQwTxrkTk3Mqex8al000djCqimv";

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Initialize total amount
$total_amount = 0;
$discount = 0;
$discounted_total_amount = 0;

// Check if the cart data is available in the session
if (isset ($_SESSION['cart'])) {
    $cart_data = $_SESSION['cart'];

    // Calculate total amount of items in the cart
    $total_amount = calculateTotalAmount($cart_data);

    // Store the original total amount in a session variable
    $_SESSION['total_amount'] = $total_amount;

    // Check if the promotional code form is submitted
   // Check if the promotional code form is submitted
if (isset($_POST['apply_promo_code'])) {
    // Retrieve the promotional code entered by the user
    $promotional_code = $_POST['promotional_code'];

    if (!empty($promotional_code)) {
        // Promotional code is submitted, attempt to apply it
        $sql = "SELECT * FROM promotion WHERE promotion_name = '$promotional_code' AND NOW() BETWEEN start_date AND end_date";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Promotional code is valid, fetch the discount amount
            $row = $result->fetch_assoc();
            $discount = $row['direct_discount'];

            // Calculate the discounted total amount
            $discounted_total_amount = $total_amount - $discount;

            // Store the discounted total amount and flag in session variables
            $_SESSION['discounted_total_amount'] = $discounted_total_amount;
            $_SESSION['promotional_code_applied'] = true;

            // Display the promotional information
            ?>
            <div class="container">
                <div class="alert alert-success" role="alert">
                    Your Promotion Code is successfully applied!
                </div>
            </div>
            <?php
        } else {
            // Invalid promotional code, display error message
            ?>
            <div class="container">
                <div class="alert alert-danger" role="alert">
                    Invalid promotional code.
                </div>
            </div>
            <?php
        }
    } else {
        // If the promotional code form is submitted with an empty field, reset the discounted total amount and the flag
        unset($_SESSION['discounted_total_amount']);
        $_SESSION['promotional_code_applied'] = false;
    }
}
}

// Function to calculate total amount of items in the cart
function calculateTotalAmount($cart_data)
{
    $total_amount = 0;
    foreach ($cart_data as $item) {
        $total_amount += $item['productPrice'] * $item['quantity'];
    }
    return $total_amount;
}

// If the promotional code is not applied, use the original total amount
if (!$_SESSION['promotional_code_applied']) {
    unset($_SESSION['discounted_total_amount']); // Unset the discounted total amount
    $discounted_total_amount = $_SESSION['total_amount'];
    $total_amount = $_SESSION['total_amount'];
}
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

        .buttonLinktoPayment {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: 2px solid #007bff;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .buttonLinktoPayment:hover {
            background-color: #007bff;
            border-color: #0056b3;
            color: white;
        }

        .buttonApplyCode {
            color: white;
            background: #007bff;
        }
    </style>
</head>

<body>
    <!-- Cart display section -->
    <div class="container">
        <div class="card">
            <!-- Cart table -->
            <div class="table-responsive">
                <table class="table">
                    <!-- Table header -->
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
                        <!-- Table body -->
                        <?php
                        foreach ($cart_data as $item) {
                            $total = $item['productPrice'] * $item['quantity'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $item['productName']; ?>
                                </td>
                                <td><img src="images/<?php echo $item['productImage']; ?>"
                                        style="width: 200px; height: auto;"></td>
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
                        <!-- Display total amount and discount in a separate row -->
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right">Total Amount:</td>
                            <td>
                                RM
                                <?php echo number_format($total_amount, 2); ?>
                            </td>
                        </tr>
                        <?php if ($discount > 0): ?>
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-right">Discount:</td>
                                <td>
                                    RM
                                    <?php echo number_format($discount, 2); ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3"></td>
                                <td class="text-right">Discounted Price:</td>
                                <td>
                                    RM
                                    <?php echo number_format($discounted_total_amount, 2); ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <!-- Promotional code form -->
            <form action="checkout.php?order_id=<?php echo $order_id; ?>" method="post">
                <input type="text" name="promotional_code" placeholder="Enter Promotional Code">
                <button type="submit" name="apply_promo_code" class="buttonApplyCode">Apply</button>
            </form>

            <!-- Payment options -->
            <h2 class="mt-5">Payment Options</h2>
            <div id="payment-section">
                <!-- Stripe checkout button -->
                <a href="checkout_stripe.php?order_id=<?php echo $order_id; ?>" class="buttonLinktoPayment">Proceed to
                    Payment Checkout</a>

            </div>

        </div>
    </div>
</body>

</html>