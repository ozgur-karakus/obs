<!DOCTYPE html>
<html>
<head>
    <title>Öğrenci Sınıfı</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
  
</head><div class="background-container">
        <div id=".background-container">
        </div>
<body>
    <div class="container mt-5">
        <?php
        session_start();
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
        if (isset($_SESSION['user_id'])) {
            $student_id = $_SESSION['user_id'];
        } else {
            echo "Oturum açmış bir öğrenci bulunamadı.";
            exit;
        }
        $sql = "SELECT c.class_name
                FROM t_classes c
                JOIN t_classes_students cs ON c.id = cs.class_id
                WHERE cs.student_id = :student_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<h2 class='mb-3'>Öğrencinin Sınıfı</h2>";
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-dark'>
                    <tr>
                        <th class='text-ligth'>Sınıf Adı</th>
                    </tr>
                </thead>
                <tbody>";

        if ($result) {
            $class_name = $result['class_name'];
            echo "<tr class='table-dark'>
                    <td class='text-white'>$class_name</td>
                </tr>";
        } else {
            echo "<tr class='table-dark'>
                    <td>Öğrencinin sınıf bilgisi bulunamadı.</td>
                </tr>";
        }

        echo "</tbody>
            </table>";
        ?>
    </div>
</body>
</html>
