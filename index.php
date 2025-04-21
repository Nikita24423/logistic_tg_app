<?php 
// Подключение конфигурации для базы данных
require_once('config.php');

// Инициализация переменных
$name = '';
$pass = '';
$errorMessage = '';

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    if (isset($_POST['name']) && isset($_POST['pass'])) {
        $name = $_POST['name'];
        $pass = $_POST['pass'];

        // Очистка данных для предотвращения SQL инъекций
        $name = mysqli_real_escape_string($dbcnx, $name);
        $pass = mysqli_real_escape_string($dbcnx, $pass);

        // SQL-запрос для поиска пользователя в базе данных
        $query = "SELECT * FROM users WHERE _NAME = '$name' AND _PASS = '$pass' LIMIT 1";
        $result = mysqli_query($dbcnx, $query);

        // Если найден пользователь с таким именем и паролем
        if (mysqli_num_rows($result) > 0) {
            // Переход на главную страницу после успешной авторизации
            header("Location: main_admin.php");
            exit();
        } else {
            // Если не удалось найти пользователя
            $errorMessage = 'Неверное имя пользователя или пароль.';
        }
    } elseif (isset($_POST['guest_login'])) {
        // Если нажата кнопка "Войти как обычный пользователь"
        header("Location: main.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    
   <style>
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
	
</head>
<body>
    <div class="container">
        <h2>Авторизация</h2>
        
        <!-- Форма авторизации -->
        <form method="POST" action="index.php">
            <div>
                <label for="name">Имя пользователя:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            
            <div>
                <label for="pass">Пароль:</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            
            <div>
                <button type="submit">Войти</button>
            </div>
            
            <?php if ($errorMessage): ?>
                <p class="error"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>
        
        <form method="POST" action="index.php">
            <div>
			
            <br>    <button type="submit" name="guest_login">Войти как обычный пользователь</button>
            </div>
        </form>
    </div>
</body>
</html>
