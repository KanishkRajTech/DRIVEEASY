<?php
include '../main/head.php';
session_start();
if (!isset($_SESSION["dealer_id"])) {
    header("location: pear_login.php");
    exit; // Always add exit after header redirect
}
$dealerId = $_SESSION['dealer_id'];
$bookingId = $_GET['ref'];

$query = "SELECT b.*, v.*, u.*
          FROM booking b
          JOIN vehicle v ON b.vehicle_id = v.vehicle_id
          JOIN user u ON b.user_id = u.user_id
          WHERE b.booking_id = $bookingId AND b.dealer_id = $dealerId";
$result = mysqli_query($conn, $query);

// Check if there are any bookings
if (mysqli_num_rows($result) > 0) {
    $bookingdetail = mysqli_fetch_assoc($result);
} else {
    // Handle case where no bookings exist
    $bookingdetail = null;
}
?>

<div class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include '../main/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Booking Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </header>

            <section class="bg-gray-100 py-8">
                <div class="container mx-auto px-4">
                    <?php if ($bookingdetail): ?>
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <!-- Header -->
                            <div class="bg-blue-600 px-6 py-4">
                                <h1 class="text-2xl font-bold text-white">Booking ID: <span class="font-normal"><?php echo htmlspecialchars($bookingdetail['booking_id'] ?? 'N/A'); ?></span></h1>
                            </div>

                            <!-- Main Content -->
                            <div class="p-6">
                                <!-- Vehicle and Customer Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                    <!-- Vehicle Details -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h2 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Vehicle Information</h2>
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Vehicle:</span>
                                                <span class="font-medium"><?php echo $bookingdetail['vehicle_make'] ?> <?php echo $bookingdetail['vehicle_model'] ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Pickup Date:</span>
                                                <span class="font-medium"><?php echo htmlspecialchars($bookingdetail['start_date'] ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Return Date:</span>
                                                <span class="font-medium"><?php echo htmlspecialchars($bookingdetail['end_date'] ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Payment Status:</span>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium"><?php echo htmlspecialchars($bookingdetail['payment_status'] ?? 'N/A'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Details -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h2 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Customer Information</h2>
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Name:</span>
                                                <span class="font-medium"><?php echo $bookingdetail['user_name'] ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Phone:</span>
                                                <span class="font-medium">+91 <?php echo $bookingdetail['user_phone'] ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Email:</span>
                                                <span class="font-medium"> <?php echo $bookingdetail['user_email'] ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Total Amount:</span>
                                                <span class="font-medium">₹<?php echo number_format($bookingdetail['total_price'], 2) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Controls -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Pickup Status -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h2 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Pickup Status</h2>
                                        <div class="flex items-center space-x-4">
                                            <?php
                                            // Get current status first
                                            $currentStatusQuery = mysqli_query($conn, "SELECT pickup_status FROM booking WHERE booking_id = '$bookingId'");
                                            $currentStatusData = mysqli_fetch_assoc($currentStatusQuery);
                                            $currentStatus = $currentStatusData['pickup_status'] ?? 'Not Picked Up';

                                            if (isset($_POST['pickupUpdate'])) {
                                                $pickupStatus = mysqli_real_escape_string($conn, $_POST['picupStatus']);
                                                $query = "UPDATE `booking` SET `pickup_status` = '$pickupStatus' WHERE `booking_id` = '$bookingId'";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    echo '<div class="text-green-600 mb-2">Status updated successfully!</div>';
                                                    // Refresh current status after update
                                                    $currentStatusQuery = mysqli_query($conn, "SELECT pickup_status FROM booking WHERE booking_id = '$bookingId'");
                                                    $currentStatusData = mysqli_fetch_assoc($currentStatusQuery);
                                                    $currentStatus = $currentStatusData['pickup_status'];
                                                } else {
                                                    echo '<div class="text-red-600 mb-2">Error updating status: ' . mysqli_error($conn) . '</div>';
                                                }
                                            }
                                            ?>

                                            <form method="POST">
                                                <select name="picupStatus" class="flex-grow border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="Not Picked Up" <?= $currentStatus === 'Not Picked Up' ? 'selected' : '' ?>>Not Picked Up</option>
                                                    <option value="Picked Up" <?= $currentStatus === 'Picked Up' ? 'selected' : '' ?>>Picked Up</option>
                                                </select>
                                                <button type="submit" name="pickupUpdate" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200
                                                    <?php
                                                            // Disable button if item is already picked up
                                                            if ($currentStatus === 'Picked Up') {
                                                                echo 'opacity-50 cursor-not-allowed';
                                                            }
                                                    ?>">
                                                    <i class="fas fa-save mr-2"></i> Update
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Return Status -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h2 class="text-lg font-semibold mb-4 text-blue-600 border-b pb-2">Return Status</h2>
                                        <div class="flex items-center justify-between space-x-4">
                                            <?php
                                            if (isset($_POST['returnUpdate'])) {
                                                $returnStatus = mysqli_real_escape_string($conn, $_POST['returnStatus']);
                                                $query = "UPDATE `booking` SET `return_status` = '$returnStatus' WHERE `booking_id` = '$bookingId'";
                                                $result = mysqli_query($conn, $query);

                                                if ($result) {
                                                    // Show success message and refresh status data

                                                    $statusQuery = "SELECT pickup_status, return_status FROM booking WHERE booking_id = '$bookingId'";
                                                    $statusResult = mysqli_query($conn, $statusQuery);
                                                    $statusData = $statusResult ? mysqli_fetch_assoc($statusResult) : null;
                                                } else {
                                                    echo '<div class="text-red-600 mb-2">Error updating status: ' . mysqli_error($conn) . '</div>';
                                                }
                                            }

                                            // Get current statuses
                                            if (!isset($statusData)) { // Only query if we didn't already refresh the data
                                                $statusQuery = "SELECT pickup_status, return_status FROM booking WHERE booking_id = '$bookingId'";
                                                $statusResult = mysqli_query($conn, $statusQuery);
                                                $statusData = $statusResult ? mysqli_fetch_assoc($statusResult) : null;
                                            }
                                            ?>

                                            <form method="POST">
                                                <select name="returnStatus" class="flex-grow border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                    <?php
                                                    // Disable dropdown if item hasn't been picked up yet
                                                    if ($statusData && $statusData['pickup_status'] === 'Not Picked Up') {
                                                        echo 'disabled';
                                                    }
                                                    ?>>
                                                    <option value="Not Returned" <?= ($statusData['return_status'] ?? '') === 'Not Returned' ? 'selected' : '' ?>>Not Returned</option>
                                                    <option value="Returned" <?= ($statusData['return_status'] ?? '') === 'Returned' ? 'selected' : '' ?>>Returned</option>
                                                </select>

                                                <button type="submit" name="returnUpdate" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 
                                                <?php
                                                // Disable button if item hasn't been picked up or is already returned
                                                if ($statusData && ($statusData['pickup_status'] === 'Not Picked Up' || $statusData['return_status'] === 'Returned')) {
                                                    echo 'opacity-50 cursor-not-allowed';
                                                }
                                                ?>">
                                                    <i class="fas fa-save mr-2"></i> Update
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 text-center">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">No bookings found</h2>
                            <p class="text-gray-600">You don't have any bookings yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>
<?php include '../main/footer.php'; ?>