<?php

$link = mysqli_connect('localhost', 'root', '1');

if (!$link)
{
    exit('数据库连接失败');
}

mysqli_set_charset($link, 'utf8');

mysqli_select_db($link, 'practice');

// -----------分页-----------
// 求出总条数
$sql = "select count(*) as count from student";
$result = mysqli_query($link, $sql);
$pageRes = mysqli_fetch_assoc($result); // 保存总条数，但是此时是count(*)
// var_dump($pageRes);
$count = $pageRes['count']; // 将count(*)改变为count
// var_dump($count);
// 每页显示2条数据
$num = 2;
// 根据每页显示可以求出总页数
$pageCount = ceil($count/$num); //ceil向上取整
// var_dump($pageCount);
// 根据总页数可以求出偏移量
$page = empty($_GET['page']) ? 1 : $_GET['page'];   // 设置page的初始值，如果为空初始化为1，否则就是传输过去的page值
$offset = ($page-1) * $num;
// -----------结束-----------
$sql = "select * from student limit " . $offset . ',' . $num;

$obj = mysqli_query($link, $sql);

echo '<a href="add.html">add</a><br />';
echo '<table width="600" border="1">';
    echo '<th>编号</th><th>姓名</th><th>性别</th><th>年龄</th>';
    while ($rows = mysqli_fetch_assoc($obj))
    {
        echo '<tr>';
            echo '<td>'.$rows['id'].'</td>';
            echo '<td>'.$rows['name'].'</td>';
            echo '<td>'.($rows['sex'] == 1 ? '男' : '女').'</td>';
            echo '<td>'.$rows['age'].'</td>';
            echo '<td>'
                    .'<a href="delete.php?id='.$rows['id'].'">删除</a>'
                    .'/'
                    .'<a href="update.php?id='.$rows['id'].'">更新</a>'
                .'</td>';

        echo '</tr>';
    }
echo '</table>';

$next = $page + 1;
$prev = $page - 1;
if ($prev < 1)
{
    $prev = 1;
}
if ($next > $pageCount)
{
    $next = $pageCount;
}

mysqli_close($link);

?>

<a href="userlist.php?page=1">index</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="userlist.php?page=<?php echo $prev; ?>">prev</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="userlist.php?page=<?php echo $next; ?>">next</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="userlist.php?page=<?php echo $pageCount; ?>">end</a>
