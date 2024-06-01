<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\mappers\UserMapper;
use app\mappers\EventMapper;
use app\models\User;

class AuthController
{
    public function register(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("register.html", ["action" => "register"]);
    }

    public function handleRegister(Request $request): void
    {
        $body = $request->getBody();

        // Валидация данных
        if (empty($body['first_name']) || empty($body['second_name']) || empty($body['age']) || empty($body['email']) || empty($body['phone']) || empty($body['password'])) {
            Application::$app->getRouter()->renderTemplate("register.html", ["error" => "All fields are required"]);
            return;
        }

        $mapper = new UserMapper();
        $user = new User(null, $body['first_name'], $body['second_name'], (int)$body['age'], $body['email'], $body['phone'], $body['password']);

        try {
            $mapper->Insert($user);
            Application::$app->getRouter()->renderTemplate("login.html", ["message" => "Registration successful, please login"]);
        } catch (\Exception $e) {
            Application::$app->getRouter()->renderTemplate("register.html", ["error" => "Error during registration: " . $e->getMessage()]);
        }
    }
    public function login(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("login.html", ["action" => "login"]);
    }

    public function handleLogin(Request $request): void
    {
        $body = $request->getBody();

        if (empty($body['email']) || empty($body['password'])) {
            Application::$app->getRouter()->renderTemplate("login.html", ["error" => "Email and password are required"]);
            return;
        }

        $mapper = new UserMapper();
        $user = $mapper->findByEmail($body['email']);

        if ($user && password_verify($body['password'], $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            $eventMapper = new EventMapper();
            $events = $eventMapper->selectAll();
            Application::$app->getRouter()->renderTemplate("main.html", ["events" => $events]);
        } else {
            Application::$app->getRouter()->renderTemplate("login.html", ["error" => "Invalid email or password"]);
        }
    }

    public function logout(): void
    {
        session_destroy();
        Application::$app->getRouter()->renderTemplate("login.html", ["message" => "You have been logged out"]);
    }
}
