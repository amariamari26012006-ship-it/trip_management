<?php

session_start();

include 'config.php';


// ==========================================
// SESSION CHECK
// ==========================================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}


// ==========================================
// DATABASE CONNECTION
// ==========================================

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "trip_management"
);


if (!$conn) {

    die(
        "Connection failed: "
        . mysqli_connect_error()
    );

}


// ==========================================
// DELETE BOOKING
// ==========================================

if (isset($_GET['delete'])) {

    $booking_id = intval(
        $_GET['delete']
    );


    if ($booking_id > 0) {

        $delete_query = "
            DELETE FROM bookings
            WHERE booking_id = $booking_id
        ";


        mysqli_query(
            $conn,
            $delete_query
        );

    }


    header(
        "Location: dashboard.php"
    );

    exit();

}


// ==========================================
// TOTAL BOOKINGS
// ==========================================

$total_bookings_query = "

    SELECT COUNT(*) AS total

    FROM bookings

";


$total_bookings_result =
    mysqli_query(
        $conn,
        $total_bookings_query
    );


$total_bookings = 0;


if ($total_bookings_result) {

    $data =
        mysqli_fetch_assoc(
            $total_bookings_result
        );


    $total_bookings =
        $data['total'] ?? 0;

}


// ==========================================
// ACTIVE PACKAGES
// ==========================================

$pkg_count_query = "

    SELECT COUNT(*) AS total

    FROM packages

    WHERE status = 'Active'

";


$pkg_count_result =
    mysqli_query(
        $conn,
        $pkg_count_query
    );


$total_packages = 0;


if ($pkg_count_result) {

    $data =
        mysqli_fetch_assoc(
            $pkg_count_result
        );


    $total_packages =
        $data['total'] ?? 0;

}


// ==========================================
// TOTAL GUESTS
// ==========================================

$total_guests_query = "

    SELECT
        SUM(guests) AS total_guests

    FROM bookings

";


$total_guests_result =
    mysqli_query(
        $conn,
        $total_guests_query
    );


$total_guests = 0;


if ($total_guests_result) {

    $data =
        mysqli_fetch_assoc(
            $total_guests_result
        );


    $total_guests =
        $data['total_guests'] ?? 0;

}


// ==========================================
// TOTAL REVENUE
// ==========================================
//
// bookings table-ல் price/status
// column தேவையில்லை.
//
// packages.price பயன்படுத்துகிறோம்.
//
// Revenue = package price × guests
//
// ==========================================

$revenue_query = "

    SELECT

        SUM(
            p.price * b.guests
        ) AS total_revenue

    FROM bookings b

    LEFT JOIN packages p

        ON b.package_name = p.package_id

";


$revenue_result =
    mysqli_query(
        $conn,
        $revenue_query
    );


$total_revenue = 0;


