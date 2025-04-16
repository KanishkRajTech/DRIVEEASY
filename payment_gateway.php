<?php
// File: payment_gateway.php
// This file handles the Razorpay payment gateway integration

// Include necessary files
require_once './config/dbcon.php';
require_once 'razorpay-php/Razorpay.php';
use Razorpay\Api\Api;

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to create a Razorpay order
function createRazorpayOrder($amount, $receipt_id) {
    // Load API credentials from a secure configuration file
    // IMPORTANT: Store these in a separate config file that is not accessible via web
    $config = require_once './config/payment_config.php';
    $keyId = $config['razorpay_key_id'];
    $keySecret = $config['razorpay_key_secret'];
    
    // Initialize Razorpay API
    $api = new Api($keyId, $keySecret);
    
    // Convert amount to paise (Razorpay uses smallest currency unit)
    $amountInPaise = $amount * 100;
    
    // Create order data
    $orderData = [
        'receipt'         => $receipt_id,
        'amount'          => $amountInPaise,
        'currency'        => 'INR',
        'payment_capture' => 1, // Auto-capture payment
        'notes'           => [
            'vehicle_id' => $_SESSION['booking_vehicle_id'] ?? '',
            'user_id'    => $_SESSION['user_id'] ?? ''
        ]
    ];
    
    try {
        // Create the order
        $razorpayOrder = $api->order->create($orderData);
        return $razorpayOrder;
    } catch (Exception $e) {
        // Log error
        error_log('Razorpay Order Creation Error: ' . $e->getMessage());
        return false;
    }
}

// Function to verify Razorpay payment signature
function verifyRazorpayPayment($paymentId, $orderId, $signature) {
    // Load API credentials
    $config = require_once './config/payment_config.php';
    $keyId = $config['razorpay_key_id'];
    $keySecret = $config['razorpay_key_secret'];
    
    // Initialize Razorpay API
    $api = new Api($keyId, $keySecret);
    
    try {
        // Verify signature
        $attributes = [
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id'   => $orderId,
            'razorpay_signature'  => $signature
        ];
        
        $api->utility->verifyPaymentSignature($attributes);
        return true;
    } catch (Exception $e) {
        // Log error
        error_log('Razorpay Signature Verification Error: ' . $e->getMessage());
        return false;
    }
}

// Function to save booking details after successful payment
function saveBookingDetails($paymentId, $orderId) {
    global $conn;
    
    // Get booking details from session
    $vehicleId = $_SESSION['booking_vehicle_id'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;
    $startDate = $_SESSION['booking_start_date'] ?? null;
    $endDate = $_SESSION['booking_end_date'] ?? null;
    $amount = $_SESSION['booking_amount'] ?? null;
    $dealerId = $_SESSION['booking_dealer_id'] ?? null;
    
    if (!$vehicleId || !$userId || !$startDate || !$endDate || !$amount) {
        return false;
    }
    
    // Prepare and execute the query using prepared statements
    $query = "INSERT INTO booking (`user_id`, `dealer_id`, `start_date`, `end_date`, `payment_status`, `total_price`, `vehicle_id`) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
              
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log('Prepare failed: ' . $conn->error);
        return false;
    }
    
    $status = "confirmed";
    $stmt->bind_param("iisssdi",$userId, $dealerId, $startDate, $endDate, $status, $amount, $vehicleId);
    
    $result = $stmt->execute();
    if (!$result) {
        error_log('Execute failed: ' . $stmt->error);
        return false;
    }
    
    $bookingId = $stmt->insert_id;
    $stmt->close();
    
    // Update vehicle status to 'booked' or 'unavailable'
    $updateQuery = "UPDATE vehicle SET vehicle_status = 'unavailable' WHERE vehicle_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $vehicleId);
    $updateStmt->execute();
    $updateStmt->close();
    
    return $bookingId;
}

// Function to generate a unique receipt ID
function generateReceiptId() {
    return 'BOOKING_' . time() . '_' . rand(1000, 9999);
}
?>