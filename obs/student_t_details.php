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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Bilgileri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>

<body>
<div class="background-container"></div>
    <div class="container mt-5">
        <h2>Öğrenci Bilgileri</h2>
        <table class="table table-bordered table-dark mt-4">
            <thead>
                <tr>
                    <th>Öğrenci ID</th>
                    <th>Adı</th>
                    <th>Soyadı</th>
                    <th>Sınıfı</th>
                    <th>Genel Başarı Ortalaması</th>
                    <th>Sınav Notları</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $studentId = $_GET['student_id'];
                $studentInfoQuery = "SELECT u.id AS student_id, u.name, u.surname, c.class_name, AVG(e.exam_score) AS avg_score
                                    FROM t_users u
                                    LEFT JOIN t_classes_students cs ON u.id = cs.student_id
                                    LEFT JOIN t_classes c ON cs.class_id = c.id
                                    LEFT JOIN t_exams e ON u.id = e.student_id
                                    WHERE u.id = :student_id
                                    GROUP BY u.id";
                $studentInfoStatement = $pdo->prepare($studentInfoQuery);
                $studentInfoStatement->bindParam(':student_id', $studentId);
                $studentInfoStatement->execute();
                $studentInfo = $studentInfoStatement->fetch(PDO::FETCH_ASSOC);

                $examScoresQuery = "SELECT lesson_name, exam_score
                                    FROM t_exams e
                                    LEFT JOIN t_lessons l ON e.lesson_id = l.id
                                    WHERE e.student_id = :student_id";
                $examScoresStatement = $pdo->prepare($examScoresQuery);
                $examScoresStatement->bindParam(':student_id', $studentId);
                $examScoresStatement->execute();
                $examScores = $examScoresStatement->fetchAll(PDO::FETCH_ASSOC);

                if ($studentInfo) {
                    echo "<tr>";
                    echo "<td>{$studentInfo['student_id']}</td>";
                    echo "<td>{$studentInfo['name']}</td>";
                    echo "<td>{$studentInfo['surname']}</td>";
                    echo "<td>{$studentInfo['class_name']}</td>";
                    echo "<td>" . number_format($studentInfo['avg_score'], 2) . "</td>";
                    echo "<td>";
                    foreach ($examScores as $score) {
                        echo "{$score['lesson_name']}: {$score['exam_score']}<br>";
                    }
                    echo "</td>";
                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='6'>Öğrenci bulunamadı.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
