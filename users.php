<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users - Trip Admin</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; background: #f8fafc; color: #333; }

        /* Sidebar Navigation */
        .sidebar { width: 250px; background: #0f172a; height: 100vh; color: #fff; padding: 20px; position: fixed; }
        .sidebar h2 { font-size: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 10px; color: #38bdf8; }
        .sidebar ul { list-style: none; }
        .sidebar ul li { margin-bottom: 15px; }
        .sidebar ul li a { color: #94a3b8; text-decoration: none; font-size: 16px; display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 6px; transition: 0.3s; }
        .sidebar ul li a.active, .sidebar ul li a:hover { background: #1e293b; color: #fff; }

        /* Main Content */
        .main-content { margin-left: 250px; width: calc(100% - 250px); padding: 40px; }
        .page-title { font-size: 28px; color: #1e293b; margin-bottom: 20px; }

        /* Table Card Design */
        .table-card { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: #475569; font-size: 15px; }
        td { color: #334155; font-size: 15px; }
        
        .user-avatar { width: 35px; height: 35px; background: #2563eb; color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2><i class="fa-solid fa-plane-departure"></i> Trip Admin</h2>
        <ul>
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="booking.php"><i class="fa-solid fa-bookmark"></i> Bookings</a></li>
            <li><a href="packages.php"><i class="fa-solid fa-map-location-dot"></i> Packages</a></li>
            <li><a href="users.php"><i class="fa-solid fa-users"></i> Users</a></li>
            <li><a href="index.php"><i class="fa-solid fa-globe"></i> View Website</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <h1 class="page-title"><i class="fa-solid fa-users-gear"></i> User Management</h1>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>#User ID</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch Users from Database
                    $sql = "SELECT * FROM users ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $user_id   = $row['id'] ?? $row['user_id'] ?? '1';
                            $username = $row['fullname'] ?? $row['username'] ?? $row['name'] ?? 'User';
                            $email     = $row['email'] ?? 'N/A';
                            $role      = $row['role'] ?? 'Customer';
                            $firstLetter = strtoupper(substr($username, 0, 1));
                            ?>
                            <tr>
                                <td>#<?php echo $user_id; ?></td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span class="user-avatar"><?php echo $firstLetter; ?></span>
                                        <b><?php echo htmlspecialchars($username); ?></b>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($email); ?></td>
                                <td><span style="background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 4px; font-weight: 600; font-size: 13px;"><?php echo htmlspecialchars($role); ?></span></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align:center; color: #64748b;'>No registered users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>