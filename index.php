<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "trip_management_recovered");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management - Explore The World</title>
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
            background-color: #f8fafc;
            color: #333;
        }

        /* Navbar Styling */
        header {
            background: #1e40af;
            color: #fff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
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
            align-items: center;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #93c5fd;
        }

        .btn-login {
            background: #ffffff;
            color: #1e40af !important;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #e0e7ff;
        }

        /* Hero Banner Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            padding: 20px;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 25px;
            color: #e2e8f0;
        }

        .btn-primary {
            background: #f59e0b;
            color: #fff;
            padding: 12px 28px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #d97706;
        }

        /* Packages Section */
        .packages-section {
            padding: 60px 50px;
            text-align: center;
        }

        .section-title {
            font-size: 32px;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .section-subtitle {
            color: #64748b;
            margin-bottom: 40px;
        }

        .package-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .package-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s;
        }

        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .package-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .package-title {
            font-size: 20px;
            color: #1e40af;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .package-duration {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .package-map-link {
            font-size: 14px;
            color: #0284c7;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .package-map-link:hover {
            text-decoration: underline;
            color: #0369a1;
        }

        .package-price {
            font-size: 22px;
            color: #059669;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .btn-book {
            background: #0ea5e9;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            display: block;
            text-align: center;
            transition: 0.2s;
        }

        .btn-book:hover {
            background: #0284c7;
        }

        /* Footer */
        footer {
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
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
                <li><a href="#packages">Trip Packages</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="login.php" class="btn-login"><i class="fa-solid fa-user"></i> Login / Admin</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Banner -->
    <section class="hero">
        <h1>Explore The World With Us</h1>
        <p>Plan your perfect trip and manage bookings easily!</p>
        <a href="#packages" class="btn-primary">Book Now</a>
    </section>

    <!-- Dynamic Packages Section -->
    <section class="packages-section" id="packages">
        <h2 class="section-title">Popular Trip Packages</h2>
        <p class="section-subtitle">Choose from our best handpicked tour destinations</p>
        
        <div class="package-grid">
            <?php
// 'locations' table-ku bathila 'packages' table-a query panrom
$sql = "SELECT * FROM packages";
$result = mysqli_query($conn, $sql);

// Correct mapping for your 34 database images so they match perfectly without repeating
$default_images = [
                "Coorg Coffee Estate Stay" => "https://media.istockphoto.com/id/538649450/photo/sign-for-a-coffee-grove-in-a-belize-jungle.jpg?s=1024x1024&w=is&k=20&c=9BuWTnuubSM2L-hENpuyyS8MZ609ivm_p0uu7wQoBPY=",
                "Wayanad Hill Station Trip" => "https://images.unsplash.com/photo-1668522871883-d3ea92f64dbc?q=80&w=875&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Goa Beach Vacation" => "https://plus.unsplash.com/premium_photo-1697729594707-0fc9e51c8eed?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Kerala Backwaters Tour" => "https://images.unsplash.com/photo-1718821585734-e22a8fc425a2?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Manali Adventure Package" => "https://images.unsplash.com/photo-1587628091708-f68a3dec664b?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Agra & Taj Mahal Special" => "https://images.unsplash.com/photo-1564507592333-c60657eea523?q=80&w=871&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Chikmagalur Coffee Tour" => "https://images.unsplash.com/photo-1627661224418-a82bc277c4aa?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Nepal Kathmandu & Pokhara" => "https://images.unsplash.com/photo-1574072538640-65ce052abc09?q=80&w=1032&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Ladakh Pangong Lake Trip" => "https://images.unsplash.com/photo-1652204597589-962156b9483d?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Bali Island Gateway" => "https://plus.unsplash.com/premium_photo-1730035378679-0df681b2ace5?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Bangkok & Pattaya Special" => "https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=500",
                "Hampi Historical Heritage Tour" => "https://images.unsplash.com/photo-1722934804353-0d9f6a55ab5e?q=80&w=845&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Gokarna Beach & Cliff Trek" => "https://images.unsplash.com/photo-1554787990-fd7a431e3b0a?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Yercaud Coffee Hills Escape" => "https://images.unsplash.com/photo-1544015759-237f87d55ef3?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Dhanushkodi Edge of India Tour" => "https://images.unsplash.com/photo-1628953735383-38ce7e3b7f7b?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Agumbe Rainforest & Sunset Tour" => "https://images.unsplash.com/photo-1625060958567-8ceab8fb0104?q=80&w=934&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Valparai Tea Estate Special" => "https://images.unsplash.com/photo-1595815771614-ade9d652a65d?w=500",
                "Mysore Palace & Brindavan Express" => "https://plus.unsplash.com/premium_photo-1691031428843-fffcb81cf454?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Mahabalipuram Coastal Tour" => "https://images.unsplash.com/photo-1717417926142-dc5ce3772690?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                 "Udaipur Lake Palace Special" => "https://images.unsplash.com/photo-1695956353120-54ce5e91632b?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Kashmir Tulip & Snow Tour" => "https://images.unsplash.com/photo-1637558929744-024c00b06075?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Singapore City Highlights" => "https://images.unsplash.com/photo-1565967511849-76a60a516170?q=80&w=871&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Dubai City & Safari Express" => "https://images.unsplash.com/photo-1580674684081-7617fbf3d745?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Maldives Water Villa Tour" => "https://images.unsplash.com/photo-1574223706388-0e0f6f0390b2?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Ooty Special Tour" => "https://images.unsplash.com/photo-1583700295619-04893ffa2741?q=80&w=774&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Jaipur Royal Heritage Tour" => "https://images.unsplash.com/photo-1650530777057-3a7dbc24bf6c?q=80&w=501&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Shimla Snow & Valley Trip" => "https://images.unsplash.com/photo-1597074866923-dc0589150358?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Rishikesh River Rafting" => "https://images.unsplash.com/photo-1718383537744-13289af20e12?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Meghalaya Waterfalls Trip" => "https://images.unsplash.com/photo-1721742496564-b8f711c9d6d9?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Kanyakumari Sunrise Tour" => "https://images.unsplash.com/photo-1760828329500-00b474db9330?q=80&w=1028&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Varanasi Cultural Heritage" => "https://images.unsplash.com/photo-1561359313-0639aad49ca6?q=80&w=435&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Rameshwaram Spiritual Trip" => "https://images.unsplash.com/photo-1706932642959-97cdde19ef0b?q=80&w=1031&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Munnar Tea Garden" => "https://images.unsplash.com/photo-1690107645237-8a7ec86e44f7?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                "Kodaikanal Nature Trip" => "https://images.unsplash.com/photo-1665481193520-86e5eaba1af6?q=80&w=1032&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            ];
            while ($row = mysqli_fetch_assoc($result)) {
    // 'location_name'-ku bathila 'package_name' use panrom
    $name = trim($row['package_name']);
    $words = explode(" ", $name);
$city = $words[0]; // First word-ah mattum edukkum (Example: Coorg, Goa, Munnar)
    $img = isset($default_images[$name]) ? $default_images[$name] : "https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500";
    
    $price = $row['price'];        
    $duration = $row['duration'];  

   $map_query = urlencode($city);
$google_map_url = "https://www.google.com/maps/search/?api=1&query=" . $map_query;
?>
    <div class="package-card">
        <div>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Trip Image">
            <h3 class="package-title"><?php echo htmlspecialchars($name); ?></h3>
            <div class="package-duration">
                <i class="fa-regular fa-clock"></i> Duration: <?php echo htmlspecialchars($duration); ?>
            </div>
            <div>
                <a href="<?php echo $google_map_url; ?>" target="_blank" class="package-map-link">
                    <i class="fa-solid fa-map-location-dot"></i> View on Google Maps (<?php echo htmlspecialchars($city); ?>)
                </a>
            </div>
           <div class="package-price">
            ₹<?php echo number_format($price); ?>
        </div>
    </div>
    <a href="booking.php?id=<?php echo $row['package_id']; ?>" class="btn-book">Now Book</a>
</div>
<?php 
}
?>