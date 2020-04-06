<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php

function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

if(!isset($_SESSION)) {
  	session_start();
}

$loginFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if(isset($_GET['accesscheck'])) {
	$_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if(isset($_POST['name'])) {
	$loginUsername = $_POST['name'];
	$password = $_POST['password'];
	$MM_fldUserAuthorization = "rights";
	$MM_redirectLoginSuccess = "/";
	$MM_redirectLoginFailed = "/";
	$MM_redirecttoReferrer = false;

	mysqli_select_db($studies_date, $database_studies_date);
	$LoginRS__query = sprintf("SELECT name, password, rights 
		FROM users WHERE name = %s AND password = %s", 
		GetSQLValueString($loginUsername, "text"),
		GetSQLValueString($password, "text"));
	$LoginRS = mysqli_query($studies_date, $LoginRS__query) or die($studies_date->error);
	$loginFoundUser = mysqli_num_rows($LoginRS);
	if($loginFoundUser) {
		$loginStrGroup = mysqli_result($LoginRS, 0, 2);
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_UserGroup'] = $loginStrGroup;

		if(isset($_SESSION['PrevUrl'])) {
			$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
		}
		header("Location: " . $MM_redirectLoginSuccess);
	}
	else {
		header("Location: " . $MM_redirectLoginFailed);
	}
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Вход</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h2 align="center">Вход на сайт "Обучение студентов УТиИТ"</h2>
	<p>Авторизация посетителя:</p>
	<form action="<?php echo $loginFormAction; ?>" method="POST" name="login" id="login" autocomplete="off">
		<p>Имя:
			<input type="text" name="name" id="name" class="border-bottom" size="20" maxlength="20">
		</p>
		<p>Пароль:
			<input type="password" name="password" id="password" class="border-bottom" value="" size="20" maxlength="20">
		</p>
		<p>
			<input type="submit" name="Submit" id="Submit" value="Войти">
		</p>
	</form>
	<a href="/">На главную страницу</a>
</body>
</html>

<?php
if(isset($LoginRS)) {
  mysqli_free_result($LoginRS);
}
?>