<?php
// admin/products.php
include 'admin_protect.php';
include '../connect.php';

// Archive handler
if (isset($_GET['archive'])) {
    $id = (int)$_GET['archive'];
    $conn->query("UPDATE products SET archived = 1 WHERE id = $id");
    $_SESSION['msg'] = "Product moved to archive.";
    header("Location: products.php");
    exit();
}

if (isset($_GET['restore'])) {
    $id = (int)$_GET['restore'];
    $conn->query("UPDATE products SET archived = 0 WHERE id = $id");
    $_SESSION['msg'] = "Product restored!";
    header("Location: products.php");
    exit();
}

// Count archived safely
$archived_count = 0;
$count_res = $conn->query("SELECT COUNT(*) as c FROM products WHERE archived = 1");
if ($count_res && $row = $count_res->fetch_assoc()) {
    $archived_count = $row['c'];
}

// Fetch active products
$result = $conn->query("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.archived = 0 OR p.archived IS NULL
    ORDER BY p.created_at DESC
");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Products - Admin</title>
  <link rel="stylesheet" href="adminCSS/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    /* Adjust table and page container */
    .admin-container {
        margin-left: 240px; /* Space for sidebar */
        padding: 70px 30px 80px;
        min-height: 100vh;
    }

    /* Table styling */
    .admin-table {
        width: 75%;           /* Table width */
        margin: 30px auto;    /* Center table */
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
    }

    .admin-table th,
    .admin-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #fff;
        text-align: left;
    }

    .admin-table th {
        background: #121212;
        color: #C9A227;
        font-weight: 600;
    }

    /* Product image */
    .product-img { 
        width:70px; 
        height:70px; 
        object-fit:cover; 
        border-radius:10px; 
        border:2px solid #333; 
    }

    /* Category badge */
    .category-badge { 
        padding:6px 14px; 
        color:#fff;  
        font-size:0.85em; 
        font-weight:bold; 
    }

    .no-category { color:#888; font-style:italic; }

    /* Buttons */
    .small-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        font-size: 0.85em;
        font-weight: 600;
        background: #C9A227;
        color: #000;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: 0.25s ease;
        width: 98px;
        justify-content:center;
    }

    .small-btn:hover { background: #e0ba40; transform: translateY(-2px); }
    .small-btn:active { transform: scale(0.95); }
    .small-btn.danger { background: #e74c3c; color: #fff; }
    .small-btn.danger:hover { background: #c0392b; }

    /* Status colors */
    .status-available { color:#28a745; font-weight:bold; }
    .status-low       { color:#ffc107; font-weight:bold; }
    .status-soldout   { color:#dc3545; font-weight:bold; }

    /* Archive link */
    .archive-link { color:#C9A227; font-weight:bold; text-decoration:none; }
    .archive-link:hover { text-decoration:underline; }

    /* Success message */
    .success-msg { 
        background:#d4edda; 
        color:#155724; 
        padding:15px; 
        border-radius:10px; 
        margin:20px 0; 
        text-align:center; 
        font-weight:bold; 
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .admin-container { padding:120px 20px 60px; }
        .admin-table { width: 90%; }
    }

    @media (max-width: 768px) {
        .admin-container { margin-left:0; padding:140px 15px 50px; }
        .admin-table { width: 100%; font-size:0.9em; }
        .small-btn { width:auto; padding:5px 12px; font-size:0.8em; }
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

  <div style="display:flex; justify-content:space-between; align-items:center; margin:30px 0; flex-wrap:wrap; gap:10px;">
    <a href="product_add.php" 
       style="background:#C9A227;color:#000;padding:12px 30px;border-radius:50px;text-decoration:none;font-weight:bold;">
        Add New Product
    </a>

    <a href="archive.php" class="archive-link">
      Archived Items (<?= $archived_count ?>)
    </a>
  </div>

  <table class="admin-table">

    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>

    <tbody>
    <?php if (!$result || $result->num_rows == 0): ?>
      <tr>
        <td colspan="9" style="text-align:center; padding:80px; color:#888; font-size:1.2em;">
          No products yet. <a href="product_add.php" style="color:#C9A227;">Add your first product!</a>
        </td>
      </tr>

    <?php else: while($p = $result->fetch_assoc()): ?>
      <tr>
        <td>#<?= str_pad($p['id'], 3, '0', STR_PAD_LEFT) ?></td>

        <td>
          <?php $img = $p['image'] ?? 'uploads/default.jpg'; ?>
          <img src="../<?= htmlspecialchars($img) ?>" 
               alt="<?= htmlspecialchars($p['name']) ?>" 
               class="product-img"
               onerror="this.src='../uploads/default.jpg'">
        </td>

        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
        <td><?= htmlspecialchars($p['brand']) ?></td>

        <td>
          <?php if ($p['category_name']): ?>
            <span class="category-badge"><?= htmlspecialchars($p['category_name']) ?></span>
          <?php else: ?>
            <span class="no-category">Uncategorized</span>
          <?php endif; ?>
        </td>

        <td>₱<?= number_format($p['price'], 2) ?></td>

        <td><?= $p['quantity'] ?></td>

        <td>
          <?php if ($p['quantity'] > 5): ?>
            <span class="status-available">Available</span>
          <?php elseif ($p['quantity'] > 0): ?>
            <span class="status-low">Low Stock</span>
          <?php else: ?>
            <span class="status-soldout">Sold Out</span>
          <?php endif; ?>
        </td>

        <td>
          <a href="product_edit.php?id=<?= $p['id'] ?>" class="small-btn">
            <i class="fa-solid fa-pen-to-square"></i> Edit
          </a>

          <a href="products.php?archive=<?= $p['id'] ?>" 
             class="small-btn danger" 
             onclick="return confirm('Move to archive?')">
            <i class="fa-solid fa-box-archive"></i> Archive
          </a>
        </td>
      </tr>

    <?php endwhile; endif; ?>
    </tbody>
  </table>

</main>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
