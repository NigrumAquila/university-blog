<?php require_once('Connections/studies_date.php'); ?>
<?php
$colname_group = "-1";
if(isset($_GET['group'])) {
	$colname_group = (get_magic_quotes_gpc()) ? $_GET['group'] : addslashes($_GET['group']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_group = sprintf("SELECT * FROM groups WHERE group_id = %s", $colname_group);
$group = mysqli_query($studies_date, $query_group) or die($studies_date->error);
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Предупреждение</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h2>Предупреждение!</h2>
	<p>В группе <?php echo $row_group['group_name']; ?> имеются студенты</p>
	<p>
		<a href="delete_group_cascade?
				group=<?php echo $row_group['group_id']; ?>"
				>Удалить
		</a>
	</p>
	<p><a href="groups">На список групп</a></p>
	<p><a href="/">На главную страницу</a></p>
</body>
</html>

<?php
mysqli_free_result($group);
?>