<?php
session_start();
$conn = new mysqli("localhost", "root", "", "aquarium");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$rating = $_POST['rating'];
$content = $_POST['content'];

if ($rating && $content) {
    $stmt = $conn->prepare("INSERT INTO review (user_id, rating, content, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $rating, $content);

    if ($stmt->execute()) {
        header("Location: myticket.php?msg=review_submitted");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Please provide a rating and a review.";
}

$conn->close();
?>
