<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/AuthorizationAM.php'); ?>
<?php

$editFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}



$colname_lecturer = "-1";
if (isset($_GET['lecturer'])) {
  $colname_lecturer = (get_magic_quotes_gpc()) ? $_GET['lecturer'] : addslashes($_GET['lecturer']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_lecturer = sprintf("SELECT lec_id, surname, name, patronymic, lecturers.post, facultys.fac_abbrev, lecturers.faculty
FROM lecturers, posts, facultys   
WHERE posts.post_id =lecturers.post AND lecturers.faculty = facultys.fac_id AND lec_id=%s", $colname_lecturer);
$lecturer = mysqli_query($studies_date, $query_lecturer) or die($studies_date->error);
$row_lecturer = mysqli_fetch_assoc($lecturer);
$totalRows_lecturer = mysqli_num_rows($lecturer);


mysqli_select_db($studies_date, $database_studies_date);
$query_posts = "SELECT * FROM `posts` ORDER BY `post` ASC";
$posts = mysqli_query($studies_date, $query_posts) or die($studies_date->error);
$row_posts = mysqli_fetch_assoc($posts);
$totalRows_posts = mysqli_num_rows($posts);

mysqli_select_db($studies_date, $database_studies_date);
$query_facultys = "SELECT * FROM `facultys` ORDER BY `fac_abbrev` ASC";
$facultys = mysqli_query($studies_date, $query_facultys) or die($studies_date->error);
$row_facultys = mysqli_fetch_assoc($facultys);
$totalRows_facultys = mysqli_num_rows($facultys);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editlecturer")) {
  $updateSQL = sprintf("UPDATE `lecturers` SET `surname`=%s, `name`=%s, `patronymic`=%s, `post`=%s, `faculty`=%s
                       WHERE `lec_id`=%s",
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['patronymic'], "text"),
                       GetSQLValueString($_POST['post'], "int"),
                       GetSQLValueString($_POST['faculty'], "int"),
                       GetSQLValueString($_POST['lecturer'], "int"));
mysqli_select_db($studies_date, $database_studies_date);
$Result1 = mysqli_query($studies_date, $updateSQL) or die($studies_date->error);

$updateGoTo = "lecturers_search?faculty=" . $row_lecturer['faculty'];
  header(sprintf("Location: %s", $updateGoTo));
}



?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование преподавателя</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Редактирование преподавателя</h1>
<form action="<?php echo $editFormAction; ?>" method="POST" name="editlecturer" id="editlecturer" autocomplete="off">
  <p>Фамилия: 
    <input name="surname" type="text" id="surname" class="border-bottom" value="<?php echo $row_lecturer['surname']; ?>" size="20" maxlength="20">
</p>
  <p>Имя: 
   <input name="name" type="text" id="name" class="border-bottom" value="<?php echo $row_lecturer['name']; ?>" size="15" maxlength="15">
</p>
  <p>Отчество: 
   <input name="patronymic" type="text" id="patronymic" class="border-bottom" value="<?php echo $row_lecturer['patronymic']; ?>" size="20" maxlength="20">
</p>
<p>Должность: 
   <select name="post" id="post">
      <?php
do {  
?>
      <option value="<?php echo $row_posts['post_id']?>"
     <?php echo ($row_lecturer['post']== $row_posts['post_id']) ?  "selected" : "" ;  ?>>
     <?php echo $row_posts['post']?></option>
      <?php
} while ($row_posts = mysqli_fetch_assoc($posts));
  $rows = mysqli_num_rows($posts);
  if($rows > 0) {
      mysqli_data_seek($posts, 0);
    $row_posts = mysqli_fetch_assoc($posts);
  }
?>
    </select>
</p>

<p>
    <input name="lecturer" type="hidden" id="lecturer" value="<?php echo $row_lecturer['lec_id']; ?>">
    <input name="faculty" type="hidden" id="faculty" value="<?php echo $row_lecturer['faculty']; ?>">
  </p>
  <p>
    <input type="submit" name="Submit" value="Ввод" id="Submit">
    <input type="reset" name="Reset" value="Отмена" id="Reset"> 
  </p>
  <input type="hidden" name="MM_update" value="editlecturer">
</form>

<p><a href="lecturers_search">На список преподавателей</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($lecturer);
mysqli_free_result($posts);
mysqli_free_result($facultys);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>