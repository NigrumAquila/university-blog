<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));

$keyword_group = "";
$keyword_subject = "";
$keyword_surname = "";
$keyword_name = "";
$keyword_patronymic = "";
$keyword_exam_test = "";

if(isset($_GET['keyword_group'])) {
  $keyword_group = $_GET['keyword_group'];
}
if(isset($_GET['keyword_subject'])) {
  $keyword_subject = $_GET['keyword_subject'];
}
if(isset($_GET['keyword_surname'])) {
  $keyword_surname = $_GET['keyword_surname'];
}
if(isset($_GET['keyword_name'])) {
  $keyword_name = $_GET['keyword_name'];
}
if(isset($_GET['keyword_patronymic'])) {
  $keyword_patronymic = $_GET['keyword_patronymic'];
}
if(isset($_GET['keyword_exam_test'])) {
  $keyword_exam_test = $_GET['keyword_exam_test'];
}

mysqli_select_db($studies_date, $database_studies_date);

$query_subjects = "SELECT groups.group_name, subjects.subj_name, lecturers.surname, 
lecturers.name, lecturers.patronymic, groups_subjects.exam_test, groups_subjects.gr_sub_id
FROM groups, subjects, lecturers, groups_subjects
WHERE groups_subjects.group_name = groups.group_id 
AND groups_subjects.subject = subjects.subj_id
AND groups_subjects.lecturer = lecturers.lec_id 
%s %s %s %s %s %s
ORDER BY surname ASC";

$search_query_subjects_group = "";
 if ($keyword_group) {
  $search_query_subjects_group = sprintf("AND groups.group_name LIKE '%%%s%%' ",  $keyword_group);
}
$search_query_subjects_subject = "";
 if ($keyword_subject) {
  $search_query_subjects_subject = sprintf("AND subjects.subj_name LIKE '%%%s%%' ",  $keyword_subject);
}
$search_query_subjects_surname = "";
 if ($keyword_surname) {
  $search_query_subjects_surname = sprintf("AND lecturers.surname LIKE '%%%s%%' ",  $keyword_surname);
}
$search_query_subjects_name = "";
 if ($keyword_name) {
  $search_query_subjects_name = sprintf("AND lecturers.name LIKE '%%%s%%' ",  $keyword_name);
}
$search_query_subjects_patronymic = "";
 if ($keyword_patronymic) {
  $search_query_subjects_patronymic = sprintf("AND lecturers.patronymic LIKE '%%%s%%' ",  $keyword_patronymic);
}
$search_query_subjects_exam_test = "";
 if ($keyword_exam_test) {
  $search_query_subjects_exam_test = sprintf("AND groups_subjects.exam_test LIKE '%%%s%%' ",  $keyword_exam_test);
}

$query_subjects = sprintf($query_subjects, $search_query_subjects_group, 
$search_query_subjects_subject, $search_query_subjects_surname, 
$search_query_subjects_name, $search_query_subjects_patronymic,
$search_query_subjects_exam_test);

$subjects = mysqli_query($studies_date, $query_subjects) or die('error');
$row_subjects = mysqli_fetch_assoc($subjects);
$totalRows_subjects = mysqli_num_rows($subjects);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Программа обучения</title>
  <link href="styles.css" rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
  <h1>Список групп и предметов обучения</h1>
  <h3>Поиск:</h3>
   <table border="0" cellspacing="2" cellpadding="1">
            <tr class="tr-nohover">
              <th width="153px" scope="col">Группа</th>
              <th width="153px" scope="col">Предмет</th>
              <th width="153px" scope="col">Фамилия </th>
              <th width="153px" scope="col">Имя</th>
              <th width="153px" scope="col">Отчество</th>
              <th width="153px" scope="col">Форма сдачи</th>
        </tr>
</table>    
 <form action="group_subject" method="get" enctype="text/plain" name="search" id="search" autocomplete="off">
 
   <input name="keyword_group" type="text" id="keyword_group" class="border-bottom" value="<?php echo $keyword_group; ?>" size="13">    
   <input name="keyword_subject" type="text" id="keyword_subject" class="border-bottom" value="<?php echo $keyword_subject; ?>"size="13">
    <input name="keyword_surname" type="text" id="keyword_surname" class="border-bottom" value="<?php echo $keyword_surname; ?>"size="13">
    <input name="keyword_name" type="text" id="keyword_name" class="border-bottom" value="<?php echo $keyword_name; ?>"size="13">
    <input name="keyword_patronymic" type="text" id="keyword_patronymic" class="border-bottom" value="<?php echo $keyword_patronymic; ?>"size="13"> 
    <input name="" type="text" id="keyword_exam_test" class="border-bottom" value="<?php echo $keyword_exam_test; ?>"size="13"> 
    <input type="submit" name="Submit" value="Найти" id="Submit">    

</form>

<form action="group_subject" method="get" enctype="text/plain" name="sarch" id="sarch">
  <input name="keyword_group" type="hidden" value="">
  <input name="keyword_subject" type="hidden" value="">
  <input name="keyword_surname" type="hidden" value="">
  <input name="keyword_name" type="hidden" value="">
  <input name="keyword_patronymic" type="hidden" value="">
  <input name="keyword_exam_test" type="hidden" value="">
  <input type="submit" name="Submit" value="Все" id="Submit" style="margin-top: 10px;">
  
</form>

  <p>&nbsp;</p>
  <hr>
  <?php if ($totalRows_subjects > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th scope="col">Номер</th>
              <th scope="col">Группа</th>
              <th scope="col">Предмет</th>
              <th scope="col">Фамилия </th>
              <th scope="col">Имя</th>
              <th scope="col">Отчество</th>
              <th scope="col">Форма сдачи</th>
              <th scope="col">Ведомости</th>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="center"><?php echo $i; ?></td>
              <td><?php echo $row_subjects['group_name']; ?></td>
              <td><?php echo $row_subjects['subj_name']; ?></td>
              <td><?php echo $row_subjects['surname']; ?></td>
              <td><?php echo $row_subjects['name']; ?></td>
              <td><?php echo $row_subjects['patronymic']; ?></td>
              <td><?php echo $row_subjects['exam_test']; ?></td>
              <td><a href="exam_marks<?php if(!$isAorM) echo "_view" ?>?groups_subjects=<?php echo $row_subjects['gr_sub_id']; ?>">Ведомость</a></td>
            </tr>
<?php $i++ ?>       
<?php } while ($row_subjects = mysqli_fetch_assoc($subjects)); ?>            
          </table>
<?php } // Show if recordset not empty ?> 

<?php if ($totalRows_subjects == 0) { // Show if recordset empty ?>
<h3>Преподавателей на данной кафедре пока нет!</h3>
<?php } // Show if recordset empty ?>

<p><a href="results">На результаты сессии</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($subjects);
?>