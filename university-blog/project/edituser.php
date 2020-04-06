<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationA.php'); ?>
<?php

$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_user = "-1";
if (isset($_GET['user'])) {
  $colname_user = (get_magic_quotes_gpc()) ? $_GET['user'] : addslashes($_GET['user']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_user = sprintf("SELECT * FROM users WHERE user_id = %s", $colname_user);
$user = mysqli_query($studies_date, $query_user) or die($studies_date->error);
$row_user = mysqli_fetch_assoc($user);
$totalRows_user = mysqli_num_rows($user);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "edituser")) {
  $updateSQL = sprintf("UPDATE users SET name = %s, password = %s, rights = %s WHERE user_id = %s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['rights'], "text"),
                       GetSQLValueString($_POST['user'], "int"));
  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $updateSQL) or die($studies_date->error);

  $updateGoTo = "users";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование пользователя</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Редактирование пользователя</h1>
  <p>Страничка администратора, предназначенная для редактирования данных о пользователе. </p>
  <form action="<?php echo $editFormAction; ?>" method="POST" name="edituser" autocomplete="off">
    <p>Имя:
      <input name="name" type="text" id="name" class="border-bottom" size="20" value="<?php echo $row_user['name'] ?>">
    </p>
    <p>Пароль:
      <input name="password" type="text" id="password" class="border-bottom" size="20" value="<?php echo $row_user['password'] ?>">
    </p>
    <p>Права:
      <input name="rights" type="text" id="rights" class="border-bottom" size="1" value="<?php echo $row_user['rights'] ?>">
      <input type="hidden" name="user" id="user" value="<?php echo $row_user['user_id']; ?>">
    </p>
    <p>
      <input type="submit" name="Submit" id="Submit" value="Добавить">
      <input type="reset" name="Reset" id="Reset" value="Отмена">
    </p>
    <input type="hidden" name="MM_update" value="edituser">
  </form>
<p>
  <a href="users?user=<?php echo $row_user['user_id']; ?>">На список пользователей</a>
</p>

<?php
mysqli_free_result($user);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>

</body>
</html>