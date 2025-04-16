<?php
include "../config/dbcon.php";
// Initialize message variable
$msg = "";

if (isset($_POST['userLogin'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $msg = "<div class='mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded'>Please enter both email and password.</div>";
    } else {
        // Query to check if the dealer exists
        $qry = "SELECT `user_id`, `user_email`, `user_password` FROM `user` WHERE `user_email` = '$email'";
        $result = mysqli_query($conn, $qry);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                // Verify the password
                if ($password == $row['user_password']) {
                    // Password is correct, you should start a session here
                    session_start();
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_email'] = $row['user_email'];
                    // Redirect the dealer to a dashboard or welcome page
                    if (isset($_GET['redirect'])) {
                        $refurl =$_GET['redirect'];
                        header("Location: $refurl");
                    }else{
                        header("Location: ../index.php"); // Replace with your actual dashboard page
                    }
                    exit();
                } else {
                    // Incorrect password
                    $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Incorrect password.</div>";
                }
            } else {
                // Dealer not found
                $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>User with this email not found.</div>";
            }
        } else {
            // Error in the query
            $msg = "<div class='mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded'>Error during login: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<?php include '../main/head.php'; ?>
<section class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-10">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center shadow-lg mb-4">
                <i class="fas fa-car text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">User Login</h2>
            <p class="mt-2 text-sm text-gray-600">
                Access your  account
            </p>
        </div>

        <div class="bg-white py-8 px-8 shadow-xl rounded-xl sm:px-10">

            <form method="POST" class="space-y-6">
                <?php if (!empty($msg)): ?>
                    <?php echo $msg; ?>
                <?php endif; ?>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required
                               class="py-3 pl-10 block w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                               placeholder="you@example.com"
                               value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    </div>
                </div>

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
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" name="userLogin"
                            class="w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            Don't have an account?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="user_reg.php" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Register 
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../main/footer.php'; ?>