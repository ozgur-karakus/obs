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

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];

    $sql = "SELECT u.id AS student_id, u.name, u.surname
            FROM t_classes_students cs
            INNER JOIN t_users u ON cs.student_id = u.id
            WHERE cs.class_id = :class_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Sınıf ID'si belirtilmedi.";
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
</head>

<body>

    <div class="container mt-4">
        <h2 class="mb-4">Sınıf Öğrencileri</h2>
        <div class="table-responsive">
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
    </div>
</body>
</html>
