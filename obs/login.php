<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=database", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Veritabanına bağlantı hatası: " . $e->getMessage();
    }

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $sql = "SELECT * FROM t_users WHERE username = ? AND role = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if ($role == 'admin') {
                header("Location: admin.php");
                exit();
            } elseif ($role == 'student') {
                header("Location: student.php");
                exit();
            } elseif ($role == 'teacher') {
                header("Location: teacher.php");
                exit();
            }
        } else {
            echo "Kullanıcı adı veya şifre hatalı.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Formu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Giriş Formu</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="role">Kullanıcı Türü:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="student">Öğrenci</option>
                    <option value="teacher">Öğretmen</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Giriş Yap</button>
            <div class="register-link">
            <p>Hesabınız yok mu? <a href="register.php" class="btn-register">Kayıt Ol</a></p>
        </div>
        </form>
    </div>
</body>
</html>
