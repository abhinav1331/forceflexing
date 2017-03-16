<?php
$server   = "localhost";
$database = "homeapps";
$username = "root";
$password = "5E$/KBuu~9?cL58s";

$mysqlConnection = mysql_connect($server, $username, $password);
if (!$mysqlConnection)
{
  echo "Please try later.";
}
else
{
mysql_select_db($database, $mysqlConnection);
}
?>