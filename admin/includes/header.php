<!-- admin/includes/header.php -->
<header class="admin-header">
    <h2>
        <?php
        $titles = [
            'dashboard.php'     => 'Admin Dashboard',
            'products.php'      => 'Products Management',
            'product_add.php'   => 'Add Product',
            'product_edit.php'  => 'Edit Product',
            'orders.php'        => 'Orders',
            'users.php'         => 'Users',
            'sales_report.php'  => 'Sales Report',
            'archive.php'       => 'Archive ',
            'settings.php'      => 'Settings',
            'add_city'          => 'Add City',
            'city.php'          => 'Delivery Location Management',
        ];
        $current = basename($_SERVER['PHP_SELF']);
        echo $titles[$current] ?? 'Admin Panel';
        ?>
    </h2>
    <div class="left">
        <span id="hamburger" class="hamburger"><i class="fa-solid fa-bars"></i></span>
    </div>
    <div class="right">
        <button id="themeToggle" class="theme-btn"><i class="fa-solid fa-moon"></i></button>
    </div>
</header>