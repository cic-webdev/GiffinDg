<?php
header('Content-Type: application/json');

$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "dbStuddyBuddy";

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit();
}

if (isset($_POST['sub_id'])) {
    $sub_id = $_POST['sub_id'];

    $sql = "DELETE FROM tblSubject WHERE SUB_ID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $sub_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Deletion failed.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the statement.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid subject ID.']);
}

$conn->close();
?>