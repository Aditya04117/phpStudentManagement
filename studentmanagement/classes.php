<?php include 'includes/header.php'; ?>
<?php
include 'includes/db.php';

// Handle add and edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $class_id = $_POST['class_id'] ?? null;
    $error = null;

    if (empty($class_name)) {
        $error = "Class name is required!";
    } else {
        if ($class_id) {
            // Edit class
            $stmt = $conn->prepare("UPDATE classes SET name = ? WHERE class_id = ?");
            $stmt->execute([$class_name, $class_id]);
        } else {
            // Add new class
            $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
            $stmt->execute([$class_name]);
        }
        header("Location: classes.php");
        exit;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
    $stmt->execute([$class_id]);
    header("Location: classes.php");
    exit;
}

// Fetch all classes
$classes = $conn->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Classes</title>
</head>
<body>
    <h1>Manage Classes</h1>

    <!-- Add/Edit Class Form -->
    <form method="post">
        <input type="hidden" name="class_id" value="<?= $_GET['edit'] ?? '' ?>">
        <label>Class Name:</label>
        <input class='form-control w-50 d-inline' type="text" name="class_name" value="<?= $_GET['class_name'] ?? '' ?>" required>
        <button class='btn btn-dark' type="submit"><?= isset($_GET['edit']) ? 'Update Class' : 'Add Class' ?></button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <!-- Class List -->
    <h2>Class List</h2>
    <table class='table'>
        <tr>
            <th>Class Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($classes as $class): ?>
        <tr>
            <td><?= htmlspecialchars($class['name']) ?></td>
            <td>
                <a class='btn btn-warning' href="classes.php?edit=<?= $class['class_id'] ?>&class_name=<?= urlencode($class['name']) ?>">Edit</a>
                <a class='btn btn-danger' href="classes.php?delete=<?= $class['class_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a class='btn btn-outline-info' href="index.php">Back to Home</a>
</body>
</html>
