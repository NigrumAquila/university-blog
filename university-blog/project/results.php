<?php require_once('Connections/studies_date.php'); ?>
<?php

mysqli_select_db($studies_date, $database_studies_date);

$query_groups = "SELECT groups.group_name, groups_subjects.exam_test, 
count(gr_sub_id) as count_subject, 
if (groups_subjects.exam_test = 1, 5*count(*), count(*)) AS Max_Ball 
FROM groups, groups_subjects  
WHERE groups_subjects.group_name = groups.group_id
GROUP BY groups.group_name, groups_subjects.exam_test 
ORDER BY groups.group_name, groups_subjects.exam_test ASC";

$groups = mysqli_query($studies_date, $query_groups) or die('error');
$row_groups = mysqli_fetch_assoc($groups);
$totalRows_groups = mysqli_num_rows($groups);

mysqli_select_db($studies_date, $database_studies_date);
$query_result_students = "SELECT groups.group_name, concat_ws('.', students.surname, LEFT(students.name, 1), LEFT(students.patronymic, 1)) AS student,
students.number,
groups_subjects.exam_test, count(exam_marks.mark) AS count_mark, 
min(exam_marks.mark) AS min_mark, sum(exam_marks.mark) AS sum_mark
FROM groups_subjects INNER JOIN  exam_marks ON groups_subjects.gr_sub_id = exam_marks.group_subj
INNER JOIN groups ON groups_subjects.group_name = groups.group_id
INNER JOIN students ON exam_marks.student = students.stud_id
GROUP BY groups_subjects.group_name, exam_marks.student, groups_subjects.exam_test"; 
$result_students = mysqli_query($studies_date, $query_result_students) or die($studies_date->error);
$row_result_students = mysqli_fetch_assoc($result_students);
$totalRows_result_students = mysqli_num_rows($result_students);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html;  charset='utf-8'">
  <title>Результаты сессии</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Результаты сессии</h1>
  <h3>Планируемые показатели по группам</h3>
  <hr>
  <?php if ($totalRows_groups > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th scope="col">Номер</th>
              <th scope="col">Группа</th>
              <th scope="col">Форма сдачи</th>
              <th scope="col">Количество предметов</th>
              <th scope="col">Максимальный балл</th>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="center"><?php echo $i; ?></td>
              <td class="center"><?php echo $row_groups['group_name']; ?></td>
              <td class="center"><?php echo $row_groups['exam_test']; ?></td>
              <td class="center"><?php echo $row_groups['count_subject']; ?></td>
              <td class="center"><?php echo $row_groups['Max_Ball']; ?></td>
            </tr>
<?php  
              $row_groups_group[$i] = $row_groups['group_name']; 
              $row_groups_exam_test[$i] = $row_groups['exam_test']; 
              $row_groups_count_subject[$i] =  $row_groups['count_subject']; 
              $row_groups_Max_Ball[$i] = $row_groups['Max_Ball'];
?>
<?php $i++ ?>       
<?php } while ($row_groups = mysqli_fetch_assoc($groups)); ?>            
          </table>
<?php } // Show if recordset not empty ?> 

<?php if ($totalRows_groups == 0) { // Show if recordset empty ?>
<h3>Преподавателей на данной кафедре пока нет!</h3>
<?php } // Show if recordset empty ?>

<hr>
<h3>Фактические результаты сдачи сессии  студентами</h3>
<hr>
<?php if ($totalRows_result_students > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="10%" scope="col">Номер </th>
        <th width="10%" scope="col">Группа </th>
        <th width="10%" scope="col">Номер зачетной книжки </th>
        <th width="10%" scope="col">Студент</th>
        <th width="20%" scope="col">Форма сдачи </th> 
        <th width="15%" scope="col">Количество оценок </th>
        <th width="15%" scope="col">Минимальная оценка</th>
        <th width="15%" scope="col">Суммарный балл</th>
        </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="number"><?php echo $i; ?></td>
              <td class="number"><?php echo $row_result_students['group_name']; ?></td>
              <td class="number"><?php echo $row_result_students['number']; ?></td>
              <td class="number"><?php echo $row_result_students['student']; ?></td>
              <td class="number"><?php echo $row_result_students['exam_test']; ?></td>
              <td class="number"><?php echo $row_result_students['count_mark']; ?></td>
              <td class="number"><?php echo $row_result_students['min_mark']; ?></td>
        <td class="number"><?php echo $row_result_students['sum_mark']; ?></td>
        
  </tr>
<?php  
              $row_result_students_group[$i] = $row_result_students['group_name']; 
              $row_result_students_number[$i] = $row_result_students['number']; 
            $row_result_students_student[$i] = $row_result_students['student']; 
            $row_result_students_exam_test[$i] =  $row_result_students['exam_test']; 
              $row_result_students_count_mark[$i] = $row_result_students['count_mark'];
        $row_result_students_min_mark[$i] = $row_result_students['min_mark']; 
              $row_result_students_sum_mark[$i] = $row_result_students['sum_mark']; 
?>
<?php $i++ ?>       
<?php } while ($row_result_students = mysqli_fetch_assoc($result_students)); ?>            
          </table>
