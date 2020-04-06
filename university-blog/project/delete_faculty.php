<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php


$deleteFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $deleteFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['faculty'])) && ($_POST['faculty'] != "")) {
  $deleteSQL = sprintf("DELETE FROM facultys WHERE fac_id=%s",
                       GetSQLValueString($_POST['faculty'], "int"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $deleteSQL) or die($studies_date->error);

  $deleteGoTo = "facultys";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_faculty = "-1";
if (isset($_GET['faculty'])) {
  $colname_faculty = (get_magic_quotes_gpc()) ? $_GET['faculty'] : addslashes($_GET['faculty']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_faculty = sprintf("SELECT * FROM facultys WHERE fac_id = %s", $colname_faculty);
$faculty = mysqli_query($studies_date, $query_faculty) or die($studies_date->error);
$row_faculty = mysqli_fetch_assoc($faculty);
$totalRows_faculty = mysqli_num_rows($faculty);



?>


<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Удаление кафедры</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Удаление кафедры</h1>
	<form action="" method="post" name="deletefaculty" id="deletefaculty">
		<h3>Наименование: <?php echo $row_faculty['fac_name']; ?></h3>
		<h3>Аббревиатура: <?php echo $row_faculty['fac_abbrev']; ?>
			<input type="hidden" name="faculty" id="faculty" value="<?php echo $row_faculty['fac_id']; ?>">
		</h3>
		<p>
			<input type="submit" name="Submit" value="Удалить" id="Delete">
		</p>
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