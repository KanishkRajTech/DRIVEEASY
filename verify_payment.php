<?php
// File: verify-payment.php
// Verifies the Razorpay payment and completes the booking process

// Include necessary files
require_once './config/dbcon.php';
require_once 'payment_gateway.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user/user_login.php");
    exit();
}

// Check if payment parameters are received
if (!isset($_GET['payment_id']) || !isset($_GET['order_id']) || !isset($_GET['signature'])) {
    $_SESSION['payment_error'] = "Invalid payment response. Please try again.";
    header("Location: index.php");
    exit();
}

// Get payment details
$paymentId = $_GET['payment_id'];
$orderId = $_GET['order_id'];
$signature = $_GET['signature'];

// Verify payment signature
$isValid = verifyRazorpayPayment($paymentId, $orderId, $signature);

if (!$isValid) {
    $_SESSION['payment_error'] = "Payment verification failed. Please contact support.";
    header("Location: index.php");
    exit();
}

// Save booking details to database
$bookingId = saveBookingDetails($paymentId, $orderId);

if (!$bookingId) {
    $_SESSION['payment_error'] = "Payment was successful, but booking failed to save. Please contact support.";
    header("Location: index.php");
    exit();
}

// Clear booking session data
unset($_SESSION['booking_vehicle_id']);
unset($_SESSION['booking_start_date']);
unset($_SESSION['booking_end_date']);
unset($_SESSION['booking_amount']);
unset($_SESSION['booking_dealer_id']);

// Set success message
$_SESSION['payment_success'] = true;
$_SESSION['booking_id'] = $bookingId;

// Redirect to booking confirmation page
header("Location: booking-confirmation.php?id=" . $bookingId);
exit();
?>