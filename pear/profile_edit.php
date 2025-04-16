<?php
include '../main/head.php';

?>
<?php
session_start();
if (!isset($_SESSION["dealer_id"])) {
    header("location: pear_login.php");
}

// Include database connection (assuming it's in 'db.php')


// Initialize variables to store dealer details and error message
$dealerDetails = null;
$errorMessage = "";
$msg = ""; // Initialize message variable

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

// update the dealer profile
if (isset($_POST['updateDealerProfile'])) {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['streetAddress']);
    $about = mysqli_real_escape_string($conn, $_POST['about']);
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $zipcode = mysqli_real_escape_string($conn, $_POST['postalCode']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    $image = mysqli_real_escape_string($conn, $_FILES['profileImage']['name']);
    $tempimage = $_FILES['profileImage']['tmp_name'];
    $imagePath = '../assets/img/dealer/' . $image;
    $existingImage = $dealerDetails['dealer_Image']; // Get the current image name

    $qry = "UPDATE `dealer` SET
            `dealer_fname` = '$firstName',
            `dealer_lname` = '$lastName',
            `dealer_email` = '$email',
            `dealer_phone` = '$phone',
            `dealer_address` = '$address',
            `dealer_about` = '$about',
            `dealer_latitude` = '$latitude',
            `dealer_longitude` = '$longitude',
            `dealer_city` = '$city',
            `dealer_district` = '$district',
            `dealer_zipcode` = '$zipcode',
            `dealer_state` = '$state',
            `dealer_country` = '$country'";

    if (!empty($image)) {
        $qry .= ", `dealer_Image` = '$image'";
    }

    $qry .= " WHERE `dealer_id` = $dealerId";

    $updateResult = mysqli_query($conn, $qry);

    if ($updateResult) {
        $msg = "<div class='mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded'>Dealer profile updated successfully!</div>";
        // Move the uploaded image if a new one was provided
        if (!empty($tempimage)) {
            // Delete the old image if it exists and is different from the new one
            if (!empty($existingImage) && file_exists('../assets/img/dealer/' . $existingImage) && $existingImage != $image) {
                unlink('../assets/img/dealer/' . $existingImage);
            }
            move_uploaded_file($tempimage, $imagePath);
        }
        // Refresh dealer details after update
        $sql = "SELECT * FROM `dealer` WHERE `dealer_id` = $dealerId";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) == 1) {
            $dealerDetails = mysqli_fetch_assoc($result);
            header("Location: pear_dashbord.php");
        }
    } else {
        $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Error updating dealer profile: " . mysqli_error($conn) . "</div>";
    }
}
?>











