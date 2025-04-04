<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStuddyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

$title = $_POST['title'];
$description = $_POST['description'];
$file = $_FILES['pptFile'];

$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 777, true);
}
$targetFilePath = $uploadDir . basename($file["name"]);


$targetFilePath =basename($file["name"]);
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

$allowedTypes = ['ppt', 'pptx'];
if (in_array($fileType, $allowedTypes)) {
    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {

        $sql = "INSERT INTO tblUnit (title, description, file_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $description, $targetFilePath);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Subject added successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add subject."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Only PPT and PPTX files are allowed."]);
}

$conn->close();
?>