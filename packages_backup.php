<?php
// ==========================================
// DATABASE CONNECTION
// ==========================================
$conn = mysqli_connect("localhost", "root", "", "trip_management");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";


// ==========================================
// ADD NEW PACKAGE
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_package'])) {

    $package_name = mysqli_real_escape_string(
        $conn,
        $_POST['package_name']
    );

    $price = mysqli_real_escape_string(
        $conn,
        $_POST['price']
    );

    $duration = mysqli_real_escape_string(
        $conn,
        $_POST['duration']
    );

    $location_id = intval($_POST['location_id']);


    // Check location selected
    if ($location_id <= 0) {

        $message = "
        <div class='alert error'>
            Please select a location.
        </div>";

    } else {

        $insert_sql = "
            INSERT INTO packages
            (
                package_name,
                price,
                duration,
                location_id,
                status
            )
            VALUES
            (
                '$package_name',
                '$price',
                '$duration',
                '$location_id',
                'Active'
            )
        ";

        if (mysqli_query($conn, $insert_sql)) {

            $message = "
            <div class='alert success'>
                Package added successfully!
            </div>";

        } else {

            $message = "
            <div class='alert error'>
                Error: " . mysqli_error($conn) . "
            </div>";
        }
    }
}


// ==========================================
// DELETE PACKAGE
// ==========================================
if (isset($_GET['delete'])) {

    $delete_id = intval($_GET['delete']);

    if ($delete_id > 0) {

        $delete_sql = "
            DELETE FROM packages
            WHERE package_id = $delete_id
        ";

        mysqli_query($conn, $delete_sql);
    }

    header("Location: packages.php");
    exit();
}


// ==========================================
// GET LOCATIONS FOR DROPDOWN
// ==========================================
$locations_sql = "
    SELECT
        id,
        location_name,
        city,
        country
    FROM locations
    ORDER BY location_name ASC
";

$locations_result = mysqli_query($conn, $locations_sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Trip Management - Packages</title>


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
            font-family: 'Segoe UI',
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


        /* =========================
           SIDEBAR
        ========================= */

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


        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            width: calc(100% - 260px);
        }


        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }


        /* =========================
           FORM
        ========================= */

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 30px;
        }


        .form-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(200px, 1fr));

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


        .form-group input,
        .form-group select {

            width: 100%;

            padding: 10px;

            border: 1px solid #d1d5db;

            border-radius: 6px;

            font-size: 14px;

            background: #fff;
        }


        .form-group input:focus,
        .form-group select:focus {

            outline: none;

            border-color: #2563eb;
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


        /* =========================
           TABLE
        ========================= */

        .table-container {

            background: #fff;

            padding: 20px;

            border-radius: 12px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.03);

            overflow-x: auto;
        }


        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 15px;

            min-width: 850px;
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


        /* =========================
           BUTTONS
        ========================= */

        .btn-delete {

            color: #ef4444;

            text-decoration: none;

            font-weight: 600;

            margin-right: 10px;
        }


        .btn-delete:hover {
            color: #b91c1c;
        }


        .btn-map {

            display: inline-block;

            background: #16a34a;

            color: #fff;

            text-decoration: none;

            padding: 7px 10px;

            border-radius: 5px;

            font-size: 13px;

            font-weight: 600;
        }


        .btn-map:hover {
            background: #15803d;
        }


        /* =========================
           ALERT
        ========================= */

        .alert {

            padding: 10px 15px;

            border-radius: 6px;

            margin-bottom: 15px;

            font-size: 14px;
        }


        .alert.success {

            background: #dcfce7;

            color: #15803d;
        }


        .alert.error {

            background: #fee2e2;

            color: #b91c1c;
        }


        /* =========================
           LOCATION
        ========================= */

        .location-text {

            display: flex;

            align-items: center;

            gap: 6px;
        }


        .location-text i {

            color: #ef4444;
        }


        .price {

            font-weight: 700;

            color: #2563eb;
        }


        .status {

            color: #15803d;

            font-weight: 600;
        }

    </style>

</head>


<body>


<!-- ==========================================
     SIDEBAR
========================================== -->

<div class="sidebar">

    <h2>
        <i class="fa-solid fa-plane-departure"></i>
        Trip Admin
    </h2>


    <ul>

        <li>
            <a href="dashboard.php">
                <i class="fa-solid fa-chart-pie"></i>
                Dashboard
            </a>
        </li>


        <li>
            <a href="#">
                <i class="fa-solid fa-bookmark"></i>
                Bookings
            </a>
        </li>


        <li>
            <a href="packages.php" class="active">
                <i class="fa-solid fa-map-location-dot"></i>
                Packages
            </a>
        </li>


        <li>
            <a href="#">
                <i class="fa-solid fa-users"></i>
                Users
            </a>
        </li>


        <li>
            <a href="index.html">
                <i class="fa-solid fa-globe"></i>
                View Website
            </a>
        </li>

    </ul>

</div>



<!-- ==========================================
     MAIN CONTENT
========================================== -->

