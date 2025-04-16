<?php
include "./config/dbcon.php";


// Initialize filter variables from GET parameters if they exist
$locationFilter = isset($_GET['location']) ? $_GET['location'] : '';
$vehicleTypeFilter = isset($_GET['vehicle_type']) ? $_GET['vehicle_type'] : 'all';
$priceRangeFilter = isset($_GET['price_range']) ? $_GET['price_range'] : 'all';

//If 'ref' is set, initialize locationFilter with it.
if (isset($_GET['ref'])) {
    $locationFilter = $_GET['ref'];
}

?>

<?php
include('./includes/header.php');
include('./includes/navbar.php');
?>

<div class="min-h-screen bg-gray-50">
    <section class="py-12">
        <div class=" mx-auto px-4">
            <div class="bg-white shadow-md rounded-lg py-6 text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800">Available Vehicles</h1>
                <p class="text-gray-600 mt-2">Find the perfect ride for your journey</p>

                <div class="mt-6 max-w-4xl mx-auto px-4">
                    <form action="" method="get">
                        <div class="flex flex-col md:flex-row gap-4 justify-between">
                            <div class="flex-1">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1 text-left">Location</label>
                                <input name="location" value="<?php echo htmlspecialchars($locationFilter); ?>" id="location" class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </input>
                            </div>
                            <div class="flex-1">
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1 text-left">Vehicle Type</label>
                                <select name="vehicle_type" id="vehicle_type" class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all" <?php if ($vehicleTypeFilter == 'all') echo 'selected'; ?>>All Types</option>
                                    <option value="bike" <?php if ($vehicleTypeFilter == 'bike') echo 'selected'; ?>>Bikes</option>
                                    <option value="car" <?php if ($vehicleTypeFilter == 'car') echo 'selected'; ?>>Car</option>
                                    <option value="scooty" <?php if ($vehicleTypeFilter == 'scooty') echo 'selected'; ?>>Scooty</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1 text-left">Price Range (per day)</label>
                                <select name="price_range" id="price_range" class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all" <?php if ($priceRangeFilter == 'all') echo 'selected'; ?>>All Prices</option>
                                    <option value="0-500" <?php if ($priceRangeFilter == '0-500') echo 'selected'; ?>>Under ₹500</option>
                                    <option value="500-1000" <?php if ($priceRangeFilter == '500-1000') echo 'selected'; ?>>₹500 - ₹1000</option>
                                    <option value="1000-2000" <?php if ($priceRangeFilter == '1000-2000') echo 'selected'; ?>>₹1000 - ₹2000</option>
                                    <option value="2000+" <?php if ($priceRangeFilter == '2000+') echo 'selected'; ?>>Over ₹2000</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow transition duration-200 h-[50px]">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="popular-models" class="py-16 bg-gray-50">
                <div class=" mx-auto px-6">

                    <?php
                    // Build the SQL query with JOIN and location filter
                    $query = "SELECT vehicle.* FROM vehicle JOIN dealer ON vehicle.dealer_id = dealer.dealer_id WHERE vehicle.vehicle_status='available'";

                    if (!empty($locationFilter)) {
                        $query .= " AND (dealer.dealer_address LIKE '%$locationFilter%' OR dealer.dealer_city LIKE '%$locationFilter%' OR dealer.dealer_district LIKE '%$locationFilter%' OR dealer.dealer_zipcode LIKE '%$locationFilter%' OR dealer.dealer_state LIKE '%$locationFilter%')";
                    }

                    if ($vehicleTypeFilter != 'all') {
                        $query .= " AND vehicle.vehicle_type = '$vehicleTypeFilter'";
                    }

                    if ($priceRangeFilter != 'all') {
                        if ($priceRangeFilter == '2000+') {
                            $query .= " AND vehicle.daily_price >= 2000";
                        } else {
                            list($minPrice, $maxPrice) = explode('-', $priceRangeFilter);
                            $query .= " AND vehicle.daily_price >= $minPrice AND vehicle.daily_price <= $maxPrice";
                        }
                    }

                    $result = mysqli_query($conn, $query);

                    // Check if no vehicles found
                    if (mysqli_num_rows($result) == 0) {
                        echo "<h3>No data found</h3>";
                    } else {
                    ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                            <?php while ($vehicle = mysqli_fetch_assoc($result)) { ?>
                                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                                    <div class="relative h-56 overflow-hidden">
                                        <img src="./assets/img/vehicle/<?php echo $vehicle['main_image']; ?>" alt="<?php echo $vehicle['vehicle_make'] . ' ' . $vehicle['vehicle_model']; ?>" class="w-full h-full object-cover transition duration-500 hover:scale-110">
                                        <div class="absolute top-4 right-4  px-3 py-1  font-bold">
                                            <span class=" text-green-800 text-xs px-2 py-1 rounded bg-<?php
                                                                                                        echo ($vehicle['vehicle_status'] == 'available') ? 'green' : (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-100
                                                                                                    text-<?php echo ($vehicle['vehicle_status'] == 'available') ? 'green' : (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-800
                                                                                                    text-xs px-2 py-1 rounded-full">
                                                <?php echo ucfirst($vehicle['vehicle_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-bold text-gray-900"><?php echo $vehicle['vehicle_make']; ?> <?php echo $vehicle['vehicle_model']; ?></h3>
                                            <div class="flex items-center text-yellow-400">
                                                <i class="fas fa-star"></i>
                                                <span class="ml-1 text-gray-700">4.8</span>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex justify-between items-center">
                                            <div>
                                                <span class="text-2xl font-bold text-blue-600"><?php echo $vehicle['daily_price']; ?></span>
                                                <span class="text-gray-500">/Day</span>
                                            </div>
                                            <a href="vehicle_details.php?ref=<?php echo $vehicle['vehicle_id']; ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-full transition">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                        </div>


                </div>
            </div>
        </div>
    </section>
</div>

<?php
include('./includes/footer.php');
?>