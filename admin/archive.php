<?php
include 'admin_protect.php';
include '../connect.php';

// Restore handler
if (isset($_GET['restore'])) {
    $type = $_GET['type'] ?? '';
    $id   = (int)$_GET['restore'];
    $table = $type === 'user' ? 'user' : ($type === 'order' ? 'sales' : 'products');
    $id_col = $type === 'user' ? 'user_id' : 'id';
    $conn->query("UPDATE `$table` SET archived = 0 WHERE `$id_col` = $id");
    $_SESSION['msg'] = ucfirst($type ?? 'item') . " restored!";
    header("Location: archive.php" . (isset($_GET['filter']) ? "?filter=" . $_GET['filter'] : ''));
    exit();
}

// Permanent delete
if (isset($_GET['delete'])) {
    $type = $_GET['type'] ?? '';
    $id   = (int)$_GET['delete'];
    if ($type === 'order') {
        $conn->query("DELETE FROM sales_products WHERE sale_id = $id");
        $conn->query("DELETE FROM sales WHERE id = $id");
    } elseif ($type === 'product') {
        $conn->query("DELETE FROM products WHERE id = $id");
    } elseif ($type === 'user') {
        $conn->query("DELETE FROM `user` WHERE user_id = $id");
    }
    $_SESSION['msg'] = ucfirst($type ?? 'item') . " permanently deleted!";
    header("Location: archive.php");
    exit();
}

$filter = $_GET['filter'] ?? 'all';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive - Admin</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="adminCSS/archive.css">
</head>
<body class="admin-page">

    <?php include 'includes/sidebar.php'; ?>  
    <?php include 'includes/header.php'; ?>   

    <main class="admin-container">
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="success-msg"><?= htmlspecialchars($_SESSION['msg']) ?></div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <div class="archive-tabs">
            <a href="?filter=all"      class="tab-btn <?= $filter==='all'?'active':'' ?>">All Items</a>
            <a href="?filter=products" class="tab-btn <?= $filter==='products'?'active':'' ?>">Products</a>
            <a href="?filter=orders"   class="tab-btn <?= $filter==='orders'?'active':'' ?>">Orders</a>
            <a href="?filter=users"    class="tab-btn <?= $filter==='users'?'active':'' ?>">Users</a>
        </div>

        <div class="archive-list">
            <?php
            $items = [];

            if (in_array($filter, ['all', 'products'])) {
                $res = $conn->query("SELECT 'product' as type, id, name as title, brand, price FROM products WHERE archived = 1");
                while ($r = $res->fetch_assoc()) $items[] = $r;
            }
            if (in_array($filter, ['all', 'orders'])) {
                $res = $conn->query("SELECT 'order' as type, id, CONCAT('Order #',id) as title, total_amount as price, status as extra FROM sales WHERE archived = 1");
                while ($r = $res->fetch_assoc()) $items[] = $r;
            }
            if (in_array($filter, ['all', 'users'])) {
                $res = $conn->query("SELECT 'user' as type, user_id as id, CONCAT(first_name,' ',last_name) as title, email_address as brand, created_at as extra FROM `user` WHERE archived = 1");
                while ($r = $res->fetch_assoc()) $items[] = $r;
            }

            if (empty($items)): ?>
                <div class="empty">
                    <h3>No archived items</h3>
                    <p>Everything is active!</p>
                </div>
            <?php else: foreach ($items as $item): ?>
                <div class="archive-card">
                    <div class="archive-header">
                        <div>
                            <span class="type-badge type-<?= $item['type'] ?>"><?= ucfirst($item['type']) ?></span>
                            <strong><?= htmlspecialchars($item['title']) ?></strong>
                            <?php if ($item['type'] === 'product'): ?>
                                <small>• <?= htmlspecialchars($item['brand']) ?> • ₱<?= number_format($item['price'],2) ?></small>
                            <?php elseif ($item['type'] === 'order'): ?>
                                <small>• ₱<?= number_format($item['price'],2) ?> • <?= ucfirst($item['extra']) ?></small>
                            <?php elseif ($item['type'] === 'user'): ?>
                                <small>• <?= htmlspecialchars($item['brand']) ?> • Joined <?= date("M Y", strtotime($item['extra'])) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="actions">
                            <a href="?restore=<?= $item['id'] ?>&type=<?= $item['type'] ?>&filter=<?= $filter ?>"
                               class="restore" onclick="return confirm('Restore this <?= $item['type'] ?>?')">Restore</a>
                            <a href="?delete=<?= $item['id'] ?>&type=<?= $item['type'] ?>"
                               class="delete" onclick="return confirm('Permanently delete? Cannot be undone!')">Permanently Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </main>

    <?php include 'includes/scripts.php'; ?>   <!-- Full functionality -->

</body>
</html>