<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/AuthorizationA.php'); ?>
<?php

mysqli_select_db($studies_date, $database_studies_date);
$query_user = "SELECT * FROM users ORDER BY name ASC";
$user = mysqli_query($studies_date, $query_user) or die($studies_date->error);
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Список пользователей</title>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Список пользователей</h1>
	<p>Страничка администратора, предназначенная для управления пользователями. </p>
	<table width="500" border="1" cellspacing="2" cellpadding="1">
		<caption>
			Список пользователей
		</caption>
		<tr>
			<th width="120" scope="col">Пользователь</th>
			<th width="80" scope="col">Права</th>
			<th width="120" scope="col">Редактировать</th>
			<th width="100" scope="col">Удалить</th>
		</tr>
		<?php do { ?>
		<tr>
			<td class="number"><?php echo $row_user['name']; ?></td>
			<td class="number"><?php echo $row_user['rights']; ?></td>
			<td class="number">
				<a href="edituser?user=<?php echo$row_user['user_id']; ?>">Изменить</a>
			</td>
			<td class="number">
				<a href="deleteuser?user=<?php echo$row_user['user_id']; ?>">Удалить</a>
			</td>
		</tr>
		<?php } while ($row_user = mysqli_fetch_assoc($user)); ?>
	</table>
<p><a href="adduser">Добавить пользователя</a></p> 
<p><a href="/">На главную страницу</a></p>

<?php
mysqli_free_result($user);
?>
</body>
</html>