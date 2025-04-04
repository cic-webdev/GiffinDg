<?php
$serverName = "localhost";  
$username = "root";
$password = "";
$dbName = "dbStuddyBuddy";

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $_POST['title'];
$description = $_POST['description'];

$sql = "INSERT INTO tblSubject (SUBJECT_TITLE, SUB_DESC) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ss", $title, $description);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Subject added successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error adding subject: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>