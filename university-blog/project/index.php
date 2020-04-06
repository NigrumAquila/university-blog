<?php require_once('Addons/Authorization.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>УТиИТ при ИрГУПС</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h1>Иркутский государственный университет путей сообщения</h1>
  <h2>Факультет "Управление на транспорте и информационные технологии"</h2>
  <p>Здравствуйте, уважаемые посетители блога! </p>
  <p>Здесь публикуются сведения о кафедрах, преподавателях, студентах и процессе обучения в институте.</p>
  <p>Читайте на здоровье!</p>
  <hr>
<?php
$isAorM = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a,m", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>

<?php
$isA = (((isset($_SESSION['MM_Username'])) && (isAuthorized("", "a", $_SESSION['MM_Username'],
$_SESSION['MM_UserGroup']))));
?>
    <p align="center"> | <a href="login">Вход</a> |  
    <?php if($isAorM) { ?><a href="logout">Выход</a> | <?php } ?>
    <?php if($isA) { ?> <a href="users">Пользователи</a> |<?php } ?>
    </p>
  <hr>
    <p align="center">  | <a href="facultys">Кафедры</a> | <a href="lecturers_search">Преподаватели</a> | <a href="subject">Предметы</a> | </p>
  <hr>
    <p align="center"> | <a href="groups">Группы</a> | <a href="students_search">Студенты</a> | <a href="group_subject">Программа обучения</a> | <a href="results">Результаты сессии</a> | </p>
  <hr>
  <div class="footer">
  </div>
</body>

</html>
