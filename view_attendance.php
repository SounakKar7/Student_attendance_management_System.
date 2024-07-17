<?php
session_start();

include 'connect.php';
require_once __DIR__ . '/vendor/autoload.php';

use Fpdf\Fpdf;

// Retrieve data from total_attendance table
$sql_attendance_data = "SELECT students.rollno, students.student_name, total_attendance.total_days
                        FROM students
                        LEFT JOIN total_attendance ON students.rollno = total_attendance.rollno";
$result_attendance_data = mysqli_query($con, $sql_attendance_data);

// Check if any data is available
if ($result_attendance_data && mysqli_num_rows($result_attendance_data) > 0) {
    $attendance_data_available = true;
} else {
    $attendance_data_available = false;
}

// Calculate total session
$sql_total_session = "SELECT SUM(total_session) as sum_session FROM attendance_session";
$result_total_session = mysqli_query($con, $sql_total_session);
$row_total_session = mysqli_fetch_assoc($result_total_session);
$sum_session = $row_total_session['sum_session'];

// Check if download button clicked
if(isset($_POST['download'])) {
    // Create a new FPDF instance
    $pdf = new Fpdf();
    $pdf->AddPage();

    // Set font for the title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Attendance Data for CSE 1st year', 0, 1, 'C');

    // Add a new line
    $pdf->Ln(10);

    // Set font for the total session header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Total Session: ' . $sum_session, 0, 1, 'L');

    // Add a new line
    $pdf->Ln(10);

    // Set font for the table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Roll Number', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Name', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total Days Present', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Percentage', 1, 1, 'C');

    // Set font for the table content
    $pdf->SetFont('Arial', '', 12);

    // Fetch and add data to the table
    while ($row = mysqli_fetch_assoc($result_attendance_data)) {
        $percentage = ($sum_session > 0) ? round(($row['total_days'] / $sum_session) * 100, 2) : 0;
        $pdf->Cell(40, 10, $row['rollno'], 1, 0, 'C');
        $pdf->Cell(60, 10, $row['student_name'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['total_days'], 1, 0, 'C');
        $pdf->Cell(40, 10, $percentage . '%', 1, 1, 'C');
    }

    // Output PDF to browser with forced download
    $pdf->Output('D', 'attendance_data.pdf');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Show Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('ST1.jpg'); /* Replace 'your-background-image.jpg' with the path to your image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: black;
        }

        .container {
            margin-top: 5%;
        }

        h2 {
            color: orange;
        }

        .table {
            background-color: black;
            color: white;
        }

        .table th,
        .table td {
            border: 2px solid green;
        }

        .table th {
            background-color: green;
        }

        .table td {
            background-color: black;
        }

        .btn-back-home {
            background-color: red !important; /* Set background color to red */
            color: white !important; /* Set text color to white */
            border-color: red !important; /* Set border color to red */
            margin-top: 10px;
            position: absolute;
            top: 90px;
            right: 230px;
        }

        .btn-download {
            background-color: blue !important; /* Set background color to blue */
            border-color: blue !important; /* Set border color to blue */
            color: white !important; /* Set text color to white */
        }

        .total-session-box {
            background-color: orange;
            color: black;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
            height: 50px;
            width: 400px;
        }

        .show-attendance {
            color: orange;
            font-size: 50px;
            margin: 0;
            margin-right: 50px;
        }
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

<div class="container mt-5 text-center">
    <h2 class="show-attendance">
        Show Attendance
    </h2>
    <a href="CSE_1_sem_Physics.php" class="btn btn-back-home mt-3">Back </a>
    <!-- Download button form -->
    <form method="post" action="" class="d-inline">
        <input type="hidden" name="download">
        <button type="submit" id="downloadButton" class="btn btn-primary btn-download mt-3 ml-2">Download as PDF</button>
    </form>
</div>

<div class="container mt-5 text-center">
    <div class="text-center">
        <!-- Your PHP code to fetch and display total session here -->
        <div class="total-session-box">
            Total Session: <?php echo $sum_session; ?>
        </div>
    </div>
    <?php if ($attendance_data_available) : ?>
        <table class="table" id="attendance-table">
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Name</th>
                    <th>Total Days Present</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_attendance_data)) : ?>
                    <tr>
                        <td><?php echo $row['rollno']; ?></td>
                        <td><?php echo $row['student_name']; ?></td>
                        <td><?php echo $row['total_days']; ?></td>
                        <td>
                            <?php
                                // Calculate percentage
                                $percentage = ($sum_session > 0) ? round(($row['total_days'] / $sum_session) * 100, 2) : 0;
                                echo $percentage . '%';
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No attendance data available.</p>
    <?php endif; ?>
</div>
<div class="logo-container">
<img src="ST3.jpg" alt="Logo">
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include your JS files if any -->
</body>
</html>
