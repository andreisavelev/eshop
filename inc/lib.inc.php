<?php
function addItemToCatalog($title, $author, $pubyear, $price){
	$sql = "INSERT INTO cataloge (title, author, pubyear, price)
									VALUES (?,?,?,?)";
// Создаем подготовленный запрос
$stmt = mysqli_prepare($link, $sql);
if(!$stmt){
	printf("Подготовленный запрос завершился неудачей: %s\n", mysqli_error($link));
	return false;
}
// Функция для обертывания подготовленного запроса
mysqli_stmt_bind_param($stmt, "ssii", $title, $author, $pubyear, $price);
// Исполнение подготовленного запроса
mysqli_execute($stmt);
// Закрытие соединения
mysqli_close($stmt);
}