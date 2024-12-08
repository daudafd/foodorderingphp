<?php
include 'db_connect.php'; // Ensure you have the correct database connection here

// Define admin details
$username = 'admin';
$password = 'Thanos'; // Replace with a secure password
$type = '1';
$name = 'Administrator';

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert the admin user into the database
$sql = "INSERT INTO users (name, username, password, type) VALUES ('$name', '$username', '$hashedPassword', '$type')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
