<?php
include "db.php";

$sql = "SELECT * FROM bookings ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Trip Management - All Bookings</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f7fb;
}

/* Top Header */

.header {
    height: 75px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 35px;
    border-bottom: 1px solid #eee;
}

.header h2 {
    margin: 0;
    color: #333;
}

.admin {
    background: #f1f1f1;
    padding: 10px 18px;
    border-radius: 20px;
}

/* Main */

.container {
    padding: 35px;
}

.page-title {
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 25px;
    color: #333;
}

/* Table Box */

.table-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}

th {
    text-align: left;
    padding: 15px;
    background: #f8f9fb;
    color: #555;
    font-size: 13px;
}

td {
    padding: 15px;
    border-top: 1px solid #eee;
    color: #444;
    font-size: 14px;
}

.customer-name {
    font-weight: bold;
    color: #333;
}

.phone {
    font-size: 12px;
    color: #777;
}

/* Status */

.status {
    display: inline-block;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.confirmed {
    background: #dff7e8;
    color: #198754;
}

.pending {
    background: #fff3cd;
    color: #856404;
}

/* Buttons */

.view-btn {
    padding: 8px 13px;
    border: none;
    border-radius: 6px;
    background: #eeeeee;
    cursor: pointer;
    text-decoration: none;
    color: #333;
    margin-right: 5px;
}

.whatsapp-btn {
    display: inline-block;
    padding: 9px 13px;
    background: #25D366;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.whatsapp-btn:hover {
    opacity: 0.85;
}

/* Empty */

.empty {
    text-align: center;
    padding: 40px;
    color: #777;
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

    <h2>🌍 Trip Management</h2>

    <div class="admin">
        👤 Admin
    </div>

</div>


<!-- MAIN -->

<div class="container">

    <div class="page-title">
        All Bookings
    </div>


    <div class="table-box">

        <table>

            <tr>

                <th>#</th>

                <th>CUSTOMER</th>

                <th>PACKAGE</th>

                <th>TOTAL</th>

                <th>STATUS</th>

                <th>TRAVEL DATE</th>

                <th>GUESTS</th>

                <th>ACTIONS</th>

            </tr>


<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        /*
         * Remove + and spaces from phone number
         * Example:
         * +91 98765 43210
         * becomes:
         * 919876543210
         */

        $phone = preg_replace('/[^0-9]/', '', $row['phone']);


        /*
         * If Indian number is stored as 10 digits,
         * automatically add 91.
         */

        if (strlen($phone) == 10) {
            $phone = "91" . $phone;
        }


        /* WhatsApp message */

        $message =
            "Hello, I am contacting you regarding my trip booking.%0A%0A" .

            "Booking ID: " . $row['id'] . "%0A" .

            "Customer: " . $row['customer_name'] . "%0A" .

            "Package: " . $row['package_name'] . "%0A" .

            "Travel Date: " . $row['travel_date'] . "%0A" .

            "Guests: " . $row['persons'] . "%0A" .

            "Total Amount: ₹" . $row['total_amount'] . "%0A%0A" .

            "Please confirm my booking.";


        $whatsapp_url =
            "https://wa.me/" . $phone .
            "?text=" . $message;


        /* Status class */

        $statusClass = "pending";

        if (strtolower($row['booking_status']) == "confirmed") {
            $statusClass = "confirmed";
        }

?>

            <tr>

                <!-- ID -->

                <td>
                    #<?php echo $row['id']; ?>
                </td>


                <!-- CUSTOMER -->

                <td>

                    <div class="customer-name">
                        <?php
                        echo htmlspecialchars(
                            $row['customer_name']
                        );
                        ?>
                    </div>

                    <div class="phone">
                        <?php
                        echo htmlspecialchars(
                            $row['phone']
                        );
                        ?>
                    </div>

                </td>


                <!-- PACKAGE -->

                <td>
                    <?php
                    echo htmlspecialchars(
                        $row['package_name']
                    );
                    ?>
                </td>


                <!-- TOTAL -->

                <td>
                    <strong>
                        ₹<?php
                        echo number_format(
                            $row['total_amount'],
                            2
                        );
                        ?>
                    </strong>
                </td>


                <!-- STATUS -->

                <td>

                    <span class="status <?php
                        echo $statusClass;
                    ?>">

                        ●
                        <?php
                        echo htmlspecialchars(
                            $row['booking_status']
                        );
                        ?>

                    </span>

                </td>


                <!-- TRAVEL DATE -->

                <td>
                    <?php
                    echo date(
                        "d M Y",
                        strtotime($row['travel_date'])
                    );
                    ?>
                </td>


                <!-- GUESTS -->

                <td>
                    <?php
                    echo $row['persons'];
                    ?>
                </td>


                <!-- ACTIONS -->

                <td>

                    <a
                        href="view_booking.php?id=<?php echo $row['id']; ?>"
                        class="view-btn">

                        👁 View

                    </a>


                    <a
                        href="<?php echo $whatsapp_url; ?>"
                        target="_blank"
                        class="whatsapp-btn">

                        📱 WhatsApp

                    </a>

                </td>

            </tr>

<?php

    }

} else {

?>

            <tr>

                <td colspan="8" class="empty">

                    No bookings found.

                </td>

            </tr>

<?php

}

?>

        </table>

    </div>

</div>

</body>
</html>