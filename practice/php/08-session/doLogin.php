<?php

session_start();    //开启session，需要配合cookie进行使用

$_SESSION['user'] = $_POST; //可以存储数组

var_dump($_SESSION);

?>
