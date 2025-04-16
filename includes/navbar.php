
<!-- Enhanced Navigation -->
<nav class="bg-gray-900 shadow-lg  top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo and brand name -->
            <div class="flex-shrink-0 flex items-center">
                <a href="#" class="flex items-center">
                    <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 12h3v9h6v-6h4v6h6v-9h3L12 2z" />
                    </svg>
                    <span class="ml-2 text-2xl font-bold text-white">DRIVE<span class="text-blue-400">EASY</span></span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])) {
                    $ref_id = $_SESSION['user_id'];
                    $queryu = "SELECT * FROM user WHERE user_id = '$ref_id'";
                    $resultu = mysqli_query($conn, $queryu);
                    $user = mysqli_fetch_assoc($resultu);
                ?>
                    <a href="user/my_profile.php" class="flex items-center space-x-2 group">
                        <div class="relative">
                            <img src="https://static.vecteezy.com/system/resources/previews/013/042/571/large_2x/default-avatar-profile-icon-social-media-user-photo-in-flat-style-vector.jpg"
                                alt="User profile"
                                class="h-8 w-8 rounded-full object-cover border-2 border-blue-400 group-hover:border-blue-300 transition-all">
                        </div>
                        <span class="text-white font-medium group-hover:text-blue-300 transition-colors">
                            <?php echo htmlspecialchars($user['user_name']); ?>
                        </span>
                    </a>
                <?php } else { ?>
                    <div class="flex items-center space-x-3">
                        <a href="user/user_login.php" class="px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors font-medium">
                            Login
                        </a>
                        <a href="user/user_reg.php" class="px-4 py-2 rounded-md text-blue-100 bg-blue-800 hover:bg-blue-700 transition-colors font-medium">
                            Register
                        </a>
                    </div>
                <?php } ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div class="hidden mobile-menu bg-gray-800 md:hidden">
        <?php if (isset($_SESSION['user_id'])) { ?>
            <div class="px-2 pt-2 pb-3 space-y-1">
                <div class="flex items-center px-3 py-3 border-b border-gray-700">
                    <img src="https://static.vecteezy.com/system/resources/previews/013/042/571/large_2x/default-avatar-profile-icon-social-media-user-photo-in-flat-style-vector.jpg"
                        alt="User profile"
                        class="h-10 w-10 rounded-full object-cover mr-3">
                    <span class="text-white font-medium"><?php echo htmlspecialchars($user['user_name']); ?></span>
                </div>
                <a href="user/my_profile.php" class="block px-3 py-3 text-white hover:bg-gray-700 rounded-md">My Profile</a>
            </div>
        <?php } else { ?>
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="user/user_login.php" class="block px-3 py-3 text-white hover:bg-gray-700 rounded-md">Login</a>
                <a href="user/user_reg.php" class="block px-3 py-3 text-blue-300 hover:bg-gray-700 rounded-md">Register</a>
            </div>
        <?php } ?>
    </div>
</nav>



<script>
    // Enhanced mobile menu toggle
    const btn = document.querySelector(".mobile-menu-button");
    const menu = document.querySelector(".mobile-menu");
    
    btn.addEventListener("click", () => {
        const expanded = btn.getAttribute("aria-expanded") === "true";
        btn.setAttribute("aria-expanded", !expanded);
        menu.classList.toggle("hidden");
        
        // Toggle icon
        if (!expanded) {
            btn.innerHTML = `
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            `;
        } else {
            btn.innerHTML = `
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            `;
        }
    });
</script>

