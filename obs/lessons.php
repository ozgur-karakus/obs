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
$errors = [];
if (isset($_POST['add_lesson'])) {
    $lessonName = $_POST['lesson_name'];
    $teacherUserId = $_POST['teacher_user_id'];

    if (empty($lessonName) || empty($teacherUserId)) {
        $errors[] = "Ders Adı ve Öğretmen Kullanıcı ID alanları boş bırakılamaz.";
    } else {
        $sql = "INSERT INTO t_lessons (lesson_name, teacher_user_id) VALUES (:lessonName, :teacherUserId)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':lessonName', $lessonName, PDO::PARAM_STR);
        $stmt->bindParam(':teacherUserId', $teacherUserId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Yeni ders eklendi.";
        } else {
            $errors[] = "Ders eklenirken bir hata oluştu.";
        }
    }
}

if (isset($_POST['delete_lesson'])) {
    $lessonId = $_POST['lesson_id_delete'];

    if (empty($lessonId)) {
        $errors[] = "Ders ID alanı boş bırakılamaz.";
    } else {
        $sql = "DELETE FROM t_lessons WHERE id = :lessonId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Ders silindi.";
        } else {
            $errors[] = "Ders silinirken bir hata oluştu.";
        }
    }
}

if (isset($_POST['update_lesson'])) {
    $newLessonUserId = $_POST['new_lesson_user_id'];
    $newTeacherUserId = $_POST['new_teacher_user_id'];
    $lessonId = $_POST['lesson_id_update'];

    if (empty($newLessonUserId) || empty($newTeacherUserId) || empty($lessonId)) {
        $errors[] = "Ders ID, Yeni Ders Kullanıcı ID ve Yeni Öğretmen Kullanıcı ID alanları boş bırakılamaz.";
    } else {
        $sql = "UPDATE t_lessons SET lesson_name = :newLessonUserId, teacher_user_id = :newTeacherUserId WHERE id = :lessonId";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':newLessonUserId', $newLessonUserId, PDO::PARAM_INT);
        $stmt->bindParam(':newTeacherUserId', $newTeacherUserId, PDO::PARAM_INT);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Ders bilgisi güncellendi.";
        } else {
            $errors[] = "Ders bilgisi güncellenirken bir hata oluştu.";
        }
    }
}

if (isset($_POST['add_teacher_to_lesson'])) {
    $teacherUserId = $_POST['teacher_user_id_add'];
    $lessonId = $_POST['lesson_id_add_teacher'];

    if (empty($teacherUserId) || empty($lessonId)) {
        $errors[] = "Öğretmen Kullanıcı ID ve Ders ID alanları boş bırakılamaz.";
    } else {
        $sql = "INSERT INTO t_lessons (teacher_user_id, lesson_id) VALUES (:teacherUserId, :lessonId)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':teacherUserId', $teacherUserId, PDO::PARAM_INT);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Ders sorumlusu eklendi.";
        } else {
            $errors[] = "Ders sorumlusu eklenirken bir hata oluştu.";
        }
    }
}

if (isset($_POST['remove_teacher_from_lesson'])) {
    $lessonId = $_POST['lesson_id_remove_teacher'];

    if (empty($lessonId)) {
        $errors[] = "Ders ID alanı boş bırakılamaz.";
    } else {
        $sql = "DELETE FROM t_lessons WHERE teacher_user_id = :lessonId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Ders sorumlusu kaldırıldı.";
        } else {
            $errors[] = "Ders sorumlusu kaldırılırken bir hata oluştu.";
        }
    }
}

if (isset($_POST['list_lessons'])) {
    $sql = "SELECT * FROM t_lessons";
    $stmt = $pdo->query($sql);

    if ($stmt) {
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($lessons) > 0) {
            echo '<h3>Ders Listesi</h3>';
            echo '<ul>';
            foreach ($lessons as $lesson) {
                echo '<li>' . $lesson['lesson_name'] . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'Ders bulunamadı.';
        }
    } else {
        $errors[] = 'Ders listesi alınırken bir hata oluştu.';
    }
}

if (!empty($errors)) {
    echo '<h3>Uyarılar</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #111;
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
            background-color: #846bab;
        }
       
.lesson-list {
    background-color: #fff;
    margin: 20px auto;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    overflow-x: auto;
}

.lesson-list h2 {
    color: #111;
    text-align: center;
}

.lesson-list table {
    width: 100%;
    border-collapse: collapse;
}

.lesson-list th, .lesson-list td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.lesson-list th {
    background-color: #111;
    color: #fff;
}

.lesson-list tr:nth-child(even) {
    background-color: #f2f2f2;
}

    </style>
    <title>Ders İşlemleri</title>
</head>
<body>
    <h2>Ders İşlemleri</h2>

    <div class="form-container">
        <h3>Ders Ekle</h3>
        <form action="" method="POST">
            <label for="lesson_name">Ders Kullanıcı ID:</label>
            <input type="text" name="lesson_name" id="lesson_name">
            <label for="teacher_user_id">Öğretmen Kullanıcı ID:</label>
            <input type="text" name="teacher_user_id" id="teacher_user_id">
            <button type="submit" name="add_lesson">Ekle</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Ders Sil</h3>
        <form action="" method="POST">
            <label for="lesson_id_delete">Ders ID:</label>
            <input type="text" name="lesson_id_delete" id="lesson_id_delete">
            <button type="submit" name="delete_lesson">Sil</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Ders Güncelle</h3>
        <form action="" method="POST">
            <label for="lesson_id_update">Ders ID:</label>
            <input type="text" name="lesson_id_update" id="lesson_id_update">
            <label for="new_lesson_user_id">Yeni Ders Kullanıcı ID:</label>
            <input type="text" name="new_lesson_user_id" id="new_lesson_user_id">
            <label for="new_teacher_user_id">Yeni Öğretmen Kullanıcı ID:</label>
            <input type="text" name="new_teacher_user_id" id="new_teacher_user_id">
            <button type="submit" name="update_lesson">Güncelle</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Ders Sorumlusu Ekle</h3>
        <form action="" method="POST">
            <label for="teacher_user_id_add">Öğretmen Kullanıcı ID:</label>
            <input type="text" name="teacher_user_id_add" id="teacher_user_id_add">
            <label for="lesson_id_add_teacher">Ders ID:</label>
            <input type="text" name="lesson_id_add_teacher" id="lesson_id_add_teacher">
            <button type="submit" name="add_teacher_to_lesson">Ekle</button>
        </form>
    </div>
    <div class="form-container">
        <h3>Ders Sorumlusunu Kaldır</h3>
        <form action="" method="POST">
            <label for="lesson_id_remove_teacher">Ders ID:</label>
            <input type="text" name="lesson_id_remove_teacher" id="lesson_id_remove_teacher">
            <button type="submit" name="remove_teacher_from_lesson">Kaldır</button>
        </form>
    </div>
<div class="lesson-list">
    <h2>Ders Listesi</h2>
    <?php
    $lessonSql = "SELECT * FROM t_lessons";
    $lessonStmt = $pdo->query($lessonSql);

    if ($lessonStmt->rowCount() > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Ders Adı</th>';
        echo '<th>Öğretmen Kullanıcı ID</th>';
        echo '</tr>';
        while ($row = $lessonStmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['lesson_name'] . '</td>';
            echo '<td>' . $row['teacher_user_id'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo 'Ders bulunamadı.';
    }
    ?>
</div>

</body>
</html>
