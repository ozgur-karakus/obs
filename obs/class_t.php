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
function listClassesForTeacher($teacher_id) {
    global $pdo;
    $sql = "SELECT c.id, c.class_name
            FROM t_classes c
            WHERE c.class_teacher_id = :teacher_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$classes = [];

if (isset($_SESSION['user_id'])) {
    $teacher_id = $_SESSION['user_id'];
    $classes = listClassesForTeacher($teacher_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorumlu Olduğunuz Sınıflar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>

</style>
<body>
<div class="background-container">
        <div id=".background-container">
        </div>
    <div class="container mt-5">
        <h2 class="mb-4">Sorumlu Olduğunuz Sınıflar</h2>
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sınıf Adı</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td>
                            <?php echo $class['class_name']; ?>

                            <a href="students_t_class.php?class_id=<?php echo $class['id']; ?>" class="btn btn-primary float-end">Detaylar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
