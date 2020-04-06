<?php require_once('Connections/studies_date.php'); ?>
<?php
mysqli_select_db($studies_date, $database_studies_date);
$query_lecturers = "SELECT surname, name, patronymic, posts.post, facultys.fac_abbrev 
FROM lecturers, posts, facultys  
WHERE posts.post_id =lecturers.post AND lecturers.faculty = facultys.fac_id 
ORDER BY surname ASC";
$lecturers = mysqli_query($studies_date, $query_lecturers) or die($studies_date->error);
$row_lecturers = mysqli_fetch_assoc($lecturers);
$totalRows_lecturers = mysqli_num_rows($lecturers);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Преподаватели УТиИТ</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Полный список преподавателей УТиИТ</h1>
	<p>&nbsp;</p>
	<hr>
  <?php if ($totalRows_lecturers > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="11%" scope="col">Номер</th>
              <th width="18%" scope="col">Фамилия </th>
              <th width="18%" scope="col">Имя</th>
              <th width="18%" scope="col">Отчество</th>
              <th width="18%" scope="col">Должность</th>
              <th width="18%" scope="col">Кафедра</th>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td class="center"><?php echo $i; ?></td>
              <td><?php echo $row_lecturers['surname']; ?></td>
              <td><?php echo $row_lecturers['name']; ?></td>
              <td><?php echo $row_lecturers['patronymic']; ?></td>
              <td><?php echo $row_lecturers['post']; ?></td>
              <td><?php echo $row_lecturers['fac_abbrev']; ?></td>   
            </tr>
<?php $i++ ?>       
<?php } while ($row_lecturers = mysqli_fetch_assoc($lecturers)); ?>            
          </table>
<?php } // Show if recordset not empty ?> 

<?php if ($totalRows_lecturers == 0) { // Show if recordset empty ?>
<h3>Преподавателей на данной кафедре пока нет!</h3>
<?php } // Show if recordset empty ?>

<p><a href="facultys">На список кафедр</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($lecturers);
?>