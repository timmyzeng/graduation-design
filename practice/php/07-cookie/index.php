<?php

setcookie('user', 'zeng');    //设置cookie

// 10s过期
setcookie('sex', '男', time() + 10);    //设置cookie

// 删除cookie
setcookie('sex', '男', time() - 3600);    //设置cookie

// setcookie第四第五个参数
// path cookie路径，针对url的路径
// domain 域名

// 取出cookie
var_dump($_COOKIE);
?>
