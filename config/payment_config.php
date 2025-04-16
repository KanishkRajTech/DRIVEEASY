<?php
// File: config/payment_config.php
// This file should be placed outside the web root or properly secured

// Payment gateway configurations
return [
    // Razorpay API credentials
    'razorpay_key_id' => 'rzp_test_ISPT8LFG4JT4Au', // Replace with your actual key
    'razorpay_key_secret' => 'teGLk7VtNxMi8TNrG927Hi47', // Replace with your actual secret
    
    // Payment settings
    'currency' => 'INR',
    'company_name' => 'DRIVE EASY',
    'company_logo' => 'https://your-website.com/logo.png',
    'contact_email' => 'support@your-website.com',
    
    // Payment modes enabled
    'payment_modes' => [
        'card' => true,
        'netbanking' => true,
        'wallet' => true,
        'upi' => true
    ]
];
?>