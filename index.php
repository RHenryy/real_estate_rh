<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//requires
require_once((__DIR__) . "/vendor/autoload.php");
$env_path = dirname(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable($env_path);
$dotenv->load();
require("functions/functions.php");
require("functions/middlewares.php");
require("Router/Router.php");
require("Models/Database.php");
require_once("Controllers/RenderController.php");
// requireModels();
// requireControllers();
//database initialization and migration, and redirect to home if successfull
if (getInstance()->firstInitialization()) {
    // Router
    getInstance("Router")->handle($_SERVER["REQUEST_URI"]);
    $_SESSION['flash_message'] = [];
}
