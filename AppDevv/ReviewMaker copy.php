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

        .title-container, .note-container, .note-display {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
        }

        .add-title, .add-note {
            border: none;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px grey;
            font-family: Arial, sans-serif;
            outline: none;
        }

        .add-title { background-color: maroon; color: white; font-size: 20px; }
        .add-note { background-color: white; color: black; font-size: 18px; height: 150px; }
        .note-display h3 { color: maroon; }
        .note-display p { color: black; }
    </style>
</head>
<body>

<div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="study student.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

<form id="noteForm" method="post">
    <div class="title-container">
        <input type="text" name="title" class="add-title" placeholder="Title..." required>
    </div>
    <div class="note-container">
        <textarea name="note" class="add-note" placeholder="Note" required></textarea>
    </div>
    <div style="text-align: center;">
        <button type="button" onclick="submitForm()">Save Note</button>
    </div>
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStuddyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $note = $_POST['note'];

    $sql = "INSERT INTO tblNotes (title, note) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $note);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Note saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<div id="responseMessage" style="text-align:center; color:green;"></div>
<div class="note-display" id="notesDisplay"></div>

<script>
function toggleMenu() {
    var navbar = document.getElementById("navbar");
    if (navbar.className === "navbar") {
        navbar.className += " responsive";
    } else {
        navbar.className = "navbar";
    }
}

function submitForm() {
        var formData = new FormData(document.getElementById("noteForm"));

        fetch("submit_note.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("responseMessage").innerHTML = data;
            document.getElementById("noteForm").reset();
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("responseMessage").innerHTML = "<p style='color:red;'>Failed to save note.</p>";
        });
    }
</script>
</script>

</body>
</html>