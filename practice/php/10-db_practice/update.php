<?php 

echo '这是更新页面<br />';

$id = $_GET['id'];

$link = mysqli_connect('localhost', 'root', '1');   //返回mysqli对象
if (!$link)
{
    exit('connect database failed');
}

mysqli_set_charset($link, 'utf8');

mysqli_select_db($link, 'practice');

$sql = "select * from student where id=$id";

$res = mysqli_query($link, $sql);

$rows = mysqli_fetch_assoc($res);

mysqli_close($link);
?>

<html>
    <form action="doUpdate.php">
        <!-- 通过隐藏表单将id传输到doUpdate.php中-->
        <input type="hidden" value="<?php echo $id; ?>" name="id" />
        姓名:<input type="text" value="<?php echo $rows['name'];?>" name="name"/><br />
        性别:<input type="text" value="<?php echo $rows['sex'];?>" name="sex"/><br />
        年龄:<input type="text" value="<?php echo $rows['age'];?>" name="age"/><br />
        <input type="submit" value="change"/>
    </form>
</html>
