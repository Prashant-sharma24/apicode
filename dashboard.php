<?php
// Start the session
session_start();

// Check if the user is not logged in, then redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection configuration
$host = 'localhost';
$dbName = 'crud';
$username = 'root';
$password = '';

// Establish database connection
$conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);

// Retrieve the user's data from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_GET['logout'])){
    session_destroy();

    header("Location: login.php");
    exit();
}

?>




<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <img src="<?php echo $_SESSION['user_picture']; ?>" alt="Profile Picture" class= "img-fluid" width= 120px; height=150px; style="float: right;">
        
        <!-- Menu -->
        <ul class="nav bg-primary text-white">
            <li class="nav-item">
                <a class="nav-link text-white active" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="search.php">Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="?logout=true">Logout</a>
            </li>
        </ul>
        
        <!-- Dashboard content goes here -->
    </div>
</body>
</html>
