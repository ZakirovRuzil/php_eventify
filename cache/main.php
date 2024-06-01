<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <link rel="stylesheet" href="/views/css/style.css">
    
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
    <form id="search-form" method="POST" action="/search">
        <input type="text" name="query" placeholder="Поиск" value="<?= isset($query) ? htmlspecialchars($query) : '' ?>">
        <button type="submit">Поиск</button>
    </form>
    <div id="events" class="events-container">
        <?php if (isset($events) && $events instanceof \app\core\Collection && count($events) > 0): ?>
        <?php foreach ($events->getNextRow() as $event): ?>
        <div class="event" onclick="window.location='/event/<?= $event->getId() ?>'">
            <h2><?= htmlspecialchars($event->getName()) ?></h2>
            <img src="<?= htmlspecialchars($event->getImage()) ?>" alt="<?= htmlspecialchars($event->getName()) ?>">
            <p><?= htmlspecialchars($event->getShortDescription()) ?></p>
            <p>Дата: <?= date('d.m.Y', strtotime($event->getDate())) ?> Время: <?= date('H:i', strtotime($event->getTime())) ?></p>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>Мероприятий не найдено</p>
        <?php endif; ?>
    </div>
</div>

</main>
</body>
</html>





<!---->

