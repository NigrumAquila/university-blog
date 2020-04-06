<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php

$deleteFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $deleteFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST['student'])) && ($_POST['student'] != "")) {
  $deleteSQL = sprintf("DELETE FROM students WHERE stud_id=%s",
                       GetSQLValueString($_POST['student'], "int"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $deleteSQL) or die($studies_date->error);

  $deleteGoTo = "students_search";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_student = "-1";
if (isset($_GET['student'])) {
  $colname_student = (get_magic_quotes_gpc()) ? $_GET['student'] : addslashes($_GET['student']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_student= sprintf("SELECT * FROM students WHERE stud_id = %s", $colname_student);
$student = mysqli_query($studies_date, $query_student) or die($studies_date->error);
$row_student = mysqli_fetch_assoc($student);
$totalRows_student = mysqli_num_rows($student);

?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Удаление студента</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Удаление студента</h1>
<form action="" method="post" name="deletestudent" id="deletestudent">
<h3>Фамилия: <?php echo $row_student['surname']; ?>
</h3>
<h3>Имя: <?php echo $row_student['name']; ?>
</h3>
<h3>Отчество: <?php echo $row_student['patronymic']; ?>
</h3>
<p>
 <input name="student" type="hidden" id="student" value="<?php echo $row_student['stud_id']; ?>">
</p>
  <p>
    <input type="submit" name="Submit" value="Удалить" id="Delete">
</p>
</form>

  <p><a href="students_search">На список студентов</a> </p>
  <p><a href="/">На главную страницу</a> </p>
</body>
</html>

<?php
mysqli_free_result($student);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>