<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование события</title>
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
        <h1>Редактирование события</h1>
        <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="/event/<?= htmlspecialchars($event->getId()) ?>/edit" method="POST">
            <label>Название:
                <input type="text" name="name" value="<?= htmlspecialchars($event->getName()) ?>" required>
            </label>
            <label>Короткое описание:
                <input type="text" name="short_description" value="<?= htmlspecialchars($event->getShortDescription()) ?>" required>
            </label>
            <label>Длинное описание:
                <textarea name="long_description" required><?= htmlspecialchars($event->getLongDescription()) ?></textarea>
            </label>
            <label>Место:
                <input type="text" name="place" value="<?= htmlspecialchars($event->getPlace()) ?>" required>
            </label>
            <label>Дата:
                <input type="date" name="date" value="<?= htmlspecialchars($event->getDate()) ?>" required>
            </label>
            <label>Время:
                <input type="time" name="time" value="<?= htmlspecialchars($event->getTime()) ?>" required>
            </label>
            <label>Изображение (URL):
                <input type="text" name="image" value="<?= htmlspecialchars($event->getImage()) ?>" required>
            </label>
            <button type="submit">Сохранить изменения</button>
            <a href="/event/<?= htmlspecialchars($event->getId()) ?>" class="cancel-button">Отменить</a>
        </form>
    </div>
</div>

</main>
</body>
</html>






