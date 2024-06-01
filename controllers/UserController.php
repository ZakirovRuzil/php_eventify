<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\mappers\EventMapper;
use app\mappers\UserMapper;
use app\mappers\MemberMapper;

class UserController
{
    public function profilePage(Request $request): void
    {
        $userId = $_SESSION['user']; // Получаем ID текущего пользователя из сессии

        $userMapper = new UserMapper();
        $user = $userMapper->Select($userId);

        if ($user === null) {
            Application::$app->getRouter()->renderTemplate("404.html", ["error" => "User not found"]);
            return;
        }

        $eventMapper = new EventMapper();
        $createdEvents = $eventMapper->getEventsByUser($userId);

        $memberMapper = new MemberMapper();
        $joinedEvents = $memberMapper->getJoinedEvents($userId);

        Application::$app->getRouter()->renderTemplate("profile.html", [
            "user" => $user,
            "createdEvents" => $createdEvents,
            "joinedEvents" => $joinedEvents
        ]);
    }
}
