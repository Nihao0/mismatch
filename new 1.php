<?php

$query = "SELECT response_id, topic_id, response FROM mismatch_response " .
			"WHERE user_id = '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc, $query);
$responses = array ();
while ($row = mysqli_fetch_array($data)) {
	//Извлечение наименования признаков несоответствия и принадлежности их к категориям
	//из таблицы mismatch_topic
$query2 = "SELECT name, category FROM mismatch_topic WHERE topic_id '" . $row['topic_id'] . "'";
$data2 = mysqli_query($dbc, $query2) ;
if (mysqli_num_rows($data2) == 1) {
	$row2 = mysqli_fetch_array($data2) ;
	$row = ['topic_name'] = $row2['name'];
	$row = ['category_name'] = $row2 ['category'];
	array_push($responses, $row);
}
}





//Если этот пользователь ни разу не вводил данные в анкету 
//добавление записей с пустыми значениями признаков несоответствия 
//в таблицу mismatch response


$query = "SELECT * FROM mismatch_response WHERE user_id '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc,$query);
if (mysqli_num_rows($data) == 0) {
    //Извлечение списка идентификаторов признаков несоответствия из таблицы mismatch_topic
	$query = "SELECT topic_id FROM mismatch_topic ORDER BY category_id, topic_id";
	$data = mysqli_query($dbc, $query);
	$topicIDs = array();
	while ($row = mysqli_fetch_array($data)) {
		array_push($topicIDs, $row['topic_id']);
	}
	//Добавление записей с пустыми значениями
	//признаков несоответствия в таблицу mismatch_response
	foreach ($topicIDs as $topic_id) {
		$query = "INSERT INTO mismatch_response 
		(user_id , topic_id) VALUES ('" . $_SESSION['user_id'] . "' , '$topic_id')";
		mysqli_query ($dbc, $query);
	}
}
//Если форма АНКЕТА отправлена на сервер для обработки 
//обновление признаков несоответствия в таблице mismatch_response
	
	if (isset ($_POST['submit'])) {
		foreach ($_POST as $response_id => $response) {
			$query = "UPDATE mismatch_response SET response = '$response' . WHERE response_id = '$response_id'";
			mysqli_query($dbc, $query);
		}
		echo '<p>Your suggestions saved</p>';
	}
