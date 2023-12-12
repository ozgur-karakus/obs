<!DOCTYPE html>
<html>
<head>
    <title>Öğrenci Sınav Sonuçları</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>   


<body>
    <div class="container">
        <h2 class="mt-5">Öğrencinin Girdiği Sınavlar</h2>
        <?php
        session_start();

        if (isset($_SESSION['user_id'])) {
            $student_id = $_SESSION['user_id'];
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
            $sql = "SELECT l.lesson_name, e.exam_score, e.exam_date
                    FROM t_exams e
                    JOIN t_lessons l ON e.lesson_id = l.id
                    WHERE e.student_id = :student_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
            $examResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($examResults) {
                echo "<table class='table table-bordered table-dark mt-3'>
                        <thead>
                            <tr>
                                <th scope='col'>Sınav Adı</th>
                                <th scope='col'>Sınav Sonucu</th>
                                <th scope='col'>Sınav Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($examResults as $result) {
                    echo "<tr class='table-dark'>
                            <td>" . $result['lesson_name'] . "</td>
                            <td>" . $result['exam_score'] . "</td>
                            <td>" . $result['exam_date'] . "</td>
                        </tr>";
                }

                echo "</tbody>
                    </table>";
            } else {
                echo "<div class='alert alert-info mt-3'>Öğrencinin girdiği sınav bilgisi bulunamadı.</div>";
            }
        } else {
            echo "<div class='alert alert-danger mt-3'>Oturum bulunamadı. Lütfen giriş yapın.</div>";
        }
        ?>
    </div>
</body>
</html>
