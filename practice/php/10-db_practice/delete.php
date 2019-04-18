<?php 

echo '这是删除页面<br>';

// 获取删除id
$id = $_GET['id'];

$link = mysqli_connect('localhost', 'root', '1');   //返回mysqli对象
if (!$link)
{
    exit('connect database failed');
}

mysqli_set_charset($link, 'utf8');

mysqli_select_db($link, 'practice');

$sql = "delete from student where id=$id";

$res = mysqli_query($link, $sql);

if ($res && mysqli_affected_rows($link))
{
    echo 'delete success<a href="userlist.php">返回</a>';
}
else
{
    echo 'delete failed';
}

mysqli_close($link);
?>
