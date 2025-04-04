<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "dbStuddyBuddy";

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['taskName'];
    $due_date = $_POST['dueDate'];
    $task_time = $_POST['taskTime'];

    $sql = "INSERT INTO tasks (task_name, due_date, task_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("sss", $task_name, $due_date, $task_time);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Task added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add task: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>