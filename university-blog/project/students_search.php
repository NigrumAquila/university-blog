<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

$keyword_group = "";
$keyword_number = "";
$keyword_surname = "";
$keyword_name = "";
$keyword_patronymic = "";
$keyword_gender = "";
$keyword_birhday = "";

if(isset($_GET['keyword_group'])) {
  $keyword_group = $_GET['keyword_group'];
}
if(isset($_GET['keyword_number'])) {
  $keyword_number = $_GET['keyword_number'];
}
if(isset($_GET['keyword_surname'])) {
  $keyword_surname = $_GET['keyword_surname'];
}
if(isset($_GET['keyword_name'])) {
  $keyword_numberame = $_GET['keyword_name'];
}
if(isset($_GET['keyword_patronymic'])) {
  $keyword_patronymic = $_GET['keyword_patronymic'];
}
if(isset($_GET['keyword_gender'])) {
  $keyword_gender = $_GET['keyword_gender'];
}
if(isset($_GET['keyword_birhday'])) {
  $keyword_birhday = $_GET['keyword_birhday'];
}

mysqli_select_db($studies_date, $database_studies_date);

$query_students = "SELECT students.stud_id, groups.group_name, number, surname, name, patronymic, gender, birthday 
FROM students, groups WHERE students.group_name = groups.group_id %s %s %s %s %s %s %s
ORDER BY groups.group_name, name ASC";

$search_query_lecturers_group = "";
 if ($keyword_group) {
  $search_query_lecturers_group = sprintf("AND groups.group_name LIKE '%%%s%%' ",  $keyword_group);
}
$search_query_lecturers_number = "";
 if ($keyword_number) {
  $search_query_lecturers_number = sprintf("AND number LIKE '%%%s%%' ",  $keyword_number);
}
$search_query_lecturers_surname = "";
 if ($keyword_surname) {
  $search_query_lecturers_surname = sprintf("AND surname LIKE '%%%s%%' ",  $keyword_surname);
}
$search_query_lecturers_name = "";
 if ($keyword_name) {
  $search_query_lecturers_name = sprintf("AND name LIKE '%%%s%%' ",  $keyword_name);
}
$search_query_lecturers_patronymic = "";
 if ($keyword_patronymic) {
  $search_query_lecturers_patronymic = sprintf("AND patronymic LIKE '%%%s%%' ",  $keyword_patronymic);
}
$search_query_lecturers_gender = "";
 if ($keyword_gender) {
  $search_query_lecturers_gender = sprintf("AND gender LIKE '%%%s%%' ",  $keyword_gender);
}
$search_query_lecturers_birthday = "";
 if ($keyword_birhday) {
  $search_query_lecturers_birthday = sprintf("AND birthday LIKE '%%%s%%' ",  $keyword_birhday);
}

$query_students = sprintf($query_students, $search_query_lecturers_group, 
$search_query_lecturers_number, $search_query_lecturers_surname, 
$search_query_lecturers_name, $search_query_lecturers_patronymic,
$search_query_lecturers_gender, $search_query_lecturers_birthday);

$students = mysqli_query($studies_date, $query_students) or die($studies_date->error);
$row_students = mysqli_fetch_assoc($students);
$totalRows_students = mysqli_num_rows($students);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Студенты УТиИТ</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Полный список студентов УТиИТ</h1>
  <h3>Поиск:</h3>
   <table border="0" cellspacing="2" cellpadding="1">
            <tr class="tr-nohover">
              <th width="153px" scope="col">Группа</th>
              <th width="153px" scope="col">Зачетная книжка</th>
              <th width="153px" scope="col">Фамилия </th>
              <th width="153px" scope="col">Имя</th>
              <th width="153px" scope="col">Отчество</th>
              <th width="153px" scope="col">Пол</th>
              <th width="153px" scope="col">День рождения</th>
        </tr>
</table>    
 <form action="students_search" method="get" enctype="text/plain" name="search" id="search" autocomplete="off">
 
   <input name="keyword_group" type="text" id="keyword_group" class="border-bottom" value="<?php echo $keyword_group; ?>" size="13">    
   <input name="keyword_number" type="text" id="keyword_number" class="border-bottom" value="<?php echo $keyword_number; ?>"size="13">
    <input name="keyword_surname" type="text" id="keyword_surname" class="border-bottom" value="<?php echo $keyword_surname; ?>"size="13">
    <input name="keyword_name" type="text" id="keyword_name" class="border-bottom" value="<?php echo $keyword_name; ?>"size="13" maxlength="30">
    <input name="keyword_patronymic" type="text" id="keyword_patronymic" class="border-bottom" value="<?php echo $keyword_patronymic; ?>"size="13"> 
    <input name="keyword_gender" type="text" id="keyword_gender" class="border-bottom" value="<?php echo $keyword_gender; ?>"size="13"> 
    <input name="keyword_birhday" type="text" id="keyword_birhday" class="border-bottom" value="<?php echo $keyword_birhday; ?>"size="13"> 
    <input type="submit" name="Submit" value="Найти" id="Submit">    

</form>

<form action="students_search" method="get" enctype="text/plain" name="sarch" id="sarch">
    <input name="keyword_group" type="hidden" value="">
    <input name="keyword_number" type="hidden" value="">
  <input name="keyword_surname" type="hidden" value="">
  <input name="keyword_name" type="hidden" value="">
  <input name="keyword_patronymic" type="hidden" value="">
  <input name="keyword_gender" type="hidden" value="">
  <input name="keyword_birhday" type="hidden" value="">
  <input type="submit" name="Submit" value="Все" id="Submit" style="margin-top: 10px;">
  
</form>
	<p>&nbsp;</p>
	<hr>

<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>

  <?php if ($totalRows_students > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="1%" scope="col">Номер</th>
              <th width="8%" scope="col">Группа</th>
              <th width="10%" scope="col">Зачетная книжка</th>
              <th width="22%" scope="col">Фамилия </th>
              <th width="15%" scope="col">Имя</th>
              <th width="24%" scope="col">Отчество</th>
              <th width="5%" scope="col">Пол</th>
              <th width="15%" scope="col">День рождения</th>
              <?php if($isAorM) { ?><th width="5%" scope="col">Администрирование</th><?php } ?>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="center"><?php echo $i; ?></td>
              <td><?php echo $row_students['group_name']; ?></td>
              <td><?php echo $row_students['number']; ?></td>
              <td><?php echo $row_students['surname']; ?></td>
              <td><?php echo $row_students['name']; ?></td>
              <td><?php echo $row_students['patronymic']; ?></td>
              <td><?php echo $row_students['gender']; ?></td>
              <td><?php echo $row_students['birthday']; ?></td>
              <?php if($isAorM) { ?>
              <td>
                <a href="edit_student?student=<?php echo $row_students['stud_id']; ?>">Изменить</a>| 
                <a href="delete_student?student=<?php echo $row_students['stud_id']; ?>">Удалить</a>
              </td>
              <?php } ?>
            </tr>
<?php $i++ ?>       
<?php } while ($row_students = mysqli_fetch_assoc($students)); ?>            
          </table>
<?php } // Show if recordset not empty ?> 

<?php if ($totalRows_students == 0) { // Show if recordset empty ?>
<h3>Студентов пока нет!</h3>
<?php } // Show if recordset empty ?>

<p><a href="groups">На список групп</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($students);
?>