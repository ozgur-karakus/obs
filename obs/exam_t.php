<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=database", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

function listTeacherCourses($teacher_id, $pdo) {
    $sql = "SELECT id, lesson_name FROM t_lessons WHERE teacher_user_id = :teacher_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

session_start();
$teacher_id = $_SESSION['user_id'];

if (isset($_POST['ekle'])) {
    $student_id = $_POST['student_id'];
    $lesson_id = $_POST['lesson_id'];
    $class_id = $_POST['class_id'];
    $exam_score = $_POST['exam_score'];

    $exam_date = date("Y-m-d H:i:s");

    $teacher_courses = listTeacherCourses($teacher_id, $pdo);

    $is_teacher_course = false;
    foreach ($teacher_courses as $course) {
        if ($course['id'] == $lesson_id) {
            $is_teacher_course = true;
            break;
        }
    }

    if ($is_teacher_course) {
        $sql = "INSERT INTO t_exams (student_id, lesson_id, class_id, exam_score, exam_date) VALUES (:student_id, :lesson_id, :class_id, :exam_score, :exam_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $student_id,
            'lesson_id' => $lesson_id,
            'class_id' => $class_id,
            'exam_score' => $exam_score,
            'exam_date' => $exam_date,
        ]);
        echo "Sınav puanı başarıyla eklendi.";
    } else {
        echo "Bu dersi eklemek için yetkiniz yok.";
    }
}

if (isset($_POST['sil'])) {
    $exam_id = $_POST['exam_id'];
    $sql = "DELETE FROM t_exams WHERE id = :exam_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['exam_id' => $exam_id]);
    echo "Sınav puanı başarıyla silindi.";
}

if (isset($_POST['guncelle'])) {
    $exam_id = $_POST['exam_id'];
    $new_score = $_POST['new_score'];
    $sql = "UPDATE t_exams SET exam_score = :new_score WHERE id = :exam_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['new_score' => $new_score, 'exam_id' => $exam_id]);
    echo "Sınav puanı başarıyla güncellendi.";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Sınav Puanları Yönetimi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

</style>
<body>

    <div class="container my-5">
        <h2 class="text-center mb-4">Sınav Puanları Yönetimi</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Sınav Puanı Ekle</h3>
                        <form method="post" action="exam_t.php">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="student_id" placeholder="Öğrenci ID" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="lesson_id" placeholder="Ders ID" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="class_id" placeholder="Sınıf ID" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="exam_score" placeholder="Puan" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="ekle">Ekle</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class ="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Sınav Puanı Sil</h3>
                        <form method="post" action="exam_t.php">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="exam_id" placeholder="Sınav ID" required>
                            </div>
                            <button type="submit" class="btn btn-danger" name="sil">Sil</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">Sınav Puanı Güncelle</h3>
                        <form method="post" action="exam_t.php">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="exam_id" placeholder="Sınav ID" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="new_score" placeholder="Yeni Puan" required>
                            </div>
                            <button type="submit" class="btn btn-warning" name="guncelle">Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Sınav Puanları Listesi</h3>
                <?php
                $sql = "SELECT * FROM t_exams";
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    echo "<table class='table table-bordered table-striped'>";
                    echo "<thead class='table-dark'><tr><th>ID</th><th>Öğrenci ID</th><th>Ders ID</th><th>Sınıf ID</th><th>Puan</th><th>Tarih</th></tr></thead>";
                    echo "<tbody>";
                    foreach ($results as $row) {
                        echo "<tr><td>" . $row['id'] . "</td><td>" . $row['student_id'] . "</td><td>" . $row['lesson_id'] . "</td><td>" . $row['class_id'] . "</td><td>" . $row['exam_score'] . "</td><td>" . $row['exam_date'] . "</td></tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "Hiç sınav puanı bulunamadı.";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
