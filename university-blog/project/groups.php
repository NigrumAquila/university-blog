<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

mysqli_select_db($studies_date, $database_studies_date);
$query_groups = "SELECT * FROM groups ORDER BY `group_name` ASC";
$groups = mysqli_query($studies_date, $query_groups) or die($studies_date->error);
$row_groups = mysqli_fetch_assoc($groups);
$totalRows_groups = mysqli_num_rows($groups);

$addFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);

if (isset($_SERVER['QUERY_STRING'])) {
  $addFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addgroup")) {
  $insertSQL = sprintf("INSERT INTO groups (group_name) VALUES (%s)",
                       GetSQLValueString($_POST['group_name'], "text"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $insertSQL) or die(debug_backtrace());

  $insertGoTo = "groups";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Группы УТиИТ</title>
<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Группы студентов УТиИТ</h1>
<p>&nbsp;</p>
<hr>
<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>
<p align="center">&nbsp;</p>
<?php do { ?>
<div class="fullnote">
 <h2>
 	<a href="students?group=<?php echo $row_groups['group_id']; ?>">
 		<?php 
 			echo $row_groups['group_name']; 
 		?>
 	</a> 
 </h2>
 <?php if($isAorM) { ?>
  <p><a href="edit_groups?group=<?php echo $row_groups['group_id']; ?>">Изменить</a> </p>
  <p><a href="delete_group_blocade?group=<?php echo $row_groups['group_id']; ?>
  ">Удалить</a> </p>
 <?php } ?>
</div>
<?php } while ($row_groups = mysqli_fetch_assoc($groups)); ?>

<p>Группы с <?php echo "1" ?> по 
<?php echo $totalRows_groups ?> </p>
<?php if($isAorM) { ?>
<p>&nbsp;</p>

<form action="<?php echo $addFormAction; ?>" method="POST" name="addgroup" id="addgroup" autocomplete="off">
	<p>Группа: 
    	<input name="group_name" type="text" id="group_name" class="border-bottom" size="50" maxlength="50">
	</p>

	<p>
    	<input type="submit" name="Submit" id="Submit" value="Добавить">
    	<input type="reset" name="Reset" id="Reset" value="Отмена">
	</p>
  		<input type="hidden" name="MM_insert" value="addgroup">
</form>
<?php } ?>
<p><a href="/">На главную страницу</a> </p>
<p>&nbsp; </p>
</body>
</html>


<?php
mysqli_free_result($groups);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>
