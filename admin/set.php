<?php
include '../connect.php';
$price_per_km = $conn->query("SELECT value FROM settings WHERE key_name='price_per_km'")->fetch_assoc()['value'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $new_price = $_POST['price'];
    $stmt = $conn->prepare("UPDATE settings SET value=? WHERE key_name='price_per_km'");
    $stmt->bind_param("d",$new_price);
    $stmt->execute();
    echo "<script>alert('Price updated successfully!'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Shipping Price</title>
<link rel="stylesheet" href="adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: rgba(0,0,0,0.5); /* match Add City design overlay */
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .window-container {
        width: 400px;
        background: #fff;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: scale(0.95);}
        to {opacity: 1; transform: scale(1);}
    }

    .window-container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .window-container input[type="number"] {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 1rem;
        margin-bottom: 20px;
        transition: 0.25s ease;
    }

    .window-container input[type="number"]:focus {
        outline: none;
        border-color: #C9A227;
        box-shadow: 0 0 5px rgba(201,162,39,0.5);
    }

    .window-container button[type="submit"] {
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

    .window-container button[type="submit"]:hover {
        background: #e0ba40;
        transform: translateY(-2px);
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #C9A227;
        font-weight: 500;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .back-link:hover {
        text-decoration: underline;
        color: #e0ba40;
    }

    @media (max-width: 500px) {
        .window-container {
            width: 90%;
            padding: 25px 20px;
        }
    }
</style>
</head>
<body>
    <div class="window-container">
        <form method="post">
            <h2>Shipping Price per km</h2>
            <input name="price" type="number" step="0.01" value="<?= $price_per_km ?>" required>
            <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Update</button>
        </form>
        <a href="city.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
</body>
</html>
