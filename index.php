<?php
include('./includes/header.php');
// Database connection and location handling
if (isset($_GET['setlocation'])) {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    header("Location: vehicle_profile.php?ref=$location");
    exit();
}
// Include navbar file
include('./includes/navbar.php');
?>

<!-- Hero Section -->
<section class="bg-cover w-full min-h-screen bg-center relative flex items-center justify-center" style="background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')">
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <div class="relative z-10 w-full max-w-6xl mx-auto text-center px-4">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight animate-fadeIn">
            Vehicle Rental Services
        </h1>
        <p class="text-gray-200 text-xl md:text-2xl lg:text-3xl mt-4 animate-fadeIn delay-100">
            Rent from India's Largest Fleet of Vehicles, Trusted by millions.
        </p>

        <div class="flex flex-wrap gap-4 mt-8 justify-center animate-fadeIn delay-200">
            <a href="vehicle_profile.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                Rent Vehicle
            </a>
            <a href="map.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                Map View
            </a>
        </div>

        <!-- Booking Form -->
        <div class="mt-8 lg:mt-20 w-full mx-auto bg-white bg-opacity-20 backdrop-blur-md p-6 md:p-8 rounded-xl border border-white border-opacity-30 shadow-2xl animate-fadeInUp delay-300">
            <form method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- City Input -->
                <div>
                    <label class="block text-gray-100 text-left text-md font-bold mb-2">City</label>
                    <input type="text" name="location" value="Kolkata" class="w-full bg-white bg-opacity-20 border-b-2 border-blue-400 text-white placeholder-gray-300 text-md font-medium focus:outline-none focus:border-blue-300 transition duration-300 p-2 rounded-t" required>
                </div>

                <!-- Pick Up Date -->
                <div>
                    <label class="block text-gray-100 text-md font-bold text-left mb-2">Pick Up Date</label>
                    <input type="date" value="<?php echo date('Y-m-d'); ?>" class="w-full bg-white bg-opacity-20 border-b-2 border-blue-400 text-white text-md font-medium focus:outline-none focus:border-blue-300 transition duration-300 p-2 rounded-t">
                </div>

                <!-- Pick Time -->
                <div>
                    <label class="block text-gray-100 text-md text-left font-bold mb-2">Pick Time</label>
                    <input type="time" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date('H:i'); ?>" class="w-full bg-white bg-opacity-20 border-b-2 border-blue-400 text-white text-md font-medium focus:outline-none focus:border-blue-300 transition duration-300 p-2 rounded-t">
                </div>

                <!-- Drop Off Date -->
                <div>
                    <label class="block text-gray-100 text-md text-left font-bold mb-2">Drop Off Date</label>
                    <input type="date" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full bg-white bg-opacity-20 border-b-2 border-blue-400 text-white text-md font-medium focus:outline-none focus:border-blue-300 transition duration-300 p-2 rounded-t">
                </div>

                <!-- Drop Time -->
                <div>
                    <label class="block text-gray-100 text-md text-left font-bold mb-2">Drop Time</label>
                    <input type="time" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date('H:i'); ?>" class="w-full bg-white bg-opacity-20 border-b-2 border-blue-400 text-white text-md font-medium focus:outline-none focus:border-blue-300 transition duration-300 p-2 rounded-t">
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" name="setlocation" class="w-full text-white bg-gradient-to-br from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-bold rounded-lg text-md px-5 py-3.5 text-center transition-all duration-300 shadow-lg hover:shadow-xl">
                        Find Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out forwards;
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
</style>

