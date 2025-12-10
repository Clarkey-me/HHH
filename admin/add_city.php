<?php
include '../connect.php';

if (isset($_POST['add_city'])) {
    $category = $_POST['category'];
    $province = $_POST['province'];
    $city_name = $_POST['name'];
    $distance = $_POST['distance_from_cebu_km'];

    $stmt = $conn->prepare("INSERT INTO cities (category, province, name, distance_from_cebu_km) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $category, $province, $city_name, $distance);
    $stmt->execute();

    echo "<script>alert('City added successfully!'); window.location='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add City</title>
<link rel="stylesheet" href="adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: rgba(0,0,0,0.5);
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .window-container {
        width: 450px;
        background: #fff;
        padding: 35px 40px;
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

    .window-container form label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #555;
    }

    .window-container form input,
    .window-container form select {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 18px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        transition: 0.25s ease;
    }

    .window-container form input:focus,
    .window-container form select:focus {
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
        color: #e0ba40;
        text-decoration: underline;
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
    <h2>Add City</h2>
    <form method="post">
        <label>Region:</label>
        <select name="category" required>
            <option value="Luzon">Luzon</option>
            <option value="Visayas">Visayas</option>
            <option value="Mindanao">Mindanao</option>
        </select>

        <label>Province:</label>
        <input type="text" name="province" placeholder="Enter province" required>

        <label>City Name:</label>
        <input type="text" name="name" placeholder="Enter city name" required>

        <label>Distance from Cebu (km):</label>
        <input type="number" step="0.01" name="distance_from_cebu_km" placeholder="Distance in km" required>

        <button type="submit" name="add_city"><i class="fa-solid fa-plus"></i> Add City</button>
    </form>

    <a href="city.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
</div>

</body>
</html>
