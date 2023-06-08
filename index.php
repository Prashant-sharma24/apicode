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
  $uploadDir = 'uploads';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }
  move_uploaded_file($pictureTmpName, $pictureDestination);

  // Hash the password for security
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Store user data in the database
  $sql = "INSERT INTO users (name, email, password, picture) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$name, $email, $hashedPassword, $pictureDestination]);
  // echo json_decode($sql);
  // exit();

  // Display success message
  echo "Registration successful";
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Registration</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h2>Registration</h2>
    <?php if (isset($errors) && !empty($errors)) { ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error) { ?>
          <p><?php echo $error; ?></p>
        <?php } ?>
      </div>
    <?php } ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
      </div>
      <div class="form-group">
        <label for="email">Email address:</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
      </div>
      <div class="form-group">
        <label for="picture">picture</label>
        <input type="file" class="form-control" name="picture" accept="image/*" required>
      </div>


      <button class="btn btn-success text-end "><a href="login.php" class="text-white">Login</a> </button>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</body>

</html>