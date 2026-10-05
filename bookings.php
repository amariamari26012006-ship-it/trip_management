<?php
include 'config.php';
session_start();

// 1. User Login Check: User login pannalana login page-ukku redirect pannum
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first to book a trip!'); window.location.href='login.php';</script>";
    exit();
}

$message = "";

// 2. Booking Form Process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id      = $_SESSION['user_id'];
    $member_name  = mysqli_real_escape_string($conn, $_POST['member_name'] ?? ''); // 👈 Intha line-ah pudhusa add pannunga
    $destination  = mysqli_real_escape_string($conn, $_POST['package_name'] ?? '');
    $travel_date  = mysqli_real_escape_string($conn, $_POST['journey_date'] ?? '');
    $guests       = mysqli_real_escape_string($conn, $_POST['guests'] ?? '');
    $phone        = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions'] ?? '');

    // 👈 INSERT Query-la 'member_name' column-um values-um irukanum:
    $insert_query = "INSERT INTO bookings (user_id, member_name, package_name, phone, journey_date, guests, instructions) 
                     VALUES ('$user_id', '$member_name', '$destination', '$phone', '$travel_date', '$guests', '$instructions')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Booking successful!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Trip - Trip Management</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #333;
        }

        /* Header Navigation */
        header {
            background: #1e40af;
            color: #fff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        nav ul li a:hover {
            color: #93c5fd;
        }

        /* Container & Form */
        .container {
            max-width: 550px;
            margin: 40px auto;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .form-title {
            text-align: center;
            color: #1e40af;
            font-size: 26px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #475569;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: #2563eb;
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #1d4ed8;
        }

        .alert.error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #fca5a5;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #64748b;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="logo">
            <i class="fa-solid fa-plane-departure"></i> Trip Management
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#packages">Trip Packages</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><span style="color: #93c5fd; font-weight: 600;"><i class="fa-solid fa-user"></i> <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></span></li>
            </ul>
        </nav>
    </header>

    <!-- Main Booking Form -->
    <div class="container">
        <h2 class="form-title"><i class="fa-solid fa-calendar-check"></i> Book Your Trip</h2>

        <?php echo $message; ?>

        <form action="booking.php" method="POST">
        <div class="form-group">
    <label><i class="fa-solid fa-user"></i> Booked By / Member Name</label>
    <input type="text" name="member_name" placeholder="Enter traveler name" required>
</div>

<div class="form-group">
    <label><i class="fa-solid fa-map-location-dot"></i> Select Package / Destination</label>
    <select name="package_name" required>
        <option value="">-- Choose Package --</option>
                    <?php
    
    $selected_id = isset($_GET['id']) ? $_GET['id'] : 0;

    $pkg_sql = "SELECT * FROM packages";
    $pkg_result = mysqli_query($conn, $pkg_sql);

    if ($pkg_result && mysqli_num_rows($pkg_result) > 0) {
        while ($pkg = mysqli_fetch_assoc($pkg_result)) {
            $pid = $pkg['package_id'];
            $pname = $pkg['package_name'];
            $pprice = $pkg['price'];

            $is_selected = ($pid == $selected_id) ? "selected" : "";
            
            echo '<option value="' . $pid . '" ' . $is_selected . '>' . htmlspecialchars($pname) . ' (₹' . number_format($pprice) . ')</option>';
        }
    }
    ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-phone"></i> Phone Number</label>
                <input type="text" name="phone" placeholder="Enter mobile number" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-calendar-days"></i> Journey Date</label>
                <input type="date" name="journey_date" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-users"></i> Number of Guests</label>
                <input type="number" name="guests" value="1" min="1" max="20" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-comment-dots"></i> Special Instructions (Optional)</label>
                <textarea name="instructions" rows="3" placeholder="Any specific requirements..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Confirm Booking</button>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Trip Management System. All Rights Reserved.</p>
    </footer>

</body>
</html>