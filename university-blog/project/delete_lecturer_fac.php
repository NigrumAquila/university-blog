<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php

$deleteFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $deleteFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['lecturer'])) && ($_POST['lecturer'] != "")) {
  $deleteSQL = sprintf("DELETE FROM lecturers WHERE lec_id=%s",
                       GetSQLValueString($_POST['lecturer'], "int"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $deleteSQL) or die($studies_date->error);

  $deleteGoTo = "lecturers";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_lecturer = "-1";
if (isset($_GET['lecturer'])) {
  $colname_lecturer = (get_magic_quotes_gpc()) ? $_GET['lecturer'] : addslashes($_GET['lecturer']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_lecturer= sprintf("SELECT * FROM lecturers WHERE lec_id = %s", $colname_lecturer);
$lecturer = mysqli_query($studies_date, $query_lecturer) or die($studies_date->error);
$row_lecturer = mysqli_fetch_assoc($lecturer);
$totalRows_lecturer = mysqli_num_rows($lecturer);


$colname_faculty = "-1";
if (isset($_GET['faculty'])) {
  $colname_faculty = (get_magic_quotes_gpc()) ? $_GET['faculty'] : addslashes($_GET['faculty']);
}
$query_faculty = sprintf("SELECT * FROM facultys WHERE fac_id = %s", $colname_faculty);
$faculty = mysqli_query($studies_date, $query_faculty) or die($studies_date->error);
$row_faculty = mysqli_fetch_assoc($faculty);
$totalRows_faculty = mysqli_num_rows($faculty);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Удаление группы</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Удаление преподавателя</h1>
<form action="" method="post" name="deletelecturer" id="deletelecturer">
<h3>Фамилия: <?php echo $row_lecturer['surname']; ?>
</h3>
<h3>Имя: <?php echo $row_lecturer['name']; ?>
</h3>
<h3>Отчество: <?php echo $row_lecturer['patronymic']; ?>
</h3>
<p>
 <input name="lecturer" type="hidden" id="lecturer" value="<?php echo $row_lecturer['lec_id']; ?>">
</p>
  <p>
    <input type="submit" name="Submit" value="Удалить" id="Delete">
</p>
</form>

<p><a href="lecturers?faculty=<?php echo $row_lecturer['faculty']; ?>">На список преподавателей кафедры <?php echo $row_faculty['fac_name']; ?></a> </p>
<p><a href="/">На главную страницу</a> </p>
</body>
</html>

<?php
mysqli_free_result($lecturer);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>