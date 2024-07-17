<?php
session_start();
include 'connect.php';

// Fetch distinct months from the attendance data
$sql_distinct_months = "SELECT DISTINCT MONTH(date) AS month FROM attendance_data";
$result_distinct_months = mysqli_query($con, $sql_distinct_months);
$distinct_months = [];
while ($row_month = mysqli_fetch_assoc($result_distinct_months)) {
    $distinct_months[] = $row_month['month'];
}

// Initialize variables
$search_results = null;
$selected_month = null;
$row_total_session = null;
$warning_message = null;

// Check if search button clicked
if(isset($_POST['search'])) {
    // Check if a month is selected
    if (!empty($_POST['selected_month'])) {
        $selected_month = $_POST['selected_month'];

        // Fetch total session for the selected month
        $sql_total_session = "SELECT SUM(total_session) AS total_session FROM attendance_session WHERE MONTH(date) = ?";
        $stmt_total_session = mysqli_prepare($con, $sql_total_session);
        mysqli_stmt_bind_param($stmt_total_session, 'i', $selected_month);
        mysqli_stmt_execute($stmt_total_session);
        $result_total_session = mysqli_stmt_get_result($stmt_total_session);
        $row_total_session = mysqli_fetch_assoc($result_total_session);
        mysqli_stmt_close($stmt_total_session);

        // Fetch student data for the selected month and search query (roll number or name)
        $search_query = '%' . $_POST['search_query'] . '%';
        $sql_student_data = "SELECT students.rollno, students.student_name, COUNT(attendance_data.rollno) AS occurrences 
                             FROM students 
                             LEFT JOIN attendance_data ON students.rollno = attendance_data.rollno AND MONTH(attendance_data.date) = ?
                             WHERE (students.rollno = ? OR students.student_name LIKE ?)
                             GROUP BY students.rollno";
        $stmt_student_data = mysqli_prepare($con, $sql_student_data);
        mysqli_stmt_bind_param($stmt_student_data, 'iss', $selected_month, $_POST['search_query'], $search_query);
        mysqli_stmt_execute($stmt_student_data);
        $search_results = mysqli_stmt_get_result($stmt_student_data);
    } else {
        $warning_message = "Please select a month.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Search Attendance</title>
    <link rel="stylesheet" href="search_month_attendance.style.css">
    <style>
        body {
            background-image: url('ST1.jpg'); /* Replace 'your-background-image.jpg' with the path to your image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: black;
        }
        .warning-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            width: fit-content;
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

<div class="container mt-5">
    <!-- Warning Message -->
    <?php if (!empty($warning_message)) : ?>
    <div class="warning-message">
        <?php echo $warning_message; ?>
    </div>
    <?php endif; ?>

    <!-- Search Form -->
    <form action="" method="post" class="form-inline mt-3">
        <div class="form-group mr-2">
            <label for="selected_month" class="text-color-primary mr-2">Select Month:</label>
            <select class="form-control" id="selected_month" name="selected_month">
                <option value="">Select Month</option>
                <?php foreach ($distinct_months as $month) : ?>
                    <option value="<?php echo $month; ?>"><?php echo date('F', mktime(0, 0, 0, (int)$month, 1)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mr-2">
            <label for="search_query" class="text-color-primary mr-2">Search by Roll No. or Name:</label>
            <input type="text" class="form-control" id="search_query" name="search_query" placeholder="Enter Roll No. or Name">
        </div>
        <div class="form-group">
            <button type="submit" name="search" class="btn btn-primary search-button">Search</button>
            <a href="CSE_1_sem_Physics.php" class="btn btn-danger" style="text-decoration: none;">Back </a>
        </div>
    </form>

    <!-- Display search results -->
    <?php if (!empty($search_results) && isset($row_total_session['total_session'])) : ?>
        <div class="mt-4">
            <h4 class="text-center text-color-orange font-weight-bold">Attendance Data for <?php echo date('F', mktime(0, 0, 0, (int)$selected_month, 1)); ?></h4>

            <!-- Display Total Session -->
            <div>
                <h4 class="text-center text-color-orange font-weight-bold"><strong>Total session for <?php echo date('F', mktime(0, 0, 0, (int)$selected_month, 1)); ?>:</strong> <?php echo $row_total_session['total_session']; ?></h4>
            </div>

            <!-- Table for Attendance Data -->
            <table id="attendanceTable" class="table" style="border-collapse: collapse;">
                <thead>
                    <tr style="border: 1px solid green;">
                        <th style="border: 1px solid green;">Roll Number</th>
                        <th style="border: 1px solid green;">Name</th>
                        <th style="border: 1px solid green;">Attendance Count</th>
                        <th style="border: 1px solid green;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($search_results)) : ?>
                        <tr style="border: 1px solid green;">
                            <td style="border: 1px solid green;"><?php echo $row['rollno']; ?></td>
                            <td style="border: 1px solid green;"><?php echo $row['student_name']; ?></td>
                            <td style="border: 1px solid green;"><?php echo $row['occurrences']; ?></td>
                            <td style="border: 1px solid green;">
                                <?php
                                $percentage = ($row['occurrences'] / $row_total_session['total_session']) * 100;
                                echo number_format($percentage, 2) . '%';
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
<div class="logo-container">
<img src="ST3.jpg" alt="Logo">
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
