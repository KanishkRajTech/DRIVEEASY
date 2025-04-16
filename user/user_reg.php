<?php
include('../main/head.php');
?>
<?php 
include "../config/dbcon.php";
if (isset($_POST['userRegistration'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);

    $qry = "INSERT INTO `user`( `user_name`, `user_email`, `user_password`, `user_phone`) VALUES ('$name','$email','$password','$phone')";
    $result = mysqli_query($conn, $qry);

    if ($result) {
        $msg = "<div class='mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded'>Dealer registered successfully!</div>";
        header("Location: user_login.php");
        
    } else {
        $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Error registering dealer: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!-- Registration Form Section -->
<section class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">

        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg mb-4">
                <i class="fas fa-car text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">User Registration</h2>
            <p class="mt-2 text-sm text-gray-600">
                Book your bike
            </p>
        </div>

        <!-- Card Container -->
        <div class="bg-white w-full py-8 px-8 shadow-xl rounded-xl sm:px-12">
            <form id="dealerRegistrationForm" action="" method="POST" class="mb-0 space-y-6">
                <?php
                if (!empty($msg)) {
                    echo  $msg;
                }
                ?>
                <!-- Name Fields -->

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">First name</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" required
                            class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            placeholder="John" value="">
                    </div>
                </div>



                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            placeholder="you@example.com" value="">
                    </div>
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone number</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input id="phone" name="phone" type="tel" required
                            class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            placeholder="+1 (555) 123-4567" value="">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            placeholder="••••••••">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">At least 8 characters with one number and one symbol</p>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm password</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="confirmPassword" name="confirmPassword" type="password" required
                            class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                            class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded-lg">
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">terms</a> and <a href="#" class="text-blue-600 hover:text-blue-500">privacy policy</a></label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" name="userRegistration"
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Create an account
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-8 text-center text-sm leading-5">
                <span class="text-gray-600">Already have an account? </span>
                <a href="pear_login.php" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    Sign in
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('dealerRegistrationForm').addEventListener('submit', function(e) {
        // Get form values
        const form = e.target;
        const password = form.password.value;
        const confirmPassword = form.confirmPassword.value;
        const termsChecked = form.terms.checked;

        // Clear previous errors
        const existingError = document.getElementById('form-error');
        if (existingError) existingError.remove();

        // Validate password match
        if (password !== confirmPassword) {
            showError('Passwords do not match');
            e.preventDefault();
            return;
        }

        // Validate password strength
        if (password.length < 8) {
            showError('Password must be at least 8 characters');
            e.preventDefault();
            return;
        }

        if (!/\d/.test(password) || !/[!@#$%^&*]/.test(password)) {
            showError('Password must contain at least one number and one special character');
            e.preventDefault();
            return;
        }

        // Validate terms
        if (!termsChecked) {
            showError('You must accept the terms and conditions');
            e.preventDefault();
            return;
        }
    });

    function showError(message) {
        // Create error message display
        const errorDiv = document.createElement('div');
        errorDiv.id = 'form-error';
        errorDiv.className = 'mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded';
        errorDiv.textContent = message;

        // Insert at the top of the form
        const form = document.getElementById('dealerRegistrationForm');
        form.insertBefore(errorDiv, form.firstChild);
    }
</script>

<?php
include('../main/footer.php');
?>