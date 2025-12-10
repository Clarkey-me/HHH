<?php
include 'admin_protect.php';
include '../connect.php';

// Date filter
$start = $_GET['start'] ?? date('Y-m-01');
$end   = $_GET['end']   ?? date('Y-m-d');
$start = date('Y-m-d', strtotime($start));
$end   = date('Y-m-d', strtotime($end));

// Main sales query
$sql = "
    SELECT 
        s.id AS sale_id, s.order_date, s.total_amount, s.payment_method, s.status,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.contact_number,
        COUNT(sp.product_id) AS items_count,
        SUM(sp.quantity) AS total_qty
    FROM sales s
    LEFT JOIN user u ON s.user_id = u.user_id
    LEFT JOIN sales_products sp ON s.id = sp.sale_id
    WHERE DATE(s.order_date) BETWEEN ? AND ?
    GROUP BY s.id
    ORDER BY s.order_date DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) die("Database Error: " . $conn->error);
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();
$sales = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Summary stats
$stats = $conn->query("
    SELECT 
        COUNT(*) AS total_orders,
        COALESCE(SUM(total_amount), 0) AS total_revenue,
        COALESCE(AVG(total_amount), 0) AS avg_order_value,
        SUM(CASE WHEN payment_method = 'gcash' THEN 1 ELSE 0 END) AS gcash_count,
        SUM(CASE WHEN payment_method = 'bank' THEN 1 ELSE 0 END) AS bank_count
    FROM sales 
    WHERE DATE(order_date) BETWEEN '$start' AND '$end'
")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - Admin</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="adminCSS/sales_report.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="admin-page">

    <?php include 'includes/sidebar.php'; ?>   <!-- "Sales" auto-highlighted -->
    <?php include 'includes/header.php'; ?>    <!-- Title: "Sales Report" -->

    <main class="admin-container">
        <div class="report-wrapper">

            <div class="date-range">
                <?= date('F d, Y', strtotime($start)) ?> to <?= date('F d, Y', strtotime($end)) ?>
            </div>

            <div class="filters">
                <form method="GET">
                    <input type="date" name="start" value="<?= $start ?>" required>
                    <input type="date" name="end" value="<?= $end ?>" required>
                    <button type="submit">Apply Filter</button>
                    <a href="sales_report.php">Reset</a>
                </form>
            </div>

            <div class="stats-grid">
                <div class="stat-card"><h3>₱<?= number_format($stats['total_revenue'], 2) ?></h3><p>Total Revenue</p></div>
                <div class="stat-card"><h3><?= $stats['total_orders'] ?></h3><p>Total Orders</p></div>
                <div class="stat-card"><h3>₱<?= number_format($stats['avg_order_value'], 2) ?></h3><p>Average Order</p></div>
                <div class="stat-card"><h3><?= $stats['gcash_count'] ?></h3><p>GCash Payments</p></div>
                <div class="stat-card"><h3><?= $stats['bank_count'] ?></h3><p>Bank Transfers</p></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                        <tr><td colspan="8" class="no-data">No sales in this period.</td></tr>
                    <?php else: foreach ($sales as $sale): ?>
                        <tr>
                            <td><strong>#<?= str_pad($sale['sale_id'], 6, '0', STR_PAD_LEFT) ?></strong></td>
                            <td><?= date('M d, Y • h:i A', strtotime($sale['order_date'])) ?></td>
                            <td><?= htmlspecialchars($sale['customer_name'] ?? 'Guest') ?></td>
                            <td><?= htmlspecialchars($sale['contact_number'] ?? '—') ?></td>
                            <td><?= $sale['total_qty'] ?> item<?= $sale['total_qty'] > 1 ? 's' : '' ?></td>
                            <td><strong>₱<?= number_format($sale['total_amount'], 2) ?></strong></td>
                            <td><span class="badge <?= $sale['payment_method'] ?>"><?= ucfirst($sale['payment_method']) ?></span></td>
                            <td><span class="badge <?= $sale['status'] === 'completed' ? 'completed' : 'pending' ?>"><?= ucfirst($sale['status'] ?? 'pending') ?></span></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include 'includes/scripts.php'; ?>   <!-- Full theme toggle + sidebar behavior -->

</body>
</html>