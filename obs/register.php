<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=database","root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanına bağlantı hatası: " . $e->getMessage();
}

if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
    $role = $_POST['role'];
    $sql = "INSERT INTO t_users (name, surname, username, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $surname, $username, $password, $role]);
    echo "Kullanıcı başarıyla kaydedildi.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Formu</title>
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
        <h2>Kayıt Formu</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Ad:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="surname">Soyad:</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>

            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="role">Kullanıcı Türü:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="student">Öğrenci</option>
                    <option value="teacher">Öğretmen</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
