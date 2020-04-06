<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/GetSQLValueString.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

$addFormAction = str_replace(".php", "", $_SERVER['PHP_SELF']);

if (isset($_SERVER['QUERY_STRING'])) {
  $addFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addstudent")) {
  $insertSQL = sprintf("INSERT INTO students (`group_name`, `number`, `surname`,
                      `name`, `patronymic`, `gender`, `birthday`)
                       VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['group_name'], "int"),
                       GetSQLValueString($_POST['number'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['patronymic'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['birthday'], "date"));


  mysqli_select_db($studies_date, $database_studies_date);
  $Result1 = mysqli_query($studies_date, $insertSQL) or die(debug_backtrace());

  $insertGoTo = "students";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$colname_group = "-1";
if (isset($_GET['group'])) {
  $colname_group = (get_magic_quotes_gpc()) ? $_GET['group'] : addslashes($_GET['group']);
}
mysqli_select_db($studies_date, $database_studies_date);
$query_group = sprintf("SELECT `group_name` FROM groups WHERE group_id = %s", $colname_group);
$group = mysqli_query($studies_date, $query_group) or die($studies_date->error);
$row_group = mysqli_fetch_assoc($group);
$totalRows_group = mysqli_num_rows($group);

mysqli_select_db($studies_date, $database_studies_date);
$query_students = sprintf("SELECT * FROM students WHERE `group_name` = %s ORDER BY number ASC", $colname_group);
$students = mysqli_query($studies_date, $query_students) or die($studies_date->error);
$row_students = mysqli_fetch_assoc($students);
$totalRows_students = mysqli_num_rows($students);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Сведения о студентах <?php echo isset($row_group['group_name']) ? $row_group['group_name'] :  'группы' ?></title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<h1>Список студентов группы</h1>
	<p>&nbsp;</p>
	<div class="fullnote">
		<h2><?php 
		echo $row_group['group_name']; ?></h2>
	</div>
	<p>&nbsp;</p>
		<hr>
<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>

	<?php if ($totalRows_students > 0) { // Show if recordset not empty ?>
	
	      <table width="100%"  border="1" cellspacing="2" cellpadding="1">
            <tr>
              <th width="11%" scope="col">Номер</th>
              <th width="17%" scope="col">Зачетная книжка </th>
              <th width="26%" scope="col">Фамилия</th>
              <th width="9%" scope="col">Имя</th>
              <th width="16%" scope="col">Отчество</th>
              <th width="4%" scope="col">Пол</th>
              <th width="17%" scope="col">День рождения</th>
              <?php if($isAorM) { ?><th width="17%" scope="col">Администрирование</th><?php } ?>
            </tr>
<?php $i=1 ?>			
<?php do { ?>
            <tr>
              <td class="center"><?php echo $i; ?></td>
              <td><?php echo $row_students['number']; ?></td>
              <td><?php echo $row_students['surname']; ?></td>
              <td><?php echo $row_students['name']; ?></td>
              <td><?php echo $row_students['patronymic']; ?></td>
              <td><?php echo $row_students['gender']; ?></td>
              <td><?php echo $row_students['birthday']; ?></td>
              <?php if($isAorM) { ?>
              <td>
                <a href="edit_students_group?student=<?php echo $row_students['stud_id']; ?>&group=<?php echo $colname_group; ?>">Изменить
                </a> | 
                <a href="delete_students_group?student=<?php echo $row_students['stud_id']; ?>&group=<?php echo $colname_group; ?>">Удалить
                </a>
              </td>
              <?php } ?>
              
            </tr>
<?php $i++ ?>				
<?php } while ($row_students = mysqli_fetch_assoc($students)); ?>            
          </table>
<?php } // Show if recordset not empty ?>	

<?php if ($totalRows_students == 0) { // Show if recordset empty ?>
<h3>Студентов в данной группе  пока нет!</h3>
<?php } // Show if recordset empty ?>

<p>&nbsp;</p>
<?php if($isAorM) { ?>
<div class="fullnote">
 <h3>Добавить студента:</h3>
 <form action="<?php echo $addFormAction; ?>" method="POST" name="addstudent" id="addstudent" autocomplete="off"> 
   <table width="900"  border="0" cellspacing="2" cellpadding="2">
     <tr class="tr-nohover">
        <th width="150" scope="col">Номер зачетной книжки: </th>
    <td> <input name="number" type="text" id="number" class="border-bottom" size="20" maxlength="10"></td>
   </tr>
   <tr class="tr-nohover">
     <th width="150" scope="col" >Фамилия: </th>
     <td><input name="surname" type="text" id="surname" class="border-bottom" size="20" maxlength="15"></td>
   </tr>
     <tr class="tr-nohover">   
    <th width="150" scope="col">Имя</th>
      <td><input name="name" type="text" id="name" class="border-bottom" size="20" maxlength="10"></td>
   </tr>
   <tr class="tr-nohover">
      <th width="150" scope="col">Отчество</th>
      <td><input name="patronymic" type="text" id="patronymic" class="border-bottom" size="20" maxlength="15"></td>
   </tr>
   <tr class="tr-nohover">
       <th width="150" scope="col">Пол</th> 
       <td> мужской  
              <input <?php if (!(strcmp($row_students['gender'],0)))
             {echo "checked=\"checked\"";} ?> name="gender" type="radio" value="м" > 
             женский 
             <input <?php if (!(strcmp($row_students['gender'],1)))
             {echo "checked=\"checked\"";} ?> name="gender" type="radio" value="ж"> 
            </td>
     </tr>
   <tr class="tr-nohover">
       <th width="150" scope="col">День рождения</th>
       <td><input name="birthday" type="date" id="birthday" ></p></td>
 </table>
           <input type="submit" name="Submit" id="Submit" value="Добавить">
             <input type="reset" name="Reset" id="Reset" value="Отмена">
           <input name="group_name" type="hidden" id="group_name" value="<?php echo $colname_group; ?>">
             <input type="hidden" name="MM_insert" value="addstudent">
</form>
 </div>
<?php } ?>

<p><a href="groups">На список групп</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>

<?php
mysqli_free_result($group);
mysqli_free_result($students);
if(isset($Result1)) {
  mysqli_free_result($Result1);
}
?>