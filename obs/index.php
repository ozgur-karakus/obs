<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğrenci Bilgilendirme Sistemi</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      body {
            background-image: url(yavuzlar.jpeg);
            background-size: 100% auto; 
            
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            text-align: center;
            incet:0;
            opacity: 0.5;
            z-index: -5;
            margin: 0;
            padding: 0;
            position: relative;
            color: #333;
        }

        header {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .login-link {
            background-color: darkslategray  ;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .custom-paragraph {
            margin-top: 1200px;
        }

        .bottom-content {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <a class="login-link" href="login.php">Giriş Yap</a>
    </header>
    <h1>Öğrenci Bilgi Sistemi</h1>
    <div class="bottom-content">
        <p class="custom-paragraph">
            <?php
                echo "Özgür Karakuş" . "<br>"; 
                echo "06.10.2023";
            ?>
        </p>
    </div>
</body>
</html>
