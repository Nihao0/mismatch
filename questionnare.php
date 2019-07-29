<?php 
  require_once('startsession.php');
  
  
  $page_title='Questionnaire!';
  require_once('header.php');
  
  
  require_once('appvars.php');
  require_once('connectvars.php');
  
//
if (!isset($_SESSION['user_id'])) {
	echo '<p class="login">Please, <a href="login.php">enter in app </a>' .
	'to access this page.</p>';
exit();
}
//
require_once('navmenu.php');

//
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


//Если этот пользователь ни разу не вводил данные в анкету 
//добавление записей с пустыми значениями признаков несоответствия 
//в таблицу mismatch response


$query="SELECT * FROM mismatch_response WHERE user_id '" . $_SESSION['user_id'] . "'";
$data =mysqli_query($dbc,$query);
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
		$query = "INSERT INTO mismatch_response (user_id , topic_id) VALUES ('" . $_SESSION['user_id'] . "' , '$topic_id')";
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
		echo '<p>Your responses have been saved</p>';
	}
	
$query = "SELECT response_id, topic_id, response FROM mismatch_response WHERE user_id = '" . $_SESSION['user_id'] . "'";
$data = mysqli_query($dbc, $query);
$responses = array ();
while ($row = mysqli_fetch_array($data)) {
	//Извлечение наименования признаков несоответствия и принадлежности их к категориям
	//из таблицы mismatch_topic
$query2 = "SELECT name, category FROM mismatch_topic WHERE topic_id '" . $row['topic_id'] . "'";
$data2 = mysqli_query($dbc, $query2);
if (mysqli_num_rows($data2) == 1) {
	$row2 = mysqli_fetch_array($data2);
	$row['topic_name'] = $row2['name'];
	$row['category_name'] = $row2['category'];
	array_push($responses, $row);
}
}
mysqli_close($dbc);

//Создание формы Анкета путем прохождения в цикле массива с данными признаков соответствия 
echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<p> What do you feel about each topic? </p>';
$category = $responses[0]['category_name'];
echo '<fieldset><legend>' . $responses[0]['category_name'] . '</legend>';
foreach ($responses as $response) {
	//
	//
if ($category != $response['category_name']) {
	$category = $response['category_name'];
	echo '</fieldset><fieldset><legend>' . $response['category_name'] . '</legend>';
}
//
echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="'.
	$response['response_id'] . '">' . $response['topic_name'] . ':</label>';
echo '<input type="radio" id="' . $response['response_id'] . '"name="' . $response['response_id'] . 
	'"value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Addiction';
echo '<input type="radio" id="' . $response['response_id'] . '"name="' . $response['response_id'] . 
	'"value="2" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Disgust';
	}
echo '</fieldset>';
echo '<input type="submit" value="Save questionnary" name="submit" />';
echo '</form>';

require_once('footer.php');

?>
