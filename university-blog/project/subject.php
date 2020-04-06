<?php require_once('Connections/studies_date.php'); ?>
<?php

mysqli_select_db($studies_date, $database_studies_date);
$query_subjects = sprintf("SELECT * FROM subjects order by subj_name asc");
$subjects = mysqli_query($studies_date, $query_subjects) or die($studies_date->error);
$row_subjects = mysqli_fetch_assoc($subjects);
$totalRows_subjects = mysqli_num_rows($subjects);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Предметы УТиИт</title>
  	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Предметы УТиИТ</h1>
	<p>&nbsp;</p>
	<hr>
	<p>&nbsp;</p>
<?php do { 
?>
	<div class="fullnote">
		<h2>
			<?php echo $row_subjects['subj_name'] . " (" . $row_subjects['hour'] . ")"; ?>
		</h2>
	</div>
<?php } while ($row_subjects = mysqli_fetch_assoc($subjects)); ?>            

<?php if ($totalRows_subjects == 0) { // Show if recordset empty ?>
<h3>Кафедр пока нет!</h3>
<?php } // Show if recordset empty ?>

<p>Предметы с <?php echo "1" ?> по 
<?php echo $totalRows_subjects ?> </p>

<p>&nbsp;</p>

<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($subjects);
?>