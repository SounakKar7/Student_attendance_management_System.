<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Set background image to cover entire page */
        body {
            background-image: url('ST1.jpg'); /* Online background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0; /* Remove default body margin */
            padding: 0; /* Remove default body padding */
        }

        /* CSS for the navigation bar */
        .navbar {
            overflow: hidden;
            text-align: right; /* Aligning the navbar to the right */
            padding: 10px 0; /* Adjusting padding for links */
        }
        
        .navbar a {
            display: inline-block; /* Display links inline */
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            margin-right: 10px; /* Separate each link */
            background-color: black; /* Set buttons background to black */
        }
        
        .navbar a:first-child {
            margin-right: 0; /* Remove margin from the first link */
        }
        
        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent white on hover */
            color: black;
        }

        /* Style for the photo and text */
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

<div class="navbar">
    <a href="Home_attendance.php"><i class="fas fa-user-check"></i> Attendance</a>
    <a href="schedule.php"><i class="fas fa-calendar-alt"></i> Schedule</a>
    <a href="contract_student.php"><i class="fas fa-phone"></i> Contract Students</a>
    <a href="help.php"><i class="fas fa-question-circle"></i> Help</a>
    <a href="login.php"><i class="fas fa-sign-out-alt"></i> LogOut</a>
</div>

<div class="logo-container">
<img src="ST3.jpg" alt="Logo">
</div>

</body>
</html>
