<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationA.php'); ?>
<?php


$deleteFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $deleteFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST['user'])) && ($_POST['user'] != "")) {
  $deleteSQL = sprintf("DELETE FROM users WHERE user_id=%s",
                       GetSQLValueString($_POST['user'], "int"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $deleteSQL) or die($studies_date->error);

  $deleteGoTo = "users";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Удаление пользователя</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Удаление пользователя</h1>
  <p>Страничка администратора, предназначенная для удаления пользователя. </p>
  <form action="" method="POST" name="deleteuser">
    <h3>Имя:
      <?php echo $row_user['name'] ?>
    </h3>
    <h3>Пароль:
      <?php echo $row_user['password'] ?>
    </h3>
    <h3>Права:
      <?php echo $row_user['rights'] ?>
      <input type="hidden" name="user" id="user" value="<?php echo $row_user['user_id']; ?>">
    </h3>
    <p>
      <input type="submit" name="Submit" value="Удалить" id="Delete">
    </p>
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