<!-- Edit Profile Section -->
<section class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Card Container -->
        <div class="bg-white w-full py-8 px-8 shadow-xl rounded-xl sm:px-12">
            <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Dealer Profile</h2>
            <a href="dashbord.php" class="bg-red-500 text-semibold p-2 rounded-md text-white font-semibold">Back</a>
            </div>
            
            <form id="dealerEditForm" action="" method="POST" class="mb-0 space-y-6" enctype="multipart/form-data">
                <?php 
                    if(!empty($msg)){
                        echo '<div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">' . $msg . '</div>';
                    }
                ?>
                <!-- get the dealer id -->
                <input type="hidden" value="<?php echo  $dealerDetails['dealer_id']; ?>" name="dealer_id"> 
                
                <!-- Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First name</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="firstName" name="firstName" type="text" required 
                                   class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                   placeholder="John" value="<?php echo  $dealerDetails['dealer_fname']; ?>">
                        </div>
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last name</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="lastName" name="lastName" type="text" required 
                                   class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                   placeholder="Doe" value="<?php echo  $dealerDetails['dealer_lname']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                placeholder="you@example.com" value="<?php echo  $dealerDetails['dealer_email']; ?>">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone number</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone" name="phone" type="tel" required 
                                class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                placeholder="+1 (555) 123-4567" value="<?php echo  $dealerDetails['dealer_phone']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Image Upload Field -->
                <div>
                    <label for="profileImage" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                    <div class="flex items-center">
                        <div class="relative rounded-lg overflow-hidden w-24 h-24 bg-gray-200 mr-4">
                            <?php if(isset($dealerDetails['dealer_image']) && !empty($dealerDetails['dealer_image'])): ?>
                                <img src="<?php echo  $dealerDetails['dealer_image']; ?>" alt="Profile Image" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-user-circle text-4xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <input id="profileImage" name="profileImage" type="file" accept="image/*"
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div>
                    <label for="about" class="block text-sm font-medium text-gray-700 mb-2">About Your Business</label>
                    <textarea id="about" name="about" rows="4" required 
                        class="py-3 px-4 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        placeholder="Tell us about your business..."><?php echo  $dealerDetails['dealer_about']; ?></textarea>
                </div>

                <!-- Location Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Business Location</h3>
                    
                    <!-- Location Toggle -->
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input id="useCurrentLocation" name="useCurrentLocation" type="checkbox" 
                                   class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded-lg">
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="useCurrentLocation" class="font-medium text-gray-700">Use my current location</label>
                            <p class="text-gray-500">This will automatically fill your address details</p>
                        </div>
                    </div>
                    
                    <!-- Full Address Fields -->
                    <div class="space-y-6" id="locationFields">
                        <!-- Street Address -->
                        <div>
                            <label for="streetAddress" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-road text-gray-400"></i>
                                </div>
                                <input id="streetAddress" name="streetAddress" type="text" required 
                                    class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    placeholder="123 Main St" value="<?php echo  $dealerDetails['dealer_address']; ?>">
                            </div>
                        </div>
                        
                        <!-- City/District/Postal Code -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-city text-gray-400"></i>
                                    </div>
                                    <input id="city" name="city" type="text" required 
                                        class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        placeholder="City" value="<?php echo  $dealerDetails['dealer_city']; ?>">
                                </div>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-pin text-gray-400"></i>
                                    </div>
                                    <input id="district" name="district" type="text" 
                                        class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        placeholder="District" value="<?php echo  $dealerDetails['dealer_district']; ?>">
                                </div>
                            </div>
                            <div>
                                <label for="postalCode" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-mail-bulk text-gray-400"></i>
                                    </div>
                                    <input id="postalCode" name="postalCode" type="text" required 
                                        class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        placeholder="12345" value="<?php echo  $dealerDetails['dealer_zipcode']; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- State/Region and Country -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Region</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input id="state" name="state" type="text" required 
                                        class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        placeholder="State/Region" value="<?php echo  $dealerDetails['dealer_state']; ?>">
                                </div>
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-globe text-gray-400"></i>
                                    </div>
                                    <input id="country" name="country" type="text" required 
                                        class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        placeholder="Country" value="<?php echo  $dealerDetails['dealer_country']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="latitude" name="latitude" value="<?php echo  $dealerDetails['dealer_latitude']; ?>"> 
                <input type="hidden" id="longitude" name="longitude" value="<?php echo  $dealerDetails['dealer_longitude']; ?>"> 
                
                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" name="updateDealerProfile"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const useCurrentLocation = document.getElementById('useCurrentLocation');
    
    // Check if geolocation is available
    if (!navigator.geolocation) {
        useCurrentLocation.disabled = true;
        useCurrentLocation.parentNode.innerHTML += '<p class="text-red-500 text-xs mt-1">Geolocation is not supported by your browser</p>';
    }
    
    // Handle location toggle
    useCurrentLocation.addEventListener('change', function() {
        if (this.checked) {
            // Show loading state
            const loadingText = document.createElement('p');
            loadingText.className = 'text-blue-500 text-xs mt-1';
            loadingText.textContent = 'Fetching your location...';
            this.parentNode.parentNode.appendChild(loadingText);
            
            // Get current position
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Store coordinates
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    
                    // Reverse geocode to get address details
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            // Fill form fields with complete address data
                            const address = data.address || {};
                            document.getElementById('streetAddress').value = 
                                [address.road, address.house_number].filter(Boolean).join(' ') || '';
                            document.getElementById('city').value = address.city || address.town || address.village || '';
                            document.getElementById('district').value = address.county || address.state_district || '';
                            document.getElementById('state').value = address.state || address.region || '';
                            document.getElementById('country').value = address.country || '';
                            document.getElementById('postalCode').value = address.postcode || '';
                            
                            // Remove loading text
                            loadingText.remove();
                        })
                        .catch(error => {
                            console.error('Error fetching location data:', error);
                            loadingText.textContent = 'Error fetching address details. Please enter manually.';
                            loadingText.className = 'text-red-500 text-xs mt-1';
                            useCurrentLocation.checked = false;
                        });
                },
                function(error) {
                    console.error('Error getting location:', error);
                    loadingText.textContent = 'Error getting location. Please enable location services or enter manually.';
                    loadingText.className = 'text-red-500 text-xs mt-1';
                    useCurrentLocation.checked = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    });
    
    // Form validation
    document.getElementById('dealerEditForm').addEventListener('submit', function(e) {
        // Validate required address fields
        const requiredFields = [
            'streetAddress', 'city', 'state', 'country', 'postalCode'
        ];
        
        let isValid = true;
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        // Validate image file size if selected
        const imageInput = document.getElementById('profileImage');
        if (imageInput.files.length > 0) {
            const fileSize = imageInput.files[0].size / 1024 / 1024; // in MB
            if (fileSize > 2) {
                alert('Image size must be less than 2MB');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Preview image when selected
    document.getElementById('profileImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const previewContainer = document.querySelector('.relative.rounded-lg.overflow-hidden.w-24.h-24');
                previewContainer.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

<?php
include('../main/footer.php');
?>