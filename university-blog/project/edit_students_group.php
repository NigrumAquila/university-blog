<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php

$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}



$colname_student = "-1";
if (isset($_GET['student'])) {
  $colname_student = (get_magic_quotes_gpc()) ? $_GET['student'] : addslashes($_GET['student']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_student = sprintf("SELECT * FROM students WHERE stud_id=%s", $colname_student);
$student = mysqli_query($studies_date, $query_student) or die($studies_date->error);
$row_student = mysqli_fetch_assoc($student);
$totalRows_student = mysqli_num_rows($student);


$query_groups = "SELECT * from groups";
$groups = mysqli_query($studies_date, $query_groups) or die($studies_date->error);
$row_groups = mysqli_fetch_assoc($groups);
$totalRows_groups = mysqli_num_rows($groups);

$query_group_name = sprintf("SELECT groups.group_name FROM groups, students WHERE stud_id = %s AND students.group_name = groups.group_id", $colname_student);
$group_name = mysqli_query($studies_date, $query_group_name) or die($studies_date->error);
$row_group_name = mysqli_fetch_assoc($group_name);




if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editstudent")) {
  $updateSQL = sprintf("UPDATE `students` SET `group_name`=%s, `number`=%s, `surname`=%s, `name`=%s, `patronymic`=%s, `gender`=%s, `birthday`=%s
                       WHERE `stud_id`=%s",
                       GetSQLValueString($_POST['group_name'], "int"),
                       GetSQLValueString($_POST['number'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['patronymic'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['birthday'], "date"),
                       GetSQLValueString($_POST['stud_id'], "int"));
mysqli_select_db($studies_date, $database_studies_date);
$Result1 = mysqli_query($studies_date, $updateSQL) or die($studies_date->error);

$updateGoTo = "students?group=" . $row_student['group_id'];
  header(sprintf("Location: %s", $updateGoTo));
}

?>




<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование студента</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>

  <h1>Редактирование студента</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="editstudent" id="editstudent" autocomplete="off">
  <p>Группа: 
    <select name="group_name" id="group_name">
      <?php
do {  
?>
      <option value="<?php echo $row_groups['group_id']; ?>" 
        <?php echo ($row_student['group_name'] == $row_groups['group_id']) ? "selected" : ""; ?>
      >
     <?php echo $row_groups['group_name']; ?></option>
      <?php
} while ($row_groups = mysqli_fetch_assoc($groups));
  $rows = mysqli_num_rows($groups);
  if($rows > 0) {
      mysqli_data_seek($groups, 0);
    $row_groups = mysqli_fetch_assoc($groups);
  }
?>
    </select>
</p>
<p>Номер зачетной книжки: 
   <input name="number" type="text" id="number" class="border-bottom" value="<?php echo $row_student['number']; ?>" size="15" maxlength="15">
</p>
  <p>Фамилия: 
   <input name="surname" type="text" id="surname" class="border-bottom" value="<?php echo $row_student['surname']; ?>" size="15" maxlength="15">
</p>
  <p>Имя: 
   <input name="name" type="text" id="name" class="border-bottom" value="<?php echo $row_student['name']; ?>" size="15" maxlength="15">
</p>
  <p>Отчество: 
   <input name="patronymic" type="text" id="patronymic" class="border-bottom" value="<?php echo $row_student['patronymic']; ?>" size="20" maxlength="20">
</p>
  <p>Пол: 
   <input name="gender" type="radio" id="gender" value="<?php echo $row_student['gender']; ?>" size="20" maxlength="20" <?php echo ($row_student['gender']== 'м') ?  "checked" : "" ;  ?>>
   <label for="gender">мужской</label>
   <input name="gender" type="radio" id="gender" value="<?php echo $row_student['gender']; ?>" size="20" maxlength="20" <?php echo ($row_student['gender']== 'ж') ?  "checked" : "" ;  ?>>
   <label for="gender">женский</label>
</p>
<p>День рождения: 
   <input name="birthday" type="date" id="birthday" value="<?php echo $row_student['birthday']; ?>" size="20" maxlength="20">
</p>

<p>
    <input name="stud_id" type="hidden" id="stud_id" value="<?php echo $row_student['stud_id']; ?>">
  </p>
  <p>
    <input type="submit" name="Submit" value="Ввод" id="Submit">
    <input type="reset" name="Reset" value="Отмена" id="Reset"> 
  </p>
  <input type="hidden" name="MM_update" value="editstudent">
</form>

<p><a href="students?group=<?php echo $row_student['group_name']; ?>">На список студентов группы <?php echo $row_group_name['group_name']; ?></a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($student);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>