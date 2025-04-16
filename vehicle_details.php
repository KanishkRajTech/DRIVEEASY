<?php
include "./config/dbcon.php";
// Include header file
include('./includes/header.php');
$redirect = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// Initialize vehicleId
$vehicleId = null;

// Get vehicleId from GET parameter
if (isset($_GET['ref'])) {
    $vehicleId = $_GET['ref'];
} else {
    header("Location: index.php");
    exit(); // Important: Stop further execution after redirection
}

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
}



?>


<?php
// This code should be added to your vehicle_details.php file

// Process the booking form submission
if (isset($_POST['submitBooking'])) {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: user/user_login.php?redirect=" . urlencode($redirect));
        exit();
    }

    // Validate inputs
    $startDate = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'end', FILTER_SANITIZE_STRING);
    $vehicleId = filter_input(INPUT_POST, 'vehicleId', FILTER_VALIDATE_INT);
    $totalPrice = filter_input(INPUT_POST, 'totalPrice', FILTER_VALIDATE_FLOAT);
    $dealerId = filter_input(INPUT_POST, 'dealer_id', FILTER_VALIDATE_INT);

    if (!$startDate || !$endDate || !$vehicleId || !$totalPrice || !$dealerId) {
        $_SESSION['booking_error'] = "Invalid booking data. Please try again.";
        header("Location: vehicle_details.php?ref=" . $vehicleId);
        exit();
    }

    // Store booking info in session for payment processing
    $_SESSION['booking_vehicle_id'] = $vehicleId;
    $_SESSION['booking_start_date'] = $startDate;
    $_SESSION['booking_end_date'] = $endDate;
    $_SESSION['booking_amount'] = $totalPrice;
    $_SESSION['booking_dealer_id'] = $dealerId;

    // Include payment gateway functions
    require_once 'payment_gateway.php';

    // Generate receipt ID
    $receiptId = generateReceiptId();

    // Create Razorpay order
    $razorpayOrder = createRazorpayOrder($totalPrice, $receiptId);

    if (!$razorpayOrder) {
        $_SESSION['booking_error'] = "Payment initialization failed. Please try again.";
        header("Location: vehicle_details.php?ref=" . $vehicleId);
        exit();
    }

    // Store Razorpay order ID in session
    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];

    // Load payment configuration
    // $config = require_once './config/payment_config.php';
    // $keyId = $config['razorpay_key_id'];
    $keyId = 'rzp_test_ISPT8LFG4JT4Au';

    // The checkout.js script and payment initialization will be 
    // included in the HTML part of the page
}
?>


<?php
$qry = "SELECT * FROM `vehicle` WHERE vehicle_id=$vehicleId";
$result = mysqli_query($conn, $qry);
$vehicle = mysqli_fetch_assoc($result);
?>

