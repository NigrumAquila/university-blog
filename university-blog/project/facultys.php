<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

mysqli_select_db($studies_date, $database_studies_date);
$query_facultys = sprintf("SELECT * FROM facultys");
$facultys = mysqli_query($studies_date, $query_facultys) or die($studies_date->error);
$row_facultys = mysqli_fetch_assoc($facultys);
$totalRows_facultys = mysqli_num_rows($facultys);


$addFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $addFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfaculty")) {
  $insertSQL = sprintf("INSERT INTO facultys (fac_id, fac_abbrev, fac_name) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['fac_id'], "int"),
                       GetSQLValueString($_POST['fac_abbrev'], "text"),
                       GetSQLValueString($_POST['fac_name'], "text"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $insertSQL) or die($studies_date->error);

  $insertGoTo = "facultys";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Кафедры УТиИт</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Кафедры УТиИТ</h1>
	<p>&nbsp;</p>
	<hr>
<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>
	<p>&nbsp;</p>
<?php do { 
?>
	<div class="fullnote">
		<h2>
			<a href="lecturers?faculty=<?php echo $row_facultys['fac_id']; ?>">
			<?php echo $row_facultys['fac_name'] . " (" . $row_facultys['fac_abbrev'] . ")"; ?>
			</a>
		</h2>
    <?php if($isAorM) { ?>
    <p><a href="edit_faculty?faculty=<?php echo $row_facultys['fac_id']; ?>">Изменить</a> </p>
    <p><a href="delete_faculty?faculty=<?php echo $row_facultys['fac_id']; ?>">Удалить</a> </p>
  <?php } ?>
	</div>
<?php } while ($row_facultys = mysqli_fetch_assoc($facultys)); ?>            

<?php if ($totalRows_facultys == 0) { // Show if recordset empty ?>
<h3>Кафедр пока нет!</h3>
<?php } // Show if recordset empty ?>

<p>Кафедры с <?php echo "1" ?> по 
<?php echo $totalRows_facultys ?> </p>

<?php if($isAorM) { ?>

<p>&nbsp;</p>

<form action="<?php echo $addFormAction; ?>" method="POST" name="addfaculty" id="addfaculty" autocomplete="off">
  <p>Кафедра: 
    <input name="fac_name" type="text" id="fac_name" class="border-bottom" size="50" maxlength="50">
</p>
  <p>Аббревиатура: 
   <input name="fac_abbrev" type="text" id="fac_abbrev" class="border-bottom" size="10" maxlength="10">
   <input name="fac_id" type="hidden" id="fac_id" value="<?php echo $totalRows_facultys+1 ?>">
   </p>
  <p>
    <input type="submit" name="Submit" id="Submit" value="Добавить">
    <input type="reset" name="Reset" id="Reset" value="Отмена">
</p>
  <input type="hidden" name="MM_insert" value="addfaculty">
</form>

<?php } ?>

<p><a href="groups">На список групп</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($facultys);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>