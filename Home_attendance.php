<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="Home_attendance.css">
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* CSS for the back button */
        .back-button {
            background-color: #007bff; /* Blue color */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .back-button:hover {
            background-color: #0056b3; /* Darker shade of blue on hover */
        }

        /* CSS for the Enter button */
        .enter-button {
            background-color: #4CAF50; /* Green color */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .enter-button:hover {
            background-color: #45a049; /* Darker shade of green on hover */
        }

        /* CSS for the Enter button icon */
        .enter-button i {
            margin-right: 5px;
        }

        /* Background image */
        body {
            background-image: url('ST1.jpg'); /* Placeholder image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; /* Specify a fallback font */
            position: relative; /* Ensure relative positioning for absolute elements */
        }

        /* Blue background for individual text elements */
        .text-background {
            background-color: green; /* Blue color */
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Text color to white for better visibility */
        h2, form label {
            color: white;
        }

        /* Center the content on the page */
        .text-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
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
    <script>
        // JavaScript function to dynamically populate subjects based on selected department and semester
        function populateSubjects() {
            var department = document.getElementById("department").value;
            var semester = document.getElementById("semester").value;
            var subjectSelect = document.getElementById("subject");

            // Clear existing options
            subjectSelect.innerHTML = "";

            // Define subjects based on selected department and semester
            var subjects = [];

            if (department === "CSE" && semester === "1") {
                subjects = ['Physics', 'Basic Electronics', 'Math']; // Default subjects for CSE 1st Semester
            } else if (department === "IT" && semester === "1") {
                subjects = ['Subject1', 'Subject2']; // Default subjects for IT 1st Semester
            }
            // Add more conditions for other departments and semesters as needed

            // Populate subjects in the dropdown
            for (var i = 0; i < subjects.length; i++) {
                var option = document.createElement("option");
                option.text = subjects[i];
                option.value = subjects[i];
                subjectSelect.add(option);
            }
        }

        // JavaScript function to set default values for department and semester
        function setDefaultValues() {
            var departmentSelect = document.getElementById("department");
            var semesterSelect = document.getElementById("semester");

            // Set default values for department and semester
            departmentSelect.value = "CSE";
            semesterSelect.value = "1";
            populateSubjects(); // Populate subjects dropdown with default values
        }

        // Call setDefaultValues() function when the page loads
        window.onload = function() {
            setDefaultValues();
        };

        // JavaScript function to validate password and open the attendance page
        function openAttendancePage() {
            var department = document.getElementById("department").value;
            var semester = document.getElementById("semester").value;
            var subject = document.getElementById("subject").value;
            var password = document.getElementById("password").value;

            // Define the correct password (first three letters of the subject in uppercase)
            var correctPassword = subject.substring(0, 3).toUpperCase();

            if (password === correctPassword) {
                // Construct the URL based on selected department, semester, and subject
                var url = department + "_" + semester + "_sem_" + subject + ".php";
                // Load the URL in the current window
                window.location.href = url;
            } else {
                alert("Incorrect password. Please try again.");
            }
        }

        // JavaScript function to navigate back to the home page
        function goToHomePage() {
            window.location.href = "Home.php";
        }
    </script>
</head>
<body>
    <!-- Logo and text container -->
    <div class="logo-container">
        <img src="ST3.jpg" alt="Logo">
    </div>
    <div class="text-container">
        <h2 class="text-background">Attendance Management</h2>
        <form onsubmit="openAttendancePage(); return false;">
            <label for="department" class="text-background">Select Department:</label>
            <select name="department" id="department" onchange="populateSubjects()">
                <option value="CSE">CSE</option>
                <option value="IT">IT</option>
                <option value="AIML">AIML</option>
                <option value="ECE">ECE</option>
                <option value="EE">EE</option>
                <!-- Add more departments as needed -->
            </select><br><br>

            <label for="semester" class="text-background">Select Semester:</label>
            <select name="semester" id="semester" onchange="populateSubjects()">
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
                <option value="3">3rd Semester</option>
                <option value="4">4th Semester</option>
                <option value="5">5th Semester</option>
                <option value="6">6th Semester</option>
                <option value="7">7th Semester</option>
                <option value="8">8th Semester</option>
                <!-- Add more options as needed -->
            </select><br><br>
            
            <label for="subject" class="text-background">Select Subject:</label>
            <select name="subject" id="subject">
                <option value="Subject1">Subject 1</option>
                <option value="Subject2">Subject 2</option>
                <!-- Add more subjects as needed -->
            </select><br><br>
            
            <label for="password" class="text-background">Enter Password:</label>
            <input type="password" id="password" name="password"><br><br>
            
            <button type="submit" class="enter-button"><i class="fas fa-sign-in-alt"></i> Enter</button>
        </form>
    </div>

    <!-- Back button -->
    <button class="back-button" onclick="goToHomePage()"><i class="fas fa-arrow-left"></i> Back </button>
</body>
</html>
