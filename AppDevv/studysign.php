<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    body {
        background-image: url('s.png');
        background-color: maroon;
        background-size: cover;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .signup-container {
        background-color: rgba(255, 255, 255, 0.8);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        width: 400px;
    }

    .signup-container h1 {
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
    <div class="signup-container">
        <h1>Sign Up</h1>
        <?php
        session_start();

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "dbStuddyBuddy";

        $connection = new mysqli($servername, $username, $password, $database);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $connection->real_escape_string($_POST['username']);
            $password = $_POST['password'];
            $role = $connection->real_escape_string($_POST['role']);

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tblUsers (username, password, role) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($sql);

            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                echo "Sign up successful! <a href='studylog.php'>Click here to log in</a>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $connection->close();
        ?>

        <form method="post">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <select name="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <div class="d-grid gap-2">
                <input type="submit" class="btn btn-primary btn-block" value="Sign Up">
            </div>
        </form>
        <div class="mt-3 text-center">
            <a href="studylog.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>