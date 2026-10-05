<?php
session_start();
include 'config.php';

// Session check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database Connection
$conn = mysqli_connect("localhost", "root", "", "trip_management");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Delete Booking
if (isset($_GET['delete'])) {

    $booking_id = intval($_GET['delete']);

    $delete_query = "DELETE FROM bookings WHERE booking_id = $booking_id";
    mysqli_query($conn, $delete_query);

    header("Location: dashboard.php");
    exit();
}


// Fetch Total Bookings Count
$total_bookings_query = "SELECT COUNT(*) as total FROM bookings";
$total_bookings_result = mysqli_query($conn, $total_bookings_query);
$total_bookings = mysqli_fetch_assoc($total_bookings_result)['total'] ?? 0;


// Fetch Total Packages Count
$pkg_count_query = "SELECT COUNT(*) as total FROM packages";
$pkg_count_result = mysqli_query($conn, $pkg_count_query);
$total_packages = mysqli_fetch_assoc($pkg_count_result)['total'] ?? 0;


// Fetch Total Guests Count
$total_guests_query = "SELECT SUM(guests) as total_guests FROM bookings";
$total_guests_result = mysqli_query($conn, $total_guests_query);
$total_guests = mysqli_fetch_assoc($total_guests_result)['total_guests'] ?? 0;


// Fetch Total Revenue
$revenue_query = "SELECT SUM(price) as total_revenue FROM bookings";
$revenue_result = mysqli_query($conn, $revenue_query);
$total_revenue = mysqli_fetch_assoc($revenue_result)['total_revenue'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trip Management - Admin Dashboard</title>

    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* Sidebar */

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

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #1f2937;
            color: #fff;
        }

        /* Main Content */

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        /* Header */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #111827;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            padding: 8px 15px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Stats */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-info h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .card-info p {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Table */

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        /* Status */

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.confirmed {
            background: #dcfce7;
            color: #15803d;
        }

        /* Action Buttons */

        .action-buttons {
            white-space: nowrap;
        }

        .btn-view,
        .btn-whatsapp,
        .btn-map,
        .btn-delete {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            margin-right: 4px;
            color: white;
        }

        .btn-view {
            background: #6c757d;
        }

        .btn-view:hover {
            background: #555;
        }

        .btn-whatsapp {
            background: #25D366;
        }

        .btn-whatsapp:hover {
            opacity: 0.85;
        }

        .btn-map {
            background: #4285F4;
        }

        .btn-map:hover {
            opacity: 0.85;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #b02a37;
        }

    </style>

</head>

<body>


<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <h2>
        <i class="fa-solid fa-plane-departure"></i>
        Trip Admin
    </h2>

    <ul>

        <li>
            <a href="dashboard.php" class="active">
                <i class="fa-solid fa-chart-pie"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="booking.php">
                <i class="fa-solid fa-bookmark"></i>
                Booking
            </a>
        </li>

        <li>
            <a href="packages.php">
                <i class="fa-solid fa-map-location-dot"></i>
                Packages
            </a>
        </li>

        <li>
            <a href="users.php">
                <i class="fa-solid fa-users"></i>
                Users
            </a>
        </li>

        <li>
            <a href="index.php">
                <i class="fa-solid fa-globe"></i>
                View Website
            </a>
        </li>

        <li>
            <a href="logout.php" class="nav-link text-danger">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </li>

    </ul>

</div>


<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">


    <!-- Header -->

    <div class="header">

        <h1>Logistics & Trip Dashboard</h1>

        <div class="user-profile">

            <i class="fa-solid fa-user-circle fa-lg"></i>

            <span>Admin</span>

        </div>

    </div>


    <!-- ================= STATS ================= -->

    <div class="stats-grid">


        <!-- Total Bookings -->

        <div class="card">

            <div class="card-info">

                <h3>Total Bookings</h3>

                <p>
                    <?php echo $total_bookings; ?>
                </p>

            </div>

            <div class="card-icon">
                <i class="fa-solid fa-ticket"></i>
            </div>

        </div>


        <!-- Active Tours -->

        <div class="card">

            <div class="card-info">

                <h3>Active Tours</h3>

                <p>
                    <?php echo $total_packages; ?>
                </p>

            </div>

            <div class="card-icon">
                <i class="fa-solid fa-route"></i>
            </div>

        </div>


        <!-- Total Guests -->

        <div class="card">

            <div class="card-info">

                <h3>Total Guests</h3>

                <p>
                    <?php echo $total_guests; ?>
                </p>

            </div>

            <div class="card-icon">
                <i class="fa-solid fa-user-group"></i>
            </div>

        </div>


        <!-- Revenue -->

        <div class="card">

            <div class="card-info">

                <h3>Revenue</h3>

                <p>
                    ₹<?php echo number_format($total_revenue); ?>
                </p>

            </div>

            <div class="card-icon">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>

        </div>


    </div>


    <!-- ================= RECENT BOOKINGS ================= -->

    <div class="table-container">


        <div class="table-header">

            <h2>Recent Bookings</h2>


            <!-- Search -->

            <div>

                <form method="GET"
                      action="dashboard.php"
                      style="display:flex;gap:10px;">

                    <input
                        type="text"
                        name="search"
                        placeholder="Search by Package or Phone..."
                        value="<?php
                        echo isset($_GET['search'])
                            ? htmlspecialchars($_GET['search'])
                            : '';
                        ?>"
                        style="
                        padding:6px 12px;
                        width:250px;
                        border:1px solid #ccc;
                        border-radius:4px;
                        ">

                    <button
                        type="submit"
                        style="
                        padding:6px 15px;
                        background:#007bff;
                        color:white;
                        border:none;
                        border-radius:4px;
                        cursor:pointer;
                        ">

                        Search

                    </button>

                </form>

            </div>

        </div>


        <!-- ================= TABLE ================= -->

        <table>

            <thead>

                <tr>

                    <th>Booking ID</th>

                    <th>Member Name</th>

                    <th>Package Name</th>

                    <th>Phone</th>

                    <th>Journey Date</th>

                    <th>Guests</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

