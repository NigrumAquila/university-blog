<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php

$colname_faculty = "-1";
if (isset($_GET['faculty'])) {
  $colname_faculty = (get_magic_quotes_gpc()) ? $_GET['faculty'] : addslashes($_GET['faculty']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_faculty = sprintf("SELECT fac_id, fac_abbrev, fac_name FROM facultys WHERE fac_id = %s", $colname_faculty);
$faculty = mysqli_query($studies_date, $query_faculty) or die($studies_date->error);
$row_faculty = mysqli_fetch_assoc($faculty);
$totalRows_faculty = mysqli_num_rows($faculty);




$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editfaculty")) {
  $updateSQL = sprintf("UPDATE `facultys` SET `fac_abbrev`=%s, `fac_name`=%s WHERE `fac_id`=%s",
                       GetSQLValueString($_POST['fac_abbrev'], "text"),
                       GetSQLValueString($_POST['fac_name'], "text"),
                       GetSQLValueString($_POST['fac_id'], "int"));
  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $updateSQL) or die($studies_date->error);

  $updateGoTo = "facultys";
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
  <title>Редактирование кафедры</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Редактирование кафедры</h1>

  <form action="<?php echo $editFormAction; ?>" method="POST" name="editfaculty" id="editfaculty" autocomplete="off">
  <p>Наименование: 
    <input name="fac_name" type="text" id="fac_name" class="border-bottom" value="<?php echo $row_faculty['fac_name'] ?>" size="50" maxlength="50">
</p>
  <p>Аббревиатура: 
   <input name="fac_abbrev" type="text" id="fac_abbrev" class="border-bottom" value="<?php echo $row_faculty['fac_abbrev'] ?>" size="10" maxlength="10">
   <input name="fac_id" type="hidden" id="fac_id" value="<?php echo $row_faculty['fac_id'] ?>">
   </p>
  <p>
    <input type="submit" name="Submit" id="Submit" value="Изменить">
    <input type="reset" name="Reset" id="Reset" value="Отмена">
</p>
  <input type="hidden" name="MM_update" value="editfaculty">
</form>
  <p><a href="facultys">На список кафедр</a> </p>
  <p><a href="/">На главную страницу</a> </p>
</body>
</html>

<?php
mysqli_free_result($faculty);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>