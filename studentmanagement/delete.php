<?php include 'includes/header.php'; ?>

<?php
include 'includes/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT image FROM student WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if ($student) {
    // Delete the image from the server
    if ($student['image']) {
        unlink("uploads/" . $student['image']);
    }

    // Delete the student record
    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
