<?php
include('../main/head.php');
session_start();

if (!isset($_SESSION["dealer_id"])) {
    header("location: pear_login.php");
    exit();
}

$dealerId = $_SESSION["dealer_id"];

$msg = "";

if (isset($_POST['vehicleAdd'])) {
    // Basic information
    $make = mysqli_real_escape_string($conn, $_POST['make']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $daily_price = mysqli_real_escape_string($conn, $_POST['daily_price']);
    $weekly_price = mysqli_real_escape_string($conn, $_POST['weekly_price']);
    $monthly_price = mysqli_real_escape_string($conn, $_POST['monthly_price']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $dealer_latitude = mysqli_real_escape_string($conn, $_POST['dealer_latitude']);
    $dealer_longitude = mysqli_real_escape_string($conn, $_POST['dealer_longitude']);
    // Handle main image upload
    $main_image = '';
    if(isset($_FILES['main_image']['name']) && !empty($_FILES['main_image']['name'])) {
        $main_image = time().'_'.mysqli_real_escape_string($conn, $_FILES['main_image']['name']);
        $temp_image = $_FILES['main_image']['tmp_name'];
        $imagePath = '../assets/img/vehicle/'.$main_image;
        
        if(!move_uploaded_file($temp_image, $imagePath)) {
            $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Error uploading main image</div>";
        }
    }
    
    // Handle additional images
    $additional_images = [];
    if(!empty($_FILES['additional_images']['name'][0])) {
        foreach($_FILES['additional_images']['name'] as $key => $name) {
            if(!empty($name)) {
                $temp_name = $_FILES['additional_images']['tmp_name'][$key];
                $image_name = time().'_'.mysqli_real_escape_string($conn, $name);
                $image_path = '../assets/img/vehicle/'.$image_name;
                
                if(move_uploaded_file($temp_name, $image_path)) {
                    $additional_images[] = $image_name;
                }
            }
        }
    }
    
    // Handle specifications
    $specifications = [];
    if(isset($_POST['spec_keys']) && isset($_POST['spec_values'])) {
        foreach($_POST['spec_keys'] as $key => $spec_key) {
            if(!empty($spec_key) && !empty($_POST['spec_values'][$key])) {
                $spec_key_escaped = mysqli_real_escape_string($conn, $spec_key);
                $spec_value_escaped = mysqli_real_escape_string($conn, $_POST['spec_values'][$key]);
                $specifications[] = "$spec_key_escaped: $spec_value_escaped";
            }
        }
    }
    
    // Handle features
    $features = [];
    if(isset($_POST['features'])) {
        foreach($_POST['features'] as $feature) {
            if(!empty(trim($feature))) {
                $features[] = mysqli_real_escape_string($conn, $feature);
            }
        }
    }
    
    // Convert arrays to strings for storage
    $additional_images_str = implode(',', $additional_images);
    $specifications_str = implode('|', $specifications);
    $features_str = implode('|', $features);
    
    // Generate vehicle ID
    $vehicle_id = 'VEH'.time();
    
    // Prepare the SQL query
    $qry = "INSERT INTO `vehicle` (
            `vehicle_id`, 
            `dealer_id`, 
            `vehicle_make`, 
            `vehicle_model`, 
            `vehicle_year`, 
            `daily_price`, 
            `weekly_price`, 
            `monthly_price`, 
            `vehicle_description`, 
            `main_image`, 
            `additional_images`, 
            `vehicle_specKey`, 
            `vehicle_features`, 
            `vehicle_type`, 
            `vehicle_status`,
            `dealer_longitude`, `dealer_latitude`
        ) VALUES (
            '$vehicle_id',
            '$dealerId',
            '$make',
            '$model',
            '$year',
            '$daily_price',
            '$weekly_price',
            '$monthly_price',
            '$description',
            '$main_image',
            '$additional_images_str',
            '$specifications_str',
            '$features_str',
            '$vehicle_type',
            '$status',
            '$dealer_longitude',
            '$dealer_latitude'
        )";
    
    $result = mysqli_query($conn, $qry);

    if ($result) {
        $msg = "<div class='mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded'>Vehicle added successfully!</div>";
    } else {
        $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Error adding vehicle: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!-- Main Content -->
<main class="bg-gray-50 min-h-screen">
    <!-- Page Header -->
    <div class="bg-white py-6 shadow-sm">
        <div class="container mx-auto px-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="dashbord.php" class="text-blue-600 hover:underline">Home</a>
                <span class="mx-2">/</span>
                <a href="#" class="text-blue-600 hover:underline">Admin</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600">Add Vehicle</span>
            </div>
            <div class="flex items-center justify-between"> 
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Add New Vehicle</h1>
            <a href="dashbord.php" class="bg-red-500 text-semibold  text-white rounded-md p-2">Back</a>
            </div>
            
        </div>
    </div>

    <!-- Add Vehicle Form -->
    <div class="container mx-auto px-6 py-8">
        <?php if(!empty($msg)) echo $msg; ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form id="addVehicleForm" class="p-6" enctype="multipart/form-data" method="POST" action="">
                <!-- Basic Information Section -->
                 <?php
                    $qry ="SELECT * FROM `dealer` WHERE dealer_id=$dealerId ";
                    $result = mysqli_query($conn, $qry);

                    $vehicle = mysqli_fetch_assoc($result);
                    // echo $vehicle['dealer_id'];
                    ?>
                   
                    <input type="hidden" name="dealer_longitude" value="<?php echo $vehicle['dealer_longitude']; ?>">
                    <input type="hidden" name="dealer_latitude" value="<?php echo $vehicle['dealer_latitude']; ?>">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="make" class="block text-gray-700 font-medium mb-2">Make*</label>
                            <input type="text" id="make" name="make" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="model" class="block text-gray-700 font-medium mb-2">Model*</label>
                            <input type="text" id="model" name="model" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="year" class="block text-gray-700 font-medium mb-2">Year*</label>
                            <input type="number" id="year" name="year" min="2000" max="<?= date('Y') + 1 ?>" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Pricing Section -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-medium mb-2">Rental Prices*</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="daily_price" class="block text-sm text-gray-500 mb-1">Daily (₹)</label>
                                    <input type="number" id="daily_price" name="daily_price" min="0" step="50" required 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="weekly_price" class="block text-sm text-gray-500 mb-1">Weekly (₹)</label>
                                    <input type="number" id="weekly_price" name="weekly_price" min="0" step="50" required 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="monthly_price" class="block text-sm text-gray-500 mb-1">Monthly (₹)</label>
                                    <input type="number" id="monthly_price" name="monthly_price" min="0" step="50" required 
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="vehicle_type" class="block text-gray-700 font-medium mb-2">Vehicle Type*</label>
                            <select id="vehicle_type" name="vehicle_type" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="bike">Bike</option>
                                <option value="scooter">Scooter</option>
                                <option value="car">Car</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-gray-700 font-medium mb-2">Status*</label>
                            <select id="status" name="status" required 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                                <option value="maintenance">Under Maintenance</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="description" class="block text-gray-700 font-medium mb-2">Description*</label>
                        <textarea id="description" name="description" rows="4" required 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Images</h2>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Main Image*</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="main_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="mb-2 text-sm text-gray-500">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP (Max 5MB)</p>
                                </div>
                                <input id="main_image" name="main_image" type="file" class="hidden" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Additional Images (Max 5)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="additionalImagesContainer">
                            <!-- Image upload boxes will be added here dynamically -->
                        </div>
                        <button type="button" id="addImageBtn" class="mt-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-plus mr-2"></i>Add Another Image
                        </button>
                    </div>
                </div>

                <!-- Specifications Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Specifications</h2>
                    
                    <div id="specsContainer">
                        <!-- Specification fields will be added here dynamically -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <input type="text" name="spec_keys[]" placeholder="Specification (e.g., Engine)" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" name="spec_values[]" placeholder="Value (e.g., 349cc Single Cylinder)" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="addSpecBtn" class="mt-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-plus mr-2"></i>Add Another Specification
                    </button>
                </div>

                <!-- Features Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Features</h2>
                    
                    <div id="featuresContainer">
                        <!-- Feature fields will be added here dynamically -->
                        <div class="flex items-center mb-2">
                            <input type="text" name="features[]" placeholder="Feature (e.g., ABS)" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="remove-feature-btn ml-2 text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" id="addFeatureBtn" class="mt-2 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-plus mr-2"></i>Add Another Feature
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="/admin" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" name="vehicleAdd" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add another image upload field
    document.getElementById('addImageBtn').addEventListener('click', function() {
        const container = document.getElementById('additionalImagesContainer');
        if (container.children.length >= 5) {
            alert('Maximum 5 additional images allowed');
            return;
        }
        
        const newImageDiv = document.createElement('div');
        newImageDiv.className = 'relative';
        newImageDiv.innerHTML = `
            <div class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <i class="fas fa-cloud-upload-alt text-xl text-gray-400 mb-2"></i>
                    <p class="text-xs text-gray-500">Additional Image</p>
                </div>
                <input type="file" name="additional_images[]" class="hidden" accept="image/*">
            </div>
            <button type="button" class="absolute top-2 right-2 bg-white rounded-full p-1 text-red-500 hover:text-red-700 shadow-sm">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add event listener for the new file input
        const fileInput = newImageDiv.querySelector('input[type="file"]');
        const uploadDiv = newImageDiv.querySelector('div');
        uploadDiv.addEventListener('click', () => fileInput.click());
        
        // Add event listener for the remove button
        const removeBtn = newImageDiv.querySelector('button');
        removeBtn.addEventListener('click', () => container.removeChild(newImageDiv));
        
        container.appendChild(newImageDiv);
    });
    
    // Add another specification field
    document.getElementById('addSpecBtn').addEventListener('click', function() {
        const container = document.getElementById('specsContainer');
        const newSpecDiv = document.createElement('div');
        newSpecDiv.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 mb-4';
        newSpecDiv.innerHTML = `
            <div>
                <input type="text" name="spec_keys[]" placeholder="Specification" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2 flex items-center">
                <input type="text" name="spec_values[]" placeholder="Value" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" class="ml-2 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add event listener for the remove button
        const removeBtn = newSpecDiv.querySelector('button');
        removeBtn.addEventListener('click', () => container.removeChild(newSpecDiv));
        
        container.appendChild(newSpecDiv);
    });
    
    // Add another feature field
    document.getElementById('addFeatureBtn').addEventListener('click', function() {
        const container = document.getElementById('featuresContainer');
        const newFeatureDiv = document.createElement('div');
        newFeatureDiv.className = 'flex items-center mb-2';
        newFeatureDiv.innerHTML = `
            <input type="text" name="features[]" placeholder="Feature" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" class="remove-feature-btn ml-2 text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add event listener for the remove button
        const removeBtn = newFeatureDiv.querySelector('.remove-feature-btn');
        removeBtn.addEventListener('click', () => container.removeChild(newFeatureDiv));
        
        container.appendChild(newFeatureDiv);
    });
    
    // Handle main image upload preview
    const mainImageInput = document.getElementById('main_image');
    const mainImageLabel = mainImageInput.previousElementSibling;
    
    mainImageInput.addEventListener('change', function(e) {
        if (e.target.files.length) {
            const reader = new FileReader();
            reader.onload = function(event) {
                mainImageLabel.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-64 object-contain rounded-lg" alt="Preview">
                `;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    
    // Auto-calculate weekly/monthly prices when daily price changes
    document.getElementById('daily_price').addEventListener('input', function(e) {
        const dailyPrice = parseFloat(e.target.value) || 0;
        const weeklyPrice = document.getElementById('weekly_price');
        const monthlyPrice = document.getElementById('monthly_price');
        
        // Apply 10% discount for weekly, 15% for monthly
        weeklyPrice.value = (dailyPrice * 7 * 0.9).toFixed(0);
        monthlyPrice.value = (dailyPrice * 30 * 0.85).toFixed(0);
    });
});
</script>

<?php
include('../main/footer.php');
?>










