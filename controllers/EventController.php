<?php

namespace app\controllers;

use app\core\Application;
use app\core\Collection;
use app\core\Request;
use app\mappers\EventMapper;
use app\mappers\CommentMapper;
use app\mappers\MemberMapper;
use app\models\Event;
use app\models\Comment;
use app\models\Member;

class EventController
{
    public function mainPage(Request $request): void
    {
        $eventMapper = new EventMapper();
        $events = $eventMapper->SelectAll();

        Application::$app->getRouter()->renderTemplate("main.html", ["events" => $events]);
    }

    public function searchEvents(Request $request): void
    {
        $body = $request->getBody();
        $query = strtolower($body['query'] ?? '');

        $eventMapper = new EventMapper();
        $events = $eventMapper->searchByQuery($query);

        $eventCollection = new Collection($events, $eventMapper);

        Application::$app->getRouter()->renderTemplate("main.html", ["events" => $eventCollection, "query" => $query]);
    }

    public function createPage(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("create_event.html");
    }

    public function handleCreate(Request $request): void
    {
        $body = $request->getBody();

        // Валидация данных
        if (empty($body['name']) || empty($body['short_description']) || empty($body['long_description']) ||
            empty($body['place']) || empty($body['date']) || empty($body['time']) || empty($body['image'])) {
            Application::$app->getRouter()->renderTemplate("create_event.html", ["error" => "All fields are required"]);
            return;
        }

        // Создание нового события
        $event = new Event(
            null,
            $body['name'],
            $body['short_description'],
            $body['long_description'],
            $body['place'],
            $body['date'],
            $body['time'],
            $body['image'],
            $_SESSION['user']
        );

        $eventMapper = new EventMapper();
        try {
            $eventMapper->Insert($event);
            header('Location: /main');
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("create_event.html", ["error" => "Error creating event: " . $e->getMessage()]);
        }
    }

    public function viewEvent(Request $request, $id): void
    {
        $eventMapper = new EventMapper();
        $event = $eventMapper->Select((int)$id);

        if ($event === null) {
            Application::$app->getRouter()->renderTemplate("404.html", ["error" => "Event not found"]);
            return;
        }

        $commentMapper = new CommentMapper();
        $comments = $commentMapper->SelectAllByEventId((int)$id);

        $memberMapper = new MemberMapper();

        Application::$app->getRouter()->renderTemplate("view_event.html", [
            "event" => $event,
            "comments" => $comments,
            "memberMapper" => $memberMapper
        ]);;
    }

    public function showEditPage(Request $request): void
    {
        $eventId = (int)$request->params[0]; // Получаем ID события из параметров запроса

        $eventMapper = new EventMapper();
        $event = $eventMapper->Select($eventId);

        if ($event === null) {
            Application::$app->getRouter()->renderTemplate("404.html", ["error" => "Event not found"]);
            return;
        }

        // Проверка прав пользователя на редактирование
        if ($event->getUserId() !== $_SESSION['user']) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "You do not have permission to edit this event", "event" => $event]);
            return;
        }

        Application::$app->getRouter()->renderTemplate("edit_event.html", ["event" => $event]);
    }

    public function handleEdit(Request $request): void
    {
        $eventId = (int)$request->params[0]; // Получаем ID события из параметров запроса
        $body = $request->getBody();

        // Валидация данных
        if (empty($body['name']) || empty($body['short_description']) || empty($body['long_description']) ||
            empty($body['place']) || empty($body['date']) || empty($body['time']) || empty($body['image'])) {
            Application::$app->getRouter()->renderTemplate("edit_event.html", ["error" => "All fields are required", "event" => $body]);
            return;
        }

        $eventMapper = new EventMapper();
        $event = $eventMapper->Select($eventId);

        // Проверка прав пользователя на редактирование
        if ($event->getUserId() !== $_SESSION['user']) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "You do not have permission to edit this event", "event" => $event]);
            return;
        }

        // Обновление события
        $event->setName($body['name']);
        $event->setShortDescription($body['short_description']);
        $event->setLongDescription($body['long_description']);
        $event->setPlace($body['place']);
        $event->setDate($body['date']);
        $event->setTime($body['time']);
        $event->setImage($body['image']);

        try {
            $eventMapper->Update($event);
            // Редирект на страницу с изменённым событием
            header('Location: /event/' . $eventId);
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("edit_event.html", ["error" => "Error updating event: " . $e->getMessage(), "event" => $body]);
        }
    }

    public function handleDelete(Request $request): void
    {
        $eventId = (int)$request->params[0]; // Получаем ID события из параметров запроса

        $eventMapper = new EventMapper();
        $event = $eventMapper->Select($eventId);

        // Проверка, что событие существует и принадлежит текущему пользователю
        if ($event === null) {
            Application::$app->getRouter()->renderTemplate("404.html", ["error" => "Event not found"]);
            return;
        }

        if ($event->getUserId() !== $_SESSION['user']) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "You do not have permission to delete this event", "event" => $event]);
            return;
        }

        // Удаление события
        try {
            $eventMapper->Delete($eventId);
            // Редирект на главную страницу после успешного удаления
            header('Location: /main');
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "Error deleting event: " . $e->getMessage(), "event" => $event]);
        }
    }

    public function handleComment(Request $request): void
    {
        $segments = explode('/', trim($request->getUri(), '/'));
        $eventId = (int)end($segments);  // Получаем ID события из URL
        $body = $request->getBody();

        // Валидация данных
        if (empty($body['comment']) || empty($body['rate'])) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "All fields are required"]);
            return;
        }

        // Создание нового комментария
        $comment = new Comment(
            null,
            $body['comment'],
            (int)$body['rate'],
            $_SESSION['user'],
            $eventId
        );

        $commentMapper = new CommentMapper();
        try {
            $commentMapper->Insert($comment);
            header('Location: /event/' . $eventId);
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "Error adding comment: " . $e->getMessage()]);
        }
    }

    public function handleJoin(Request $request): void
    {
        $eventId = (int)$request->params[0];
        $userId = $_SESSION['user'];

        $memberMapper = new MemberMapper();

        if ($memberMapper->isUserJoined($userId, $eventId)) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "You are already participating in this event", "event" => $eventId]);
            return;
        }

        try {
            $memberMapper->joinEvent($userId, $eventId);
            header('Location: /event/' . $eventId);
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "Error joining event: " . $e->getMessage(), "event" => $eventId]);
        }
    }

    public function handleLeave(Request $request): void
    {
        $eventId = (int)$request->params[0];
        $userId = $_SESSION['user'];

        $memberMapper = new MemberMapper();

        if (!$memberMapper->isUserJoined($userId, $eventId)) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "You are not participating in this event", "event" => $eventId]);
            return;
        }

        try {
            $memberMapper->leaveEvent($userId, $eventId);
            header('Location: /event/' . $eventId);
            exit();
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("view_event.html", ["error" => "Error leaving event: " . $e->getMessage(), "event" => $eventId]);
        }
    }
}