<style>
    /* Map styling */
    #map-container {
        position: relative;
        width: 100%;
        height: 400px;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #map {
        width: 100%;
        height: 100%;
    }

    .map-controls {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .map-controls button {
        padding: 8px 15px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .map-controls button.clear {
        background: #f44336;
    }

    /* Other existing styles */
    .input-group {
        margin: 10px 0;
    }

    #info {
        margin: 10px 0;
        padding: 10px;
        background: #f5f5f5;
    }
</style>




<?php include('./includes/navbar.php'); ?>






<!-- Main Content -->
<main class="bg-gray-50">
    <!-- Vehicle Header -->
    <div class="bg-white py-6 shadow-sm">
        <div class="container mx-auto px-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="index.php" class="text-blue-600 hover:underline">Home</a>
                <span class="mx-2">/</span>
                <a href="#" class="text-blue-600 hover:underline">Bikes</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600"><?php echo $vehicle['vehicle_make']; ?></span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900"><?php echo $vehicle['vehicle_make']; ?><?php echo $vehicle['vehicle_model']; ?></h1>
        </div>
    </div>

    <!-- Vehicle Gallery and Details -->
    <div class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Gallery -->
            <div class="lg:col-span-2">
                <!-- Main Image -->
                <div class="bg-white rounded-xl overflow-hidden shadow-md mb-4">
                    <img id="mainImage" src="./assets/img/vehicle/<?php echo $vehicle['main_image']; ?>" alt="" class="w-full h-96 object-contain">
                </div>

                <!-- Thumbnail Gallery -->
                <!-- <div class="grid grid-cols-4 gap-2">
                    <?php //foreach ($vehicle['images'] as $index => $image): ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:border-blue-400 transition" onclick="document.getElementById('mainImage').src = '<?//= $image ?>'">
                            <img src="<?//= $image ?>" alt="Thumbnail <?//= $index + 1 ?>" class="w-full h-20 object-cover">
                        </div>
                    <?php // endforeach; ?>
                </div> -->

                <!-- Vehicle Description -->
                <div class="bg-white rounded-xl shadow-md p-6 mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Description</h2>
                    <p class="text-gray-600 leading-relaxed"><?= $vehicle['vehicle_description'] ?></p>
                </div>

                <!-- Specifications -->
                <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Specifications</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        // Get the specifications string from database
                        $specsString = $vehicle['vehicle_specKey'] ?? '';

                        // Split by pipe character and clean up
                        $specPairs = explode('|', $specsString);
                        $specPairs = array_map('trim', $specPairs); // Trim whitespace
                        $specPairs = array_filter($specPairs);      // Remove empty items

                        if (!empty($specPairs)) {
                            foreach ($specPairs as $pair) {
                                // Split each pair into key and value
                                $parts = explode(':', $pair, 2); // Split on first colon only
                                if (count($parts) === 2) {
                                    $key = trim($parts[0]);
                                    $value = trim($parts[1]);
                        ?>
                                    <div class="border-b border-gray-100 pb-2">
                                        <span class="font-medium text-gray-700"><?= htmlspecialchars($key) ?>:</span>
                                        <span class="text-gray-600 ml-2"><?= htmlspecialchars($value) ?></span>
                                    </div>
                        <?php
                                }
                            }
                        } else {
                            echo '<p class="text-gray-500 md:col-span-2">No specifications listed</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Features</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php
                        // Get the features string from database
                        $featuresString = $vehicle['vehicle_features'] ?? '';

                        // Split by pipe character and clean up
                        $features = explode('|', $featuresString);
                        $features = array_map('trim', $features); // Trim whitespace
                        $features = array_filter($features);      // Remove empty items

                        if (!empty($features)) {
                            foreach ($features as $feature): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-gray-700"><?= htmlspecialchars($feature) ?></span>
                                </div>
                        <?php endforeach;
                        } else {
                            echo '<p class="text-gray-500 col-span-2">No features listed</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- View on map -->
                <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Dealer Location</h2>
                    <div class="border-t border-gray-100 ">
                        <input type="hidden" value="<?= htmlspecialchars($vehicle['dealer_latitude']) ?>" id="destLat">
                        <input type="hidden" value="<?= htmlspecialchars($vehicle['dealer_longitude']) ?>" id="destLng">

                        <div id="map-container">
                            <div id="map" ></div>
                        </div>

                        <div class="map-controls">
                            <button onclick="getMyLocation()">Use My Location</button>
                            <button onclick="showRoute()">Show Route</button>
                            <button onclick="clearRoute()" class="clear">Clear Route</button>
                        </div>
                        <div id="info" hidden></div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg sticky top-4 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-3xl font-bold text-gray-900">₹<?= $vehicle['daily_price'] ?></span>
                            <span class="text-gray-500">/ day</span>
                        </div>
                        <div class="font-medium">
                            <span class="text-xs px-2 py-1 rounded-full <?php
                                                                        echo ($vehicle['vehicle_status'] == 'available') ? 'bg-green-100 text-green-800' : (($vehicle['vehicle_status'] == 'unavailable') ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                                                        ?>">
                                <?php echo ucfirst($vehicle['vehicle_status']); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form id="bookingForm" method="POST" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Pickup Date</label>
                                <input <?php echo isset($_SESSION['user_id']) ? '' : 'disabled'; ?> type="date" name="start" value="<?php echo date('Y-m-d'); ?>" onchange="calculate()" id="start" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Pickup Time</label>
                                <input <?php echo isset($_SESSION['user_id']) ? '' : 'disabled'; ?> type="time" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date('H:i'); ?>" name="pickup_time" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" value="10:00" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Return Date</label>
                                <input <?php echo isset($_SESSION['user_id']) ? '' : 'disabled'; ?> type="date" name="end"  onchange="calculate()" id="end" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Return Time</label>
                                <input <?php echo isset($_SESSION['user_id']) ? '' : 'disabled'; ?> type="time" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date('H:i'); ?>" name="return_time" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" value="18:00" required>
                            </div>
                        </div>

                        <!-- Price breakdown -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="font-medium text-gray-700 mb-3">Price Breakdown</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Daily (₹<?= $vehicle['daily_price'] ?>/day)</span>
                                    <input type="hidden" id="daily_price" value="<?= $vehicle['daily_price'] ?>">
                                    <span id="outputd">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Weekly (₹<?= $vehicle['weekly_price'] ?>/week)</span>
                                    <input type="hidden" id="weekly_price" value="<?= $vehicle['weekly_price'] ?>">
                                    <span id="outputw">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly (₹<?= $vehicle['monthly_price'] ?>/month)</span>
                                    <input type="hidden" id="monthly_price" value="<?= $vehicle['monthly_price'] ?>">
                                    <span id="outputm">0</span>
                                </div>
                            </div>

                            <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t border-gray-200">
                                <span>Total Amount</span>
                                <span id="tPrice">₹0.00</span>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="vehicleId" value="<?php echo $vehicle['vehicle_id']; ?>">
                        <input type="hidden" name="totalPrice" id="priceInput" value="0">
                        <input type="hidden" name="dealer_id" value="<?= $vehicle['dealer_id'] ?>">

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" name="submitBooking" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 mt-6
                            <?php
                                // Disable button if item is already picked up
                                if ($vehicle['vehicle_status'] === 'unavailable') {
                                 echo 'opacity-50 cursor-not-allowed';
                                }
                            ?>">
                                Proceed to Payment
                            </button>
                        <?php else: ?>
                            <a href="user/user_login.php?redirect=<?php echo $redirect; ?>" class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 mt-6">
                                Login to Book
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['booking_error'])): ?>
                            <div class="text-red-500 mt-4 text-sm"><?= $_SESSION['booking_error']; ?></div>
                            <?php unset($_SESSION['booking_error']); ?>
                        <?php endif; ?>
                    </form>
                </div>

 
                
            </div>
        </div>
    </div>

    <!-- Related Vehicles -->
    <div class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Similar Vehicles</h2>
        <?php
        // Get vehicles from database
        $query = "SELECT * FROM vehicle lIMIT 6 ";
        $result = mysqli_query($conn, $query);

        // Check if no vehicles found
        if (mysqli_num_rows($result) == 0) {
            echo "<h3>No data found</h3>";
        } else {
        ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php while ($vehicle = mysqli_fetch_assoc($result)) { ?>
                    <!-- Related Vehicle 1 -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition">
                        <div class="relative h-48 overflow-hidden">
                            <img src="./assets/img/vehicle/<?php echo $vehicle['main_image']; ?>" alt="Royal Enfield" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4  font-bold">
                                <span class=" text-green-800 text-xs px-2 py-1 rounded bg-<?php
                                                                                            echo ($vehicle['vehicle_status'] == 'available') ? 'green' : (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-100 
                                 text-<?php echo ($vehicle['vehicle_status'] == 'available') ? 'green' : (($vehicle['vehicle_status'] == 'unavailable') ? 'red' : 'yellow'); ?>-800 
                                 text-xs px-2 py-1 rounded-full">
                                    <?php echo ucfirst($vehicle['vehicle_status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-bold text-gray-700"><?php echo $vehicle['vehicle_make']; ?></h3>
                            <p class="text-gray-600"><?php echo $vehicle['vehicle_model']; ?></p>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <span class="text-lg font-bold text-blue-600">₹ <?php echo $vehicle['daily_price']; ?></span>
                                    <span class="text-gray-500 text-sm">/day</span>
                                </div>
                                <div class="flex items-center text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1 text-gray-700">4.8</span>
                                </div>
                            </div>
                            <a href="vehicle_details.php?ref=<?php echo $vehicle['vehicle_id']?>" class="block mt-4 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                                View Details
                            </a>
                        </div>
                    </div>
                    <script>
                        // Store the daily price in a JavaScript variable
                        const dailyPrice = document.getElementById('daily_price').value;
                        
                        const weekPrices = document.getElementById('weekly_price').value;
                        const monthPrices =  document.getElementById('monthly_price').value;
                      
                        function calculate() {
                            const start = new Date(document.getElementById("start").value);
                            const end = new Date(document.getElementById("end").value);

                            if (isNaN(start) || isNaN(end)) {
                                document.getElementById("outputm").innerText = "0";
                                document.getElementById("outputw").innerText = "0";
                                document.getElementById("outputd").innerText = "0";
                                document.getElementById("tPrice").innerText = "₹0.00";
                                alert("Please select both dates.");
                                return;
                            }

                            if (end < start) {
                                document.getElementById("outputm").innerText = "0";
                                document.getElementById("outputw").innerText = "0";
                                document.getElementById("outputd").innerText = "0";
                                document.getElementById("tPrice").innerText = "₹0.00";

                                alert("End date must be after start date.");
                                return;
                            }

                            // Total days (inclusive)
                            const diffTime = end - start;
                            let totalDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;



                            // Break it into months, weeks, days
                            const months = Math.floor(totalDays / 30);
                            const remainingDaysAfterMonths = totalDays % 30;

                            const weeks = Math.floor(remainingDaysAfterMonths / 7);
                            const days = remainingDaysAfterMonths % 7;
                            // Calculate total price
                            const totalPrice = (days * dailyPrice) + (months * monthPrices) + (weeks * weekPrices);
                            // Update the display
                            document.getElementById("outputm").innerText = months;
                            document.getElementById("outputw").innerText = weeks;
                            document.getElementById("outputd").innerText = days;
                            document.getElementById("tPrice").innerText = "₹" + totalPrice.toFixed(2);
                            document.getElementById('priceInput').value = +totalPrice.toFixed(2);
                        }

                        // Make sure to call calculate() when your date inputs change
                        document.getElementById("start").addEventListener("change", calculate);
                        document.getElementById("end").addEventListener("change", calculate);
                    </script>
            <?php }
            } ?>

            </div>
    </div>
</main>
<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    // Initialize map with dealer location as default view
    const dealerLat = parseFloat(document.getElementById('destLat').value);
    const dealerLng = parseFloat(document.getElementById('destLng').value);
    const map = L.map('map').setView([dealerLat, dealerLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add dealer marker
    const dealerMarker = L.marker([dealerLat, dealerLng])
        .addTo(map)
        .bindPopup("Dealer Location")
        .openPopup();

    // Variables for routing
    let myLocation = null;
    let myMarker = null;
    let routeControl = null;

    function getMyLocation() {
        if (navigator.geolocation) {
            document.getElementById('info').innerHTML = "Getting your location...";
            navigator.geolocation.getCurrentPosition(
                position => {
                    myLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    document.getElementById('info').innerHTML =
                        `Your location: ${myLocation.lat.toFixed(6)}, ${myLocation.lng.toFixed(6)}`;

                    // Show on map
                    if (myMarker) map.removeLayer(myMarker);
                    myMarker = L.marker([myLocation.lat, myLocation.lng])
                        .addTo(map)
                        .bindPopup("You are here");

                    // Auto zoom to show both locations
                    const group = new L.featureGroup([dealerMarker, myMarker]);
                    map.fitBounds(group.getBounds().pad(0.5));
                },
                error => {
                    document.getElementById('info').innerHTML = "Error: " + error.message;
                }
            );
        } else {
            document.getElementById('info').innerHTML = "Geolocation not supported by your browser";
        }
    }

    function showRoute() {
        if (!myLocation) {
            alert("Please get your location first by clicking 'Use My Location'");
            return;
        }

        // Clear previous route if exists
        if (routeControl) map.removeControl(routeControl);

        // Create route
        routeControl = L.Routing.control({
            waypoints: [
                L.latLng(myLocation.lat, myLocation.lng),
                L.latLng(dealerLat, dealerLng)
            ],
            routeWhileDragging: false,
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            show: true,
            router: L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1'
            }),
            lineOptions: {
                styles: [{
                    color: '#3388ff',
                    opacity: 0.7,
                    weight: 5
                }]
            }
        }).addTo(map);
    }

    function clearRoute() {
        if (routeControl) {
            map.removeControl(routeControl);
            routeControl = null;
        }
        if (myMarker) {
            map.removeLayer(myMarker);
            myMarker = null;
        }
        document.getElementById('info').innerHTML = "";
        map.setView([dealerLat, dealerLng], 13);
    }
</script>
<?php
include('./includes/footer.php');
?>

<?php if (isset($_SESSION['razorpay_order_id'])): ?>
    <!-- Razorpay payment script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                "key": "<?= $keyId ?>",
                "amount": <?= $_SESSION['booking_amount'] * 100 ?>, // Amount in paise
                "currency": "INR",
                "name": "DRIVE EASY",
                "description": "Vehicle Booking Payment",
                "image": "https://your-website.com/logo.png", // Your company logo
                "order_id": "<?= $_SESSION['razorpay_order_id'] ?>",
                "handler": function(response) {
                    // On payment success, redirect to verification page
                    window.location.href = "verify_payment.php?payment_id=" +
                        response.razorpay_payment_id +
                        "&order_id=" + response.razorpay_order_id +
                        "&signature=" + response.razorpay_signature;
                },
                "prefill": {
                    "name": "<?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '' ?>",
                    "email": "<?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '' ?>",
                    "contact": "<?= isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '' ?>"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            var rzp = new Razorpay(options);

            // Auto-open payment modal if order ID exists
            rzp.open();

            // Also allow manual opening
            document.getElementById('rzp-button').onclick = function(e) {
                rzp.open();
                e.preventDefault();
            }
        });
    </script>

    <!-- Hidden button for manual trigger -->
    <button id="rzp-button" type="button" style="display:none;">Pay with Razorpay</button>

<?php
    // Clear the Razorpay order ID from session to prevent duplicate modals
    unset($_SESSION['razorpay_order_id']);
endif;
?>