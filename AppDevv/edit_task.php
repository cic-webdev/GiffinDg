<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "dbStuddyBuddy";

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $task_name = $_POST['taskName'];
    $due_date = $_POST['dueDate'];
    $task_time = $_POST['taskTime'];

    $sql = "UPDATE tasks SET task_name = ?, due_date = ?, task_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssi", $task_name, $due_date, $task_time, $task_id);
        if ($stmt->execute()) {
            echo "Task updated successfully";
        } else {
            echo "Error updating task: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>