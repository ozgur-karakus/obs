<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar Example</title>
   
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
        #stats {
            background-color: #f7f7f7;
            padding: 10px;
            text-align: center;
        }
        #stats p {
            font-size: 18px;
        }
      


    </style>
</head>
<body>
  
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li><a href="admin.php?page=students">Öğrenciler</a></li>
                <li><a href="admin.php?page=teachers">Öğretmenler</a></li>
                <li><a href="admin.php?page=classes">Sınıflar</a></li>
                <li><a href="admin.php?page=lessons">Dersler</a></li>
            </ul>
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a href="#" class="btn" id="menu-toggle"><span class="glyphicon glyphicon-menu-hamburger"></span></a>
                        <h1 class="text-center">ADMİN</h1>
                        <h2 class="small text-center">kontrol paneli</h2>
                        <p class="text-center">Merhaba Admin! Bu alan kontrol mekanizması güçlendrilmiş tam erişimli alandır.</p>

                        <?php
                       
                        if (isset($_GET['page'])) {
                            $page = $_GET['page'];
                            if ($page === 'students') {
                                include('students.php');
                            } elseif ($page === 'teachers') {
                                include('teachers.php');
                            } elseif ($page === 'classes') {
                                include('classes.php');
                            } elseif ($page === 'lessons') {
                                include('lessons.php');
                            } else {
                                echo "Sayfa bulunamadı.";
                            }
                        } else {
                            echo "Lütfen bir sayfa seçin.";
                        }
                        ?>
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
        });
    </script>
</body>
</html>

