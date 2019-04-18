<?php
file_put_contents('log.txt', '首次加入\n');
// 追加的方式加入
file_put_contents('log.txt', '二次加入', FILE_APPEND);

var_dump(file_get_contents('log.txt'));

$html = file_get_contents('http://www.baidu.com');

file_put_contents('baidu.html', $html);
?>
