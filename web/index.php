<?php

use app\controllers\AuthController;
use app\controllers\EventController;
use app\controllers\ErrorController;
use app\controllers\UserController;
use app\core\Application;
use app\core\ConfigParser;
use app\core\Request;
use app\exceptions\RouteException;

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|html?|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}
const PROJECT_DIR = __DIR__ . "/../";
require PROJECT_DIR . "/vendor/autoload.php";

spl_autoload_register(function ($classname) {
    require str_replace("app\\", PROJECT_DIR, $classname) . ".php";
});

date_default_timezone_set("Europe/Moscow");
ConfigParser::load();
$app_env = getenv("APP_ENV");
if ($app_env=="dev") {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    ini_set("log_errors", "1");
    ini_set("error_log", PROJECT_DIR."/runtime/logs/".getenv("PHP_LOG"));
}


//$router = $application->getRouter();


try {
    $application = new Application();

    $application->setRoute(Request::GET, "/", [new AuthController(), "register"]);
    $application->setRoute(Request::POST, "/register", [new AuthController(), "handleRegister"]);
    $application->setRoute(Request::GET, "/login", [new AuthController(), "login"]);
    $application->setRoute(Request::POST, "/login", [new AuthController(), "handleLogin"]);
    $application->setRoute(Request::GET, "/logout", [new AuthController(), "logout"]);

    $application->setRoute(Request::GET, "/main", [new EventController(), "mainPage"]);
    $application->setRoute(Request::POST, "/search", [new EventController(), "searchEvents"]);
    $application->setRoute(Request::POST, "/load-more", [new EventController(), "loadMoreEvents"]);
    $application->setRoute(Request::GET, "/create", [new EventController(), "createPage"]);
    $application->setRoute(Request::POST, "/create", [new EventController(), "handleCreate"]);

    $application->setRoute(Request::GET, "/event/{id}", [new EventController(), "viewEvent"]);
    $application->setRoute(Request::GET, "/event/{id}/edit", [new EventController(), "showEditPage"]);
    $application->setRoute(Request::POST, "/event/{id}/edit", [new EventController(), "handleEdit"]);
    $application->setRoute(Request::POST, "/event/{id}/delete", [new EventController(), "handleDelete"]);
    $application->setRoute(Request::POST, "/event/{id}/comment", [new EventController(), "handleComment"]);
    $application->setRoute(Request::POST, "/event/{id}/join", [new EventController(), "handleJoin"]);
    $application->setRoute(Request::POST, "/event/{id}/leave", [new EventController(), "handleLeave"]);

    $application->setRoute(Request::GET, "/profile", [new UserController(), "profilePage"]);

    $application->setRoute(Request::GET, "/404", [new ErrorController(), "notFound"]);

    $application->run();
} catch (RouteException $e) {
    // логирование ошибки
    exit;
}
//} catch (RouteException $e) {
//    echo "Route error: " . $e->getMessage();
//    // Перенаправление на 404
//    header("Location: /404");
//} catch (\Exception $e) {
//    echo "Generic error: " . $e->getMessage();
//    // Перенаправление на 404
//    header("Location: /404");
//}



//ob_start();
//
//ob_flush();