<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="students.css">
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
            transition: all .5s;
            display: flex;
            align-items: center;
        }

        #page-content-wrapper {
            width: 100%;
            padding: 15px;
        }

        #menu-toggle {
            transition: all .3s;
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
            transition: all .3s;
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

    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
            <li><a href="student_t.php">Öğrenci Listesi</a></li>
                <li><a href="exam_t.php">Sınav İşlemleri</a></li>
                <li><a href="password.php">Şifre Değiştir</a></li>
                <li><a href="lesson_t.php">Dersler</a></li>
                <li><a href="class_t.php">Sınıflar</a></li>
        </div>

        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" class="btn" id="menu-toggle"><span class="glyphicon glyphicon-menu-hamburger"></span></a>
                        <h1 class="text-center">Öğretmen</h1>
                        <h2 class="small text-center">Kontrol Paneli</h2>
                        <p class="text-center">Merhaba hocam! Bu alan kontrol mekanizması güçlendirilmiş kısmi erişimli alandır.</p>
                        <br>

                        <?php
                        session_start();

                        $host = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "database";

                        try {
                            $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        } catch (PDOException $e) {
                            die("Veritabanı hatası: " . $e->getMessage());
                        }

                        $teacher_id = $_SESSION['user_id'];

                        $teacherInfoQuery = "SELECT name, surname FROM t_users WHERE id = :teacher_id";
                        $teacherInfoStatement = $db->prepare($teacherInfoQuery);
                        $teacherInfoStatement->bindParam(':teacher_id', $teacher_id);
                        $teacherInfoStatement->execute();
                        $teacherInfo = $teacherInfoStatement->fetch(PDO::FETCH_ASSOC);

                        $teacherName = $teacherInfo['name'];
                        $teacherSurname = $teacherInfo['surname'];

                        $studentCountQuery = "SELECT COUNT(*) FROM t_classes_students WHERE class_id IN (SELECT id FROM t_classes WHERE class_teacher_id = :teacher_id)";
                        $studentCountStatement = $db->prepare($studentCountQuery);
                        $studentCountStatement->bindParam(':teacher_id', $teacher_id);
                        $studentCountStatement->execute();
                        $studentCount = $studentCountStatement->fetchColumn();

                        $sorumluSiniflarQuery = "SELECT class_name FROM t_classes WHERE class_teacher_id = :teacher_id";
                        $sorumluSiniflarStatement = $db->prepare($sorumluSiniflarQuery);
                        $sorumluSiniflarStatement->bindParam(':teacher_id', $teacher_id);
                        $sorumluSiniflarStatement->execute();
                        $sorumluSiniflar = $sorumluSiniflarStatement->fetchAll(PDO::FETCH_COLUMN);

                        $averageScoreQuery = "SELECT AVG(e.exam_score) 
                            FROM t_exams e
                            INNER JOIN t_classes_students cs ON e.student_id = cs.student_id
                            WHERE cs.class_id IN (SELECT id FROM t_classes WHERE class_teacher_id = :teacher_id)";
                        $averageScoreStatement = $db->prepare($averageScoreQuery);
                        $averageScoreStatement->bindParam(':teacher_id', $teacher_id);
                        $averageScoreStatement->execute();
                        $averageScore = $averageScoreStatement->fetchColumn();
                        ?>
                        <div class="info-card">
                             <h3>Öğretmen Adı:</h3>
                             <p><?php echo $teacherName,$teacherSurname; ?></p>
                        </div>
                        <div class="info-card">
                            <h3>Öğrenci Sayısı:</h3>
                            <p><?php echo $studentCount; ?></p>
                        </div>

                        <div class="info-card">
                            <h3>Sorumlu Olduğu Sınıflar:</h3>
                            <p><?php echo implode(", ", $sorumluSiniflar); ?></p>
                        </div>

                        <div class="info-card">
                            <h3>Sorumlu Olduğu Sınıfın Başarı Ortalaması:</h3>
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
        $(document).ready(function(){
            $("#menu-toggle").click(function(e){
                e.preventDefault();
                $("#wrapper").toggleClass("menuDisplayed");
            });
            $("ul.sidebar-nav a").click(function(){
                var target = $(this).attr("href");
                $("#page-content-wrapper > div").hide();
                $(target).show();
            });
        });
    </script>
</body>
</html>
