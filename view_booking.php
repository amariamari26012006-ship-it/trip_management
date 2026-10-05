<?php
include "config.php";

$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM bookings WHERE booking_id=$id");

if ($result->num_rows == 0) {
    die("Booking not found");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Details</title>

<style>
body{
    font-family:Arial;
    background:#f5f7fb;
    padding:40px;
}
.box{
    max-width:600px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:12px;
}
p{
    padding:10px;
    border-bottom:1px solid #eee;
}
a{
    padding:10px 15px;
    background:#eee;
    text-decoration:none;
    color:#333;
    border-radius:5px;
}
</style>
</head>

<body>

<div class="box">

<h2>📋 Booking Details</h2>

<p><b>Booking ID:</b> #<?php echo $row['booking_id']; ?></p>

<p><b>Customer:</b>
<?php echo htmlspecialchars($row['member_name']); ?></p>

<p><b>Phone:</b>
<?php echo htmlspecialchars($row['phone']); ?></p>

<p><b>Package:</b>
<?php echo htmlspecialchars($row['package_name']); ?></p>

<p><b>Journey Date:</b>
<?php echo htmlspecialchars($row['journey_date']); ?></p>

<p><b>Guests:</b>
<?php echo $row['guests']; ?></p>

<p><b>Price:</b>
₹<?php echo number_format((float)$row['price']); ?></p>

<a href="dashboard.php">← Back</a>

</div>

</body>
</html>