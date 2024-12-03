<?php include 'includes/header.php'; ?>
<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $error = null;

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
        $stmt = $conn->prepare("INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $address, $class_id, $image_name]);
        header("Location: index.php");
        exit;
    }
}

$classes = $conn->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Create Student</title>
</head>
<body>
    <h1>Add Student</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <form class='form-group' method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input class='form-control' type="text" name="name" required><br>
        <label>Email:</label>
        <input class='form-control' type="email" name="email"><br>
        <label>Address:</label>
        <textarea class='form-control' name="address"></textarea><br>
        <label>Class:</label>
        <select class='custom-select' name="class_id">
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Image:</label>
        <input class='form-control' type="file" name="image" required><br>
        <button class='btn btn-primary' type="submit">Submit</button>
    </form>
</body>
</html>
