<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$servername = "localhost";
$database = "weather";
$username = "nastya.ignatova.99";
$password = "vigodu39";
// Создаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
