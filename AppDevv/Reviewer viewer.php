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
    <title>Study Buddy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightgray;
        }

        .navbar {
            background-color: rgba(128, 0, 0, 0.8); 
            width: 100%;
            height: 8vh; 
            display: flex;
            justify-content: space-between;
            align-items: center; 
            border-bottom: 3px solid rgb(210, 210, 71);
            padding: 0 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; 
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            margin-right: 20px; 
        }

        .navbar-links {
            display: flex;
            align-items: center; 
        }

        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
        }

        .menu-icon {
            display: none;
            padding: 14px 20px;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .navbar a:hover {
            background-color: rgb(210, 210, 71);
            color: white;
        }

        .note-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .title-container {
            padding: 20px;
            background-color: maroon;
            color: white;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-size: 2em;
            font-weight: bold;
        }

        .content-container {
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 0 0 8px 8px;
            font-size: 1.1em;
            color: #333;
            line-height: 1.6;
            text-align: justify;
        }

        @media screen and (max-width: 600px) {
            .navbar a {
                display: none;
            }

            .navbar a.icon {
                float: right;
                display: block;
            }

            .menu-icon {
                display: block;
            }

            .navbar.responsive {
                position: relative;
            }

            .navbar.responsive .menu-icon {
                position: absolute;
                right: 0;
                top: 0;
            }

            .navbar.responsive a {
                float: none;
                display: block;
                text-align: left;
            }

            .navbar.responsive .navbar-right {
                float: none;
            }
        }

        .three-dots {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1;
            width: 120px;
        }

        .dropdown-menu button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: white;
            cursor: pointer;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-menu button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="studyrev.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

    <div class="note-container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dbStuddyBuddy";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $sql = "SELECT title, note FROM tblNotes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<div class='title-container'>" . htmlspecialchars($row['title']) . "</div>";
                echo "<div class='content-container'>" . nl2br(htmlspecialchars($row['note'])) . "</div>";
            } else {
                echo "<p>Note not found.</p>";
            }
            $stmt->close();
        } else {
            echo "<p>No note selected.</p>";
        }

        $conn->close();
        ?>
    </div>
    <script>
        function toggleDropdown(dotElement) {
            var dropdown = dotElement.nextElementSibling;
            var isVisible = dropdown.style.display === 'block';
            
            document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });

            if (!isVisible) {
                dropdown.style.display = 'block';
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches('.three-dots')) {
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.style.display = 'none';
                });
            }
        }

        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            if (navbar.className === "navbar") {
                navbar.className += " responsive";
            } else {
                navbar.className = "navbar";
            }
        }
    </script>

</body>
</html>