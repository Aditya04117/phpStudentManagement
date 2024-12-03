<?php include 'includes/header.php'; ?>

<?php
include 'includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT s.*, c.name AS class_name FROM student s LEFT JOIN classes c ON s.class_id = c.class_id WHERE s.id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>View Student</title>
</head>
<body>
    <h1>View Student</h1>
    <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
    <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?: 'N/A') ?></p>
    <p><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
    <p><strong>Image:</strong></p>
    <img src="<?= htmlspecialchars($student['image']) ?>" width="200">
    <br><a class='btn btn-outline-dark my-1' href="index.php">Back to Home</a>
</body>
</html>
