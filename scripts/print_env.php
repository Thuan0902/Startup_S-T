<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();
echo 'host=' . getenv('DB_HOST') . "\n";
echo 'user=' . getenv('DB_USERNAME') . "\n";
echo 'pass=' . (getenv('DB_PASSWORD') ?: '(empty)') . "\n";
