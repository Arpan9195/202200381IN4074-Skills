<?php
// Example of processing form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Here you would typically save the data to a database or send an email
// For example:
// mail($email_to, $subject, $message, $headers);

// Redirect to success.html after successful form submission
header("Location: sucess.html");
exit();
?>
