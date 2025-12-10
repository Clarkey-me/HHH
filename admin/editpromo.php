<?php
include '../connect.php';
// Fetch promo info
$sql = "SELECT * FROM sale_promo WHERE id = 1";
$result = $conn->query($sql);
$promo = $result->fetch_assoc();

// Update Sale Promo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $update = $conn->prepare("UPDATE sale_promo SET title=?, description=? WHERE id=1");
    $update->bind_param("ss", $title, $description);
    $update->execute();

    echo "<script>alert('Sale promo updated successfully!'); window.location='editpromo.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sale Promo</title>
    <link rel="stylesheet" href="adminCSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* ====== ADMIN CONTAINER ====== */
        .admin-container {
            margin-left: 240px; /* leave space for sidebar */
            padding: 80px 30px;
            max-width: 700px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        /* ====== FORM STYLING ====== */
        form {
            background: #fff;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            transition: 0.25s ease;
        }

        form input[type="text"]:focus,
        form textarea:focus {
            outline: none;
            border-color: #C9A227;
            box-shadow: 0 0 5px rgba(201,162,39,0.5);
        }

        form textarea {
            resize: vertical;
            min-height: 120px;
        }

        button[type="submit"] {
            width: 100%;
            background: #C9A227;
            color: #000;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button[type="submit"]:hover {
            background: #e0ba40;
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #C9A227;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .admin-container {
                margin-left: 0;
                padding: 120px 20px 50px;
            }
        }
    </style>
</head>
<body class="admin-page">

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

    <main class="admin-container">
        <h2>Edit Sale Promo</h2>
        <form method="POST">
            <label>Promo Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($promo['title']) ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($promo['description']) ?></textarea>

            <button type="submit">Save Changes</button>
        </form>

        <a href="settings.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Settings</a>
    </main>

</body>
</html>