<div class="main-content">


    <div class="header">

        <h1>
            Tour Packages Management
        </h1>

    </div>


    <?php echo $message; ?>



    <!-- ======================================
         ADD NEW PACKAGE
    ======================================= -->

    <div class="form-container">

        <h2>
            <i class="fa-solid fa-plus-circle"></i>
            Add New Package
        </h2>


        <form
            method="POST"
            action="packages.php"
        >


            <div class="form-grid">


                <!-- PACKAGE NAME -->

                <div class="form-group">

                    <label>
                        Package Name
                    </label>

                    <input
                        type="text"
                        name="package_name"
                        placeholder="e.g. Manali Adventure Package"
                        required
                    >

                </div>



                <!-- PRICE -->

                <div class="form-group">

                    <label>
                        Price (₹)
                    </label>

                    <input
                        type="number"
                        name="price"
                        placeholder="e.g. 25000"
                        min="0"
                        required
                    >

                </div>



                <!-- DURATION -->

                <div class="form-group">

                    <label>
                        Duration
                    </label>

                    <input
                        type="text"
                        name="duration"
                        placeholder="e.g. 3 Days / 2 Nights"
                        required
                    >

                </div>



                <!-- LOCATION -->

                <div class="form-group">

                    <label>
                        Location
                    </label>


                    <select
                        name="location_id"
                        required
                    >

                        <option value="">
                            -- Select Location --
                        </option>


                        <?php

                        if (
                            $locations_result &&
                            mysqli_num_rows(
                                $locations_result
                            ) > 0
                        ) {

                            while (
                                $location =
                                mysqli_fetch_assoc(
                                    $locations_result
                                )
                            ) {

                                echo "<option value='" .
                                    $location['id'] .
                                    "'>" .

                                    htmlspecialchars(
                                        $location['location_name']
                                    ) .

                                    " - " .

                                    htmlspecialchars(
                                        $location['city']
                                    ) .

                                    "</option>";
                            }

                        }

                        ?>

                    </select>

                </div>


            </div>


            <button
                type="submit"
                name="add_package"
                class="btn-submit"
            >

                <i class="fa-solid fa-plus"></i>

                Add Package

            </button>


        </form>

    </div>



    <!-- ======================================
         PACKAGES LIST
    ======================================= -->

    <div class="table-container">

        <h2>
            All Tour Packages
        </h2>


        <table>


            <thead>

                <tr>

                    <th>ID</th>

                    <th>Package Name</th>

                    <th>Price</th>

                    <th>Duration</th>

                    <th>Location</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>



            <tbody>


                <?php

                // ==================================
                // GET PACKAGES + LOCATIONS
                // ==================================

                $sql = "

                    SELECT

                        p.package_id,

                        p.package_name,

                        p.price,

                        p.duration,

                        p.status,

                        l.location_name,

                        l.city,

                        l.country

                    FROM packages p

                    LEFT JOIN locations l

                        ON p.location_id = l.id

                    ORDER BY p.package_id DESC

                ";


                $result = mysqli_query(
                    $conn,
                    $sql
                );


                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {


                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {


                        // Google Maps location

                        $map_location =
                            trim(
                                $row['city'] .
                                ', ' .
                                $row['country']
                            );


                        $map_url =
                            "https://www.google.com/maps/search/?api=1&query=" .
                            urlencode($map_location);


                        echo "<tr>";


                        // ID

                        echo "<td>#"
                            . $row['package_id']
                            . "</td>";


                        // PACKAGE NAME

                        echo "<td>"
                            . htmlspecialchars(
                                $row['package_name']
                            )
                            . "</td>";


                        // PRICE

                        echo "<td class='price'>₹"
                            . number_format(
                                $row['price']
                            )
                            . "</td>";


                        // DURATION

                        echo "<td>"
                            . htmlspecialchars(
                                $row['duration']
                            )
                            . "</td>";


                        // LOCATION

                        echo "<td>";

                        echo "<div class='location-text'>";

                        echo "<i class='fa-solid fa-location-dot'></i>";

                        echo htmlspecialchars(
                            $row['city']
                        );

                        echo ", ";

                        echo htmlspecialchars(
                            $row['country']
                        );

                        echo "</div>";

                        echo "</td>";


                        // STATUS

                        echo "<td>";

                        echo "<span class='status'>";

                        echo htmlspecialchars(
                            $row['status']
                        );

                        echo "</span>";

                        echo "</td>";


                        // ACTION

                        echo "<td>";


                        // MAP BUTTON

                        if (
                            !empty($row['city'])
                        ) {

                            echo "<a
                                href='" .
                                htmlspecialchars(
                                    $map_url
                                ) .
                                "'
                                target='_blank'
                                class='btn-map'
                            >

                                <i class='fa-solid fa-map-location-dot'></i>

                                Map

                            </a>";

                        }


                        // DELETE

                        echo "<a
                            href='packages.php?delete=" .
                            $row['package_id'] .
                            "'
                            class='btn-delete'
                            onclick='return confirm(\"Delete this package?\");'
                        >

                            <i class='fa-solid fa-trash'></i>

                            Delete

                        </a>";


                        echo "</td>";


                        echo "</tr>";

                    }


                } else {


                    echo "
                    <tr>

                        <td
                            colspan='7'
                            style='text-align:center;'
                        >

                            No packages added yet.

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
