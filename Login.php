<?php
session_start();
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_name = $_POST['teacher_name'];
    $password = $_POST['password'];

    // To prevent SQL injection, use prepared statements
    $sql = "SELECT * FROM teachers WHERE teacher_name = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $teacher_name, $password);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['teacher_name'] = $teacher_name;
        header("Location: home.php");
        exit();
    } else {
        $error_message = "Invalid login credentials. Please try again.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" type="text/css" href="Login.css">

</head>
<body>
    <h2>Teacher Login</h2>
    
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="post" action="">
        <label for="teacher_name">Teacher Name:</label>
        <input type="text" name="teacher_name" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