<?php

$search_query = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';


/* Search Query */

if (!empty($search_query)) {

    $sql = "SELECT bookings.*,
                   packages.package_name AS pkg_title
            FROM bookings
            JOIN packages
            ON bookings.package_name = packages.package_id
            WHERE packages.package_name LIKE '%$search_query%'
               OR bookings.phone LIKE '%$search_query%'
               OR bookings.member_name LIKE '%$search_query%'
            ORDER BY bookings.booking_id DESC";

}


/* Normal Query */

else {

    $sql = "SELECT bookings.*,
                   packages.package_name AS pkg_title
            FROM bookings
            JOIN packages
            ON bookings.package_name = packages.package_id
            ORDER BY bookings.booking_id DESC";

}


$result = mysqli_query($conn, $sql);


if ($result && mysqli_num_rows($result) > 0) {


    while ($row = mysqli_fetch_assoc($result)) {


        /* Customer Name */

        $member_name = !empty($row['member_name'])
            ? $row['member_name']
            : 'N/A';


        /* Package Name */

        $package_name = !empty($row['pkg_title'])
            ? $row['pkg_title']
            : 'N/A';


        /* ================= GOOGLE MAP ================= */

        $package_city = $package_name;

        $map_url =
            "https://www.google.com/maps/search/?api=1&query="
            . urlencode($package_city);


        /* ================= WHATSAPP ================= */

        $customer_phone =
            preg_replace('/[^0-9]/', '', $row['phone']);


        if (strlen($customer_phone) == 10) {

            $customer_phone = "91" . $customer_phone;

        }


        /* WhatsApp Message */

        $whatsapp_message =
            "Hello " . $member_name . ",\n\n" .
            "Your Trip Booking Details:\n" .
            "Booking ID: #" . $row['booking_id'] . "\n" .
            "Package: " . $package_name . "\n" .
            "Journey Date: " . $row['journey_date'] . "\n" .
            "Guests: " . $row['guests'] . "\n" .
            "Price: ₹" . $row['price'] . "\n\n" .
            "Thank you for booking with us.";


        /* WhatsApp URL */

        $whatsapp_url =
            "https://wa.me/" .
            $customer_phone .
            "?text=" .
            urlencode($whatsapp_message);


        /* ================= TABLE ROW ================= */

        echo "<tr>";


        // Booking ID

        echo "<td>#"
            . htmlspecialchars($row['booking_id'])
            . "</td>";


        // Member Name

        echo "<td>
                <strong>"
                . htmlspecialchars($member_name) .
                "</strong>
              </td>";


        // Package Name

        echo "<td>"
            . htmlspecialchars($package_name)
            . "</td>";


        // Phone

        echo "<td>"
            . htmlspecialchars($row['phone'])
            . "</td>";


        // Journey Date

        echo "<td>"
            . htmlspecialchars($row['journey_date'])
            . "</td>";


        // Guests

        echo "<td>"
            . htmlspecialchars($row['guests'])
            . "</td>";


        // Status

        echo "<td>
                <span class='status confirmed'>
                    Confirmed
                </span>
              </td>";


        // ================= ACTION =================

        echo "<td class='action-buttons'>";


        // VIEW

        echo "<a href='view_booking.php?id="
            . $row['booking_id'] .
            "' class='btn-view'>
                <i class='fa-solid fa-eye'></i>
                View
              </a>";


        // WHATSAPP

        echo "<a href='"
            . htmlspecialchars($whatsapp_url)
            . "' target='_blank'
               class='btn-whatsapp'>

                <i class='fa-brands fa-whatsapp'></i>
                WhatsApp

              </a>";


        // GOOGLE MAP

        echo "<a href='"
            . htmlspecialchars($map_url)
            . "' target='_blank'
               class='btn-map'>

                <i class='fa-solid fa-location-dot'></i>
                Map

              </a>";


        // DELETE

        echo "<a href='dashboard.php?delete="
            . $row['booking_id']
            . "'
            onclick=\"return confirm('Delete this booking?');\"
            class='btn-delete'>

                <i class='fa-solid fa-trash'></i>
                Delete

              </a>";


        echo "</td>";


        echo "</tr>";

    }


} else {


    echo "<tr>

            <td colspan='8'
                style='text-align:center;'>

                No bookings found

            </td>

          </tr>";

}

?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>