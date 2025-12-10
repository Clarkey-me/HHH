<?php
include '../connect.php';

// Get price per km
$price_per_km = $conn->query("SELECT value FROM settings WHERE key_name='price_per_km'")->fetch_assoc()['value'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cities and Shipping</title>
<link rel="stylesheet" href="../admin/adminCSS/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
    /* ====== CONTAINER ====== */
    .admin-container {
        margin-left: 245px; /* for sidebar */
        padding: 100px 30px;
        max-width: 1300px;
    }

    /* ====== FILTERS ====== */
    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }

    .filters label {
        font-weight: 600;
        margin-right: 5px;
        color: #555;
    }

    .filters select, .filters input {
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        transition: 0.25s ease;
    }

    .filters select:focus, .filters input:focus {
        outline: none;
        border-color: #C9A227;
        box-shadow: 0 0 5px rgba(201,162,39,0.5);
    }

    .filters a {
        background: #C9A227;
        color: #000;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .filters a:hover {
        background: #e0ba40;
    }

    /* ====== TABLE ====== */
    table.admin-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    table.admin-table th, table.admin-table td {
        padding: 12px 20px;
        text-align: center;
        font-size: 0.95rem;
        border-bottom: 1px solid #eee;
    }

    table.admin-table th {
        background: #C9A227;
        color: #000;
        font-weight: 600;
    }



    /* ====== ACTION BUTTONS ====== */
    table.admin-table td .btn {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #fff;
        text-decoration: none;
        margin: 2px 3px;
        display: inline-block;
        transition: 0.3s ease;
        cursor: pointer;
    }

    .btn-edit {
        background: #3498db;
        width: 84px;
    }
    .btn-edit:hover {
        background: #2980b9;
        color: #fff;
    }

    .btn-delete {
        background: #e74c3c;
    }
    .btn-delete:hover {
        background: #c0392b;
        color: #fff;
    }

    .btn-view {
        background: #2ecc71;
    }
    .btn-view:hover {
        background: #27ae60;
        color: #fff;
    }

    .btn i {
        margin-right: 4px;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
        .admin-container {
            margin-left: 0;
            padding: 120px 20px 50px;
        }

        .filters {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
async function fetchCities() {
    let category = document.getElementById('category').value;
    let search = document.getElementById('search').value;

    let res = await fetch(`fetch_cities.php?category=${category}&search=${search}`);
    let data = await res.text();

    document.getElementById('citiesTable').innerHTML = data;
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('category').addEventListener('change', fetchCities);
    document.getElementById('search').addEventListener('keyup', fetchCities);
    fetchCities(); // initial load
});
</script>
</head>
<body class="admin-page">

<?php include '../admin/includes/sidebar.php'; ?>
<?php include '../admin/includes/header.php'; ?>

<main class="admin-container">  

    <div class="filters">
        <div>
            <label>Region:</label>
            <select id="category">
                <option value="">All</option>
                <option value="Luzon">Luzon</option>
                <option value="Visayas">Visayas</option>
                <option value="Mindanao">Mindanao</option>
            </select>
        </div>

        <div>
            <label>Search:</label>
            <input type="text" id="search" placeholder="Type city...">
        </div>

        <div>
            <a href="add_city.php"><i class="fa-solid fa-plus"></i> Add City</a>
            <a href="set.php"><i class="fa-solid fa-location"></i> Price Adjust</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Region</th>
                <th>City</th>
                <th>Province</th>
                <th>Distance (km)</th>
                <th>Shipping Cost (PHP)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="citiesTable">
            





        </tbody>
    </table>
</main>

<?php include '../admin/includes/scripts.php'; ?>
</body>
</html>
