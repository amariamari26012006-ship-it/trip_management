<?php

session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
     "trip_management_recovered"
);

if (!$conn) {
    die("Database connection failed");
}

// GET BOOKING ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Invalid Booking ID");
}

// UPDATE BOOKING
if (isset($_POST['update'])) {

    $member_name  = mysqli_real_escape_string($conn, $_POST['member_name']);
    $phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $package_name = intval($_POST['package_name']);
    $journey_date = mysqli_real_escape_string($conn, $_POST['journey_date']);
    $guests       = intval($_POST['guests']);

    if ($guests < 1) {
        $guests = 1;
    }

    $update = "
        UPDATE bookings SET
            member_name = '$member_name',
            phone = '$phone',
            package_name = '$package_name',
            journey_date = '$journey_date',
            guests = '$guests'
        WHERE booking_id = $id
    ";

    if (mysqli_query($conn, $update)) {

        header("Location: dashboard.php");
        exit();

    } else {

        $error = "Update failed";

    }
}

// GET BOOKING
$query = "
    SELECT *
    FROM bookings
    WHERE booking_id = $id
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Booking not found");
}

$booking = mysqli_fetch_assoc($result);

// GET PACKAGES
$packages = mysqli_query(
    $conn,
    "SELECT package_id, package_name FROM packages ORDER BY package_name"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Booking</title>

<style>

* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f7fe;
    padding: 30px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,.08);
}

h2 {
    margin-bottom: 20px;
    color: #111827;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
}

input,
select {
    width: 100%;
    padding: 11px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    outline: none;
}

input:focus,
select:focus {
    border-color: #2563eb;
}

.buttons {
    margin-top: 25px;
    display: flex;
    gap: 10px;
}

button,
.back {
    padding: 11px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    color: white;
}

button {
    background: #2563eb;
}

button:hover {
    background: #1d4ed8;
}

.back {
    background: #6b7280;
}

.error {
    color: red;
    margin-bottom: 10px;
}

</style>

</head>

<body>

<div class="container">

<h2>✏️ Edit Booking #<?php echo $id; ?></h2>

<?php if (isset($error)) { ?>

    <div class="error">
        <?php echo $error; ?>
    </div>

<?php } ?>

<form method="POST">

    <label>Member Name</label>

    <input
        type="text"
        name="member_name"
        value="<?php echo htmlspecialchars($booking['member_name']); ?>"
        required
    >

    <label>Phone</label>

    <input
        type="text"
        name="phone"
        value="<?php echo htmlspecialchars($booking['phone']); ?>"
        required
    >

    <label>Package</label>

    <select name="package_name" required>

        <?php while ($p = mysqli_fetch_assoc($packages)) { ?>

            <option
                value="<?php echo $p['package_id']; ?>"
                <?php
                if ($p['package_id'] == $booking['package_name']) {
                    echo "selected";
                }
                ?>
            >
                <?php echo htmlspecialchars($p['package_name']); ?>
            </option>

        <?php } ?>

    </select>

    <label>Journey Date</label>

    <input
        type="date"
        name="journey_date"
        value="<?php echo htmlspecialchars($booking['journey_date']); ?>"
        required
    >

    <label>Guests</label>

    <input
        type="number"
        name="guests"
        min="1"
        value="<?php echo intval($booking['guests']); ?>"
        required
    >

    <div class="buttons">

        <button type="submit" name="update">
            💾 Update Booking
        </button>

        <a href="dashboard.php" class="back">
            ← Back
        </a>

    </div>

</form>

</div>

</body>

</html>
