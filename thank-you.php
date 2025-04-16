<?php
include('./includes/header.php');
?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Decorative Header Strip -->
            <div class="bg-primary-600 h-2 w-full"></div>
            
            <!-- Card Content -->
            <div class="p-8 sm:p-10">
                <!-- Success Icon -->
                <div class="mx-auto bg-green-400 flex h-20 w-20 items-center justify-center rounded-full bg-primary-50 mb-8">
                    <svg class="h-10 w-10 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <!-- Heading -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">
                        Payment Successful!
                    </h1>
                    <p class="text-gray-600">
                        Thank you for your booking. our pear is waiting your pickup. 
                    </p>
                </div>
                
                <!-- Order Summary -->
                <div class="border border-gray-200 rounded-lg divide-y divide-gray-200 mb-8">
                    <div class="p-5 bg-gray-50 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Booking Summary
                        </h3>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex justify-between py-3">
                            <span class="text-gray-500">Booking Number</span>
                            <span class="font-medium text-gray-900">#BOk-<?= strtoupper(uniqid()) ?></span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-gray-100">
                            <span class="text-gray-500">Date & Time</span>
                            <span class="font-medium text-gray-900"><?= date('F j, Y \a\t g:i A') ?></span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-gray-100">
                            <span class="text-gray-500">Payment Method</span>
                            <span class="font-medium text-gray-900">VISA ****4242</span>
                        </div>
                        <div class="flex justify-between py-3 border-t border-gray-100">
                            <span class="text-gray-500">Email</span>
                            <span class="font-medium text-gray-900">customer@example.com</span>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-gray-50 rounded-b-lg">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-700">Total Paid</span>
                            <span class="text-xl font-bold text-gray-900">$129.99</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a href="#" class="w-full flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        View booking Details
                    </a>
                    <a href="#" class="w-full flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        Back
                    </a>
                </div>
                
                <!-- Support Info -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Need help? <a href="/contact" class="font-medium text-primary-600 hover:text-primary-500">Contact our support team</a>
                    </p>
                    <p class="mt-2 text-xs text-gray-400">
                        Booking confirmation and receipt sent to your email. Please check your spam folder if you don't see it.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Company Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">
                &copy; <?= date('Y') ?> Your Company Name. All rights reserved.
            </p>
        </div>
    </div>
</div>

<?php
include('./includes/footer.php');
?>