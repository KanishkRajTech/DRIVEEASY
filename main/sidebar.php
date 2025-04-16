<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-blue-800 text-white">
        <div class="flex items-center justify-center h-16 px-4 bg-blue-900">
            <span class="text-xl font-bold">DRIVEEASY</span>
        </div>
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
            <nav class="flex-1 space-y-2">
                <?php
                // Get current page filename
                $current_page = basename($_SERVER['PHP_SELF']);
                
                // Navigation items with their corresponding pages
                $nav_items = [
                    [
                        'url' => 'dashbord.php',
                        'icon' => 'fas fa-tachometer-alt',
                        'text' => 'Dashboard'
                    ],
                    [
                        'url' => 'booking.php',
                        'icon' => 'fas fa-calendar-alt',
                        'text' => 'Bookings'
                    ],
                    [
                        'url' => 'vehicle_add.php',
                        'icon' => 'fas fa-car',
                        'text' => 'Add Vehicles'
                    ]
                ];
                
                // Output each navigation item
                foreach ($nav_items as $item) {
                    $is_active = ($current_page === $item['url']) ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-700 hover:text-white';
                    echo '
                    <a href="'.$item['url'].'" class="flex items-center px-4 py-2 text-sm font-medium rounded-md '.$is_active.'">
                        <i class="'.$item['icon'].' mr-3"></i>
                        '.$item['text'].'
                    </a>';
                }
                ?>
            </nav>
        </div>
    </div>
</div>