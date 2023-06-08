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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the updated data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $picture = $_FILES['picture'];

    // Perform server-side validation if necessary

    // Update the user's data in the database
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $_SESSION['user_id']]);

    // Update the user's profile picture if a new picture is uploaded
    if (!empty($picture['name'])) {
        $pictureName = $picture['name'];
        $pictureTmpName = $picture['tmp_name'];
        $pictureDestination = 'uploads/' . $pictureName;
        move_uploaded_file($pictureTmpName, $pictureDestination);

        $sql = "UPDATE users SET picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pictureDestination, $_SESSION['user_id']]);
    }

    // Update the user's password if a new password is provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
    }

    // Display success message or perform additional actions

    // Redirect to the updated profile page
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Profile</h2>
        <button type="submit" class="btn btn-success text-end"><a href="dashboard.php" class="text-white">back</a> </button>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="picture">Profile Picture:</label>
                <input type="file" class="form-control" name="picture" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
