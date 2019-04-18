<?php

// var_dump($_GET);

$id = $_GET['id'];
$name = $_GET['name'];
$sex = $_GET['sex'];
$age = $_GET['age'];

$link = mysqli_connect('localhost', 'root', '1');

if (!$link)
{
    exit('connect failed to database');
}

mysqli_set_charset($link, 'utf8');

mysqli_select_db($link, 'practice');

$sql = "update student set name='$name', sex='$sex', age='$age' where id=$id";

$obj = mysqli_query($link, $sql);

if ($obj && mysqli_affected_rows($link))
{
    echo 'update success<br /><a href="userlist.php">back index</a>';
}
else
{
    echo 'update failed';
}

mysqli_close($link);

?>
