<?php
$str = 'abce';

// 有字母同时要有数字
// 正则表达模式如下：//；中间写入内容
$pattern = '/\d\w/';

$result = preg_match($pattern, $str);

var_dump($result);
?>
