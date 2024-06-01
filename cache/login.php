<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="../views/css/login.css">

</head>
<body>
<div class="container">
    <div class="card">
        <h1>Вход</h1>
        <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="/login" method="POST">
            <label>Электронная почта:
                <input type="email" name="email" required>
            </label>
            <label>Пароль:
                <input type="password" name="password" required>
            </label>
            <button type="submit">Войти</button>
        </form>
        <p>Нет аккаунта? <a href="/">Зарегистрироваться</a></p>
    </div>
</div>
</body>
</html>
