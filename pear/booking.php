<?php
include '../main/head.php';
session_start();
if (!isset($_SESSION["dealer_id"])) {

    header("location: pear_login.php");
}
$dealerId = $_SESSION['dealer_id'];
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

           <!-- Content Section - Same PHP but better UI -->
           <main class="p-6 overflow-y-auto">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Header Row - Improved styling -->
                    <div class="grid grid-cols-5 bg-gray-800 text-white p-4 font-medium">
                        <div>Booking ID</div>
                        <div>Vehicle</div>
                        <div>PickUp Date</div>
                        <div>Amount</div>
                        <div>Action</div>
                    </div>
                    
                    <?php
                    $query = "SELECT
                                b.booking_id,
                                b.vehicle_id,
                                b.start_date,
                                b.total_price,
                                v.vehicle_make,
                                v.vehicle_model
                              FROM booking b
                              JOIN vehicle v ON b.vehicle_id = v.vehicle_id
                              WHERE b.dealer_id = '$dealerId'";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo '<div class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings available</h3>
                                <p class="mt-2 text-gray-600">Please check back later.</p>
                              </div>';
                    } else {
                        echo '<div class="divide-y divide-gray-200">';
                        while ($booking = mysqli_fetch_assoc($result)) {
                            echo '<div class="grid grid-cols-5 p-4 items-center hover:bg-gray-50">
                                    <div class="font-medium text-blue-600">#' . $booking['booking_id'] . '</div>
                                    <div>
                                        <a href="../vehicle_details.php?ref=' . $booking['vehicle_id'] . '" class="text-blue-600 hover:underline">
                                            ' . $booking['vehicle_make'] . ' ' . $booking['vehicle_model'] . '
                                        </a>
                                    </div>
                                    <div>' . date('M j, Y', strtotime($booking['start_date'])) . '</div>
                                    <div>₹' . number_format($booking['total_price'], 2) . '</div>
                                    <div>
                                        <a href="bookingdetail.php?ref=' . $booking['booking_id'] . '" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100">
                                            View Details
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
</div>
<?php include '../main/footer.php'; ?>
























