<?php 
//Генерирование навигационного меню
echo '<hr />';
if (isset($_SESSION['username'])) {
  echo '<a href="index.php">Main page</a> &#10084; ';
  echo '<a href="viewprofile.php">View Profile</a> &#10084; ';
  echo '<a href="editprofile.php">Edit Profile</a> &#10084; ';
  echo '<a href="logout.php">logout  (' . $_SESSION['username'] . ')</a>';
  }
  else {
	   echo '&#10084; <a href="login.php">Login</a> &#10084; ';
	   echo '<a href="signup.php">Sign up</a>';
  }
  echo '<hr />';
  ?>