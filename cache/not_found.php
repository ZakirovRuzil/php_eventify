<?php class_exists('app\core\Template') or exit; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ошибка 404</title>
    <script>
        setTimeout(function() {
            window.location.href = '/main';
        }, 3000);
    </script>
</head>
<body>
<h1>Извините, вы зашли не в ту дверь</h1>
<p>Вы будете перенаправлены на главную страницу через несколько секунд...</p>
</body>
</html>
