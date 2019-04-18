<?php
// 创建文件
touch('file.txt');

// 打开文件
$handle = fopen('file.txt', 'w');

// 写入文件
fwrite($handle, 'hello'."\n");
fwrite($handle, ' world'."\n");
fwrite($handle, ' children');

fclose($handle);

// 读取文件
$file = fopen('file.txt', 'r');

// 循环获取
while(!feof($file))
{
    echo fgets($file).'<br>';
}

fclose($file);

// 判断目录
if(!is_dir('ok'))
{
    // 创建目录
    mkdir('ok', 0777);
    chmod('ok', 0777);
}

// 判断文件
var_dump(is_file('file.txt'));

?>
