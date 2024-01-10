<?php

class Router
{
    private array $routes = [];
    public function handle(string $uri)
    {

        if ($uri !== "/") {
            $route = $uri;
            $routeToCall = array_values(array_filter(explode("/", $uri)))[0];
        } else {
            $route = "/";
            $routeToCall = "/";
        }
        $this->routes = $this->getRoutes($routeToCall);
        $id = explode("/", $route)[3] ?? null;
        if ($id !== null) {
            if ((!stripos($id, "-") && !is_numeric($id) && $id !== null)) {
                return redirect(404);
            }
        }
        foreach ($this->routes as $route => $controllerToCall) {
            if (str_contains($route, "{id}") && !empty($id)) {
                $route = str_replace("{id}", $id, $route);
            }
            if ($route === $uri) {
                [$controller, $method] = explode("@", $controllerToCall);
                return getInstance(ucfirst($controller))->$method($id);
            }
        }
        return redirect(404);
    }
    public function getRoutes($route): ?array
    {
        if (stripos($route, "my") === 0) {
            $route = substr($route, 2);
        }
        switch ($route) {
            case "/":
                include("Router/homeRoutes.php");
                return $data;
            case "agencies":
                include("Router/agenciesRoutes.php");
                return $data;
            case "agency":
                include("Router/agenciesRoutes.php");
                return $data;
            case "properties":
                include("Router/propertiesRoutes.php");
                return $data;
            case "register":
                include("Router/registerRoutes.php");
                return $data;
            case "login":
                include("Router/loginRoutes.php");
                return $data;
            case "users":
                include("Router/usersRoutes.php");
                return $data;
            case "applications":
                include("Router/applicationsRoutes.php");
                return $data;
            case "dashboard":
                include("Router/dashboardRoutes.php");
                return $data;
            case "partners":
                include("Router/partnersRoutes.php");
                return $data;
            case "disconnect":
                include("Router/disconnect.php");
                return $data;
            case is_numeric($route):
                include("Router/errorRoutes.php");
                return $data;
            default:
                return redirect(404);
        }
    }
}
