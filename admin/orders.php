<?php
include 'admin_protect.php';
include '../connect.php';

// Archive handler
if (isset($_GET['archive'])) {
    $id = (int)$_GET['archive'];
    $conn->query("UPDATE sales SET archived = 1 WHERE id = $id");
    $_SESSION['msg'] = "Order moved to archive.";
    header("Location: orders.php");
    exit();
}

// Handle status change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sale_id'], $_POST['status'])) {
    $sid = (int)$_POST['sale_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE sales SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $sid);
    $stmt->execute();
    header("Location: orders.php");
    exit();
}

// Fetch only active orders
$res = $conn->query("
    SELECT s.*, u.first_name, u.last_name 
    FROM sales s 
    JOIN `user` u ON s.user_id = u.user_id 
    WHERE s.archived = 0 OR s.archived IS NULL
    ORDER BY s.order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders - Admin</title>
<link rel="stylesheet" href="adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
/* ================= CONTAINER ================= */
.admin-container {
    margin-left: 240px; /* space for sidebar */
    padding: 100px 0px 80px;
    max-width: 1400px; /* limit width */
}

/* ================= TABLE ================= */
.admin-table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
}

.admin-table th, .admin-table td {
    padding: 12px 10px;
    text-align: center;
    border-bottom: 1px solid #ccc;
}

.admin-table th {
    color: #fff;
    font-weight: 600;
    border-radius: 6px 6px 0 0;
}
.admin-table td {
   width: 200px;
}

/* ================= SUCCESS MESSAGE ================= */
.success-msg {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 10px;
    margin: 20px 0;
    text-align: center;
    font-weight: bold;
}

/* ================= ACTION BUTTONS ================= */
.action-btns {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: 0.25s ease, transform 0.2s;
    white-space: nowrap;
}

.action-btn i {
    font-size: 0.9rem;
}

.action-btn.view {
    background: #3498db;
    color: #fff;
}
.action-btn.view:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

.action-btn.archive {
    background: #e74c3c;
    color: #fff;
}
.action-btn.archive:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

.action-btn.update {
    background: #C9A227;
    color: #000;
}
.action-btn.update:hover {
    background: #e0ba40;
    transform: translateY(-2px);
}

/* ================= STATUS FORM ================= */
.status-form {
    display: inline-flex;
    gap: 6px;
    align-items: center;
    margin: 0;
}

.status-form select {
    padding: 5px 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #222;
    color: #fff;
    min-width: 100px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .admin-container {
        margin-left: 0;
        padding: 120px 15px 50px;
    }

    .action-btns {
        flex-direction: column;
        gap: 6px;
    }

    .status-form {
        flex-direction: row;
    }
}
</style>
</head>
<body class="admin-page">

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="admin-container">

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="success-msg"><?= htmlspecialchars($_SESSION['msg']) ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($o = $res->fetch_assoc()): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['first_name'].' '.$o['last_name']) ?></td>
                <td>₱<?= number_format($o['total_amount'], 2) ?></td>
                <td><?= date("M d, Y", strtotime($o['order_date'])) ?></td>
                <td><strong style="color:#C9A227;"><?= ucfirst($o['status']) ?></strong></td>
                <td>
                    <div class="action-btns">
                        <a href="order_view.php?sale_id=<?= $o['id'] ?>" class="action-btn view">
                            <i class="fa-solid fa-eye"></i> View
                        </a>

                        <a href="orders.php?archive=<?= $o['id'] ?>" class="action-btn archive"
                           onclick="return confirm('Move order to archive?')">
                            <i class="fa-solid fa-box-archive"></i> Archive
                        </a>

                        <form method="post" class="status-form">
                            <input type="hidden" name="sale_id" value="<?= $o['id'] ?>">
                            <select name="status">
                                <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                                    <option value="<?= $s ?>" <?= $o['status']===$s ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="action-btn update">
                                <i class="fa-solid fa-check"></i> Update
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</main>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
