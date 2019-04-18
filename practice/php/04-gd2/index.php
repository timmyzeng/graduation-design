<?php
// 生成图片文件
$file = imagecreate(100, 50);   

// 图片颜色分配
$bg = imagecolorallocate($file, 0, 180, 255);
$color = imagecolorallocate($file, 255, 255, 255);

// 图片填充
imagefill($file, 0, 0, $bg);

// 写入图片字符
imagechar($file, 5, 10, 25, 'p', $color);
imagechar($file, 5, 30, 25, 'h', $color);
imagechar($file, 5, 50, 25, 'p', $color);

// 告知浏览器使用图片中的png格式输出
header("Content-Type:image/png");

// 按照png的格式输出
imagepng($file);
?>
