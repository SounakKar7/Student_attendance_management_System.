<?php
session_start();

include 'connect.php';

// Retrieve student data for populating the attendance form
$sql_students = "SELECT rollno, student_name FROM students";
$result_students = mysqli_query($con, $sql_students);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the attendance date is provided
    if (empty($_POST['attendance_date'])) {
        echo '<div class="alert alert-danger" role="alert">
                Please enter the attendance date.
              </div>';
    } else {
        // Process the submitted attendance form
        $selected_date = $_POST['attendance_date'];
        $date = date('Y-m-d', strtotime($selected_date));

        // Check if the confirmation parameter is set
        if (isset($_POST['confirm'])) {
            // Update total attendance for each student
            foreach ($_POST['attendance'] as $rollno => $status) {
                // Fetch student data
                $sql_fetch_student_data = "SELECT student_name FROM students WHERE rollno = ?";
                $stmt_fetch_student_data = mysqli_prepare($con, $sql_fetch_student_data);
                mysqli_stmt_bind_param($stmt_fetch_student_data, 's', $rollno);
                mysqli_stmt_execute($stmt_fetch_student_data);
                $result_student_data = mysqli_stmt_get_result($stmt_fetch_student_data);
                $row_student_data = mysqli_fetch_assoc($result_student_data);
                mysqli_stmt_close($stmt_fetch_student_data);
            
                // Insert attendance data into the database
                $sql_insert_attendance = "INSERT INTO attendance_data (rollno, name, date, status) 
                                          VALUES (?, ?, ?, ?)";
                $stmt_insert_attendance = mysqli_prepare($con, $sql_insert_attendance);
                mysqli_stmt_bind_param($stmt_insert_attendance, 'ssss', $rollno, $row_student_data['student_name'], $date, $status);
                mysqli_stmt_execute($stmt_insert_attendance);
                mysqli_stmt_close($stmt_insert_attendance);
            
                // Update total attendance for each student
                $sql_update_total_attendance = "INSERT INTO total_attendance (rollno, name, total_days) 
                                                VALUES (?, ?, 1)
                                                ON DUPLICATE KEY UPDATE total_days = total_days + 1";
                $stmt_update_total_attendance = mysqli_prepare($con, $sql_update_total_attendance);
                mysqli_stmt_bind_param($stmt_update_total_attendance, 'ss', $rollno, $row_student_data['student_name']);
                mysqli_stmt_execute($stmt_update_total_attendance);
                mysqli_stmt_close($stmt_update_total_attendance);
            }

            // Increment total season for the selected date in attendance_season table
            $sql_increment_total_season = "INSERT INTO attendance_session (date, total_session) VALUES (?, 1)
                                           ON DUPLICATE KEY UPDATE total_session = total_session + 1";
            $stmt_increment_total_season = mysqli_prepare($con, $sql_increment_total_season);
            mysqli_stmt_bind_param($stmt_increment_total_season, 's', $date);
            mysqli_stmt_execute($stmt_increment_total_season);
            mysqli_stmt_close($stmt_increment_total_season);

            // Set a session variable to indicate successful submission
            $_SESSION['attendance_submitted'] = true;

            // Redirect to the same page using GET
            header("Location: attendance.php?date=$selected_date");
            exit();
        } else {
            // Display confirmation dialog
            echo '<script>
                    if (confirm("Are you sure you want to submit the attendance?")) {
                        document.getElementById("confirmField").value = "confirmed";
                        document.forms["attendanceForm"].submit();
                    }
                  </script>';
        }
    }
}

// Check for the session variable to display success message
if (isset($_SESSION['attendance_submitted']) && $_SESSION['attendance_submitted'] === true) {
    $formatted_date = isset($_GET['date']) ? date('F j, Y', strtotime($_GET['date'])) : '';
    echo '<div class="alert alert-success" role="alert">
            Attendance taken successfully on ' . $formatted_date . '!
          </div>';

    // Unset the session variable to avoid displaying the message on subsequent visits
    unset($_SESSION['attendance_submitted']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Take Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="attendance.css">
    <style>
        body {
            background-image: url('ST1.jpg'); /* Replace 'your-background-image.jpg' with the path to your image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: black;
        }
        .top-buttons {
            position: relative;
        }

        .top-right-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .form-group-date {
            width: 200px; /* Adjust the width as needed */
        }

        .alert {
            width: fit-content;
        }

        /* Custom style for the "Select Date" label */
        #label-attendance-date {
            font-size: 18px; /* Adjust the font size as needed */
            color: #333; /* Darker text color */
            font-weight: bold; /* Make the text bold */
        }
        .logo-container {
            position: absolute;
            top: 20px;
            left: 600px; /* Position in the top left corner */
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
            color: black;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.2; /* Increase line height for better spacing */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Take Attendance</h2>
    <div class="top-buttons">
        <div class="top-right-buttons">
            <a href="view_attendance.php" class="btn btn-info">View Attendance</a>
            <a href="CSE_1_sem_Physics.php" class="btn btn-secondary ml-2">Back</a>
        </div>
    </div>
    <form action="attendance.php" method="post" id="attendanceForm">
        <div class="form-group">
            <!-- Added id attribute to the label for custom styling -->
            <label for="attendance_date" id="label-attendance-date">Select Date:</label>
            <input type="date" class="form-control form-group-date" id="attendance_date" name="attendance_date" required>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_students)) : ?>
                    <tr>
                        <td><?php echo $row['rollno']; ?></td>
                        <td><?php echo $row['student_name']; ?></td>
                        <td>
                            <label>
                                <input type="checkbox" name="attendance[<?php echo $row['rollno']; ?>]" value="Present" checked>
                                Present
                            </label>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <input type="hidden" name="confirm" id="confirmField" value="">
        <button type="button" class="btn btn-primary" onclick="submitForm()">Submit Attendance</button>
    </form>
</div>
<div class="logo-container">
<img src="ST3.jpg" alt="Logo">
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function submitForm() {
        // Trigger the confirmation dialog
        if (confirm("Are you sure you want to submit the attendance?")) {
            document.getElementById("confirmField").value = "confirmed";
            document.forms["attendanceForm"].submit();
        }
    }
</script>

</body>
</html>
