<?php
ob_start();
require_once(dirname(__DIR__) . "/vendor/autoload.php");
$env_path = dirname(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable($env_path);
$dotenv->load();
require("../Models/Database.php");
require("../functions/functions.php");
require("../functions/middlewares.php");
if (getInstance("Database")->implementSchemaAndDatabase()) {
    redirect("");
}
ob_end_flush();
