<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "trip_management");

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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .package-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
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

        .package-title {
            font-size: 22px;
            color: #1e40af;
            margin-bottom: 15px;
        }

        .package-details {
            color: #475569;
            font-size: 15px;
            margin-bottom: 15px;
        }

        .package-price {
            font-size: 24px;
            color: #059669;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-book {
            background: #2563eb;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-book:hover {
            background: #1d4ed8;
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
                <li><a href="booking.php">Bookings</a></li>
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
    $sql = "SELECT * FROM packages ORDER BY package_id DESC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            
            $p_name = strtolower(trim($row['package_name']));

                    // Perfect matching unique colorful images for every single destination
                    if (strpos($p_name, 'coorg') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=500";
                    } 
                    elseif (strpos($p_name, 'wayanad') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1593693397690-362cb9666fc2?w=500";
                    } 
                    elseif (strpos($p_name, 'goa') !== false || strpos($p_name, 'beach') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500";
                    } 
                    elseif (strpos($p_name, 'kerala') !== false || strpos($p_name, 'backwater') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=500";
                    } 
                    elseif (strpos($p_name, 'manali') !== false) {
    $img_url = "https://picsum.photos/500/300?random=39";
}
                    elseif (strpos($p_name, 'agra') !== false || strpos($p_name, 'taj') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1564507592333-c60657eea523?w=500";
                    }
                    elseif (strpos($p_name, 'chikmagalur') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=500";
                    }
                    elseif (strpos($p_name, 'nepal') !== false || strpos($p_name, 'kathmandu') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=500";
                    }
                    elseif (strpos($p_name, 'ladakh') !== false || strpos($p_name, 'pangong') !== false) {
    $img_url = "https://picsum.photos/500/300?random=36";
}
                    elseif (strpos($p_name, 'bali') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500";
                    }
                    elseif (strpos($p_name, 'bangkok') !== false || strpos($p_name, 'pattaya') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1508009603885-50cf7c579365?w=500";
                    }
                    elseif (strpos($p_name, 'hampi') !== false) {
    $img_url = "https://picsum.photos/500/300?random=33";
}
                    elseif (strpos($p_name, 'gokarna') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?w=500";
                    }
                   
elseif (strpos($p_name, 'yercaud') !== false) {
    $img_url = "https://picsum.photos/500/300?random=30";
} 
                    elseif (strpos($p_name, 'dhanushkodi') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=500";
                    }
                    elseif (strpos($p_name, 'agumbe') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=500";
                    }
                    elseif (strpos($p_name, 'valparai') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=500";
                    }
                    elseif (strpos($p_name, 'mysore') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=500";
                    }
                    elseif (strpos($p_name, 'mahabalipuram') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=500";
                    }
                    elseif (strpos($p_name, 'udaipur') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=28";
}
                    elseif (strpos($p_name, 'kashmir') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1595815771614-ade9d652a65d?w=500";
                    }
                    elseif (strpos($p_name, 'singapore') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=500";
                    }
                    elseif (strpos($p_name, 'dubai') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=500";
                    }
                    elseif (strpos($p_name, 'maldives') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=500";
                    }
                    elseif (strpos($p_name, 'ooty') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?w=500";
                    }
                    elseif (strpos($p_name, 'jaipur') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=25";
                    }
                    elseif (strpos($p_name, 'shimla') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1626621341517-bbf3d9990a23?w=500";
                    }
                    elseif (strpos($p_name, 'rishikesh') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=500";
                    }
                    elseif (strpos($p_name, 'meghalaya') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=20";
                    }
                    elseif (strpos($p_name, 'kanyakumari') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=21";
                    }
                    elseif (strpos($p_name, 'varanasi') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=13";
                    }
                    elseif (strpos($p_name, 'rameshwaram') !== false) {
                        $img_url = "https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=500";
                    }
                    elseif (strpos($p_name, 'munnar') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=11";
                    }
                    elseif (strpos($p_name, 'kodaikanal') !== false) {
                        $img_url = "https://picsum.photos/500/300?random=10";
                    }
                    else {
                        // General colorful default image fallback
                        $img_url = "https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500";
                    }
                    ?>
                    <div class="package-card">
                        <img src="<?php echo $img_url; ?>" alt="Trip Image" style="width: 100%; height: 180px; object-fit: cover;">
                        <div style="padding: 15px;">
                            <h3 class="package-title"><?php echo htmlspecialchars($row['package_name']); ?></h3>
                            <p class="package-details"><i class="fa-regular fa-clock"></i> Duration: <b><?php echo htmlspecialchars($row['duration']); ?></b></p>
                            <div class="package-price">₹<?php echo number_format($row['price']); ?></div>
                        </div>
                        <a href="booking.php?package_id=<?php echo $row['package_id']; ?>" class="btn-book">Book Now</a>
                    </div>
            <?php 
                }
            } else {
                echo "<p style='grid-column: 1/-1; color: #64748b;'>No packages available right now. Please add packages from Admin panel.</p>";
            }
            ?>
        </div>
    </section>
    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Trip Management System. All Rights Reserved.</p>
    </footer>

</body>
</html>
                       