<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php

$colname_group = "-1";
if (isset($_GET['group'])) {
  $colname_group = (get_magic_quotes_gpc()) ? $_GET['group'] : addslashes($_GET['group']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_group = sprintf("SELECT group_id, group_name FROM groups WHERE group_id = %s", $colname_group);
$group = mysqli_query($studies_date, $query_group) or die($studies_date->error);
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);




$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editgroup")) {
  $updateSQL = sprintf("UPDATE `groups` SET `group_name`=%s WHERE `group_id`=%s",
                       GetSQLValueString($_POST['group_name'], "text"),
                       GetSQLValueString($_POST['group_id'], "int"));
  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $updateSQL) or die($studies_date->error);

  $updateGoTo = "groups";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование группы</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Редактирование группы</h1>

  <form action="<?php echo $editFormAction; ?>" method="POST" name="editgroup" id="editgroup" autocomplete="off">
  <p>Наименование: 
    <input name="group_name" type="text" id="group_name" class="border-bottom" value="<?php echo $row_group['group_name'] ?>" size="50" maxlength="50">
  </p>
  <input name="group_id" type="hidden" id="group_id" value="<?php echo $row_group['group_id'] ?>">
  
<p>
    <input type="submit" name="Submit" id="Submit" value="Изменить">
    <input type="reset" name="Reset" id="Reset" value="Отмена">
</p>
  <input type="hidden" name="MM_update" value="editgroup">
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
?>