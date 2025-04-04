<?php
session_start();
if (!isset($_SESSION['USERNAME'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";  // Replace with your DB password
$dbname = "dbstuddybuddy";  // Replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Delete quiz from database
    $sql = "DELETE FROM tblquiz WHERE quiz_id = '$quiz_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Quiz deleted successfully!";
        header("Location: quizselectoradmin.php"); // Redirect after deletion
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
