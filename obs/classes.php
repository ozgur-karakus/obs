<?php
$host = "localhost";
$dbname = "database";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

if (isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];

    if (empty($class_name)) {
        echo "Sınıf adı boş bırakılamaz.";
    } else {
        $sql = "INSERT INTO t_classes (class_name) VALUES (:class_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':class_name', $class_name, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Yeni sınıf eklendi.";
        } else {
            echo "Sınıf eklenirken bir hata oluştu.";
        }
    }
}

if (isset($_POST['delete_class'])) {
    $class_id = $_POST['class_id'];
    $removeStudentsSql = "DELETE FROM t_classes_students WHERE class_id = :class_id";
    $removeStudentsStmt = $pdo->prepare($removeStudentsSql);
    $removeStudentsStmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($removeStudentsStmt->execute()) {
        $removeTeachersSql = "UPDATE t_classes SET class_teacher_id = NULL WHERE id = :class_id";
        $removeTeachersStmt = $pdo->prepare($removeTeachersSql);
        $removeTeachersStmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

        if ($removeTeachersStmt->execute()) {
            $deleteClassSql = "DELETE FROM t_classes WHERE id = :class_id";
            $deleteClassStmt = $pdo->prepare($deleteClassSql);
            $deleteClassStmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

            if ($deleteClassStmt->execute()) {
                echo "Sınıf silindi.";
            } else {
                echo "Sınıf silinirken bir hata oluştu.";
            }
        } else {
            echo "Sınıftan sorumlu kaldırılırken bir hata oluştu.";
        }
    } else {
        echo "Sınıftan öğrenci kaldırılırken bir hata oluştu.";
    }
}

if (isset($_POST['update_class'])) {
    $new_class_name = $_POST['new_class_name'];
    $class_id = $_POST['class_id'];

    $sql = "UPDATE t_classes SET class_name = :new_class_name WHERE id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':new_class_name', $new_class_name, PDO::PARAM_STR);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Sınıf güncellendi.";
    } else {
        echo "Sınıf güncellenirken bir hata oluştu.";
    }
}

if (isset($_POST['add_student_to_class'])) {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];

    $sql = "INSERT INTO t_classes_students (student_id, class_id) VALUES (:student_id, :class_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Öğrenci sınıfa eklendi.";
    } else {
        echo "Öğrenci sınıfa eklenirken bir hata oluştu.";
    }
}

if (isset($_POST['remove_student_from_class'])) {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];

    $sql = "DELETE FROM t_classes_students WHERE student_id = :student_id AND class_id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Öğrenci sınıftan silindi.";
    } else {
        echo "Öğrenci sınıftan silinirken bir hata oluştu.";
    }
}

if (isset($_POST['add_teacher_to_class'])) {
    $teacher_id = $_POST['teacher_id'];
    $class_id = $_POST['class_id'];

    $sql = "UPDATE t_classes SET class_teacher_id = :teacher_id WHERE id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Sorumlu sınıfa eklendi.";
    } else {
        echo "Sorumlu sınıfa eklenirken bir hata oluştu.";
    }
}

if (isset($_POST['remove_teacher_from_class'])) {
    $class_id = $_POST['class_id'];

    $sql = "UPDATE t_classes SET class_teacher_id = NULL WHERE id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Sorumlu sınıftan silindi.";
    } else {
        echo "Sorumlu sınıftan silinirken bir hata oluştu.";
    }
}

if (isset($_POST['delete_all_classes'])) {
    $deleteAllClassesSql = "DELETE FROM t_classes";
    $deleteAllClassesStmt = $pdo->prepare($deleteAllClassesSql);

    if ($deleteAllClassesStmt->execute()) {
        echo "Tüm sınıflar silindi.";
    } else {
        echo "Tüm sınıfları silerken bir hata oluştu.";
    }
}

$sql = "SELECT * FROM t_classes";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin İşlemleri</title>
    <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                margin: 0;
                padding: 0;
            }
            h2 {
        color: #333;
        text-align: center;
        margin-top: 20px;
    }

    .form-container {
        background-color: #fff;
        margin: 20px auto;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 400px;
    }

    .form-container h3 {
        color: #111;
        text-align: center;
    }

    .form-container label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-container input[type="text"],
    .form-container select {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .form-container button[type="submit"] {
        background-color: #111;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 3px;
    }

    .form-container button[type="submit"]:hover {
        background-color: #111;
    }

    .class-list {
        background-color: #fff;
        margin: 20px auto;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        overflow-x: auto;
    }

    .class-list h2 {
        color: #111;
        text-align: center;
    }

    .class-list table {
        width: 100%;
        border-collapse: collapse;
    }

    .class-list th, .class-list td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .class-list th {
        background-color: #111;
        color: #fff;
    }

    .class-list tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>
    <h2>Admin İşlemleri</h2>
    <div class="form-container">
        <h3>Sınıf Ekle</h3>
        <form action="" method="POST">
            <label for="class_name">Sınıf Adı:</label>
            <input type="text" name="class_name" id="class_name">
            <button type="submit" name="add_class">Ekle</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıf Sil</h3>
        <form action="" method="POST">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <button type="submit" name="delete_class">Sil</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıf Güncelle</h3>
        <form action="" method="POST">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <label for="new_class_name">Yeni Sınıf Adı:</label>
            <input type="text" name="new_class_name" id="new_class_name">
            <button type="submit" name="update_class">Güncelle</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıfa Öğrenci Ekle</h3>
        <form action="" method="POST">
            <label for="student_id">Öğrenci ID:</label>
            <input type="text" name="student_id" id="student_id">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <button type="submit" name="add_student_to_class">Ekle</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıftan Öğrenci Sil</h3>
        <form action="" method="POST">
            <label for="student_id">Öğrenci ID:</label>
            <input type="text" name="student_id" id="student_id">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <button type="submit" name="remove_student_from_class">Sil</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıfa Sorumlu Ekle</h3>
        <form action="" method="POST">
            <label for="teacher_id">Sorumlu ID:</label>
            <input type="text" name="teacher_id" id="teacher_id">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <button type="submit" name="add_teacher_to_class">Ekle</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Sınıftan Sorumlu Sil</h3>
        <form action="" method="POST">
            <label for="class_id">Sınıf ID:</label>
            <input type="text" name="class_id" id="class_id">
            <button type="submit" name="remove_teacher_from_class">Sil</button>
        </form>
    </div>

<div class="class-list">
    <h2>Sınıf Listesi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Sınıf Adı</th>
            <th>Sınıfı Görüntüle</th>
        </tr>
        <?php foreach ($classes as $class) { ?>
            <tr>
                <td><?php echo $class['id']; ?></td>
                <td><?php echo $class['class_name']; ?></td>
                <td>
                    <form action="classes_students.php" method="POST">
                        <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                        <button type="submit">Sınıfı Görüntüle</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>


</body>
</html>
