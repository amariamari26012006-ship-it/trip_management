<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "trip_management");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Form Submission - Add New Package
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_package'])) {
    $package_name = mysqli_real_escape_string($conn, $_POST['package_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);

    $insert_sql = "INSERT INTO packages (package_name, price, duration) VALUES ('$package_name', '$price', '$duration')";
    if (mysqli_query($conn, $insert_sql)) {
        $message = "<div class='alert success'>Package added successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handle Delete Package
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM packages WHERE package_id = $delete_id");
    header("Location: packages.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management - Packages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f7fe;
            color: #333;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #111827;
            color: #fff;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
            color: #3b82f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #1f2937;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Form Container */
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-submit {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 15px;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        /* Table Styling */
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-size: 14px;
        }

        .btn-delete {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert.success { background: #dcfce7; color: #15803d; }
        .alert.error { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2><i class="fa-solid fa-plane-departure"></i> Trip Admin</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa-solid fa-bookmark"></i> Bookings</a></li>
            <li><a href="packages.php" class="active"><i class="fa-solid fa-map-location-dot"></i> Packages</a></li>
            <li><a href="#"><i class="fa-solid fa-users"></i> Users</a></li>
            <li><a href="index.html"><i class="fa-solid fa-globe"></i> View Website</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        
        <div class="header">
            <h1>Tour Packages Management</h1>
        </div>

        <?php echo $message; ?>

        <!-- Add New Package Form -->
        <div class="form-container">
            <h2><i class="fa-solid fa-plus-circle"></i> Add New Package</h2>
            <form method="POST" action="packages.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" name="package_name" placeholder="e.g. Ooty Special Tour" required>
                    </div>
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" name="price" placeholder="e.g. 15000" required>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" placeholder="e.g. 3 Days / 2 Nights" required>
                    </div>
                </div>
                <button type="submit" name="add_package" class="btn-submit">Add Package</button>
            </form>
        </div>

        <!-- Packages List Table -->
        <div class="table-container">
            <h2>All Tour Packages</h2>
            <table>
                <thead>
    <tr>
        <th>ID</th>
        <th>Location Name</th>
        <th>City</th>
        <th>Country</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM locations ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                           echo "<tr>";
                           echo "<td>#" . $row['id'] . "</td>";
                           echo "<td>" . htmlspecialchars($row['location_name']) . "</td>";
                           echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                           echo "<td>" . htmlspecialchars($row['country']) . "</td>";
                           echo "<td><span style='color:green; font-weight:600;'>Active</span></td>";
                           echo "<td><a href='packages.php?delete=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Delete this location?\");'><i class='fa-solid fa-trash'></i> Delete</a></td>";
                           echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>No locations added yet.</td></tr>";
                    } 
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>