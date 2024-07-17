<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Page</title>
    <link rel="stylesheet" type="text/css" href="home1.css">
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Background image */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('ST1.jpg'); /* Random image from Unsplash */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            z-index: -1; /* Place behind other content */
        }

        /* Content container */
        .content {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            z-index: 1; /* Place above background */
        }

        /* Text container */
        .welcome-text {
            text-align: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            margin-top: 20px; /* Add space between image and text */
        }

        /* CSS for the upper bar buttons */
        .upper-bar {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 999; /* Ensure it appears above the background */
        }
        
        .upper-bar a {
            display: inline-block; /* Display links inline */
            color: white;
            text-align: center;
            padding: 10px 15px;
            text-decoration: none;
            margin-left: 10px; /* Separate each button */
            background-color: rgba(0, 0, 0, 0.5); /* Transparent background */
            border-radius: 5px; /* Rounded corners */
        }
        
        .upper-bar a:first-child {
            margin-left: 0; /* Remove margin from the first button */
        }
        
        .upper-bar a:hover {
            background-color: rgba(0, 0, 0, 0.7); /* Darker shade on hover */
        }

        /* Content styling */
        .dashboard {
            padding: 20px;
            color: white;
        }

        /* Logo container */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px; /* Position in the top left corner */
            z-index: 999; /* Ensure it appears above the background */
            display: flex; /* Display items as a flex container */
            flex-direction: column; /* Stack items vertically */
            align-items: center; /* Center items horizontally */
            text-align: center; /* Center text horizontally */
        }

        .logo-container img {
            width: 100px; /* Adjust width as needed */
            height: auto; /* Maintain aspect ratio */
            border-radius: 50%; /* Circular border */
            margin-bottom: 10px; /* Add space between image and text */
        }

        .logo-container p {
            color: white;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.2; /* Increase line height for better spacing */
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['teacher_name'])) {
        header("Location: login.php");
        exit();
    }

    $teacher_name = $_SESSION['teacher_name'];
    $clock_in_time = isset($_SESSION['clock_in_time']) ? $_SESSION['clock_in_time'] : null;
    ?>

    <!-- Background image container -->
    <div class="background-image"></div>

    <div class="upper-bar">
        <a href="attendance.php"><i class="fas fa-book"></i> Take Attendance</a>
        <a href="view_attendance.php"><i class="fas fa-eye"></i> View Attendance</a>
        <a href="search_month_attendance.php"><i class="fas fa-calendar-alt"></i> Monthly Attendance</a>
        <a href="find_attendance.php"><i class="fas fa-history"></i> Attendance History</a>
        <a href="home_attendance.php"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="home.php"><i class="fas fa-home"></i> Home</a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="logo-container">
    <img src="ST3.jpg" alt="Logo">
    </div>

    <div class="content">
        <div class="dashboard">
            <div class="welcome-text">
                <h1>Welcome, <?php echo $teacher_name; ?>!</h1>
                <!-- Your content goes here -->
            </div>
        </div>
    </div>
</body>
</html>
