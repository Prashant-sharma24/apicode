<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  $to = 'recipient@example.com'; // Replace with the recipient's email address
  $subject = 'New Form Submission';
  $body = "Name: $name\nEmail: $email\nMessage: $message";

  $headers = "From: sender@example.com"; // Replace with the sender's email address

  if (mail($to, $subject, $body, $headers)) {
    echo 'Email sent successfully!';
  } else {
    echo 'Failed to send email.';
  }
}
?>
