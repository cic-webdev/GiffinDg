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

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $task_id);
        if ($stmt->execute()) {
            echo "Task deleted successfully";
        } else {
            echo "Error deleting task: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the statement: " . $conn->error;
    }
}

$conn->close();
?>