<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationA.php'); ?>
<?php


$MM_flag = "MM_insert";
if(isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect = "adduser";
  $loginUsername = $_POST['name'];
  $LoginRS__query = "SELECT name FROM users WHERE name = '" . $loginUsername . "'";
  //die($LoginRS__query);
  mysqli_select_db($studies_date, $database_studies_date);
  $LoginRS = mysqli_query($studies_date, $LoginRS__query) or die($studies_date->error);
  $loginFoundUser = mysqli_num_rows($LoginRS);

  if($loginFoundUser) {
    $MM_qsChar = "?";
    if(substr_count(($MM_dupKeyRedirect), "?") >= 1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar . "requsername=" . $loginUsername;
    header("Location: " . $MM_dupKeyRedirect);
    exit;
  }
}

$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if(isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "adduser")) {
  $insertSQL = sprintf("INSERT INTO users (name, password, rights) VALUES (%s, %s, %s)",
          GetSQLValueString($_POST['name'], "text"),
          GetSQLValueString($_POST['password'], "text"),
          GetSQLValueString($_POST['rights'], "text"));

  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $insertSQL) or die($studies_date->error);
  $insertGoTo = "users";
  if(isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Добавление пользователя</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Добавление пользователя</h1>
  <p>Страничка администратора, предназначенная для добавления нового пользователя. </p>
  <form action="<?php echo $editFormAction; ?>" method="POST" name="adduser" autocomplete="off">
    <p>Имя:
      <input name="name" type="text" id="name" class="border-bottom" size="20">
    </p>
    <p>Пароль:
      <input name="password" type="text" id="password" class="border-bottom" size="20">
    </p>
    <p>Права:
      <input name="rights" type="text" id="rights" class="border-bottom" size="1">
    </p>
    <p>
      <input type="submit" name="Submit" id="Submit" value="Добавить">
      <input type="reset" name="Reset" id="Reset" value="Отмена">
    </p>
    <input type="hidden" name="MM_insert" value="adduser">
  </form>

<p>
  <a href="users">На список пользователей</a>
</p>
</body>
</html>

<?php
if(isset($LoginRS)) {
  mysqli_free_result($LoginRS);
}
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>