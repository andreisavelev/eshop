<?php
function clearInt($data){
	//Возвращает только положительные числа
	return abs((int)$data);
}

function clearStr($data){
	global $link;
	return mysqli_real_escape_string($link, trim(strip_tags($data)));
}

function addItemToCatalog($title, $author, $pubyear, $price){
	// Импортируеми соединение в глобальном контексте! Всегда!
	global $link;
	$sql = "INSERT INTO catalog(
							title, 
							author, 
							pubyear, 
							price)
					VALUES (?, ?, ?, ?)";
	// Создаем подготовленный запрос
	if(!$stmt = mysqli_prepare($link, $sql))
		return false;
	// Функция для обертывания подготовленного запроса
	mysqli_stmt_bind_param($stmt, "ssii", $title, $author, $pubyear, $price);
	// Исполнение подготовленного запроса
	mysqli_stmt_execute($stmt);
	// Закрытие соединения
	mysqli_stmt_close($stmt);
	return true;
}
// Выборка всех товаров из базы
function selectAllItems(){
	global $link;
	$sql = 'SELECT id, title, author, pubyear, price FROM catalog';
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = mysqli_fetch_all($result, MYSQL_ASSOC);
	mysqli_free_result($result);
	return $items;
}

function saveBasket(){
	global $basket;
	$basket = base64_encode(serialize($basket));
	setcookie('basket', $basket, 0x7FFFFFFF);
}
// Инициализация корзины, если пользователь зашел впервые ставим куку, если кука есть, читаем куку.
function basketInit(){
	global $basket, $count;
	if(!isset($_COOKIE['basket'])){
		$basket = array('orderid' => uniqid());
		saveBasket();
	}else{
		$basket = unserialize(base64_decode($_COOKIE['basket']));
		$count = count($basket) - 1;
	}
}
// Добавление товара в корзину
function add2Basket($id, $q){
	global $basket;
	$basket[$id] = $q;
	saveBasket();
}
// Выборка товара из корзины покупателя
function myBasket(){
	global $basket, $link;
	$goods = array_keys($basket);
	$ids = implode(',', $goods);
	$sql = 'SELECT id, title, author, pubyear, price FROM catalog WHERE id IN ($ids)';
	if(!$result = mysqli_query($link, $sql))
		return false;
	$items = result2Array($result);
	mysqli_free_result($result);
	return $items;
}
// Функция, которая принимает результат myBasket() и возвращает ассоциативный массив товаров, дополненный их количеством
function result2Array($data){
	global $basket;
	$arr = array();
	while($row = mysqli_fetch_assoc($data)){
		$row['quantity'] = $basket[$row['id']];
		$arr[] = $row;
	}
	return $arr;
}
