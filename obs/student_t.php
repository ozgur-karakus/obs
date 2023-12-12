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

    <div class="container mt-5">
        <h2 class="mb-4">Öğrenci Bilgileri</h2>
        <table class="table table-striped table-bordered table-dark">
            <thead>
                <tr>
                    <th>Öğrenci Adı</th>
                    <th>Soyadı</th>
                    <th>Detaylar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $studentListQuery = "SELECT name, surname, id FROM t_users";
                $studentListStatement = $pdo->prepare($studentListQuery);
                $studentListStatement->execute();
                $studentList = $studentListStatement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($studentList as $student) {
                    echo "<tr>";
                    echo "<td>{$student['name']}</td>";
                    echo "<td>{$student['surname']}</td>";
                    echo "<td><a href='student_t_details.php?student_id={$student['id']}' class='btn btn-primary mx-aauto'>Detaylar</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