<!-- Popular Models Section -->
<section id="popular-models" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center relative">
                <div class="absolute -left-16 w-12 h-1 bg-blue-500"></div>
                <lord-icon src="https://cdn.lordicon.com/xfyxpoer.json" trigger="hover" colors="primary:#3b82f6" style="width:50px;height:50px"></lord-icon>
                <div class="absolute -right-16 w-12 h-1 bg-blue-500"></div>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mt-4">Popular Rental Models</h2>
            <p class="text-lg text-gray-600 mt-2 max-w-2xl mx-auto">
                Choose from our most popular and reliable vehicles
            </p>
        </div>

        <?php
        $query = "SELECT * FROM vehicle WHERE vehicle_status='available'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            echo '<div class="text-center py-12"><h3 class="text-xl text-gray-600">No vehicles available at the moment. Please check back later.</h3></div>';
        } else {
        ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php while ($vehicle = mysqli_fetch_assoc($result)) {
                    $status_color = [
                        'available' => 'green',
                        'unavailable' => 'red',
                        'maintenance' => 'yellow'
                    ][$vehicle['vehicle_status']] ?? 'gray';
                ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="relative h-56 overflow-hidden">
                            <img src="./assets/img/vehicle/<?php echo htmlspecialchars($vehicle['main_image']); ?>"
                                alt="<?php echo htmlspecialchars($vehicle['vehicle_make'] . ' ' . $vehicle['vehicle_model']); ?>"
                                class="w-full h-full object-cover transition duration-500 hover:scale-110">
                            <div class="absolute top-4 right-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-<?php echo $status_color; ?>-100 text-<?php echo $status_color; ?>-800">
                                    <?php echo ucfirst($vehicle['vehicle_status']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold text-gray-900">
                                    <?php echo htmlspecialchars($vehicle['vehicle_make'] . ' ' . htmlspecialchars($vehicle['vehicle_model'])); ?>
                                </h3>
                                <div class="flex items-center text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1 text-gray-700">4.8</span>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-between items-center">
                                <div>
                                    <span class="text-2xl font-bold text-blue-600">₹<?php echo number_format($vehicle['daily_price']); ?></span>
                                    <span class="text-gray-500">/Day</span>
                                </div>
                                <a href="vehicle_details.php?ref=<?php echo $vehicle['vehicle_id']; ?>"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-full transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                    View All Vehicles <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php } ?>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center relative">
                <div class="absolute -left-16 w-12 h-1 bg-blue-500"></div>
                <lord-icon src="https://cdn.lordicon.com/wloilxuq.json" trigger="hover" colors="primary:#3b82f6" style="width:50px;height:50px"></lord-icon>
                <div class="absolute -right-16 w-12 h-1 bg-blue-500"></div>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mt-4">Why Choose Us?</h2>
            <p class="text-lg text-gray-600 mt-2 max-w-2xl mx-auto">
                We're committed to providing the best rental experience
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $benefits = [
                [
                    'icon' => 'fas fa-tachometer-alt',
                    'title' => 'No Riding Limits',
                    'desc' => 'Ride as much as you want with no mileage restrictions.'
                ],
                [
                    'icon' => 'fas fa-helmet-safety',
                    'title' => 'Free Safety Gear',
                    'desc' => 'High-quality helmets and safety equipment at no extra cost.'
                ],
                [
                    'icon' => 'fas fa-shield-alt',
                    'title' => 'Secure Payments',
                    'desc' => 'Bank-level security for all transactions.'
                ],
                [
                    'icon' => 'fas fa-clock',
                    'title' => '24/7 Support',
                    'desc' => 'Our team is available round the clock to assist you.'
                ],
                [
                    'icon' => 'fas fa-user-check',
                    'title' => 'Verified Partners',
                    'desc' => 'Every dealer is thoroughly vetted for quality service.'
                ],
                [
                    'icon' => 'fas fa-money-bill-wave',
                    'title' => 'Money Back Guarantee',
                    'desc' => '100% money-back guarantee if not satisfied.'
                ]
            ];

            foreach ($benefits as $benefit) {
                echo '
                <div class="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="' . $benefit['icon'] . ' text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">' . $benefit['title'] . '</h3>
                    <p class="text-gray-600">' . $benefit['desc'] . '</p>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center relative">
                <div class="absolute -left-16 w-12 h-1 bg-blue-500"></div>
                <lord-icon src="https://cdn.lordicon.com/wfadduyp.json" trigger="hover" colors="primary:#3b82f6" style="width:50px;height:50px"></lord-icon>
                <div class="absolute -right-16 w-12 h-1 bg-blue-500"></div>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mt-4">How It Works</h2>
            <p class="text-lg text-gray-600 mt-2 max-w-2xl mx-auto">
                Rent a vehicle in just 4 simple steps
            </p>
        </div>

        <div class="relative">
            <div class="hidden lg:block absolute left-1/2 top-0 h-full w-1 bg-blue-200 transform -translate-x-1/2"></div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <?php
                $steps = [
                    [
                        'num' => 1,
                        'img' => 'select-a-bike.webp',
                        'title' => 'Select Your Vehicle',
                        'desc' => 'Browse our wide selection to find your perfect ride.'
                    ],
                    [
                        'num' => 2,
                        'img' => 'select-to-cart.webp',
                        'title' => 'Book Online',
                        'desc' => 'Reserve your vehicle with our easy online system.'
                    ],
                    [
                        'num' => 3,
                        'img' => 'pick-up.webp',
                        'title' => 'Pick Up Your Ride',
                        'desc' => 'Collect your vehicle with all required documents.'
                    ],
                    [
                        'num' => 4,
                        'img' => 'ride-your-bike.webp',
                        'title' => 'Enjoy Your Ride',
                        'desc' => 'Hit the road with no mileage limits.'
                    ]
                ];

                foreach ($steps as $step) {
                    echo '
                    <div class="relative z-10 bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
                        <div class="absolute -top-4 -left-4 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            ' . $step['num'] . '
                        </div>
                        <div class="w-16 h-16 mx-auto mb-4">
                            <img src="./assets/Images/' . $step['img'] . '" alt="' . $step['title'] . '" class="w-full h-full object-contain">
                        </div>
                        <h3 class="text-lg font-bold text-center text-gray-900 mb-2">' . $step['title'] . '</h3>
                        <p class="text-gray-600 text-center text-sm">
                            ' . $step['desc'] . '
                        </p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center relative">
                <div class="absolute -left-16 w-12 h-1 bg-blue-500"></div>
                <lord-icon src="https://cdn.lordicon.com/gqzfzudq.json" trigger="hover" colors="primary:#3b82f6" style="width:50px;height:50px"></lord-icon>
                <div class="absolute -right-16 w-12 h-1 bg-blue-500"></div>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mt-4">What Our Customers Say</h2>
            <p class="text-lg text-gray-600 mt-2 max-w-2xl mx-auto">
                Don't just take our word for it - hear from our satisfied customers
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $testimonials = [
                [
                    'img' => 'men/32.jpg',
                    'name' => 'Rahul Sharma',
                    'rating' => 5,
                    'quote' => 'The bike was in perfect condition and the pickup process was super smooth.'
                ],
                [
                    'img' => 'women/44.jpg',
                    'name' => 'Priya Patel',
                    'rating' => 5,
                    'quote' => 'Excellent service! The car was clean, well-maintained, and exactly as described.'
                ],
                [
                    'img' => 'men/75.jpg',
                    'name' => 'Amit Singh',
                    'rating' => 4.5,
                    'quote' => 'I\'ve rented bikes from several places, but this was by far the best experience.'
                ]
            ];

            foreach ($testimonials as $testimonial) {
                echo '
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <img src="https://randomuser.me/api/portraits/' . $testimonial['img'] . '" alt="' . $testimonial['name'] . '" class="w-12 h-12 rounded-full object-cover">
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-bold text-gray-900">' . $testimonial['name'] . '</h4>
                            <div class="flex items-center text-yellow-400 mt-1">';

                $fullStars = floor($testimonial['rating']);
                $halfStar = ($testimonial['rating'] - $fullStars) >= 0.5;

                for ($i = 0; $i < $fullStars; $i++) {
                    echo '<i class="fas fa-star text-xs"></i>';
                }
                if ($halfStar) {
                    echo '<i class="fas fa-star-half-alt text-xs"></i>';
                }

                echo '
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic text-sm">
                        "' . $testimonial['quote'] . '"
                    </p>
                </div>';
            }
            ?>
        </div>

        <div class="text-center mt-12">
            <a href="/testimonials" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-full shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                Read More Reviews <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-blue-600">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready for Your Next Adventure?</h2>
        <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
            Join thousands of happy customers who've explored with our vehicles
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/vehicle_profile.php" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-full hover:bg-gray-100 transition shadow-lg">
                <i class="fas fa-motorcycle mr-2"></i> Rent a Bike Now
            </a>
            <a href="/cars" class="px-6 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-full hover:bg-white/10 transition">
                <i class="fas fa-car mr-2"></i> Rent a Car Now
            </a>
        </div>
    </div>
</section>

<?php
include('./includes/footer.php');
?>