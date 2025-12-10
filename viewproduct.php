<?php
include 'connect.php';
session_start();

$isLoggedIn = isset($_SESSION['user']);
$currentUser = $isLoggedIn ? $_SESSION['user'] : null;
$user_id = $isLoggedIn ? $currentUser['user_id'] : 0;

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$product = null;
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->num_rows === 1 ? $result->fetch_assoc() : null;
    $stmt->close();
}

if (!$product) {
    die('<h2 style="text-align:center;color:#C9A227;padding:150px;">Product Not Found</h2>');
}

// Fix image path
$img = $product['image'] && $product['image'] !== '0'
    ? (strpos($product['image'], 'uploads/') === false ? 'uploads/' . $product['image'] : $product['image'])
    : 'uploads/default.jpg';

// Get average rating + review count
$stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$rating = $stmt->get_result()->fetch_assoc();
$stmt->close();
$avgRating = $rating['avg_rating'] ? round($rating['avg_rating'], 1) : 0;
$totalReviews = $rating['total_reviews'];

// Check if user can write a review
$canReview = false;
if ($isLoggedIn) {
    $stmt = $conn->prepare("
        SELECT 1 FROM sales s
        JOIN sales_products sp ON s.id = sp.sale_id
        WHERE s.user_id = ? AND sp.product_id = ? AND s.status = 'delivered'
        AND NOT EXISTS (SELECT 1 FROM reviews r WHERE r.sale_id = s.id AND r.product_id = ?)
        LIMIT 1
    ");
    $stmt->bind_param("iii", $user_id, $product_id, $product_id);
    $stmt->execute();
    $canReview = $stmt->get_result()->num_rows > 0;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> • Thriftoes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/index.css">
    <style>
        body { background:#0f0f0f; color:#eee; margin:0; font-family:'Segoe UI',sans-serif; }
        .back-arrow {
            position:fixed; top:100px; left:30px; z-index:1000;
            background:rgba(0,0,0,0.8); color:#C9A227; width:50px; height:50px;
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-size:1.8rem; text-decoration:none; box-shadow:0 5px 20px rgba(201,162,39,0.4);
            transition:0.3s;
        }
        .back-arrow:hover { background:#C9A227; color:#000; transform:scale(1.15); }

        .container {
            max-width:1300px; margin:0 auto; padding:120px 20px 100px;
        }

        .product-grid {
            display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:start;
        }
        @media (max-width:992px) { .product-grid { grid-template-columns:1fr; } }

        .product-image img {
            width:100%; border-radius:20px; border:4px solid #C9A227;
            box-shadow:0 15px 40px rgba(0,0,0,0.6);
        }

        .product-info {
            background:#1a1a1a; padding:40px; border-radius:20px;
            border:1px solid #333; box-shadow:0 10px 30px rgba(0,0,0,0.5);
        }
        .product-info h1 {
            font-size:2.8rem; color:#C9A227; margin:0 0 15px; font-weight:900;
        }
        .brand { color:#aaa; font-size:1.1rem; margin-bottom:20px; }

        .price {
            font-size:3rem; font-weight:bold; color:#FFD700; margin:20px 0;
        }

        .stock {
            padding:10px 20px; border-radius:30px; font-weight:bold;
            display:inline-block; margin:15px 0;
            background:<?= $product['quantity'] > 0 ? '#004d00' : '#4d0000' ?>;
            color:<?= $product['quantity'] > 0 ? '#00ff88' : '#ff6666' ?>;
        }

        .badges { margin:25px 0; }
        .badge {
            display:inline-block; padding:8px 16px; border-radius:30px;
            margin:6px 8px 6px 0; font-weight:bold; font-size:0.95rem;
        }
        .size-badge { background:#333; color:#C9A227; border:1px solid #C9A227; }
        .color-badge { color:#fff; }
        .color-black { background:#111; }
        .color-white { background:#eee; color:#000; }
        .color-red { background:#d32f2f; }

        .rating-summary {
            background:#111; padding:25px; border-radius:16px; text-align:center;
            margin:30px 0; border:2px solid #333;
        }
        .big-stars { font-size:3.5rem; color:#FFD700; margin:15px 0; }
        .rating-text { font-size:2rem; font-weight:bold; color:#fff; }
        .review-count { color:#aaa; margin-top:8px; }

        .add-to-cart {
            width:100%; padding:18px; background:#C9A227; color:#000;
            border:none; font-size:1.4rem; font-weight:bold; border-radius:50px;
            cursor:pointer; margin:30px 0; transition:0.3s;
        }
        .add-to-cart:hover:not(:disabled) {
            background:#f1d04b; transform:translateY(-5px);
            box-shadow:0 15px 30px rgba(201,162,39,0.5);
        }
        .add-to-cart:disabled { background:#555; cursor:not-allowed; }

        .write-review-btn {
            display:block; text-align:center; padding:18px; background:#4CAF50;
            color:#fff; font-size:1.3rem; font-weight:bold; border-radius:50px;
            text-decoration:none; margin:20px 0;
        }
        .write-review-btn:hover { background:#388e3c; }

        .reviews-section {
            margin-top:80px; padding:40px; background:#111;
            border-radius:20px; border:1px solid #333;
        }
        .reviews-section h2 {
            text-align:center; color:#C9A227; font-size:2.4rem; margin-bottom:30px;
        }
        .review {
            background:#1a1a1a; padding:25px; border-radius:16px;
            margin-bottom:20px; border:1px solid #333;
        }
        .review-header {
            display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;
        }
        .reviewer { font-weight:bold; color:#C9A227; }
        .review-date { color:#888; font-size:0.9rem; }
        .review-stars { font-size:1.6rem; color:#FFD700; }
        .review-text { margin:15px 0; line-height:1.7; color:#ddd; }
        .review-photo img {
            max-width:200px; border-radius:12px; border:2px solid #C9A227; margin-top:10px;
        }
        .no-reviews {
            text-align:center; color:#888; font-size:1.3rem; padding:60px;
        }
    </style>
</head>
<body>

<a href="shop.php" class="back-arrow" title="Back to Shop">
    <i class="fa fa-arrow-left"></i>
</a>

<div class="container">
    <div class="product-grid">
        <div class="product-image">
            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="product-info">
            <div class="brand"><?= htmlspecialchars($product['brand']) ?></div>
            <h1><?= htmlspecialchars($product['name']) ?></h1>

            <div class="rating-summary">
                <div class="big-stars">
                    <?php for($i=1;$i<=5;$i++): ?>
                        <i class="fas fa-star<?= $i <= $avgRating ? '' : ($i <= $avgRating+0.5 ? '-half-alt' : '-o') ?>"></i>
                    <?php endfor; ?>
                </div>
                <div class="rating-text"><?= $avgRating ?: 'No ratings yet' ?></div>
                <div class="review-count"><?= $totalReviews ?> review<?= $totalReviews == 1 ? '' : 's' ?></div>
            </div>

            <div class="price">₱<?= number_format($product['price'], 2) ?></div>

            <div class="stock">
                <?= $product['quantity'] > 0 ? "In Stock ({$product['quantity']} left)" : "Out of Stock" ?>
            </div>

            <div class="badges">
                <?php if ($product['size']): ?>
                    <span class="badge size-badge"><?= htmlspecialchars($product['size']) ?></span>
                <?php endif; ?>
                <?php
                $colorClass = 'color-black';
                if (stripos($product['color'] ?? '', 'white') !== false) $colorClass = 'color-white';
                elseif (stripos($product['color'] ?? '', 'red') !== false) $colorClass = 'color-red';
                ?>
                <span class="badge color-badge <?= $colorClass ?>"><?= ucfirst($product['color'] ?? 'N/A') ?></span>
                <?php if ($product['category_name']): ?>
                    <span class="badge" style="background:#0d7377;color:#fff;"><?= htmlspecialchars($product['category_name']) ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['description'])): ?>
                <p style="line-height:1.8;color:#ddd;">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <?php if ($product['quantity'] > 0): ?>
                    <form method="POST" action="addtocart.php">
                        <input type="hidden" name="product_id" value="<?= $product_id ?>">
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <button class="add-to-cart" disabled>Out of Stock</button>
                <?php endif; ?>

                <?php if ($canReview):
                    $stmt = $conn->prepare("SELECT s.id FROM sales s JOIN sales_products sp ON s.id = sp.sale_id WHERE s.user_id = ? AND sp.product_id = ? AND s.status = 'delivered' LIMIT 1");
                    $stmt->bind_param("ii", $user_id, $product_id);
                    $stmt->execute();
                    $saleResult = $stmt->get_result();
                    if ($saleRow = $saleResult->fetch_assoc()): ?>
                        <a href="write_review.php?sale_id=<?= $saleRow['id'] ?>" class="write-review-btn">
                            Write a Review
                        </a>
                    <?php endif; $stmt->close(); ?>
                <?php endif; ?>
            <?php else: ?>
                <p style="color:#888;margin:30px 0;">Log in to add to cart and write a review.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Customer Reviews -->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>

        <?php
        $stmt = $conn->prepare("
            SELECT r.rating, r.review_text, r.photo, r.created_at, u.first_name
            FROM reviews r
            JOIN user u ON r.user_id = u.user_id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $reviews = $stmt->get_result();
        ?>

        <?php if ($reviews->num_rows === 0): ?>
            <div class="no-reviews">
                <p>No reviews yet — be the first to review this helmet!</p>
            </div>
        <?php else: ?>
            <?php while ($r = $reviews->fetch_assoc()): ?>
                <div class="review">
                    <div class="review-header">
                        <div>
                            <span class="reviewer"><?= htmlspecialchars($r['first_name']) ?></span>
                            <span class="review-date">• <?= date('M d, Y', strtotime($r['created_at'])) ?></span>
                        </div>
                        <div class="review-stars">
                            <?php for($i=1;$i<=5;$i++): ?>
                                <i class="fas fa-star<?= $i <= $r['rating'] ? '' : '-o' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="review-text"><?= nl2br(htmlspecialchars($r['review_text'])) ?></div>
                    <?php if ($r['photo']): ?>
                        <div class="review-photo">
                            <img src="uploads/reviews/<?= htmlspecialchars($r['photo']) ?>" alt="Review photo">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php $stmt->close(); ?>
    </div>
</div>

<script>
document.querySelector(".back-arrow").addEventListener("click", e => {
    e.preventDefault();
    history.back();
});
</script>

</body>
</html>