<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="/views/css/style.css">
    
<style>
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px;
    }
    h1, h2 {
        color: #333;
    }
    p {
        color: #555;
    }
    ul {
        list-style: none;
        padding: 0;
    }
    li {
        margin-bottom: 10px;
    }
    a {
        color: #749eeb;
        text-decoration: none;
        transition: color 0.3s;
    }
    a:hover {
        color: #526de7;
    }
</style>

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
        <h1>Профиль пользователя</h1>
        <h2>Данные пользователя</h2>
        <p>Имя: <?= htmlspecialchars($user->getFirstName()) ?></p>
        <p>Фамилия: <?= htmlspecialchars($user->getSecondName()) ?></p>
        <p>Возраст: <?= htmlspecialchars($user->getAge()) ?></p>
        <p>Email: <?= htmlspecialchars($user->getEmail()) ?></p>
        <p>Телефон: <?= htmlspecialchars($user->getPhone()) ?></p>

        <h2>Созданные события</h2>
        <?php if (empty($createdEvents)): ?>
        <p>Вы не создали ни одного события.</p>
        <?php else: ?>
        <ul>
            <?php foreach ($createdEvents as $event): ?>
            <li><a href="/event/<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <h2>События, на которые вы подписаны</h2>
        <?php if (empty($joinedEvents)): ?>
        <p>Вы не подписаны ни на одно событие.</p>
        <?php else: ?>
        <ul>
            <?php foreach ($joinedEvents as $event): ?>
            <li><a href="/event/<?= htmlspecialchars($event['id']) ?>"><?= htmlspecialchars($event['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>

</main>
</body>
</html>






