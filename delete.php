<?php
include('connect.php');

// Get the contact and user ID from the URL
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
$user_id = intval($_GET['user_id']);

// Prepare and execute the SQL delete statement
$sql = "DELETE FROM contacts WHERE contact_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $contact_id);
    $stmt->execute();
    $stmt->close();
} else {
    die("Error preparing SQL statement: " . $conn->error);
}

// Close the database connection
$conn->close();

// Redirect to home after deletion
header("Location: home.php?user_id=" . $user_id);
exit;
