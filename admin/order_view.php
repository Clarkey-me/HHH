<?php
include 'admin_protect.php';
include '../connect.php';

$sale_id = isset($_GET['sale_id']) ? (int)$_GET['sale_id'] : 0;
if (!$sale_id) { 
    header("Location: orders.php"); 
    exit(); 
}

$stmt = $conn->prepare("
    SELECT s.*, u.first_name, u.last_name, u.email_address 
    FROM sales s 
    JOIN `user` u ON s.user_id = u.user_id 
    WHERE s.id = ?
");
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$sale) {
    die("Order not found.");
}

$stmt2 = $conn->prepare("
    SELECT sp.quantity, sp.price_at_purchase, p.* 
    FROM sales_products sp 
    JOIN products p ON sp.product_id = p.id 
    WHERE sp.sale_id = ?
");
$stmt2->bind_param("i", $sale_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();

// Compute subtotal
$subtotal = 0;
foreach ($items as $it) {
    $subtotal += $it['quantity'] * $it['price_at_purchase'];
}

// REAL TOTAL = subtotal + delivery_fee
$total_amount = $subtotal + $sale['delivery_fee'];
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Order #<?= str_pad($sale_id, 6, '0', STR_PAD_LEFT) ?> - Admin</title>

<link rel="stylesheet" href="adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
/* ===== PAGE BACKGROUND TO MATCH DASHBOARD ===== */
body.admin-page {
    background: #0D0D0D !important;
    color: #F8F8F4;
}

/* ===== MAIN CONTAINER ALIGNED TO SIDEBAR & HEADER ===== */
.admin-container {
    margin-left: 260px; /* matches sidebar width */
    padding: 110px 40px 60px; /* aligns with header */
    max-width: 1400px;
}

/* ===== ORDER CARD ===== */
.order-card {
    background: #121212;
    border-radius: 18px;
    padding: 35px;
    border: 1px solid #2a2a2a;
    box-shadow: 0 10px 40px rgba(201,162,39,0.15);
}

/* ===== TITLE ===== */
h1 {
    color: #C9A227;
    font-size: 2.4rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 35px;
}

/* ===== META INFO ===== */
.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
    gap: 22px;
    margin-bottom: 35px;
}

.meta-box {
    background: #0E0E0E;
    padding: 22px;
    border-radius: 14px;
    border: 1px solid #2a2a2a;
}

.meta-box h3 {
    margin: 0 0 10px;
    font-size: 1.3rem;
    color: #C9A227;
}

.meta-box p {
    margin: 8px 0;
    font-size: 1rem;
}

/* ===== ORDER ITEMS TABLE ===== */
.items-table {
    width: 100%;
    border-collapse: collapse;
    background: #0E0E0E;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
}

.items-table th,
.items-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #2b2b2b;
    font-size: 0.95rem;
}

.items-table th {
    background: #C9A227;
    color: #000;
    font-weight: 700;
}

.items-table tr:hover td {
    background: rgba(201,162,39,0.15);
    transition: 0.25s ease;
}

.items-table img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #C9A227;
}

/* ===== TOTALS ===== */
.total-row {
    background: #000;
    font-size: 1.2rem;
    font-weight: 700;
}

.total-row td {
    color: #C9A227;
}

/* ===== FINAL TOTAL (boldest) ===== */
.final-total td {
    font-size: 1.6rem !important;
    color: #C9A227 !important;
    font-weight: 900;
}

/* ===== BACK BUTTON ===== */
.btn-back {
    display: inline-block;
    margin-top: 35px;
    padding: 14px 35px;
    background: #C9A227;
    color: #000;
    border-radius: 40px;
    font-size: 1.2rem;
    font-weight: 700;
    text-decoration: none;
    transition: 0.25s;
}

.btn-back:hover {
    background: #e6be41;
    transform: translateY(-4px);
}
</style>

</head>
<body class="admin-page">

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="admin-container">

    <h1>Order #<?= str_pad($sale_id, 6, '0', STR_PAD_LEFT) ?></h1>

    <div class="order-card">

        <!-- CUSTOMER & ORDER INFO -->
        <div class="meta-grid">

            <div class="meta-box">
                <h3>Customer</h3>
                <p><strong>Name:</strong> <?= htmlspecialchars($sale['first_name'].' '.$sale['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($sale['email_address']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($sale['phone']) ?></p>
            </div>

            <div class="meta-box">
                <h3>Delivery Address</h3>
                <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($sale['address'])) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($sale['municipality']) ?></p>
                <p><strong>Province:</strong> <?= htmlspecialchars($sale['province']) ?></p>
                <p><strong>Delivery Fee:</strong> ₱<?= number_format($sale['delivery_fee'],2) ?></p>
            </div>

            <div class="meta-box">
                <h3>Order Details</h3>
                <p><strong>Order Date:</strong> <?= date('M d, Y • h:i A', strtotime($sale['order_date'])) ?></p>
                <p><strong>Payment Method:</strong> <?= ucfirst($sale['payment_method']) ?></p>
                <p><strong>Status:</strong> <span style="color:#C9A227;font-weight:bold;"><?= ucfirst($sale['status']) ?></span></p>
            </div>

        </div>

        <!-- ORDER ITEMS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($items as $it): 
                $line = $it['quantity'] * $it['price_at_purchase'];
                $imgPath = $it['image'] ? "../uploads/".$it['image'] : "../uploads/default.jpg";
            ?>
                <tr>
                    <td><img src="<?= $imgPath ?>" onerror="this.src='../uploads/default.jpg'"></td>
                    <td><strong><?= htmlspecialchars($it['name']) ?></strong></td>
                    <td><?= htmlspecialchars($it['brand']) ?></td>
                    <td style="text-align:center;"><?= $it['quantity'] ?></td>
                    <td>₱<?= number_format($it['price_at_purchase'], 2) ?></td>
                    <td style="color:#C9A227;font-weight:bold;">₱<?= number_format($line, 2) ?></td>
                </tr>
            <?php endforeach; ?>

                <!-- SUBTOTAL -->
                <tr class="total-row">
                    <td colspan="5" style="text-align:right;">Subtotal</td>
                    <td>₱<?= number_format($subtotal,2) ?></td>
                </tr>

                <!-- DELIVERY -->
                <tr class="total-row">
                    <td colspan="5" style="text-align:right;">Delivery Fee</td>
                    <td>₱<?= number_format($sale['delivery_fee'],2) ?></td>
                </tr>

                <!-- FINAL TOTAL = SUBTOTAL + DELIVERY -->
                <tr class="final-total">
                    <td colspan="5" style="text-align:right;">TOTAL AMOUNT</td>
                    <td>₱<?= number_format($total_amount,2) ?></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align:center;">
            <a href="orders.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
        </div>

    </div>
</main>

</body>
</html>
