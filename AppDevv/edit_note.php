<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStudyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['note'])) {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $note = $conn->real_escape_string($_POST['note']);

    $sql = "UPDATE tblNotes SET title = '$title', note = '$note' WHERE SUB_ID = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Note updated successfully";
    } else {
        echo "Error updating note: " . $conn->error;
    }
}
$conn->close();
?>