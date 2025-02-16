<?php

declare(strict_types=1);

namespace app\core;

use app\exceptions\RouteException;

class Application
{
    public static Application $app;
    private Request $request;
    private Router $router;
    private Response $response;

    private Logger $logger;
    private Database $database;

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    public function __construct()
    {
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->response->setStatusCode(Response::HTTP_OK);
        $this->router = new Router($this->request, $this->response);
        $this->logger = new Logger(PROJECT_DIR."/runtime/logs/".$_ENV["APP_LOG"]);
        $this->database = new Database($_ENV["DB_DSN"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

    }

    /**
     * @throws RouteException Undefined method
     */
    public function setRoute(string $method, string $path, string|array $callback): void
    {
        switch ($method) {
            case Request::GET:
                $this->router->setGetRoute($path, $callback);
                break;

            case Request::POST:
                $this->router->setPostRoute($path, $callback);
                break;
            default:
            {
                $this->response->setStatusCode(Response::HTTP_SERVER_ERROR);
                $this->logger->error("Router: Unknown method");
                throw new RouteException($path, $method, "Unknown method");
            }
        }
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        session_start();

        $protectedRoutes = ['/main', '/event', '/create', '/profile'];
        if (in_array($this->request->getUri(), $protectedRoutes) && !isset($_SESSION['user'])) {
            $this->router->renderTemplate("register.html", ["error" => "Please register or login first"]);
            return;
        }

        try {
            $this->router->resolve();
        } catch (RouteException $e) {
            echo "Route error: " . $e->getMessage();

        } catch (\Exception $e) {
            echo "Generic error: " . $e->getMessage();
        }
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }


}