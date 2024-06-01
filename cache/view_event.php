<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($event->getName()) ?></title>
    <link rel="stylesheet" href="/views/css/style.css">
    
<style>
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px;
    }
    .event-card {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    img {
        width: 300px;
        height: 300px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    h1 {
        color: #333;
    }
    p {
        color: #555;
    }
    .button {
        padding: 10px;
        background-color: #749eeb;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }
    .button:hover {
        background-color: #526de7;
    }
    .button-danger {
        background-color: #e74c3c;
    }
    .button-danger:hover {
        background-color: #c0392b;
    }
    .error {
        color: red;
        margin-bottom: 20px;
    }
    ul {
        list-style: none;
        padding: 0;
    }
    li {
        margin-bottom: 10px;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        margin-bottom: 10px;
        font-weight: bold;
        color: #333;
    }
    textarea {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
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
        <h1><?= htmlspecialchars($event->getName()) ?></h1>
        <div class="event-card">
            <div class="img"><img src="<?= htmlspecialchars($event->getImage()) ?>" alt="Event Image"></div>
            <div class="info">
                <p><?= htmlspecialchars($event->getShortDescription()) ?></p>
                <p><?= htmlspecialchars($event->getLongDescription()) ?></p>
                <p>Место: <?= htmlspecialchars($event->getPlace()) ?></p>
                <p>Дата: <?= date('d.m.Y', strtotime($event->getDate())) ?></p>
                <p>Время: <?= date('H:i', strtotime($event->getTime())) ?></p>
            </div>
        </div>
        <div class="submit">
            <?php if ($event->getUserId() === $_SESSION['user']): ?>
            <a href="/event/<?= $event->getId() ?>/edit" class="button">Редактировать</a>
            <form action="/event/<?= $event->getId() ?>/delete" method="POST" style="display:inline;">
                <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить это событие?')" class="button button-danger">Удалить</button>
            </form>
            <?php else: ?>
            <?php if ($memberMapper->isUserJoined($_SESSION['user'], $event->getId())): ?>
            <form action="/event/<?= $event->getId() ?>/leave" method="POST" style="display:inline;">
                <button type="submit" class="button">Отписаться</button>
            </form>
            <?php else: ?>
            <form action="/event/<?= $event->getId() ?>/join" method="POST" style="display:inline;">
                <button type="submit" class="button">Участвовать</button>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>



        <h2>Комментарии</h2>
        <?php if (!empty($comments)): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
            <li>
                <p><strong><?= htmlspecialchars($comment->getUser()->getFirstName()) ?>:</strong> <?= htmlspecialchars($comment->getText()) ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Комментариев пока нет.</p>
        <?php endif; ?>

        <form action="/event/<?= $event->getId() ?>/comment" method="POST">
            <label for="comment">Добавить комментарий:</label>
            <textarea name="comment" id="comment" rows="4" required></textarea>
            <button type="submit" class="button">Отправить</button>
        </form>
    </div>
</div>

</main>
</body>
</html>






