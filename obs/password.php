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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST["new_password"];
    $teacherId = $_SESSION["user_id"];
    $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2ID);

    $updatePasswordQuery = "UPDATE t_users SET password = :password WHERE id = :teacher_id";
    $updatePasswordStatement = $pdo->prepare($updatePasswordQuery);
    $updatePasswordStatement->bindParam(':password', $hashedPassword);
    $updatePasswordStatement->bindParam(':teacher_id', $teacherId);
    $updatePasswordStatement->execute();
    $message = "Şifreniz başarıyla güncellendi!";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Değiştir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .container {
            margin-top: 20px;
        }

        .form-container {
            background-color: #fff;
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            padding: 20px;
        }
        
    </style>
</head>
<body>

    <div class="container">
        <h2 class="mt-4">Şifre Değiştir</h2>
        <div class="form-container mt-4">
            <?php if (isset($message)) : ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="new_password" class="form-label">Yeni Şifre</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Şifreyi Değiştir</button>
            </form>
        </div>
    </div>
</body>
</html>
