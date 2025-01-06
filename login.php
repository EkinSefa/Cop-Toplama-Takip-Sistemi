<?php
session_start();
require 'db.php';

// Giriş yapılmışsa, yönlendirme yapılır
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: index.php");  // Admin'e index.php'ye yönlendir
    exit;
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'user') {
    header("Location: index.php");  // User'lara da index.php'ye yönlendir
    exit;
}

// Giriş formu kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanından sorgula
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Kullanıcının rolünü ekliyoruz

        // Admin veya User'a göre yönlendir
        if ($_SESSION['role'] == 'admin') {
            header("Location: index.php");  // Admin girişinde index.php'ye yönlendir
        } else {
            header("Location: map.php");  // User girişinde de index.php'ye yönlendir
        }
        exit;
    } else {
        $error = "Kullanıcı adı veya şifre hatalı!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1F3B4D, #85A3B2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #1F3B4D;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: rgb(67, 83, 92);
        }

        .register-link {
            margin-top: 15px;
            display: block;
            color: #4facfe;
            text-decoration: none;
            font-size: 14px;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <form action="" method="POST">
        <h2>Giriş Yap</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Şifre:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Giriş Yap</button>
        <a href="register.php" class="register-link">Hesabınız yok mu? Kayıt olun</a>
    </form>
</body>

</html>