if ($revenue_result) {

    $data =
        mysqli_fetch_assoc(
            $revenue_result
        );


    $total_revenue =
        $data['total_revenue'] ?? 0;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Trip Management - Admin Dashboard
    </title>


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >


    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            font-family:
                'Segoe UI',
                Tahoma,
                Geneva,
                Verdana,
                sans-serif;

        }


        body {

            display: flex;

            background-color: #f4f7fe;

            color: #333;

        }


        /* ==================================
           SIDEBAR
        ================================== */

        .sidebar {

            width: 260px;

            height: 100vh;

            background: #111827;

            color: #fff;

            padding: 20px;

            position: fixed;

            left: 0;

            top: 0;

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


        /* ==================================
           MAIN CONTENT
        ================================== */

        .main-content {

            margin-left: 260px;

            flex: 1;

            padding: 30px;

            width:
                calc(100% - 260px);

        }


        /* ==================================
           HEADER
        ================================== */

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

            box-shadow:
                0 2px 5px
                rgba(0,0,0,0.05);

        }


        /* ==================================
           STATS
        ================================== */

        .stats-grid {

            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(220px, 1fr)
                );

            gap: 20px;

            margin-bottom: 30px;

        }


        .card {

            background: #fff;

            padding: 20px;

            border-radius: 12px;

            box-shadow:
                0 4px 12px
                rgba(0,0,0,0.03);

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


        /* ==================================
           TABLE CONTAINER
        ================================== */

        .table-container {

            background: #fff;

            padding: 20px;

            border-radius: 12px;

            box-shadow:
                0 4px 12px
                rgba(0,0,0,0.03);

            overflow-x: auto;

        }


        .table-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

        }


        .table-header h2 {

            color: #111827;

        }


        /* ==================================
           SEARCH
        ================================== */

        .search-box {

            display: flex;

            gap: 10px;

        }


        .search-box input {

            padding: 8px 12px;

            width: 250px;

            border:
                1px solid #d1d5db;

            border-radius: 5px;

            outline: none;

        }


        .search-box input:focus {

            border-color: #2563eb;

        }


        .search-box button {

            padding: 8px 15px;

            background: #2563eb;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

        }


        .search-box button:hover {

            background: #1d4ed8;

        }


        /* ==================================
           TABLE
        ================================== */

        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 1100px;

        }


        th,

        td {

            padding: 12px 15px;

            text-align: left;

            border-bottom:
                1px solid #e5e7eb;

        }


        th {

            background-color: #f9fafb;

            color: #4b5563;

            font-size: 14px;

        }


        td {

            font-size: 14px;

        }


        tr:hover {

            background-color: #f9fafb;

        }


        /* ==================================
           PRICE
        ================================== */

        .price {

            color: #2563eb;

            font-weight: 700;

            white-space: nowrap;

        }


        .price-small {

            display: block;

            color: #6b7280;

            font-size: 11px;

            font-weight: normal;

            margin-top: 3px;

        }


        /* ==================================
           STATUS
        ================================== */

        .status {

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: 600;

            background: #dcfce7;

            color: #15803d;

        }


        /* ==================================
           ACTION BUTTONS
        ================================== */

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

            background: #1da851;

        }


        .btn-map {

            background: #4285F4;

        }


        .btn-map:hover {

            background: #2563eb;

        }


        .btn-delete {

            background: #dc3545;

        }


        .btn-delete:hover {

            background: #b02a37;

        }


        /* ==================================
           MOBILE
        ================================== */

        @media (max-width: 768px) {

            .sidebar {

                width: 220px;

            }


            .main-content {

                margin-left: 220px;

                width:
                    calc(100% - 220px);

                padding: 20px;

            }


            .table-header {

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;

            }


            .search-box input {

                width: 200px;

            }

        }

    </style>

</head>


<body>


<!-- ==========================================
     SIDEBAR
========================================== -->

<div class="sidebar">

    <h2>

        <i
            class="fa-solid fa-plane-departure"
        ></i>

        Trip Admin

    </h2>


    <ul>


        <li>

            <a
                href="dashboard.php"
                class="active"
            >

                <i
                    class="fa-solid fa-chart-pie"
                ></i>

                Dashboard

            </a>

        </li>


        <li>

            <a href="booking.php">

                <i
                    class="fa-solid fa-bookmark"
                ></i>

                Booking

            </a>

        </li>


        <li>

            <a href="packages.php">

                <i
                    class="fa-solid fa-map-location-dot"
                ></i>

                Packages

            </a>

        </li>


        <li>

            <a href="users.php">

                <i
                    class="fa-solid fa-users"
                ></i>

                Users

            </a>

        </li>


        <li>

            <a href="index.php">

                <i
                    class="fa-solid fa-globe"
                ></i>

                View Website

            </a>

        </li>


        <li>

            <a
                href="logout.php"
                style="color:#f87171;"
            >

                <i
                    class="fa-solid fa-right-from-bracket"
                ></i>

                Logout

            </a>

        </li>


    </ul>

</div>



<!-- ==========================================
     MAIN CONTENT
========================================== -->

