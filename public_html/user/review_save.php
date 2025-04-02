<?php
session_start();
$conn = new mysqli('mysql', 'user', 'password', 'aquarium');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST["user_id"] ?? null;
$rating = $_POST["rating"] ?? null;
$content = $_POST["content"] ?? null;
$event_id = $_POST["event_id"] ?? null;


if ($rating && $content&& $user_id && $event_id) {
    $stmt = $conn->prepare("INSERT INTO review (user_id, rating, content, created_at, event_id) VALUES (?, ?, ?, NOW(),?)");
    $stmt->bind_param("issi", $user_id, $rating, $content, $event_id);

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
