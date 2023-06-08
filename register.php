<?php
// Database connection configuration
$host = 'localhost';
$dbName = 'crud';
$username = 'root';
$password = '';

// Establish database connection
$conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $picture = $_FILES['picture'];

    // Perform server-side validation
    // Example: Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Example: Validate password strength
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long";
        exit;
    }

    // Store the uploaded picture
    $pictureName = $picture['name'];
    $pictureTmpName = $picture['tmp_name'];
    $pictureDestination = 'uploads/' . $pictureName;
    move_uploaded_file($pictureTmpName, $pictureDestination);

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store user data in the database
    $sql = "INSERT INTO users (name, email, password, picture) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $email, $hashedPassword, $pictureDestination]);

    // Display success message
    echo "Registration successful";
}
?>
