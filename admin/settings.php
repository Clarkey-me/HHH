<?php
include 'admin_protect.php';
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings - Admin</title>
<link rel="stylesheet" href="adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
    /* ================= SETTINGS PAGE ================= */
    .settings-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
        gap: 20px;
        padding: 30px 20px;
    }

    .settings-card {
        background: #1E1E2F;
        color: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
        height: 200px; /* fixed height for uniformity */
    }

    .settings-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.35);
    }

    .settings-card i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: #C9A227;
    }

    .settings-card h3 {
        font-size: 1.2rem;
        margin-bottom: 8px;
    }

    .settings-card p {
        font-size: 0.9rem;
        color: #ccc;
    }

    @media (max-width: 1024px) {
        .settings-container {
            grid-template-columns: repeat(2, 1fr); /* 2 per row on medium screens */
        }
    }

    @media (max-width: 768px) {
        .settings-container {
            grid-template-columns: 1fr; /* 1 per row on small screens */
        }
    }
</style>
</head>
<body class="admin-page">

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="admin-container">
    <div class="settings-container">
        <!-- Promo Sale Settings -->
        <a href="editpromo.php" class="settings-card">
            <i class="fa-solid fa-tags"></i>
            <h3>Edit Promo Sale</h3>
            <p>Manage your ongoing promotions and sales campaigns.</p>
        </a>

        <!-- Manage Delivery Address -->
        <a href="city.php" class="settings-card">
            <i class="fa-solid fa-location-dot"></i>
            <h3>Manage Delivery Address</h3>
            <p>Edit or add delivery addresses for customers or system defaults.</p>
        </a>

        <!-- Additional settings card examples -->
        <a href="users.php" class="settings-card">
            <i class="fa-solid fa-user"></i>
            <h3>Users</h3>
            <p>User Management.</p>
        </a>
         <a href="archive.php" class="settings-card">
            <i class="fa-solid fa-archive"></i>
            <h3>Archive</h3>
            <p>Archive Records / Transactions</p>
        </a>
    </div>
</main>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