<div class="main-content">


    <!-- HEADER -->

    <div class="header">

        <h1>

            Logistics & Trip Dashboard

        </h1>


        <div class="user-profile">

            <i
                class="fa-solid fa-user-circle fa-lg"
            ></i>

            <span>

                Admin

            </span>

        </div>

    </div>



    <!-- ======================================
         STATISTICS
    ======================================= -->

    <div class="stats-grid">


        <!-- TOTAL BOOKINGS -->

        <div class="card">

            <div class="card-info">

                <h3>
                    Total Bookings
                </h3>

                <p>

                    <?php

                    echo $total_bookings;

                    ?>

                </p>

            </div>


            <div class="card-icon">

                <i
                    class="fa-solid fa-ticket"
                ></i>

            </div>

        </div>



        <!-- ACTIVE TOURS -->

        <div class="card">

            <div class="card-info">

                <h3>
                    Active Tours
                </h3>

                <p>

                    <?php

                    echo $total_packages;

                    ?>

                </p>

            </div>


            <div class="card-icon">

                <i
                    class="fa-solid fa-route"
                ></i>

            </div>

        </div>



        <!-- TOTAL GUESTS -->

        <div class="card">

            <div class="card-info">

                <h3>
                    Total Guests
                </h3>

                <p>

                    <?php

                    echo $total_guests;

                    ?>

                </p>

            </div>


            <div class="card-icon">

                <i
                    class="fa-solid fa-user-group"
                ></i>

            </div>

        </div>



        <!-- REVENUE -->

        <div class="card">

            <div class="card-info">

                <h3>
                    Revenue
                </h3>

                <p>

                    ₹<?php

                    echo number_format(
                        $total_revenue
                    );

                    ?>

                </p>

            </div>


            <div class="card-icon">

                <i
                    class="fa-solid fa-indian-rupee-sign"
                ></i>

            </div>

        </div>


    </div>



    <!-- ======================================
         RECENT BOOKINGS
    ======================================= -->

    <div class="table-container">


        <div class="table-header">


            <h2>
                Recent Bookings
            </h2>


            <!-- SEARCH -->

            <form
                method="GET"
                action="dashboard.php"
                class="search-box"
            >


                <input
                    type="text"
                    name="search"
                    placeholder="Search by Package or Phone..."
                    value="<?php

                    echo isset(
                        $_GET['search']
                    )
                    ? htmlspecialchars(
                        $_GET['search']
                    )
                    : '';

                    ?>"
                >


                <button type="submit">

                    <i
                        class="fa-solid fa-search"
                    ></i>

                    Search

                </button>


            </form>


        </div>



        <!-- ==================================
             BOOKING TABLE
        ================================== -->

        <table>


            <thead>

                <tr>

                    <th>
                        Booking ID
                    </th>

                    <th>
                        Member Name
                    </th>

                    <th>
                        Package Name
                    </th>

                    <th>
                        Price
                    </th>

                    <th>
                        Phone
                    </th>

                    <th>
                        Journey Date
                    </th>

                    <th>
                        Guests
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>


<?php


// ==========================================
// SEARCH
// ==========================================

$search_query = isset(
    $_GET['search']
)
? mysqli_real_escape_string(
    $conn,
    $_GET['search']
)
: '';


// ==========================================
// BOOKING QUERY
// ==========================================
//
// bookings.package_name
// contains package_id.
//
// Example:
//
// package_name = 5
//
// packages.package_id = 5
//
// ==========================================

$sql = "

    SELECT

        b.booking_id,

        b.member_name,

        b.phone,

        b.journey_date,

        b.guests,

        p.package_name
            AS pkg_title,

        p.price
            AS package_price,

        l.city,

        l.country

    FROM bookings b


    LEFT JOIN packages p

        ON b.package_name =
           p.package_id


    LEFT JOIN locations l

        ON p.location_id =
           l.id

";


// ==========================================
// SEARCH CONDITION
// ==========================================

if (!empty($search_query)) {

    $sql .= "

        WHERE

            p.package_name LIKE
            '%$search_query%'

            OR b.phone LIKE
            '%$search_query%'

            OR b.member_name LIKE
            '%$search_query%'

    ";

}


// ==========================================
// ORDER
// ==========================================

$sql .= "

    ORDER BY
        b.booking_id DESC

";


// ==========================================
// RUN QUERY
// ==========================================

$result = mysqli_query(
    $conn,
    $sql
);


// ==========================================
// CHECK RESULT
// ==========================================

