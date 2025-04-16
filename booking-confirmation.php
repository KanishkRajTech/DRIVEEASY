<?php
// File: booking-confirmation.php
// Shows booking confirmation and details after successful payment
// Include header
include('./includes/header.php');
include('./includes/navbar.php');
// Include necessary files
require_once './config/dbcon.php';

// Start session if not already started
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user/user_login.php");
    exit();
}

// Check if booking ID is received
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Get booking ID
$bookingId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$bookingId) {
    header("Location: index.php");
    exit();
}

// Get booking details from database
$query = "SELECT b.*, v.vehicle_make, v.vehicle_model, v.main_image, v.daily_price,
           u.user_name, u.user_email, u.user_phone
          FROM booking b
          JOIN vehicle v ON b.vehicle_id = v.vehicle_id
          JOIN user u ON b.user_id = u.user_id
          WHERE b.booking_id = ? AND b.user_id = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $bookingId, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$booking = $result->fetch_assoc();
$stmt->close();


?>

<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Success Message -->
        <div class="bg-green-100 p-6 text-center border-b border-green-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500 text-white mb-4">
                <i class="fas fa-check text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-green-800">Booking Confirmed!</h1>
            <p class="text-green-700 mt-2">Your payment has been processed successfully.</p>
        </div>
        
        <!-- Booking Details -->
        <div class="p-6">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Booking #<?= $bookingId ?></h2>
                   
                </div>
                
            </div>
            
            <!-- Vehicle Info -->
            <div class="flex items-center mt-6 py-4 border-b border-gray-200">
                <img src="./assets/img/vehicle/<?= $booking['main_image'] ?>" alt="<?= $booking['vehicle_make'] ?>" class="w-24 h-24 object-cover rounded-lg">
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-gray-800"><?= $booking['vehicle_make'] ?> <?= $booking['vehicle_model'] ?></h3>
                    <p class="text-gray-600">₹<?= $booking['daily_price'] ?>/day</p>
                </div>
            </div>
            
            <!-- Booking Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <h3 class="font-medium text-gray-800 mb-2">Booking Details</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p><span class="font-medium">Pickup Date:</span> <?= date('d M Y', strtotime($booking['start_date'])) ?></p>
                        <p><span class="font-medium">Return Date:</span> <?= date('d M Y', strtotime($booking['end_date'])) ?></p>
                        <p><span class="font-medium">Duration:</span> <?= ceil((strtotime($booking['end_date']) - strtotime($booking['start_date'])) / (60*60*24)) ?> days</p>
                        <p><span class="font-medium">Status:</span> <span class="text-green-600 font-medium"><?= ucfirst($booking['payment_status']) ?></span></p>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-800 mb-2">Customer Information</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p><span class="font-medium">Name:</span> <?= $booking['user_name'] ?></p>
                        <p><span class="font-medium">Email:</span> <?= $booking['user_email'] ?></p>
                        <p><span class="font-medium">Phone:</span> <?= $booking['user_phone'] ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="font-medium text-gray-800 mb-2">Payment Summary</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    
                    <div class="flex justify-between pt-2 border-t border-gray-200 font-bold text-lg">
                        <span>Total Paid</span>
                        <span>₹<?= number_format($booking['total_price'], 2) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="mt-8 bg-blue-50 p-4 rounded-lg">
                <h3 class="font-bold text-blue-800 mb-2">What's Next?</h3>
                <p class="text-blue-700">Please bring your driver's license and a valid ID to pick up your vehicle. Our dealer will contact you shortly with additional information.</p>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="user/my_bookings.php" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg">
                    View My Bookings
                </a>
                <a href="index.php" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-center py-3 rounded-lg">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include('./includes/footer.php');
?>