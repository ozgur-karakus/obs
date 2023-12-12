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

if (isset($_POST['add_teacher'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $role = "teacher";
    $username = $_POST['username'];
    $password = "1234";

    if (empty($username)) {
        echo "Kullanıcı adı boş bırakılamaz.";
    } else {
      
        $checkSql = "SELECT COUNT(*) FROM t_users WHERE username = :username";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $checkStmt->execute();
        $existingUserCount = $checkStmt->fetchColumn();

        if ($existingUserCount > 0) {
            echo "Bu kullanıcı adı zaten kullanılıyor.";
        } else {
            $sql = "INSERT INTO t_users (name, surname, role, username, password) VALUES (:name, :surname, :role, :username, :password)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Yeni öğretmen eklendi.";
            } else {
                echo "Öğretmen eklenirken bir hata oluştu.";
            }
        }
    }
}

if (isset($_POST['delete_teacher'])) {
    $teacherId = $_POST['teacher_id'];

    $removeTeacherFromClassSql = "UPDATE t_classes SET class_teacher_id = NULL WHERE class_teacher_id = :teacherId";
    $removeTeacherFromClassStmt = $pdo->prepare($removeTeacherFromClassSql);
    $removeTeacherFromClassStmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);

    if ($removeTeacherFromClassStmt->execute()) {
        $deleteTeacherSql = "DELETE FROM t_users WHERE id = :teacherId";
        $deleteTeacherStmt = $pdo->prepare($deleteTeacherSql);
        $deleteTeacherStmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);

        if ($deleteTeacherStmt->execute()) {
            echo "Öğretmen silindi.";
        } else {
            echo "Öğretmen silinirken bir hata oluştu.";
        }
    } else {
        echo "Öğretmenin sınıf ilişkileri kaldırılırken bir hata oluştu.";
    }
}

if (isset($_POST['update_teacher'])) {
    $newName = $_POST['new_name'];
    $newSurname = $_POST['new_surname'];
    $teacherId = $_POST['teacher_id'];

    $sql = "UPDATE t_users SET name = :newName, surname = :newSurname WHERE id = :teacherId";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
    $stmt->bindParam(':newSurname', $newSurname, PDO::PARAM_STR);
    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Öğretmen bilgisi güncellendi.";
    } else {
        echo "Öğretmen bilgisi güncellenirken bir hata oluştu.";
    }
}

$sql = "SELECT * FROM t_users WHERE role = 'teacher'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğretmen İşlemleri</title>
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
            color: #333;
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

        .teacher-list {
            background-color: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            overflow-x: auto;
        }

        .teacher-list h2 {
            color: #111;
            text-align: center;
        }

        .teacher-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .teacher-list th, .teacher-list td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .teacher-list th {
            background-color: #111;
            color: #fff;
        }

        .teacher-list tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Öğretmen İşlemleri</h2>

    <div class="form-container">
        <h3>Öğretmen Ekle</h3>
        <form action="" method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" name="username" id="username">
            <label for="name">Ad:</label>
            <input type="text" name="name" id="name">
            <label for="surname">Soyad:</label>
            <input type="text" name="surname" id="surname">
            <input type="hidden" name="role" value="öğretmen">
            <button type="submit" name="add_teacher">Ekle</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Öğretmen Sil</h3>
        <form action="" method="POST">
            <label for="teacher_id">Öğretmen ID:</label>
            <input type="text" name="teacher_id" id="teacher_id">
            <button type="submit" name="delete_teacher">Sil</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Öğretmen Güncelle</h3>
        <form action="" method="POST">
            <label for="teacher_id">Öğretmen ID:</label>
            <input type="text" name="teacher_id" id="teacher_id">
            <label for="new_name">Yeni Ad:</label>
            <input type="text" name="new_name" id="new_name">
            <label for="new_surname">Yeni Soyad:</label>
            <input type="text" name="new_surname" id="new_surname">
            <button type="submit" name="update_teacher">Güncelle</button>
        </form>
    </div>
    <div class="teacher-list">
        <h2>Öğretmen Listesi</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Soyad</th>
            </tr>
            <?php foreach ($teachers as $teacher) { ?>
                <tr>
                    <td><?php echo $teacher['id']; ?></td>
                    <td><?php echo $teacher['name']; ?></td>
                    <td><?php echo $teacher['surname']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
