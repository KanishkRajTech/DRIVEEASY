<?php 
include('../main/head.php');
include "../config/dbcon.php";
?>
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: user_login.php");
}


// Initialize variables to store user details and error message
$userDetails = null;
$errorMessage = "";

$userId = $_SESSION["user_id"];

// SQL query to fetch user details by ID
$sql = "SELECT * FROM `user` WHERE `user_id` = $userId";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Check if a user with the given ID exists
    if (mysqli_num_rows($result) == 1) {
        $userDetails = mysqli_fetch_assoc($result);
    } else {
        $errorMessage = "user with ID '$userId' not found.";
    }
    mysqli_free_result($result);
} else {
    $errorMessage = "Error fetching user details: " . mysqli_error($conn);
}
?>

<div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-blue-600 p-4 text-white flex justify-between items-center">
            <h1 class="text-2xl font-bold">User Profile</h1>
            <!-- <button class="bg-red-500 px-4 py-2 rounded">Logout</button> -->
        </nav>

        <!-- Main Content -->
        <div class="flex flex-col md:flex-row flex-1">
            <!-- Sidebar -->
            <aside class="bg-white w-full md:w-64 p-6 shadow-md">
                <h2 class="text-lg font-semibold mb-4">Welcome, User</h2>
                <ul class="space-y-2">
                    <li><a href="my_profile.php" class="block p-2 bg-gray-200 rounded">Profile</a></li>
                    <li><a href="my_booking.php" class="block p-2 bg-gray-200 rounded">My Bookings</a></li>
                    <li><a href="#" class="block p-2 bg-gray-200 rounded">Settings</a></li>
                </ul>
            </aside>
            <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Dealer Logo/Image -->
                <div class="md:w-1/3 p-6 flex items-center justify-center bg-gray-50">
                    <img src="https://static.vecteezy.com/system/resources/previews/013/042/571/large_2x/default-avatar-profile-icon-social-media-user-photo-in-flat-style-vector.jpg" 
                         alt="" 
                         class="w-full h-64 object-contain rounded-lg">
                </div>
                
                <!-- Dealer Info -->
                <div class="md:w-2/3 p-8">
                    <div class="flex justify-between items-start">
                        <!-- <div>
                            <h1 class="text-3xl font-bold"></h1>
                            <p class="text-gray-600 mt-2">Registered Dealer</p>
                        </div> -->
                        <!-- <div class="flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            Verified Dealer
                        </div> -->
                    </div>
                    
                    <div class="mt-6 flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            <span><?php echo  $userDetails['user_email']; ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-green-500 mr-2"></i>
                            <span><?php echo  $userDetails['user_phone']; ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-bold text-lg mb-3">Name: <?php echo  $userDetails['user_name']; ?> </h3>
                        <h3 class="font-bold text-lg mb-3">User ID:<?php echo  $userDetails['user_id']; ?> </h3>
                        <p class="text-gray-700">
                            Welcome to your User dashboard. Here you can manage your vehicle inventory and profile details.
                        </p>
                    </div>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="vechile_owner_edit.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fa-solid fa-car mr-2"></i></i> Book Now
                        </a>
                        
                        <a href="logout.php" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>


<?php include('../main/footer.php'); ?>