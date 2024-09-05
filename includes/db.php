<?php
$host = 'localhost';
$port = '3307';
$db = 'vintagevibe';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$username = "root";
$password = '';
$pdo = new PDO($dsn, $username, $password);

