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

function listLessonsForTeacher($teacher_id) {
    global $pdo;
    $sql = "SELECT DISTINCT id, lesson_name FROM t_lessons WHERE teacher_user_id = :teacher_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$lessons = [];

if (isset($_SESSION['user_id'])) {
    $teacher_id = $_SESSION['user_id'];
    $lessons = listLessonsForTeacher($teacher_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğretmenin Sorumlu Olduğu Dersler</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>
<style>

.dark-table {
    background-color: #343a40;
    color: #ffffff;
}
</style>
<body>
<div class="background-container">
    <div id=".background-container"></div>
    <div class="container mt-5">
        <h2 class="mb-4">Sorumlu Olduğunuz Dersler</h2>
        <table class="table table-dark table-bordered table-striped">
            <thead >
                <tr>
                    <th>Ders Adı</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lessons as $lesson): ?>
                    <tr>
                        <td>
                            <?php echo $lesson['lesson_name']; ?>
                            <a href="lesson_t_details.php?lesson_id=<?php echo $lesson['id']; ?>" class="btn btn-primary float-end">Detaylar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

