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

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.name, u.surname, e.exam_score, c.class_name
        FROM t_users u
        JOIN t_exams e ON u.id = e.student_id
        JOIN t_classes_students cs ON u.id = cs.student_id
        JOIN t_classes c ON cs.class_id = c.id
        WHERE u.role = 'student' AND u.id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$studentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($studentData) {

    $examCount = count($studentData);
    
    $totalScore = 0;
    foreach ($studentData as $student) {
        $totalScore += $student['exam_score'];
    }
    $averageScore = $totalScore / $examCount;
    
    
} else {
    echo "Sınav verisi bulunamadı.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Paneli</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <a href="login.php" class="btn btn-danger" style="position: fixed; top: 10px; right: 10px;">Çıkış Yap</a>
    <style>
        
         #sidebar-wrapper {
            z-index: 1;
            position: fixed;
            width: 0;
            height: 100%;
            overflow-y: hidden;
            background: #5b4282;
            opacity: 0.9;
            transition: all 0.5s;
            display: flex;
            align-items: center;
        }

        #page-content-wrapper {
            width: 100%;
            padding: 15px;
        }

        #menu-toggle {
            transition: all 0.3s;
            font-size: 2em;
            position: fixed;
            margin-left: 10px;
        }

        #wrapper.menuDisplayed #sidebar-wrapper {
            width: 250px;
        }

        #wrapper.menuDisplayed #page-content-wrapper {
            padding-left: 250px;
        }

        .sidebar-nav {
            padding: 0;
            list-style: none;
            width: 100%;
            text-align: center;
        }

        .sidebar-nav li {
            line-height: 40px;
            width: 100%;
            transition: all 0.3s;
            padding: 10px;
        }

        .sidebar-nav li a {
            display: block;
            text-decoration: none;
            color: #ddd;
        }

        .sidebar-nav li:hover {
            background: #846bab;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #5b4282;
            padding-top: 20px;
            padding-left: 10px;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #846bab;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .info-card {
            background-color: #f8f9fa;
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
        }

        .info-card h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-card p {
            font-size: 1.2rem;
        }
      
    </style>
</head>
<body>

    </div>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li><a href="student_password.php">Şifre Değiştirme</a></li>
                <li><a href="student_class.php">Sınıf Görüntüleme</a></li>
                <li><a href="student_exam.php">Sınavlar</a></li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" class="btn" id="menu-toggle"><span class="glyphicon glyphicon-menu-hamburger"></span></a>
                        <h1 class="text-center">Öğrenci Paneli</h1>
             
                        </div>
                        <div class="info-card">
                            <h3>Sınav Sayısı:</h3>
                            <p><?php echo $examCount; ?></p>
                        </div>

                        <div class="info-card">
                            <h3>Genel Başarı Ortalaması:</h3>
                            <p><?php echo number_format($averageScore, 2); ?></p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#menu-toggle").click(function (e) {
                e.preventDefault();
                $("#wrapper").toggleClass("menuDisplayed");
            });
            $("ul.sidebar-nav a").click(function () {
                var target = $(this).attr("href");
                $("#page-content-wrapper > div").hide();
                $(target).show();
            });
        });
    </script>
</body>
</html>
<?php

?>
