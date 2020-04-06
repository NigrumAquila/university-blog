<?php require_once('Connections/studies_date.php'); ?>
<?php require_once('Addons/Authorization.php'); ?>
<?php

$keyword_s = "";
$keyword_n = "";
$keyword_p = "";
$keyword_d = "";
$keyword_f = "";
if(isset($_GET['keyword_s'])) {
  $keyword_s = $_GET['keyword_s'];
}
if(isset($_GET['keyword_n'])) {
  $keyword_n = $_GET['keyword_n'];
}
if(isset($_GET['keyword_p'])) {
  $keyword_p = $_GET['keyword_p'];
}
if(isset($_GET['keyword_d'])) {
  $keyword_d = $_GET['keyword_d'];
}
if(isset($_GET['keyword_f'])) {
  $keyword_f = $_GET['keyword_f'];
}

mysqli_select_db($studies_date, $database_studies_date);

$query_lecturers = "SELECT lecturers.lec_id, surname, name, patronymic, posts.post, facultys.fac_abbrev 
FROM lecturers, posts, facultys  
WHERE posts.post_id =lecturers.post AND lecturers.faculty = facultys.fac_id %s %s %s %s %s
ORDER BY surname ASC";

$search_query_lecturers_s = "";
 if ($keyword_s) {
  $search_query_lecturers_s = sprintf("AND surname LIKE '%%%s%%' ",  $keyword_s);
}
$search_query_lecturers_n = "";
 if ($keyword_n) {
  $search_query_lecturers_n = sprintf("AND name LIKE '%%%s%%' ",  $keyword_n);
}
$search_query_lecturers_p = "";
 if ($keyword_p) {
  $search_query_lecturers_p = sprintf("AND patronymic LIKE '%%%s%%' ",  $keyword_p);
}
$search_query_lecturers_d = "";
 if ($keyword_d) {
  $search_query_lecturers_d = sprintf("AND lecturers.post LIKE '%%%s%%' ",  $keyword_d);
}
$search_query_lecturers_f = "";
 if ($keyword_f) {
  $search_query_lecturers_f = sprintf("AND fac_abbrev LIKE '%%%s%%' ",  $keyword_f);
}

$query_lecturers = sprintf($query_lecturers, $search_query_lecturers_s, 
$search_query_lecturers_n, $search_query_lecturers_p, 
$search_query_lecturers_d, $search_query_lecturers_f);

$lecturers = mysqli_query($studies_date, $query_lecturers) or die($studies_date->error);
$row_lecturers = mysqli_fetch_assoc($lecturers);
$totalRows_lecturers = mysqli_num_rows($lecturers);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Преподаватели УТиИТ</title>
	<link href="styles.css" rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
	<h1>Полный список преподавателей УТиИТ</h1>
  <h3>Поиск:</h3>
   <table border="0" cellspacing="2" cellpadding="1">
            <tr class="tr-nohover">
              <th width="153px" scope="col">Фамилия </th>
              <th width="153px" scope="col">Имя</th>
              <th width="153px" scope="col">Отчество</th>
              <th width="153px" scope="col">Должность</th>
              <th width="153px" scope="col">Кафедра</th>
        </tr>
</table>    
 <form action="lecturers_search" method="get" enctype="text/plain" name="search" id="search" autocomplete="off">
 
   <input name="keyword_s" type="text" id="keyword_s" class="border-bottom" value="<?php echo $keyword_s; ?>" size="13">    
   <input name="keyword_n" type="text" id="keyword_n" class="border-bottom" value="<?php echo $keyword_n; ?>"size="13" >
    <input name="keyword_p" type="text" id="keyword_p" class="border-bottom" value="<?php echo $keyword_p; ?>"size="13">
    <input name="keyword_d" type="text" id="keyword_d" class="border-bottom" value="<?php echo $keyword_d; ?>"size="13">
    <input name="keyword_f" type="text" id="keyword_f" class="border-bottom" value="<?php echo $keyword_f; ?>"size="13"> 
    <input type="submit" name="Submit" value="Найти" id="Submit">    

</form>

<form action="lecturers_search" method="get" enctype="text/plain" name="sarch" id="sarch">
    <input name="keyword_s" type="hidden" value="">
    <input name="keyword_n" type="hidden" value="">
  <input name="keyword_p" type="hidden" value="">
  <input name="keyword_d" type="hidden" value="">
  <input name="keyword_f" type="hidden" value="">
  <input type="submit" name="Submit" value="Все" id="Submit" style="margin-top: 10px;">
  
</form>

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
              <th width="18%" scope="col">Фамилия </th>
              <th width="18%" scope="col">Имя</th>
              <th width="18%" scope="col">Отчество</th>
              <th width="18%" scope="col">Должность</th>
              <th width="18%" scope="col">Кафедра</th>
              <?php if($isAorM) { ?><th width="3%" scope="col">Администрирование</th><?php } ?>
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
              <?php if($isAorM) { ?>
              <td>
                <a href="edit_lecturer?lecturer=<?php echo $row_lecturers['lec_id']; ?>">Изменить</a>| 
                <a href="delete_lecturer?lecturer=<?php echo $row_lecturers['lec_id']; ?>">Удалить</a>
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

<p><a href="facultys">На список кафедр</a> </p>
<p><a href="/">На главную страницу</a> </p>

</body>
</html>


<?php
mysqli_free_result($lecturers);
?>