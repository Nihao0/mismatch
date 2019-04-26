<?php
require_once('appvars.php');
require_once('connectvars.php');


$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if(isset($_POST['submit'])){
//Извлечение данных профиля из суперглобального массива POST

$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
$password = mysqli_real_escape_string($dbc, trim($_POST['password1']));
$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));


if(!empty($username)) && (!empty($password1)) && (!empty($password2)) &&
($password1 == $password2){
//Проверка того,что никто из уже записанных пользователей не пользуется таким же именем,
//как то которое ввел новый пользователь

$query = "SELECT * FROM mismatch_user WHERE username = '$username'";
$data = mysqli_query($dbc, $query);
if(mysqli_num_rows($data) == 0){
//Имя введенное пользователем не используется поэтому добавляем данные в базу
$query = "INSERT INTO mismatch_user (username,password,joindate) VALUES" . 
		"('username',SHA('password'),NOW())";
mysqli_query($dbc, $query);


//Вывод подтверждения пользователю		

echo'<p>Your profile create.You can login and'.
'<a href="editprofile.php">edit your profile</a>.</p>';

mysqli_close($dbc);
exit();
}
else {
	//Учетная запись с таким именем уже существует в базе данных,поэтому выводится сообщение об ошибке
	
	echo'<p class="error">Please enter another name for your profile.</p>';
	$username = "";
	}
}
else {
	echo '<p class="error">You must enter all data to create profile,password twice.</p>';
	}
}
mysqli_close($dbc);
?>

<p>Enter,please your username and password
&quot;Mismatc&quot;\.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<fieldset> <legend>Enter date</legend>
	<label for="username
