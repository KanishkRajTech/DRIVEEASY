<?php include '../main/head.php'; ?>
<?php
session_start();
if (!isset($_SESSION["dealer_id"])) {
    header("location: pear_login.php");
}


// Initialize variables to store dealer details and error message
$dealerDetails = null;
$errorMessage = "";

$dealerId = $_SESSION["dealer_id"];

// SQL query to fetch dealer details by ID
$sql = "SELECT * FROM `dealer` WHERE `dealer_id` = $dealerId";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Check if a dealer with the given ID exists
    if (mysqli_num_rows($result) == 1) {
        $dealerDetails = mysqli_fetch_assoc($result);
    } else {
        $errorMessage = "Dealer with ID '$dealerId' not found.";
    }
    mysqli_free_result($result);
} else {
    $errorMessage = "Error fetching dealer details: " . mysqli_error($conn);
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
                    <h1 class="text-xl font-semibold text-gray-800">Dealer Dashboard</h1>
                </div>
                
            </header>


            <!-- Dealer Profile Header -->
            <section class="bg-gray-100 py-4">
                <div class="container mx-auto px-4">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="md:flex">
                            <!-- Dealer Logo/Image -->
                            <div class="md:w-1/3 p-6 flex items-center justify-center bg-gray-50">
                                <img src="../assets/img/dealer/<?php echo  $dealerDetails['dealer_Image']; ?>"
                                    alt=""
                                    class="w-full h-64 object-contain rounded-lg">
                            </div>

                            <!-- Dealer Info -->
                            <div class="md:w-2/3 p-8">
                                <div class="flex justify-between items-start">
                                    <div>
                                        
                                    </div>
                                    <div class="flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Verified Dealer
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-wrap gap-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        <?php echo  $dealerDetails['dealer_email']; ?>
                                        <span></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                        <?php echo  $dealerDetails['dealer_phone']; ?>
                                        <span></span>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h3 class="font-semibold text-lg mb-3"><?php echo  $dealerDetails['dealer_fname']; ?> <span><?php echo  $dealerDetails['dealer_lname']; ?> </span></h3>
                                    <h3 class="font-bold text-lg mb-3">Dealer ID: <?php echo  $dealerDetails['dealer_id']; ?></h3>
                                    <p>About Us</p>
                                    <p class="text-gray-700">
                                        Welcome to your dealer dashboard. Here you can manage your vehicle inventory and profile details.
                                    </p>
                                </div>
                                <div class=" items-center mt-2">
                                    <i class="fa-solid fa-location-dot text-blue-500"></i>
                                    <?php echo  $dealerDetails['dealer_address']; ?>
                                </div>
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <a href="profile_edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-user-edit mr-2"></i> Edit Profile
                                    </a>
                                    <a href="vehicle_add.php" class="border border-blue-600 text-blue-600 px-6 py-2 rounded-lg hover:bg-blue-50 transition">
                                        <i class="fas fa-car mr-2"></i> Add Vehicle
                                    </a>
                                    <a href="logout.php" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-100 transition">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>



            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Vehicles</p>
                                <p class="text-2xl font-bold">
                                <?php
                                    $count_qry = "SELECT COUNT(*) as total FROM `vehicle` WHERE dealer_id = '$dealerId'";
                                    $count_result = mysqli_query($conn, $count_qry);
                                    $count = mysqli_fetch_assoc($count_result)['total'];
                                    echo $count ;
                                    ?>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Active Rentals</p>
                                <p class="text-2xl font-bold">
                                    
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-car text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Available Vehicles</p>
                                <p class="text-2xl font-bold">
                                <?php
                                    $count_qry = "SELECT COUNT(*) as total FROM `vehicle` WHERE dealer_id = '$dealerId '";
                                    $count_result = mysqli_query($conn, $count_qry);
                                    $count = mysqli_fetch_assoc($count_result)['total'];
                                    echo $count ;
                                    ?>
                                </p>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-car-side text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>




            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bookings Chart
            const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
            const bookingsChart = new Chart(bookingsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [{
                        label: 'Bookings',
                        data: [65, 59, 80, 81, 56, 55, 40, 72, 88, 94],
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Vehicle Type Chart
            const vehicleTypeCtx = document.getElementById('vehicleTypeChart').getContext('2d');
            const vehicleTypeChart = new Chart(vehicleTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Sedan', 'SUV', 'Truck', 'Luxury', 'Van'],
                    datasets: [{
                        data: [35, 25, 15, 10, 15],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(239, 68, 68, 0.7)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        });
    </script>
</div>
<?php include '../main/footer.php'; ?>