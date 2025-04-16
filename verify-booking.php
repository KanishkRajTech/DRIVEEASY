<?php
session_start();
// Include Razorpay SDK
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Razorpay API Credentials
$keyId = "Enter your keyid here";
$keySecret = "Enter your key secret here";
$api = new Api($keyId, $keySecret);

if (isset($_GET['payment_id']) && isset($_GET['order_id'])) {
    $paymentId = $_GET['payment_id'];
    $orderId = $_GET['order_id'];

    try {
        // Fetch the payment details from Razorpay
        $payment = $api->payment->fetch($paymentId);

        if ($payment->status == 'captured') {
            $payref = $paymentId;
            header("Location: user/my_booking.php");
            exit();
        } else {
            $_SESSION['error_msg'] = "Payment verification failed!";
            header("Location: payment.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_msg'] = "Error: " . $e->getMessage();
        header("Location: payment.php");
        exit();
    }
} else {
    $_SESSION['error_msg'] = "Invalid Payment Attempt!";
    header("Location: payment.php");
    exit();
}
?>
