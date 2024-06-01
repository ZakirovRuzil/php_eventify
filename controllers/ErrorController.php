<?php

namespace app\controllers;

use app\core\Application;
use app\core\Request;

class ErrorController
{
    public function notFound(Request $request): void
    {
        Application::$app->getRouter()->renderTemplate("not_found.html");
    }
}
