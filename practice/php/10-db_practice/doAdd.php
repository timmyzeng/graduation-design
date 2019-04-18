<?php

// var_dump($_GET);

$name = $_GET['name'];
$sex = $_GET['sex'];
$age = $_GET['age'];

$link = mysqli_connect('localhost', 'root', '1');   //返回mysqli对象
if (!$link)
{
    exit('connect database failed');
}

mysqli_set_charset($link, 'utf8');

mysqli_select_db($link, 'practice');

$sql = "insert into student(name, sex, age) values('$name', $sex, $age)";

$res = mysqli_query($link, $sql);

$id = mysqli_insert_id($link);

if ($id)
{
    echo 'insert success <br /><a href="userlist.php">back index</a>';
}
else
{
    echo 'insert failed';
}

mysqli_close($link);

?>
