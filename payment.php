<?php
session_start();
// Include Razorpay SDK
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

// Razorpay API Credentials
$keyId = "rzp_test_ISPT8LFG4JT4Au";
$keySecret = "teGLk7VtNxMi8TNrG927Hi47";
$api = new Api($keyId, $keySecret);

// Create an order
$orderData = [
    'receipt'         => 'REG_' . 121212,
    'amount'          => 1000, // Razorpay accepts amount in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // Auto-capture
];

$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

?>

<style>
    .payment-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .payment-container h3 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    .payment-container p {
        font-size: 16px;
        color: #666;
    }
    .btn-razorpay {
        background: #f37254;
        color: #fff;
        padding: 12px 20px;
        font-size: 18px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-top: 15px;
    }
    .btn-razorpay:hover {
        background: #d84b35;
        color: #fff;
    }
</style>

<section class="contact_section common_section-style" id="contact">
    <div class="container">
        <div class="payment-container">
            <h3>Hi, Complete Your Payment</h3>
            <p>To proceed with your registration, please make a secure payment using Razorpay.</p>
            
            <p><strong>Amount to Pay:</strong> ₹10</p>

            <img src="https://d6xcmfyh68wv8.cloudfront.net/blog-content/uploads/2023/12/RTB-Banner-2-1024x364.png" alt="Secure Payment" width="100%">

            <button id="rzp-button" class="btn-razorpay">Pay with Razorpay</button>
        </div>
    </div>
</section>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "<?= $keyId ?>",
        "amount": 1000,
        "currency": "INR",
        "name": "SODC by HF",
        "description": "SODC Participation Fee",
        "image": "https://sodc.hfskillcenter.org/images/sodc.png",
        "order_id": "<?= $razorpayOrderId ?>",
        "handler": function (response) {
            window.location.href = "verify-payment.php?payment_id=" + response.razorpay_payment_id + "&order_id=" + response.razorpay_order_id;
        },
        "prefill": {
            "name": "Raj",
            "email": "raj@gmail.com",
            "contact": "7979864304"
        },
        "theme": {
            "color": "#F37254"
        }
    };
    
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function (e) {
        rzp1.open();
        e.preventDefault();
    };
</script>

