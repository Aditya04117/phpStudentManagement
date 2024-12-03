<?php include 'includes/header.php'; ?>

<?php
include 'includes/db.php';

$query = $conn->query("SELECT s.id, s.name, s.email, s.created_at, s.image,s.created_date, c.name AS class_name FROM student s LEFT JOIN classes c ON s.class_id = c.class_id");
$students = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>School Demo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>
<body>
    <h1>Students</h1>
    <a class='btn btn-outline-primary' href="create.php">Add New Student</a>
    <table class='table'>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Class</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <?php 
                echo "<script>console.log('{$student['image']}');</script>";
                ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['created_date'])?></td>
            <td><?= htmlspecialchars($student['class_name'] ?: 'N/A') ?></td>
            <td><img src="<?= htmlspecialchars($student['image'])?>" width="50"></td>
            <td>
                <a class='btn btn-success' href="view.php?id=<?= $student['id'] ?>">View</a>
                <a class='btn btn-warning' href="edit.php?id=<?= $student['id'] ?>">Edit</a>
                <a class='btn btn-danger' href="delete.php?id=<?= $student['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
