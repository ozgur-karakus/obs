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

if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $role = "student";
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
                echo "Yeni öğrenci eklendi.";
            } else {
                echo "Öğrenci eklenirken bir hata oluştu.";
            }
        }
    }
}

if (isset($_POST['delete_student'])) {
    $studentId = $_POST['student_id'];

    $sql = "DELETE FROM t_users WHERE id = :studentId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Öğrenci silindi.";
    } else {
        echo "Öğrenci silinirken bir hata oluştu.";
    }
}

if (isset($_POST['update_student'])) {
    $newName = $_POST['new_student_name'];
    $newSurname = $_POST['new_student_surname'];
    $studentId = $_POST['update_student_id'];

    $sql = "UPDATE t_users SET name = :newName, surname = :newSurname WHERE id = :studentId";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
    $stmt->bindParam(':newSurname', $newSurname, PDO::PARAM_STR);
    $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Öğrenci bilgisi güncellendi.";
    } else {
        echo "Öğrenci bilgisi güncellenirken bir hata oluştu.";
    }
}

$sql = "SELECT * FROM t_users WHERE role = 'student'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci İşlemleri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #111;
            text-align: center;
            margin-top: 20px;
        }
        .btn-info {
    color: #000;
    background-color: #53356434;
    border-color: #111;
}

        .form-container {
            background-color: #111;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 400px;
        }

        .form-container h3 {
            color: #fff;
            text-align: center;
        }

        .form-container label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #fff;
        }

        .form-container input[type="text"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #fff;
            border-radius: 3px;
        }

        .form-container button[type="submit"] {
            background-color: #846bab;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
        }

        .form-container button[type="submit"]:hover {
            background-color: #846bab;
        }

        .student-list {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            overflow-x: auto;
        }

        .student-list h2 {
            color: #111;
            text-align: center;
        }

        .student-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .student-list th, .student-list td {
            border: 1px solid #111;
            padding: 8px;
            text-align: left;
        }

        .student-list th {
            background-color: ;
            color: #111;
        }

        .student-list tr:nth-child(even) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Öğrenci İşlemleri</h2>

        <div class="form-container">
            <h3>Öğrenci Ekle</h3>
            <form action="" method="POST">
                <label for="name">Öğrenci Adı:</label>
                <input type="text" name="name" id="name" class="form-control" required>
                <label for="surname">Öğrenci Soyadı:</label>
                <input type="text" name="surname" id="surname" class="form-control" required>
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" name="username" id="username" class="form-control" required>

                <button type="submit" name="add_student" class="btn btn-primary">Ekle</button>
            </form>
        </div>

        <div class="form-container">
            <h3>Öğrenci Sil</h3>
            <form action="" method="POST">
                <label for="student_id">Öğrenci ID:</label>
                <input type="text" name="student_id" id="student_id" class="form-control">
                <button type="submit" name="delete_student" class="btn btn-danger">Sil</button>
            </form>
        </div>

        <div class="form-container">
            <h3>Öğrenci Güncelle</h3>
            <form action="" method="POST">
                <label for="update_student_id">Öğrenci ID:</label>
                <input type="text" name="update_student_id" id="update_student_id" class="form-control">
                <label for="new_student_name">Yeni Öğrenci Adı:</label>
                <input type="text" name="new_student_name" id="new_student_name" class="form-control">
                <label for="new_student_surname">Yeni Öğrenci Soyadı:</label>
                <input type="text" name="new_student_surname" id="new_student_surname" class="form-control">
    
                <button type="submit" name="update_student" class="btn btn-warning">Güncelle</button>
            </form>
        </div>

        <div class="student-list">
            <h2>Tüm Öğrenciler</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Öğrenci Adı</th>
                        <th>Öğrenci Soyadı</th>
                        <th>Öğrenci Görüntüle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($students as $student) {
                        echo "<tr>";
                        echo "<td>{$student['id']}</td>";
                        echo "<td>{$student['name']}</td>";
                        echo "<td>{$student['surname']}</td>";
                        echo "<td><a href='students_students.php?student_id={$student['id']}' class='btn btn-info'>Görüntüle</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
