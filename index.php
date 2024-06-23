<?php

require_once("vendor/autoload.php");
require_once("src/active-user.php");
require_once("constants.php");

// Load environment variables
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$dotenv->required(["DB_HOST", "DB_PORT", "DB_USER", "DB_PASSWORD", "DB_NAME"]);

// Create database model instance
require_once("src/database-model.php");
$db = new DatabaseModel();

// Start new session
session_start();

// Layout
require_once("layout/header.php");

if (isset($_GET["action"]) && file_exists("views/{$_GET["action"]}.php")) {
	require_once("views/{$_GET["action"]}.php");
} else {
	require_once("views/main.php");
}

require_once("layout/footer.php");
