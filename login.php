<?php
require_once('connectvars.php');

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
	//Имя пользователя или его пароль не были введены,
	//поэтому отправляются заголовки аутентификации
	header('HTTP/1.1 401 Unauthorized');
	header('WWW-Authenticate: Basic realm="Mismatch"');
	exit('<h3>Mismatch</h3>Sorry,you must enter your password and username.');
}

//Соединение с базой данных
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Получение введенных пользователем данных для аутентификации
$user_username = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_USER']));
$user_password = mysqli_real_escape_string($dbc, trim($_SERVER['PHP_AUTH_PW']));

//Поиск имени пользователя и его пароля в базе данных 
$query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username' AND password = SHA('$user_password')";
$data = mysqli_query($dbc, $query);

if (mysqli_num_rows($data) == 1){
	//Процедура входа прошла нормально,присваиваем переменным значения
	//идентификатора пользователя и его пароля 
	$row = mysqli_fetch_array($data);
	$user_id = $row['user_id'];
	$username = $row['username'];
}

//Подтверждение успешного входа в приложение 
echo('<p class="login">You logged as ' . $username . '.</p>');
?>