<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['class_id'])) {
        $class_id = $_POST['class_id'];

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
        
        $sql = "SELECT t_users.id, t_users.name, t_users.surname
                FROM t_users
                INNER JOIN t_classes_students ON t_users.id = t_classes_students.student_id
                WHERE t_classes_students.class_id = :class_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sınıf Öğrencileri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        table {
            background-color: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            overflow-x: auto;
        }

        table h2 {
            color: #5b4282;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #111;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Sınıf Öğrencileri</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Ad</th>
            <th>Soyad</th>
        </tr>
        <?php foreach ($students as $student) { ?>
            <tr>
                <td><?php echo $student['id']; ?></td>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['surname']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
