<?php
session_start();

include 'connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the input data (you may need additional validation)
    $search_term = $_POST['search_term'];

    // Fetch student data based on the search term (rollno or name)
    $sql_fetch_student_data = "SELECT * FROM students WHERE rollno = ? OR student_name = ?";
    $stmt_fetch_student_data = mysqli_prepare($con, $sql_fetch_student_data);
    mysqli_stmt_bind_param($stmt_fetch_student_data, 'ss', $search_term, $search_term);
    mysqli_stmt_execute($stmt_fetch_student_data);
    $result_student_data = mysqli_stmt_get_result($stmt_fetch_student_data);
    $row_student_data = mysqli_fetch_assoc($result_student_data);
    mysqli_stmt_close($stmt_fetch_student_data);

    if ($row_student_data) {
        // Fetch the list of attendance dates and total_session from the attendance_session table
        $sql_fetch_dates = "SELECT date, total_session FROM attendance_session";
        $result_dates = mysqli_query($con, $sql_fetch_dates);

        // Initialize an associative array to store attendance status for each date
        $attendance_status_by_date = array();

        // Loop through each date
        while ($row_date = mysqli_fetch_assoc($result_dates)) {
            $specific_date = $row_date['date'];
            $total_session = $row_date['total_session'];

            // Check if there is attendance data for the specific date and student
            $sql_check_attendance = "SELECT status FROM attendance_data WHERE rollno = ? AND date = ?";
            $stmt_check_attendance = mysqli_prepare($con, $sql_check_attendance);
            mysqli_stmt_bind_param($stmt_check_attendance, 'ss', $row_student_data['rollno'], $specific_date);
            mysqli_stmt_execute($stmt_check_attendance);
            $result_attendance = mysqli_stmt_get_result($stmt_check_attendance);
            $row_attendance = mysqli_fetch_assoc($result_attendance);

            // Determine the attendance status for the specific date
            $attendance_status = ($row_attendance) ? $row_attendance['status'] : 'Absent';

            // Store the attendance status and total_session in the associative array
            $attendance_status_by_date[] = array(
                'date' => $specific_date,
                'total_session' => $total_session,
                'status' => $attendance_status
            );

            mysqli_stmt_close($stmt_check_attendance);
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Find Attendance</title>
    <link rel="stylesheet" href="search_month_attendance.style.css">
    <style>
        /* General body styling */
        body {
            background-image: url('ST1.jpg'); /* Replace with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
        }
        
        /* Container for form and results */
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 20px;
            border-radius: 10px;
            margin: 50px auto;
            max-width: 800px;
        }

        /* Centered form styling */
        .form-container {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Search bar and button styling */
        .search-bar-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-bar {
            padding: 10px;
            border-radius: 5px;
            border: none;
            margin-right: 10px;
            width: 60%;
        }

        .search-button, .back-home-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .search-button:hover, .back-home-button:hover {
            background-color: #0056b3;
        }

        /* Attendance heading styling */
        .attendance-heading {
            text-align: center;
            color: orange;
            margin-top: 20px;
            font-weight: bold;
        }

        /* Student info box styling */
        .student-info-box {
            text-align: center;
            margin-bottom: 20px;
        }

        .student-info-box h3,
        .student-info-box p {
            font-weight: bold;
        }

        /* Table styling */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid white;
            padding: 10px;
            text-align: center;
        }

        .table th {
            background-color: #007bff;
        }

        .table td {
            background-color: rgba(255, 255, 255, 0.1);
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

<div class="container mt-5">
    <!-- Center the Search Form -->
    <div class="form-container">
        <!-- Search Form -->
        <form action="find_attendance.php" method="post" class="form-group">
            <div class="search-bar-container">
                <label for="search_term" class="text-color-primary"></label>
                <input type="text" class="form-control search-bar" id="search_term" name="search_term" placeholder="Enter Roll Number or Name" required>
                <button type="submit" class="btn btn-primary search-button">Search</button>
                <a href="CSE_1_sem_Physics.php" class="btn btn-danger back-home-button">Back</a>
            </div>
        </form>
    </div>

    <?php if (isset($row_student_data) && !empty($row_student_data)) : ?>
        <!-- Display Attendance Status for Each Date -->
        <div class="student-info-box">
            <h3>Attendance Search for Student</h3>
            <h3><?php echo $row_student_data['student_name']; ?></h3>
            <p><h3>Roll No: <?php echo $row_student_data['rollno']; ?></h3></p>
        </div>

        <h1 class="attendance-heading">Attendance Status</h1>
        <table class="table">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="total-session">Total session</th>
                    <th class="status">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_status_by_date as $data) : ?>
                    <tr>
                        <td class="date"><?php echo $data['date']; ?></td>
                        <td class="total-session"><?php echo $data['total_session']; ?></td>
                        <td class="status"><?php echo $data['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($row_student_data) && empty($row_student_data)) : ?>
        <p class="attendance-status">No student found with the provided information.</p>
    <?php endif; ?>
</div>
<div class="logo-container">
<img src="ST3.jpg" alt="Logo">
    </div>

</body>
</html>
