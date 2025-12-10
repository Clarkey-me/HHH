<?php
include 'admin_protect.php';
include '../connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $brand       = trim($_POST['brand']);
    $size        = $_POST['size'];           // now from dropdown
    $color       = $_POST['color'];          // now from dropdown
    $price       = (float)$_POST['price'];
    $quantity    = (int)$_POST['quantity'];
    $description = trim($_POST['description'] ?? '');
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

    // IMAGE UPLOAD
    $imgPath = 'uploads/default.jpg';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if (in_array($ext, $allowed)) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $newName = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dest = $uploadDir . $newName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imgPath = 'uploads/' . $newName;
            } else {
                $message = "Failed to upload image — check folder permissions!";
            }
        } else {
            $message = "Invalid file type! Only JPG, PNG, WebP, GIF allowed.";
        }
    }

    if (!$message) {
        $sql = "INSERT INTO products 
                (name, brand, size, color, price, quantity, image, description, category_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssdisss", 
            $name, $brand, $size, $color, 
            $price, $quantity, $imgPath, $description, $category_id
        );

        if ($stmt->execute()) {
            header("Location: products.php?added=1");
            exit();
        } else {
            $message = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Load categories
$cats = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .error{background:#500;color:#ff6b6b;padding:15px;margin:20px auto;max-width:600px;border-radius:10px;text-align:center;font-weight:bold;}
        select option[value="Black"] { background:#222; color:#fff; }
        select option[value="White"] { background:#fff; color:#000; }
        select option[value="Red"]   { background:#d32f2f; color:#fff; }
    </style>
</head>
<body class="admin-page">

<?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

<main class="admin-container" style="padding-top:120px;">

    <?php if($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" style="max-width:900px;margin:0 auto;background:#222;padding:40px;border-radius:16px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:25px;">
            <div>
                <label style="color:#ccc;">Product Name</label>
                <input type="text" name="name" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
            </div>
            <div>
                <label style="color:#ccc;">Brand</label>
                <input type="text" name="brand" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
            </div>

            <!-- SIZE DROPDOWN -->
            <div>
                <label style="color:#ccc;">Size</label>
                <select name="size" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
                    <option value="Small">Small</option>
                    <option value="Medium" selected>Medium (Default)</option>
                    <option value="Large">Large</option>
                </select>
            </div>

            <!-- COLOR DROPDOWN -->
            <div>
                <label style="color:#ccc;">Color</label>
                <select name="color" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
                    <option value="Black">Black (Default)</option>
                    <option value="White">White</option>
                    <option value="Red">Red</option>
                </select>
            </div>

            <div>
                <label style="color:#ccc;">Price</label>
                <input type="number" name="price" step="0.01" min="0" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
            </div>
            <div>
                <label style="color:#ccc;">Quantity</label>
                <input type="number" name="quantity" min="1" value="1" required style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
            </div>

            <div>
                <label style="color:#ccc;">Category</label>
                <select name="category_id" style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;">
                    <option value="">None</option>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label style="color:#ccc;">Image</label>
                <input type="file" name="image" accept="image/*" style="padding:8px;">
            </div>
        </div>

        <div style="margin-top:25px;">
            <label style="color:#ccc;">Description</label>
            <textarea name="description" rows="5" style="width:100%;padding:12px;background:#333;border:1px solid #555;color:#fff;border-radius:8px;"></textarea>
        </div>

        <div style="text-align:center;margin-top:30px;">
            <button type="submit" style="padding:15px 50px;background:#FFC107;color:#000;font-weight:bold;border:none;border-radius:50px;font-size:1.1em;cursor:pointer;">
                ADD PRODUCT
            </button>
            <a href="products.php" style="margin-left:15px;padding:15px 40px;background:#444;color:#fff;text-decoration:none;border-radius:50px;">
                Cancel
            </a>
        </div>
    </form>
</main>
</body>
</html>