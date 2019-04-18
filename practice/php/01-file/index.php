<?php
move_uploaded_file($_FILES['img']['tmp_name'], './img/1.jpg');
// var_dump($_POST);
// var_dump($_FILES);      //获取文件所有信息，如果是图片文件可以通过这种方法还获取到信息

// var_dump($_GET);		//接收get方法
// var_dump($_POST);		//接收post方法
// var_dump($_REQUEST);	//两者都可以接收
?>
