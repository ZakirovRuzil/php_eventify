<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../views/css/register.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Регистрация</h1>
        <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="/register" method="POST" onsubmit="return validateForm()">
            <label>Имя:
                <input type="text" name="first_name" required>
            </label>
            <label>Фамилия:
                <input type="text" name="second_name" required>
            </label>
            <label>Возраст:
                <input type="number" name="age" required>
            </label>
            <label>Электронная почта:
                <input type="email" name="email" required>
            </label>
            <label>Телефон:
                <input type="text" name="phone" required>
            </label>
            <label>Пароль:
                <input type="password" name="password" required>
            </label>
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже зарегистрированы? <a href="/login">Войти</a></p>
    </div>
</div>
<script>
    function validateForm() {
        const ageInput = document.querySelector('input[name="age"]');
        const age = parseInt(ageInput.value);
        if (age < 0 || age > 120) {
            alert('Некоректный возраст');
            return false;
        }
        return true;
    }
</script>
</body>
</html>
