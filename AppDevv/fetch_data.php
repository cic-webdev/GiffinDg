<?php
include 'database.php';

$sql = "SELECT * FROM tblSubject";
$result = $conn->query($sql);

$subjects = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($subjects);
?>