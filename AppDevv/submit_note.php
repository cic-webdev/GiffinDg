<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStuddyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $note = $_POST['note'];

    $sql = "INSERT INTO tblReviewer (title, note) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $note);

    if ($stmt->execute()) {
        echo "Note saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>