   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }
        .hero {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1353&q=80');
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        .hero h1 { font-size: 50px; margin-bottom: 20px; }
        .hero p { font-size: 20px; margin-bottom: 30px; }
        .btn {
            background-color: #ffc107;
            color: #333;
            padding: 12px 30px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn:hover { background-color: #e0a800; }
    </style>
</head>
<body>

    <header>
        <h2>✈️ Trip Management</h2>
        <nav>
            <a href="index.html">Home</a>
            <a href="#">Trip Packages</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="login.html">Login</a>
            <a href="register.html">Register</a>
        </nav>
    </header>

    <div class="hero">
        <h1>Explore The World With Us</h1>
        <p>Plan your perfect trip and manage bookings easily!</p>
        <a href="booking.html" class="btn">Book Now</a>
    </div>

</body>
</html>