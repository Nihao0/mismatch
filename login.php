<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php
require_once('connectvars.php');

//Обнуление сообщения об ошибке
$error_msg = "";
//Если пользователь не вошел в приложение,попытка войти
if (!isset($_COOKIE['user_id'])) {
	
	if(isset($_POST['submit'])) {


//Соединение с базой данных
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Получение введенных пользователем данных для аутентификации
$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
if (!empty($user_username) && !empty($user_password)) {
//Поиск имени пользователя и его пароля в базе данных 
$query = "SELECT user_id, username FROM mismatch_user WHERE username = '$user_username' AND password = SHA('$user_password')";
$data = mysqli_query($dbc, $query);

if (mysqli_num_rows($data) == 1){
	//Процедура входа прошла нормально,присваиваем переменным значения
	//идентификатора пользователя и его пароля 
	$row = mysqli_fetch_array($data);
	setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30)); //срок действия 30 дней
	setcookie('username', $row['username'],  time() + (60 * 60 * 24 * 30)); //срок действия 30 дней
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
	header('Location: ' . $home_url);
}
else {
	//Имя пользователя или его пароль введены не верно создание сообщения об ошибке
	$error_msg = 'Sorry, you must enter your username and password/';
	}
}
else {	
	$error_msg = 'Sorry, you must enter your username and password/';
	}
  }
}	
?>
<html>
	<head>
		<title>Mismatch. Log in.</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<h3>Mismatch. Log in.</h3>

<?php
//Если куки не содержит данныйх,выводятся сообщение об ошибке
//и форма входа в приложение, в противном случае подтверждение входа
if (empty($_COOKIE['user_id'])) {
	echo '<p class="error"' . $error_msg . '</p>';
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Log in</legend>
		<label for="username">Username:</label>
		<input type="text" name="username"
			value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
		<label for="password">Password:</label>
		<input type="password" name="password" />
	</fieldset>
	<input type="submit" value="Log in" name="submit" />
</form>

<?php
}
else {
	//подтверждение успешного входа в приложение
	echo('<p class="login">You logged as ' . $_COOKIE['username'] . '.</p>');
}
?>
</body>
<html>
