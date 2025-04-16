<?php
include '../main/head.php';

?>
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
















<!-- Dealer Profile Header -->
<section class="bg-gray-100 py-12">
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
                            <h1 class="text-3xl font-bold"></h1>
                            <p class="text-gray-600 mt-2">Registered Dealer</p>
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

<!-- Dealer Stats -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="bg-gray-100 p-6 rounded-lg">
                <div class="text-4xl font-bold text-blue-600 mb-2"></div>
                <div class="text-gray-600">
                    <h2>Total Vehicles</h2>
                    <?php 
                        $count_qry = "SELECT COUNT(*) as total FROM `vehicle` WHERE dealer_id = '$dealerId'";
                        $count_result = mysqli_query($conn, $count_qry);
                        $count = mysqli_fetch_assoc($count_result)['total'];
                        echo $count . " vehicle" . ($count != 1 ? 's' : '');
                    ?>
                    
                </div>
            </div>
            <div class="bg-gray-100 p-6 rounded-lg">
                <div class="text-4xl font-bold text-blue-600 mb-2"></div>
                <div class="text-gray-600">Years in Business</div>
            </div>
            <!-- Add more stats as needed -->
        </div>
    </div>
</section>

<!-- Dealer Inventory - Compact View -->
<!-- Dealer Vehicle Dashboard -->
<section class="py-8 bg-gray-100">
    <div class="container mx-auto px-4">
        
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Your Vehicles</h2>
            <a href="vehicle_add.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Vehicle
            </a>
        </div>

        <?php
        // Get vehicles from database
        $query = "SELECT * FROM vehicle WHERE dealer_id = '$dealerId'";
        $result = mysqli_query($conn, $query);
        
        // Check if no vehicles found
        if (mysqli_num_rows($result) == 0) {
        ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <i class="fas fa-car text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">No Vehicles Found</h3>
                <a href="vehicle_add.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg inline-block">
                    Add Your First Vehicle
                </a>
            </div>
        <?php
        } 
        // Display vehicles if found
        else {
        ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">
                
                <?php while ($vehicle = mysqli_fetch_assoc($result)) { ?>
                
                <!-- Vehicle Card -->

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="relative">
                            <img src="../assets/img/vehicle/<?php echo $vehicle['main_image']; ?>" 
                                 alt="" class="w-full h-48 object-cover">
                            <div class="absolute top-2 right-2   text-xs px-1 py-1 rounded">
                           
                                <span class=" text-green-800 text-xs px-2 py-1 rounded bg-<?php 
                            echo ($vehicle['vehicle_status'] == 'available') ? 'green' : 
                                 (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-100 
                                 text-<?php echo ($vehicle['vehicle_status'] == 'available') ? 'green' : 
                                 (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-800 
                                 text-xs px-2 py-1 rounded-full">
                            <?php echo ucfirst($vehicle['vehicle_status']); ?></span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold"><?php echo $vehicle['vehicle_make']; ?></h3>
                            <span class="text-2xl font-bold text-blue-600"><?php echo $vehicle['vehicle_model']; ?></span>
                            <div class="flex justify-between items-center mt-2">
                                
                            </div>
                            
                            <div class="mt-4 flex gap-2">
                                <button class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                    Edit
                                </button>
                                <button class="flex-1 bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
            </div>
        <?php } ?>
        
    </div>
</section>

<!-- Simple Delete Confirmation -->
<script>
function confirmDelete(vehicleId) {
    if(confirm('Delete this vehicle?')) {
        window.location = 'vehicle_delete.php?id=' + vehicleId;
    }
}
</script>



<?php include('../main/footer.php'); ?>