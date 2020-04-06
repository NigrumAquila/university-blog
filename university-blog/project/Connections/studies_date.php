<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_studies_date = "localhost";
$database_studies_date = "students-date";
$username_studies_date = "root";
$password_studies_date = "";
$studies_date = mysqli_connect($hostname_studies_date, $username_studies_date, $password_studies_date) or 
trigger_error($studies_date->error,E_USER_ERROR); 
mysqli_query($studies_date, "SET NAMES utf8;") or die(debug_print_backtrace());
mysqli_query($studies_date, "SET CHARACTER SET utf8;") or die(debug_print_backtrace());
mysqli_query($studies_date, "SET SESSION collation_connection = 'utf8_general_ci';") or die(debug_print_backtrace()); 
?>
