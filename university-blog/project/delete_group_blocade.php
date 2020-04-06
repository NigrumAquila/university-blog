<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php


$deleteFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $deleteFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['group'])) && ($_POST['group'] != "")) {
  $deleteSQL = sprintf("DELETE FROM groups WHERE group_id=%s",
                       GetSQLValueString($_POST['group'], "int"));

  $checkSQL = sprintf("SELECT stud_id FROM students WHERE group_name = %s",
                       GetSQLValueString($_POST['group'], "int"));

  mysqli_select_db($studies_date, $database_studies_date);
  $R = mysqli_query($studies_date, "LOCK TABLES students WRITE, groups WRITE") or die($studies_date->error);
  $check = mysqli_query($studies_date, $checkSQL) or die($studies_date->error);
  if(!($row_check = mysqli_fetch_assoc($check))) {
  $Result1 = mysqli_query($studies_date, $deleteSQL) or die($studies_date->error);
  }

  $R = mysqli_query($studies_date, "UNLOCK TABLES");

  if(!($row_check)) {
    $deleteGoTo = "groups";
  } else {
    $deleteGoTo = "know_group";
  }

//  $deleteGoTo = "groups";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_group = "-1";
if (isset($_GET['group'])) {
  $colname_group = (get_magic_quotes_gpc()) ? $_GET['group'] : addslashes($_GET['group']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_group = sprintf("SELECT * FROM groups WHERE group_id = %s", $colname_group);
$group = mysqli_query($studies_date, $query_group) or die($studies_date->error);
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);



?>


<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Удаление группы</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Удаление группы</h1>
	<form action="" method="post" name="deletegroup" id="deletegroup">
		<h3>Наименование: <?php echo $row_group['group_name']; ?></h3>
			<input type="hidden" name="group" id="group" value="<?php echo $row_group['group_id']; ?>">
		</h3>
		<p>
			<input type="submit" name="Submit" value="Удалить" id="Delete">
		</p>
	</form>
  <p><a href="groups">На список групп</a> </p>
  <p><a href="/">На главную страницу</a> </p>
</body>
</html>

<?php
mysqli_free_result($group);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
if(isset($check)) {
  mysqli_free_result($check);
}
if(isset($R)) {
  mysqli_free_result($R);
}
?>