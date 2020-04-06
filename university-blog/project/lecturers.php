<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

$colname_faculty = "-1";
if (isset($_GET['faculty'])) {
  $colname_faculty = (get_magic_quotes_gpc()) ? $_GET['faculty'] : addslashes($_GET['faculty']);
}

mysqli_select_db($studies_date, $database_studies_date);
$query_lecturers = sprintf("SELECT surname, name, patronymic, posts.post, lecturers.lec_id FROM lecturers, posts WHERE faculty = %s AND posts.post_id = lecturers.post ORDER BY surname ASC", $colname_faculty);
$lecturers = mysqli_query($studies_date, $query_lecturers) or die($studies_date->error);
$row_lecturers = mysqli_fetch_assoc($lecturers);
$totalRows_lecturers = mysqli_num_rows($lecturers);

$query_faculty = sprintf("SELECT * FROM facultys WHERE fac_id = %s", $colname_faculty);
$faculty = mysqli_query($studies_date, $query_faculty) or die($studies_date->error);
$row_faculty = mysqli_fetch_assoc($faculty);

$query_posts = "SELECT * FROM posts";
$posts = mysqli_query($studies_date, $query_posts) or die($studies_date->error);
$row_posts = mysqli_fetch_assoc($posts);


$addFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $addFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addlecturer")) {
  $insertSQL = sprintf("INSERT INTO lecturers (surname, name, patronymic, post, faculty) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['patronymic'], "text"),
                       GetSQLValueString($_POST['post'], "int"),
                       GetSQLValueString($_POST['faculty'], "int"));
  $Result1 = mysqli_query($studies_date, $insertSQL) or die($studies_date->error);

  $insertGoTo = "lecturers";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Сведения о преподавателях</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Список преподавателей кафедры</h1>
  <p>&nbsp;</p>
  <div class="fullnote">
    <h2><?php 
    echo $row_faculty['fac_name'] . " (" . $row_faculty['fac_abbrev'] . ")"; ?></h2>
  </div>
  <p>&nbsp;</p>
    <hr>
<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>
  <?php if ($totalRows_lecturers > 0) { // Show if recordset not empty ?>
  
        <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="11%" scope="col">Номер</th>
              <th width="22.5%" scope="col">Фамилия </th>
              <th width="22.5%" scope="col">Имя</th>
              <th width="22.5%" scope="col">Отчество</th>
              <th width="22.5%" scope="col">Должность</th>
              <?php if($isAorM) { ?><th width="22.5%" scope="col">Администрирование</th><?php } ?>
            </tr>
<?php $i=1 ?>     
<?php do { ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $row_lecturers['surname']; ?></td>
              <td><?php echo $row_lecturers['name']; ?></td>
              <td><?php echo $row_lecturers['patronymic']; ?></td>
              <td><?php echo $row_lecturers['post']; ?></td>
              <?php if($isAorM) { ?>
              <td>
                <a href="edit_lecturer_fac?lecturer=<?php echo $row_lecturers['lec_id']; ?>&faculty=<?php echo $row_faculty['fac_id']; ?>">Изменить
                </a> | 
                <a href="delete_lecturer_fac?lecturer=<?php echo $row_lecturers['lec_id']; ?>&faculty=<?php echo $row_faculty['fac_id']; ?>">Удалить
                </a>
              </td>
              <?php } ?>
            </tr>
<?php $i++ ?>       
<?php } while ($row_lecturers = mysqli_fetch_assoc($lecturers)); ?>            
          </table>
<?php } // Show if recordset not empty ?> 

<?php if ($totalRows_lecturers == 0) { // Show if recordset empty ?>
<h3>Преподавателей на данной кафедре пока нет!</h3>
<?php } // Show if recordset empty ?>

<?php if($isAorM) { ?>

<div class="fullnote">
 <h3>Добавить преподавателя:</h3>
        <table  border="0" cellspacing="0" cellpadding="0">
            <tr class="tr-nohover">
              <th scope="col">Фамилия </th>
              <th scope="col">Имя</th>
              <th scope="col">Отчество</th>
              <th scope="col">Должность</th>
        <th scope="col"></th>
            </tr>

 <form action="<?php echo $addFormAction; ?>" method="POST" name="addlecturer" id="addlecturer" autocomplete="off"> 
            <tr class="tr-nohover">
              <td>   <input name="surname" type="text" id="surname" class="border-bottom" size="20" maxlength="20"></td>
        <td>   <input name="name" type="text" id="name" class="border-bottom" size="15" maxlength="15"></td>
        <td>   <input name="patronymic" type="text" id="patronymic" class="border-bottom" size="20" maxlength="20"></td>
        <td>
        <select name="post" id="post">
      <?php
do {  
?>
      <option value="<?php echo $row_posts['post_id']?>">
      <?php echo $row_posts['post']?></option>
      <?php
} while ($row_posts = mysqli_fetch_assoc($posts));
  $rows = mysqli_num_rows($posts);
  if($rows > 0) {
      mysqli_data_seek($posts, 0);
    $row_posts = mysqli_fetch_assoc($posts);
  }
?>
    </select></td>
   <td>  <input type="submit" name="Submit" id="Submit" value="Добавить">
    <input type="reset" name="Reset" id="Reset" value="Отмена"></td>
           </tr>

     <input name="faculty" type="hidden" id="faculty" value="<?php echo $colname_faculty; ?>">
 
   <input type="hidden" name="MM_insert" value="addlecturer">
</form>
 </table>
 </div>

<?php } ?>

<p><a href="facultys">На список кафедр</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($lecturers);
mysqli_free_result($faculty);
mysqli_free_result($posts);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>