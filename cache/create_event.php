<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать событие</title>
    <link rel="stylesheet" href="/views/css/style.css">
    
<link rel="stylesheet" href="/views/css/refactor_event.css">

</head>
<body>
<header>
    <div class="navbar">
        <a href="/main">Главная</a>
        <a href="/create">Создать</a>
        <a href="/profile">Профиль</a>
        <a href="/logout">Выйти</a>
    </div>
</header>
<main>
    
<div class="container">
    <div class="card">
        <h1>Создать событие</h1>
        <?php if (!empty($error)): ?>
        <p style="color:red"><?= $error ?></p>
        <?php endif; ?>
        <form action="/create" method="POST">
            <label>Название:
                <input type="text" name="name" required>
            </label>
            <label>Короткое описание:
                <input type="text" name="short_description" required>
            </label>
            <label>Длинное описание:
                <textarea name="long_description" required></textarea>
            </label>
            <label>Место:
                <input type="text" name="place" required>
            </label>
            <label>Дата:
                <input type="date" name="date" required>
            </label>
            <label>Время:
                <input type="time" name="time" required>
            </label>
            <label>Изображение (URL):
                <input type="text" name="image" required>
            </label>
            <button type="submit">Создать</button>
        </form>
    </div>
</div>

</main>
</body>
</html>






