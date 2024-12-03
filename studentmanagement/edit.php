<?php include 'includes/header.php'; ?>
<?php
include 'includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $error = null;
    $image_name = $student['image'];

    if (empty($name)) {
        $error = "Name is required!";
    } elseif ($_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $error = "Invalid image format. Only JPG and PNG are allowed.";
        } else {
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image_name");
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $email, $address, $class_id, $image_name, $id]);
        header("Location: index.php");
        exit;
    }
}

$classes = $conn->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input class='form-control' class='form-control' type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required><br>
        <label>Email:</label>
        <input class='form-control' type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>"><br>
        <label>Address:</label>
        <textarea class='form-control' name="address"><?= htmlspecialchars($student['address']) ?></textarea><br>
        <label>Class:</label>
        <select class='form-control' name="class_id">
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class_id'] ?>" <?= $student['class_id'] == $class['class_id'] ? 'selected' : '' ?>>
                    <?= $class['name'] ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <label>Image:</label>
        <input class='form-control' type="file" name="image"><br>
        <button class='btn btn-outline-success' type="submit">Save Changes</button>
    </form>
</body>
</html>
