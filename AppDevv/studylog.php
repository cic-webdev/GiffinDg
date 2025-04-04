<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "dbStuddyBuddy";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM tblUsers WHERE USERNAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['PASSWORD'])) {
            $_SESSION['USERNAME'] = $username;
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: study admin.php");
            } else {
                header("Location: study student.php");
            }
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study Buddy</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
        background-image: url('s.png');
        background-size: cover; 
        background-position: center; 
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0; 
    }
    .login-container {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      width: 400px;
    }
    .login-container h1 {
      font-size: 30px;
      color: #333333;
      text-align: center;
      margin-bottom: 30px;
    }
    .form-control {
      margin-bottom: 20px;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      transition: background-color 0.3s, border-color 0.3s;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }
    .text-center a {
      color: #007bff;
      text-decoration: none;
    }
    .text-center a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Study Buddy</h1>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" class="form-control" placeholder="Username" required>
      <input type="password" name="password" class="form-control" placeholder="Password" required>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-block">Login</button>
      </div>
    </form>
    <div class="mt-3 text-center">
      <a href="studysign.php">Don't have an account? Sign up here</a>
    </div>
  </div>
</body>
</html>