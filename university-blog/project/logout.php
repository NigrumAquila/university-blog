<?php

if(!isset($_SESSION)) {
  	session_start();
}

$logoutAction = str_replace(".php", "", $_SERVER['PHP_SELF']) . "?doLogout=true";
if((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
	$logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}
if((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
	$_SESSION['MM_Username'] = NULL;
	$_SESSION['MM_UserGroup'] = NULL;
 	$_SESSION['PrevUrl'] = NULL;
 	unset($_SESSION['MM_Username']);
 	unset($_SESSION['MM_UserGroup']);
 	unset($_SESSION['PrevUrl']);
 	$logoutGoTo = "/";
 	if($logoutGoTo) {
 		header("Location: $logoutGoTo");
 		exit;
 	}
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Выход</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h2 align="center">Завершение сеанса работы посетителя с сайтом "Обучение студентов УТиИТ"</h2>
	<p><a href="<?php echo $logoutAction; ?>">Выйти</a></p>
	<p><a href="/">На главную страницу</a></p>
	
</body>
</html>