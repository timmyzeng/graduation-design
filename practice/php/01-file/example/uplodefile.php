<?php
require_once 'upload.func.php';
header("Content-type:text/html;charset=utf8");
$fileinfo = $_FILES['filename'];
echo uploadFile($fileinfo);
