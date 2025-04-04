<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "dbStuddyBuddy";

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['sub_id']) && isset($_POST['title']) && isset($_POST['description'])) {
    $subId = $_POST['sub_id'];
    $newTitle = $_POST['title'];
    $newDescription = $_POST['description'];

    $sql = "UPDATE tblSubject SET SUBJECT_TITLE = ?, SUB_DESC = ? WHERE SUB_ID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $newTitle, $newDescription, $subId);
        if ($stmt->execute()) {
            echo "success";
        } else {
            error_log("Execution error: " . $stmt->error);
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("Preparation error: " . $conn->error);
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Error: Missing data.";
}

$conn->close();
?>