<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user']['user_id'];
$sale_id = (int)($_GET['sale_id'] ?? 0);

if ($sale_id <= 0) {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;font-family:Segoe UI;'>Invalid Order ID</h2>");
}

// 1. Check if order exists and belongs to user
$stmt = $conn->prepare("SELECT status FROM sales WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $sale_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;font-family:Segoe UI;'>Order not found or access denied.</h2>");
}

$order = $result->fetch_assoc();
if ($order['status'] !== 'delivered') {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;font-family:Segoe UI;'>You can only review delivered orders.</h2>");
}
$stmt->close();

// 2. Prevent duplicate review
$stmt = $conn->prepare("SELECT 1 FROM reviews WHERE sale_id = ? AND user_id = ? LIMIT 1");
$stmt->bind_param("ii", $sale_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;font-family:Segoe UI;'>You've already reviewed this order. Thank you!</h2>");
}
$stmt->close();

// 3. Get products from this order
$stmt = $conn->prepare("
    SELECT p.id AS product_id, p.name, p.brand, p.image, sp.quantity
    FROM sales_products sp
    JOIN products p ON sp.product_id = p.id
    WHERE sp.sale_id = ?
");
$stmt->bind_param("i", $sale_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;'>Database error.</h2>");
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $row['image'] = $row['image'] && $row['image'] !== '0'
        ? (strpos($row['image'], 'uploads/') === false ? 'uploads/' . $row['image'] : $row['image'])
        : 'uploads/default.jpg';
    $products[] = $row;
}
$stmt->close();

if (empty($products)) {
    die("<h2 style='text-align:center;color:#C9A227;padding:100px;'>No products found in this order.<br><small>Admin needs to fix order items.</small></h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Write Review • Thriftoes</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    body { background:#0f0f0f; color:#eee; margin:0; font-family:'Segoe UI',sans-serif; }
    .topbar { background:#000; padding:12px 20px; text-align:right; border-bottom:2px solid #C9A227; }
    .topbar a { color:#C9A227; text-decoration:none; font-weight:600; }

    .container { max-width:650px; margin:30px auto; padding:20px; }

    h1 { text-align:center; color:#C9A227; font-size:2.2rem; margin:20px 0 35px; font-weight:900; }

    .order-pill {
      text-align:center; background:#1a1a1a; color:#C9A227; padding:14px 30px;
      border-radius:50px; font-weight:bold; font-size:1.1rem; margin-bottom:40px;
      display:inline-block; box-shadow:0 6px 15px rgba(0,0,0,0.4);
    }

    .review-card {
      background:#111; border-radius:16px; padding:22px; margin-bottom:28px;
      border:1px solid #333; box-shadow:0 8px 20px rgba(0,0,0,0.5);
    }

    .product-row {
      display:flex; align-items:center; gap:16px; margin-bottom:18px;
    }
    .product-row img { width:70px; height:70px; object-fit:cover; border-radius:12px; border:2px solid #C9A227; }
    .product-info h3 { margin:0; font-size:1.25rem; color:#fff; }
    .product-info p { margin:4px 0 0; color:#aaa; font-size:0.95rem; }

    .stars {
      text-align:center; font-size:2rem; margin:20px 0; cursor:pointer;
    }
    .stars i { color:#555; transition:0.2s; }
    .stars i.fas { color:#C9A227 !important; }

    textarea {
      width:100%; background:#222; border:2px solid #444; border-radius:12px;
      padding:14px; color:#fff; font-size:1rem; min-height:110px; resize:vertical; margin:15px 0;
    }
    textarea:focus { outline:none; border-color:#C9A227; }

    .upload-box {
      border:2px dashed #555; border-radius:12px; padding:20px; text-align:center;
      cursor:pointer; transition:0.3s; margin:15px 0;
    }
    .upload-box:hover { border-color:#C9A227; }
    .upload-box input { display:none; }
    .upload-box i { font-size:1.8rem; color:#777; }

    .submit-btn {
      width:100%; background:linear-gradient(135deg,#C9A227,#f1d04b);
      color:#000; font-weight:bold; font-size:1.25rem; padding:16px;
      border:none; border-radius:50px; cursor:pointer; margin:30px 0 20px;
      transition:0.3s;
    }
    .submit-btn:hover { transform:translateY(-4px); box-shadow:0 10px 25px rgba(201,162,39,0.4); }

    .back { text-align:center; color:#888; text-decoration:none; font-size:1rem; display:block; margin-top:20px; }
    .back:hover { color:#C9A227; }
  </style>
</head>
<body>

<div class="topbar">
  <a href="index.php">Home</a>
</div>

<div class="container">
  <h1>Write a Review</h1>

  <div class="order-pill">
    Order #<?= str_pad($sale_id, 6, '0', STR_PAD_LEFT) ?> • Delivered
  </div>

  <form action="submit_review.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="sale_id" value="<?= $sale_id ?>">

    <?php foreach ($products as $item): ?>
      <div class="review-card">
        <div class="product-row">
          <img src="<?= $item['image'] ?>" alt="">
          <div class="product-info">
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            <p><?= htmlspecialchars($item['brand']) ?> × <?= $item['quantity'] ?></p>
          </div>
        </div>

        <input type="hidden" name="product_id[]" value="<?= $item['product_id'] ?>">

        <div class="stars" data-id="<?= $item['product_id'] ?>">
          <i class="far fa-star" data-value="1"></i>
          <i class="far fa-star" data-value="2"></i>
          <i class="far fa-star" data-value="3"></i>
          <i class="far fa-star" data-value="4"></i>
          <i class="far fa-star" data-value="5"></i>
        </div>
        <input type="hidden" name="rating[<?= $item['product_id'] ?>]" class="rating-val" required>

        <textarea name="review[<?= $item['product_id'] ?>]" placeholder="How was your experience?" required></textarea>

        <label class="upload-box">
          <i class="fas fa-camera"></i><br>
          <span style="color:#999;">Add photo (optional)</span>
          <input type="file" name="photo[<?= $item['product_id'] ?>]" accept="image/*">
        </label>
      </div>
    <?php endforeach; ?>

    <button type="submit" class="submit-btn">
      Submit Review<?= count($products) > 1 ? 's' : '' ?>
    </button>
  </form>

  <a href="purchaseHistory.php" class="back">Back to Purchase History</a>
</div>

<script>
  document.querySelectorAll('.stars').forEach(container => {
    const stars = container.querySelectorAll('i');
    const input = container.nextElementSibling;

    stars.forEach(star => {
      star.addEventListener('click', () => {
        const val = star.getAttribute('data-value');
        input.value = val;
        stars.forEach((s, i) => {
          if (i < val) {
            s.classList.remove('far');
            s.classList.add('fas');
          } else {
            s.classList.remove('fas');
            s.classList.add('far');
          }
        });
      });
    });
  });

  document.querySelectorAll('.upload-box').forEach(box => {
    box.addEventListener('click', () => box.querySelector('input').click());
  });
</script>
</body>
</html>