<?php
include('../main/head.php');
session_start(); // Must be at the very top

if (!isset($_SESSION["user_id"])) {
    header("location: user_login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Modified SQL query with JOINs
$sql = "SELECT 
            booking.*, 
            dealer.dealer_fname,
            dealer.dealer_phone,
            dealer.dealer_lname, 
            vehicle.vehicle_make,
            vehicle.vehicle_model
        FROM 
            `booking` 
        JOIN 
            `dealer` ON booking.dealer_id = dealer.dealer_id
        JOIN
            `vehicle` ON booking.vehicle_id = vehicle.vehicle_id
        WHERE 
            `booking`.`user_id` = $userId";

$result = mysqli_query($conn, $sql);

// Initialize variables
$bookingDetails = [];
$errorMessage = "";

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // Fetch all bookings (not just one)
        while ($row = mysqli_fetch_assoc($result)) {
            $bookingDetails[] = $row;
        }
    } else {
        $errorMessage = "No bookings found.";
    }
} else {
    $errorMessage = "Error: " . mysqli_error($conn);
}
?>

<div class="min-h-screen flex flex-col">
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center">
        <h1 class="text-2xl font-bold">User Dashboard</h1>
        <a href="../user/logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
    </nav>

    <div class="flex flex-col md:flex-row flex-1">
        <aside class="bg-white w-full md:w-64 p-6 shadow-md">
            <h2 class="text-lg font-semibold mb-4">Welcome, User</h2>
            <ul class="space-y-2">
                <li><a href="my_profile.php" class="block p-2 bg-gray-200 rounded">Profile</a></li>
                <li><a href="my_booking.php" class="block p-2 bg-gray-200 rounded">My Bookings</a></li>
                <li><a href="#" class="block p-2 bg-gray-200 rounded">Settings</a></li>
            </ul>
        </aside>

        <main class="flex-1 p-4 md:p-6">
    <div class="bg-white p-4 md:p-6 shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Bookings</h2>
            <div class="relative">
                <input type="text" placeholder="Search bookings..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($bookingDetails)): ?>
            <div class="space-y-4">
                <?php foreach ($bookingDetails as $booking): ?>
                    <div class="border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-xl text-gray-800">Booking #<?php echo htmlspecialchars($booking['booking_id']); ?></h3>
                                <div class="flex items-center mt-1">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php echo $booking['payment_status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?php echo htmlspecialchars($booking['payment_status']); ?>
                                    </span>
                                </div>
                            </div>
                            <a href="../vehicle_details.php?ref=<?php echo $booking['vehicle_id']; ?>" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                View Vehicle <i class="fas fa-chevron-right ml-1 text-sm"></i>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            <!-- Vehicle Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-car mr-2 text-blue-500"></i> Vehicle Details
                                </h4>
                                <p class="font-medium text-lg mb-1"><?php echo htmlspecialchars($booking['vehicle_make']) ?> <?php echo htmlspecialchars($booking['vehicle_model']) ?></p>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-tag mr-2"></i>
                                    <span>ID: <?php echo htmlspecialchars($booking['vehicle_id']); ?></span>
                                </div>
                            </div>

                            <!-- Dealer Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-store mr-2 text-blue-500"></i> Dealer Info
                                </h4>
                                <p class="font-medium mb-1"><?php echo htmlspecialchars($booking['dealer_fname']); ?> <?php echo htmlspecialchars($booking['dealer_lname']); ?></p>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-phone mr-2"></i>
                                    <span><?php echo htmlspecialchars($booking['dealer_phone']); ?></span>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-receipt mr-2 text-blue-500"></i> Pricing
                                </h4>
                                <div class="flex items-center text-lg font-bold text-gray-800">
                                    <i class="fa-solid fa-indian-rupee-sign mr-1"></i>
                                    <span><?php echo htmlspecialchars($booking['total_price']); ?></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mt-2">
                                    <span>Pickup: <?php echo htmlspecialchars($booking['start_date']); ?></span>
                                    <span>Return: <?php echo htmlspecialchars($booking['end_date']); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Indicators -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-100 p-3 rounded-lg">
                                    <p class="text-sm text-gray-500 mb-1">Pickup Status</p>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2 
                                            <?php echo $booking['pickup_status'] === 'Picked Up' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                        </div>
                                        <p class="font-medium"><?php echo htmlspecialchars($booking['pickup_status']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-100 p-3 rounded-lg">
                                    <p class="text-sm text-gray-500 mb-1">Return Status</p>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2 
                                            <?php echo $booking['return_status'] === 'Returned' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                        </div>
                                        <p class="font-medium"><?php echo htmlspecialchars($booking['return_status']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-10">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-600">No bookings found</h3>
                <p class="text-gray-500 mt-2">You haven't made any bookings yet.</p>
                <a href="../vehicles.php" class="mt-4 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Browse Vehicles
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>
    </div>
</div>

<?php include('../main/footer.php'); ?>