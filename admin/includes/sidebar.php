<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <span class="toggle-btn" id="collapseSidebar">
            <i class="fa-solid fa-angle-left"></i>
        </span>
    </div>

    <ul class="menu">
        <li><a href="dashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : '' ?>>
            <i class="fa-solid fa-chart-line"></i><span> Dashboard</span></a></li>
        <li><a href="products.php" <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : '' ?>>
            <i class="fa-solid fa-box"></i><span> Products</span></a></li>
            <li><a href="users.php" class="logout">
            <i class="fa-solid fa-users"></i><span> Users</span></a></li>
        <li><a href="orders.php" <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'class="active"' : '' ?>>
            <i class="fa-solid fa-list-check"></i><span> Orders</span></a></li>
        <li><a href="sales_report.php" <?= basename($_SERVER['PHP_SELF']) == 'sales_report.php' ? 'class="active"' : '' ?>>
            <i class="fa-solid fa-peso-sign"></i><span> Sales</span></a></li>
        <li><a href="settings.php" <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'class="active"' : '' ?>>
            <i class="fa-solid fa-gear"></i><span> Settings</span></a></li>
        <li><a href="logout.php" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i><span> Logout</span></a></li>
    </ul>
</aside>