if (
    $result &&
    mysqli_num_rows($result) > 0
) {


    while (
        $row =
        mysqli_fetch_assoc($result)
    ) {


        // ==================================
        // MEMBER NAME
        // ==================================

        $member_name =
            !empty(
                $row['member_name']
            )
            ? $row['member_name']
            : 'N/A';


        // ==================================
        // PACKAGE NAME
        // ==================================

        $package_name =
            !empty(
                $row['pkg_title']
            )
            ? $row['pkg_title']
            : 'N/A';


        // ==================================
        // PACKAGE PRICE
        // ==================================

        $package_price =
            floatval(
                $row['package_price'] ?? 0
            );


        // ==================================
        // GUESTS
        // ==================================

        $guests =
            intval(
                $row['guests'] ?? 1
            );


        if ($guests <= 0) {

            $guests = 1;

        }


        // ==================================
        // TOTAL PRICE
        // ==================================

        $total_price =
            $package_price *
            $guests;


        // ==================================
        // LOCATION
        // ==================================

        if (
            !empty($row['city'])
        ) {

            $map_location =
                trim(
                    $row['city'] .
                    ', ' .
                    $row['country']
                );

        } else {

            $map_location =
                $package_name;

        }


        // ==================================
        // GOOGLE MAP URL
        // ==================================

        $map_url =

            "https://www.google.com/maps/search/?api=1&query="

            .
            urlencode(
                $map_location
            );


        // ==================================
        // PHONE
        // ==================================

        $customer_phone =

            preg_replace(
                '/[^0-9]/',
                '',
                $row['phone']
            );


        if (
            strlen($customer_phone) == 10
        ) {

            $customer_phone =
                "91" .
                $customer_phone;

        }


        // ==================================
        // WHATSAPP MESSAGE
        // ==================================

        $whatsapp_message =

            "Hello " .
            $member_name .
            ",\n\n" .

            "Your Trip Booking Details:\n" .

            "Booking ID: #" .
            $row['booking_id'] .
            "\n" .

            "Package: " .
            $package_name .
            "\n" .

            "Price per Guest: ₹" .
            number_format(
                $package_price
            ) .
            "\n" .

            "Guests: " .
            $guests .
            "\n" .

            "Total Amount: ₹" .
            number_format(
                $total_price
            ) .
            "\n" .

            "Journey Date: " .
            $row['journey_date'] .
            "\n\n" .

            "Thank you for booking with us.";


        // ==================================
        // WHATSAPP URL
        // ==================================

        $whatsapp_url =

            "https://wa.me/" .
            $customer_phone .
            "?text=" .
            urlencode(
                $whatsapp_message
            );


        // ==================================
        // TABLE ROW
        // ==================================

        echo "<tr>";


        // BOOKING ID

        echo "

            <td>
                #
                " .
                htmlspecialchars(
                    $row['booking_id']
                )
                .
            "</td>

        ";


        // MEMBER NAME

        echo "

            <td>

                <strong>
                    "
                    .
                    htmlspecialchars(
                        $member_name
                    )
                    .
                    "
                </strong>

            </td>

        ";


        // PACKAGE NAME

        echo "

            <td>

                "
                .
                htmlspecialchars(
                    $package_name
                )
                .

            "</td>

        ";


        // PRICE

        echo "

            <td class='price'>

                ₹"
                .
                number_format(
                    $total_price
                )
                .

                "

                <span
                    class='price-small'
                >

                    ₹"
                    .
                    number_format(
                        $package_price
                    )
                    .
                    " × "
                    .
                    $guests
                    .

                "</span>

            </td>

        ";


        // PHONE

        echo "

            <td>

                "
                .
                htmlspecialchars(
                    $row['phone']
                )
                .

            "</td>

        ";


        // JOURNEY DATE

        echo "

            <td>

                "
                .
                htmlspecialchars(
                    $row['journey_date']
                )
                .

            "</td>

        ";


        // GUESTS

        echo "

            <td>

                "
                .
                $guests
                .

            "</td>

        ";


        // STATUS

        echo "

            <td>

                <span class='status'>

                    Confirmed

                </span>

            </td>

        ";


        // ACTION

        echo "

            <td class='action-buttons'>

        ";


        // ==================================
        // VIEW
        // ==================================

        echo "

            <a

                href='view_booking.php?id="
                .
                $row['booking_id']
                .
                "'

                class='btn-view'
            >

                <i
                    class='fa-solid fa-eye'
                ></i>

                View

            </a>

        ";


        // ==================================
        // WHATSAPP
        // ==================================

        echo "

            <a

                href='"
                .
                htmlspecialchars(
                    $whatsapp_url
                )
                .
                "'

                target='_blank'

                class='btn-whatsapp'
            >

                <i
                    class='fa-brands fa-whatsapp'
                ></i>

                WhatsApp

            </a>

        ";


        // ==================================
        // MAP
        // ==================================

        echo "

            <a

                href='"
                .
                htmlspecialchars(
                    $map_url
                )
                .
                "'

                target='_blank'

                class='btn-map'
            >

                <i
                    class='fa-solid fa-location-dot'
                ></i>

                Map

            </a>

        ";


        // ==================================
        // DELETE
        // ==================================

        echo "

            <a

                href='dashboard.php?delete="
                .
                $row['booking_id']
                .
                "'

                onclick=\"
                    return confirm(
                        'Delete this booking?'
                    );
                \"

                class='btn-delete'
            >

                <i
                    class='fa-solid fa-trash'
                ></i>

                Delete

            </a>

        ";


        echo "</td>";


        echo "</tr>";

    }


} else {


    // ==================================
    // NO BOOKINGS
    // ==================================

    echo "

        <tr>

            <td
                colspan='9'

                style='
                    text-align:center;
                    padding:30px;
                    color:#6b7280;
                '
            >

                No bookings found

            </td>

        </tr>

    ";

}

?>


            </tbody>

        </table>


    </div>


</div>


</body>

</html>
