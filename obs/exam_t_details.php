<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    
    $sql = "SELECT * FROM t_students WHERE id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        echo "<h2>Öğrenci Bilgileri</h2>";
        echo "<p>Adı: {$student['student_name']}</p>";
        echo "<p>Soyadı: {$student['student_surname']}</p>";
        echo "<p>Öğrenci ID: {$student['id']}</p>";

        $sql = "SELECT * FROM t_exams WHERE student_id = :student_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $exam_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($exam_results) {
            echo "<h2>Sınav Notları</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Sınav ID</th><th>Ders ID</th><th>Puan</th><th>Tarih</th></tr>";
            foreach ($exam_results as $result) {
                echo "<tr>";
                echo "<td>{$result['id']}</td>";
                echo "<td>{$result['lesson_id']}</td>";
                echo "<td>{$result['exam_score']}</td>";
                echo "<td>{$result['exam_date']}</td>";
                echo "</tr>";
            }
            echo "</table>";

            $total_score = 0;
            foreach ($exam_results as $result) {
                $total_score += $result['exam_score'];
            }
            $average_score = $total_score / count($exam_results);
            echo "<p>Genel Ortalama Puan: $average_score</p>";
        } else {
            echo "Öğrencinin sınav sonucu bulunamadı.";
        }
    } else {
        echo "Belirtilen öğrenci bulunamadı.";
    }
} else {
    echo "Öğrenci ID'si belirtilmedi.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Öğrenci Bilgileri ve Sınav Notları</title>
</head>
<body>
    <a href="exam_t.php">Sınav Listesine Geri Dön</a>
</body>
</html>
