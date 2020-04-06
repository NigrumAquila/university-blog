<?php require_once('Connections/studies_date.php'); ?>
<?php
mysqli_select_db($studies_date, $database_studies_date);
$query_students = "SELECT groups.group_name, number, surname, name, patronymic, gender, birthday 
FROM students, groups WHERE students.group_name = groups.group_id ORDER BY surname, name ASC";
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
	<p>&nbsp;</p>
	<hr>
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