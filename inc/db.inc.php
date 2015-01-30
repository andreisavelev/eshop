<?php
header("Content-Type: text/html; charset=utf-8");
// Расположение базы данных
define('DB_HOST', 'localhost');
// Логин для подключения к базе данных
define('DB_LOGIN', 'root');
// Пароль для подключения к базе данных
define('DB_PASSWORD', '');
// Имя базы даннх
define('DB_NAME', 'eshop');
// В логе сохраняем имя файла с данными пользователям
define('ORDERS_LOG', 'orders.log');
//Хранение корзины пользователя
$basket = array();
// Хранение корзины товаров в корзине пользователя
$count = 0;
// Подключение к базе данных в перменной лежит ресурс
$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME) or die(mysqli_error($link));