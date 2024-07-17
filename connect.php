<?php

$con = mysqli_connect('localhost', 'root', '', 'teachersdb');

if($con) {
    echo " ";
} else {
    die("Connection failed: " . mysqli_connect_error());
}

?>