<?php } // Show if recordset not empty ?>


<hr>
<h3>Подведение итогов сдачи сессии  студентами</h3>
<hr>
<?php $i=1 ?>     
<?php do { ?>
    <?php $j=1 ?>     
   <?php do { ?>
      <?php 
      if ($row_plan_groups_group[$j] = $row_result_students_group[$i] AND 
      $row_plan_groups_exam_test[$j] = $row_result_students_exam_test[$i]) {
    if (($row_plan_groups_count_subject[$j] = $row_result_students_count_mark[$i])
        && (($row_result_students_min_mark[$i] == 1) OR ($row_result_students_min_mark[$i] > 2)))
    {$total_students[$i] = "да"; break;} else {$total_students[$i] ="нет"; break;};
    };   
     ?>
  <?php $j++ ?>
  <?php } while ($j <= $totalRows_plan_groups ); ?>  
 <?php $i++ ?>      
<?php } while ($i <= $totalRows_result_students); ?>



<?php if ($totalRows_result_students > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="10%" scope="col">Номер </th>
        <th width="10%" scope="col">Группа </th>
        <th width="10%" scope="col">Номер зачетной книжки </th>
        <th width="10%" scope="col">Студент</th>
        <th width="20%" scope="col">Форма сдачи </th> 
        <th width="15%" scope="col">Количество оценок </th>
              <th width="15%" scope="col">Минимальная оценка</th>
        <th width="15%" scope="col">Суммарный балл</th>
        <th width="15%" scope="col">Итог </th>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="number"><?php echo $i; ?></td>
              <td class="number"><?php echo $row_result_students_group[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_number[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_student[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_exam_test[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_count_mark[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_min_mark[$i]; ?></td>
              <td class="number"><?php echo $row_result_students_sum_mark[$i]; ?></td>
              <td class="number"><?php echo $total_students[$i] ; ?></td>
            </tr>
<?php $i++ ?>       
<?php } while ($i <= $totalRows_result_students); ?>            
          </table>
<?php } // Show if recordset not empty ?>

<hr>
<h3>Определение стипендии</h3>
<hr>
 <?php $i = 1; $j = 1; ?>     
<?php do { ?>
     <?php 
    $students_group[$j] = $row_result_students_group[$i]; 
    $students_student[$j] = $row_result_students_student[$i]; 
      if (($total_students[$i] == "да") && ($total_students[$i+1] == "да"))
    {$session[$j] = "да";} else {$session[$j] = "нет";};
    if ($session[$j] == "нет") {$grant[$j] = 0;} else 
    {if ($row_result_students_sum_mark[$i] == $row_groups_Max_Ball[$i] ) {$grant[$j] = 200;} else 
    {if ($row_result_students_sum_mark[$i] == $row_groups_Max_Ball[$i]-1 ) {$grant[$j] = 150;} else 
    {if ($row_result_students_min_mark[$i] > 3) {$grant[$j] = 100;} else {$grant[$j] = 0;};};};};
    $j++;
    
     ?>
  <?php $i++; $i++; ?>      
<?php } while ($i <= $totalRows_result_students); ?>   
 <?php  $j--; ?>  


<?php if ($j > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="10%" scope="col">Номер </th>
        <th width="10%" scope="col">Группа </th>
        <th width="10%" scope="col">Студент</th>        
        <th width="15%" scope="col">Стипендия, в %</th>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="number"><?php echo $i; ?></td>
              <td class="number"><?php echo $students_group[$i]; ?></td>
            <td class="number"><?php echo $students_student[$i]; ?></td>
            <td class="number"><?php echo $grant[$i]; ?></td>
           </tr>
<?php $i++ ?>       
<?php } while ($i <= $j); ?>            
          </table>
<?php } // Show if recordset not empty ?>



<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($groups);
mysqli_free_result($result_students);
?>