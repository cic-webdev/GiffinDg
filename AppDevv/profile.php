<?php
session_start();
if (!isset($_SESSION['USERNAME'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: lightgray;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .profile-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-container h1 {
            margin: 0;
            color: maroon;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['USERNAME']); ?>!</h1>
        <p>This is your profile page.</p>
        <a href="studylog.php">Log Out</a>
    </div>
</body>
</html>