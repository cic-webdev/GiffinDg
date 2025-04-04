<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStudyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM tblNotes WHERE SUB_ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Note deleted successfully";
    } else {
        echo "Error deleting note: " . $conn->error;
    }
}
$conn->close();
?>