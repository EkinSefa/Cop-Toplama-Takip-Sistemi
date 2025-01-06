<?php
require 'db.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    try {
        $stmt->execute(['username' => $username, 'password' => $password]);
        $success = true;
        header("refresh:3;url=login.php"); // 3 saniye sonra login.php'ye yönlendir
    } catch (PDOException $e) {
        $error = "Kullanıcı adı zaten kayıtlı!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4caf50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeout 3s forwards;
        }

        @keyframes fadeout {
            0% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php if ($success): ?>
        <div class="success">Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...</div>
    <?php endif; ?>
    <form action="" method="POST">
        <h2>Kayıt Ol</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Şifre:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Kayıt Ol</button>
    </form>
</body>

</html>