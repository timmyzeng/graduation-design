# PHP

> zzc

---

## 3.21

输出：`echo print`

调试：`var_dump()`

变量：`$var = 'num';`	//弱类型语言

数组：`$num = array('111', 'sdf');`

对象：`$obj = new Person()`;	需要先创建一个Person类

运算符：`-x` 取反；`'hehe'.'nihao' -> 'hehenihao'` 并置，连接字符串

i`f elseif else`：**elseif**中间没有空格

相等判断：`==`：表示值是否相等。`===`：全等于，类型也要相等

foreach循环：遍历数组使用

```php
<?php
$girl = array('lili', 'jily', 'timmy');

foreach ($girl as $key => $value)
{
    echo $key.':'.$value.'<br>';
}
?>
```

函数：`function func_name()`

日期函数：`date('Y-m-d H:i:s')`

## 3.22

数组

1. 数值数组：`$cars = array("volvo", "BMW", "Toyota");`
2. 关联数组：`$age = array("Peter"=>"35", "Joe"=>"43");`
3. 二维数组：`$cars = array( array("volvo", 100, 96), array("BMW", 60, 59) );`
4. 或者不需要array：`$cars = ["volvo", "BMW"];`

可以使用count()函数计算数组中的个数。

字符串函数：

1. explode()：把字符串打散为数组
2. implode() == join()：将数组元素合成为数组
3. trim()：去掉字符串两边的字符
4. md5()：计算字符串的MD5散列
5. str_replace()：替换字符串中的一些字符（大小写敏感）

数组函数：

1. array_keys()：返回数组中所以的键名
2. array_pop()：删除数组中的最后一个元素（出栈）
3. array_push()：将一个或多个元素插入数组的末尾（入栈）
4. array_rand()：从数组中随机选出一个或多个元素，返回键名
5. array_shift()：删除数组中的第一个元素，并返回被删除的元素
6. count()：返回数组中元素的数目
7. in_array()：检查数组中是否存在指定的值

## 4.10
图片获取

其中enctype = "multipart/form-data"不能少

```php+HTML
<html>
    <head>
        <meta charset = "utf8" />
        <title>登录</title>
    </head>
    
    <body>
        <form name = "login" action = "index.php" method = "POST" enctype = "multipart/form-data">
            <input type = "text" name = "username" placeholder = "请输入用户名"/>
            <input type = "password" name = "password" placeholder = "请输入密码" />
            <!-- 单选 -->
            <input type = "radio" name = "sex" value = "1">男
            <input type = "radio" name = "sex" value = "0">女
            <!-- 文件上传 -->
            <input type = "file" name = "img" />
            <input type = "submit" name = "sub" value = "开始登陆" />
        </form>
    </body>
</html>


```

获取form表单+文件上传：

```php+HTML
<html>
    <head>
        <meta charset = "utf8" />
        <title>登录</title>
    </head>
    
    <body>
        <form name = "login" action = "index.php" method = "POST">
            <input type = "text" name = "username" placeholder = "请输入用户名"/>
            <input type = "password" name = "password" placeholder = "请输入密码" />
            <!-- 单选 -->
            <input type = "radio" name = "sex" value = 1>男
            <input type = "radio" name = "sex" value = 0>女
            <!-- 文件上传 -->
            <input type = "file" name = "img" />
            <input type = "submit" name = "sub" value = "开始登陆" />
        </form>
    </body>
</html>

// index.php
<?php
var_dump($_GET);		//接收get方法
var_dump($_POST);		//接收post方法
var_dump($_REQUEST);	//两者都可以接收
?>
```

























