<?php
    // 1 连接数据库
    $link = mysqli_connect('localhost', 'root', '1');   //返回mysqli对象
    // echo "<pre>";
    // print_r($link);
    // echo "</pre>";
    // 2 判断连接成功
    if (!$link)
    {
        exit('数据库连接失败');
    }
    // 3 设置字符集
    mysqli_set_charset($link, 'utf8');
    // 4 选择数据库
    mysqli_select_db($link, 'practice');
    // 5 准备sql语句
    $sql = "select * from student";
    // 6 发送sql语句
    $res = mysqli_query($link, $sql);
    // echo "<pre>";
    // print_r($res);
    // echo "</pre>";
    // 7 处理结果集
    // $result = mysqli_fetch_assoc($res); //返回一条关联的数组
    // $result = mysqli_fetch_assoc($res);
    // 循环获取所有的结果集
    while ($rows = mysqli_fetch_assoc($res))
    {
        var_dump($rows);
        // echo "<pre>";
        // print_r($rows);
        // echo "</pre>";
    }
    // echo "<pre>";
    // print_r($result);
    // echo "</pre>";
    //
    // $result = mysqli_fetch_assoc($res); //返回一条关联数组
    // $result = mysqli_fetch_row($res); //返回一个索引数组
    // $result = mysqli_fetch_array($res); //返回有索引又有关联的数组
    // $result = mysqli_num_rows($res); //返回查询时候的结果集的总数量
    // $result = mysqli_affected_rows($res); //返回修改、删除、添加的受影响行数
    // $result = mysqli_insert_id($res); //返回插入的当前数据的自增id值
    //
    // 8 关闭数据库
    mysqli_close($link);
?>
