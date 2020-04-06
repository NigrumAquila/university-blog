<?php require_once('Connections/studies_date.php'); ?>
<?php
$colname_groups_subjects = "-1";
if (isset($_GET['groups_subjects'])) {
$colname_groups_subjects = (get_magic_quotes_gpc()) ? 
$_GET['groups_subjects'] : addslashes($_GET['groups_subjects']);
}

mysqli_select_db($studies_date, $database_studies_date);
$query_groups_subjects = sprintf("SELECT groups.group_name, subjects.subj_name, 
lecturers.surname, lecturers.name, lecturers.patronymic, groups_subjects.exam_test, 
groups_subjects.gr_sub_id
FROM groups, subjects, lecturers, groups_subjects
WHERE groups_subjects.gr_sub_id = %s 
AND groups_subjects.group_name = groups.group_id 
AND groups_subjects.subject = subjects.subj_id 
AND groups_subjects.lecturer = lecturers.lec_id", 
$colname_groups_subjects);

$groups_subjects = mysqli_query($studies_date, $query_groups_subjects) or die($studies_date->error);
$row_groups_subjects = mysqli_fetch_assoc($groups_subjects);
$totalRows_groups_subjects = mysqli_num_rows($groups_subjects);

mysqli_select_db($studies_date, $database_studies_date);

$query_exam_student = sprintf("SELECT exam_marks.exam_mark_id, students.number, 
students.surname, students.name, students.patronymic, marks.mark, exam_marks.date_exam 
FROM students, exam_marks, marks 
WHERE exam_marks.group_subj = %s AND exam_marks.student = students.stud_id
AND exam_marks.mark = marks.mark_id
ORDER BY surname, name, patronymic  ASC", $colname_groups_subjects);

$exam_student = mysqli_query($studies_date, $query_exam_student) or die($studies_date->error);
$row_exam_student = mysqli_fetch_assoc($exam_student);
$totalRows_exam_student = mysqli_num_rows($exam_student);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ведомость</title>
	<link href="styles.css" rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
	<h2>Ведомость</h2>
<h3>&nbsp;</h3>

      <h3>Группа: <?php echo $row_groups_subjects['group_name']; ?></h3>
      <h3>Предмет: <?php echo $row_groups_subjects['subj_name']; ?></h3>
      <h3>Преподаватель: <?php echo $row_groups_subjects['surname'], " ",  
      $row_groups_subjects['name'], " ", $row_groups_subjects['patronymic']; ?></h3>
      <h3>Форма сдачи: <?php echo $row_groups_subjects['exam_test']; ?></h3>
	<hr>
	<?php if ($totalRows_exam_student > 0) { // Show if recordset not empty ?>
	
	      <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="7%" scope="col">Номер</th>
              <th width="15%" scope="col">Зачетная книжка </th>
              <th width="17%" scope="col">Фамилия</th>
              <th width="13%" scope="col">Имя</th>
              <th width="15%" scope="col">Отчество</th>
              <th width="15%" scope="col">Оценка</th>
              <th width="21%" scope="col">Дата</th>
            </tr>
<?php $i=1 ?>			
<?php do { ?>
            <tr>
              <td class="number"><?php echo $i; ?></td>
              <td class="number"><?php echo $row_exam_student['number']; ?></td>
              <td><?php echo $row_exam_student['surname']; ?></td>
              <td><?php echo $row_exam_student['name']; ?></td>
              <td><?php echo $row_exam_student['patronymic']; ?></td>
               <td><?php echo $row_exam_student['mark']; ?></td>
              <td class="number"><?php echo $row_exam_student['date_exam']; ?></td>
            </tr>
<?php $i++ ?>				
<?php } while ($row_exam_student = mysqli_fetch_assoc($exam_student)); ?>            
          </table>
<?php } // Show if recordset not empty ?>

<p><a href="group_subject?groups_subjects=<?php echo $row_groups_subjects['gr_sub_id']; ?>
">На программу обучения</a></p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($groups_subjects);
mysqli_free_result($exam_student);
?>