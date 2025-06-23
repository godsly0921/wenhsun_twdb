<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// 初始化 .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
