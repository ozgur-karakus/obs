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

session_start();

if (isset($_SESSION['user_id'])) {
    $teacher_id = $_SESSION['user_id'];
    $class_sql = "SELECT id FROM t_classes WHERE class_teacher_id = :teacher_id";
    $class_stmt = $pdo->prepare($class_sql);
    $class_stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $class_stmt->execute();
    $class_id = $class_stmt->fetchColumn();

    if (!$class_id) {
        echo "Öğretmenin sorumlu olduğu bir sınıf bulunamadı.";
        exit;
    }
    $sql = "SELECT u.id AS student_id, u.name, u.surname
            FROM t_classes_students cs
            INNER JOIN t_users u ON cs.student_id = u.id
            WHERE cs.class_id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Oturum açılmadı veya öğretmen oturumu yok.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sınıf Öğrencileri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Ders Öğrencileri</h2>
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                    <th>Öğrenci ID</th>
                    <th>Adı</th>
                    <th>Soyadı</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['student_id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['surname']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
