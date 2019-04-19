# ThinkPHP框架学习

> zzc
>
> 参考连接：[PHP中文网-ThinkPHP基础实战视频教程](http://www.php.cn/code/25987.html)

这个是国人开发，更好的入门

## 1 MVC模式

MVC强制将项目进行了输入、逻辑、输出进行了分离，分为三部分：控制器(C)、模型层(M)、展示层(V)

## 2 ThinkPHP目录结构

- Application：应用目录
- **Public**：图片、CSS、js等公共文件
- **ThinkPHP**：框架核心目录
  - Common：系统函数目录，里面存放了functions.php
  - Conf：系统配置文件目录
    - convention.php：系统配置文件（系统级别的配置文件）
  - Lang：语言包目录
  - Library：ThinkPHP目录的核心目录
    - Behavior：行文文件目录
    - Org：功能扩展目录
    - Think：Library的核心目录
    - Vendor：第三扩展目录
  - Mode
  - Tpl：系统模板目录，包含了系统使用的模板
  - ThinkPHP.php：项目接口文件，在后期开发的时候需要被项目入口文件所引入
- .htacess：分布式配置文件Apache使用
- composer.json：给composer软件使用的说明文件
- index.php：项目的入口文件

为了在开发中测试数据方便，给入口文件index.php添加一个头部的字符集

```php
// 给入口文件添加一个header头声明字符集
header('Content-Type:text/html;charset="utf-8"');
```

## 3 部署

### 在nginx上部署TP

相比于部署在Apache上，在nginx上进行部署没有那么简单。

进行如下的配置：

> sudo vim /etc/php.ini
>
> 开启：cgi.fix_pathinfo=1
>
> sudo vim /usr/local/nginx/conf/nginx.conf
>
> 修改并添加：

```
    server {
        listen       80;
        server_name  localhost;

        #charset koi8-r;

        access_log  logs/host.access.log  main;

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        # ~ \.php 只处理动态请求，对于静态资源请求由下面的 location / 匹配和处理
        location ~ \.php {
			root html;
			fastcgi_pass 127.0.0.1:9000;
			# 包含nginx服务器传递给fastcgi程序的参数，php中通过$_SERVER['参数名']可获取
			include fastcgi.conf;
			# 定义变量$fastcgi_script_name_new赋值为$fastcgi_script_name变量
			set $path_info "";
			set $fastcgi_script_name_new $fastcgi_script_name;
			#判断url是否是pathinfo形式的，如果是则把这个url分割成两部分，index.php入口文件之后的pathinfo部分
			#存入$path_info变量中，剩下的部分和$document_root根目录定位index.php入口文件在文件系统中的绝对路径
			if ($fastcgi_script_name ~*   "^(.+\.php)(/.+)$"  ) {
				set $fastcgi_script_name_new $1;
				set $path_info $2;
			}
			#对fastcgi.conf中的SCRIPT_FILENAME和SCRIPT_NAME fastcgi参数进行重写
			# 目的是指定入口文件在文件系统中的绝对路径给script_filename参数，让fastcgi知道index.php文件位置。
			fastcgi_param   SCRIPT_FILENAME   $document_root$fastcgi_script_name_new;
			fastcgi_param   SCRIPT_NAME   $fastcgi_script_name_new;
			# 定义一个新的nginx服务器传递给fastcgi的参数PATH_INFO
			# thinkphp需要这个入口文件index.php后的pathinfo信息
			fastcgi_param   PATH_INFO $path_info;
        }

        # 用来匹配静态文件
        location / {
            root   html;
            index  index.php index.html index.htm;

            if (!-e $request_filename) {
                rewrite ^(.*)$ index.php?s=$1 last;
                break;
            }   
        }

```

> sudo systemctl restart nginx	#重启nginx服务
>
> sudo systemctl restart php-fpm	#重启php服务
>
> 将官网下载好的ThinkPHP3.2.3-full目录中的ThinkPHP和index.php两个文件放到nginx/html中。直接放入网站根目录。
>
> 同时将html目录修改为0777权限。
>
> 浏览器访问ip，得到如下内容：

![访问TP](ThinkPHP框架.assets/1555581017409.png)

同时TP会在wwwroot中创建了一个新的Application目录。

### TP自动创建的Application

- Common：应用级别通用目录
  - common：函数库文件目录目录
  - conf：应用级别的配置
- Home：分组目录，平台目录
  - Common：分组级别的函数库文件目录
  - Conf：分组级别的配置
  - MVC三个目录
- Runtime：临时文件

### 部署之后输出的笑脸

默认分组/平台：Home

> vim /usr/local/nginx/html/Application/Home/Controller/IndexController.class.php

![默认输出](ThinkPHP框架.assets/1555590989564.png)

默认控制器：Index

默认方法：index

默认值在系统级别的conf中可以找到配置，如下：

> vim /usr/local/nginx/html/ThinkPHP/Conf/convention.php

![默认设置](ThinkPHP框架.assets/1555591041388.png)

## 4 控制器

### 控制器的创建

命名规则：控制器名+Controller.class.php

样例：创建一个用户控制器：UserController.class.php

控制器结构代码：

- 声明当前控制器（类）的命名空间；
- 引入父类控制器（类）；
- 声明控制器（类）并继承父类；

> /usr/local/nginx/html/Application/Home/Controller/UserController.class.php

```php
<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends Controller{

    public function test(){
        phpinfo();
    }
}
?>
```

访问通过普通路由方式进行访问：http://192.168.5.128/index.php?m=Home&c=User&a=test

## 5 路由

**路由指的是访问项目中具体某个方法的URL地址**

TP中有四个路由形式：

- 普通路由（get）：http://网址/入口文件?m=分组名&c=控制名&a=方法名&参数名=参数值
- **Pathinfo路由（默认）**：http://网址/入口文件/分组名/控制器名/方法/参数名1/参数值1/参数名2/参数值2
- Rewrite路由：http://网址/分组名/控制器名/方法/参数名1/参数值1/参数名2/参数值2；不能直接使用，需要配置。
- 兼容路由：http://网址/入口文件?s=分组名/控制器名/方法/参数名1/参数值1/参数名2/参数值2

路由形式的配置：

> /usr/local/nginx/html/ThinkPHP/Conf/convention.php

![URL_MODEL](ThinkPHP框架.assets/1555593245861.png)

这个配置影响的是TP系统封装中的URL组装函数（U函数）的生成URL地址。不会影响在地址栏的访问形式。

## 6 分组

通常项目都会根据某个功能的使用对象来区分代码，这些代码放在一起就是分组目录。通常就是我们说的平台（前台、后台）

刚部署好的TP中的/Application/Home就是一个分组目录，如果还需要更多的分组目录，按照Home目录进行创建即可。

![Home目录的结构](ThinkPHP框架.assets/1555594317445.png)

创建了一个新的Admin分组目录，在Controller目录中创建了一个新的TestController.class.php文件进行测试，内容如下：

```php
<?php
namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller {
    public function test(){
        echo "hello world.";
    }
}
?>
```

通过Pathinfo路由访问结果：http://192.168.5.128/index.php/Admin/Test/test

输出了hello world.

## 7 控制器的跳转

### URL组装

使用U方法进行url的组装，这个是系统定义的快速方法，位于：/usr/local/nginx/html/ThinkPHP/Common/functions.php中

![function U](ThinkPHP框架.assets/1555595903587.png)

U函数格式：

> U('[分组名/控制器名/]方法名', array('参数名1' => 参数值1, '参数名2' => 参数值2));

其中生成的url自动会带上.html，这个是因为在系统配置文件中设定默认的伪静态后缀名为.html，是为了优化。

```php
<?php
namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller {
    public function test1(){
        echo U('test1');
    }

    public function test2(){
        echo U('Test/test1');
    }

    public function test3(){
        echo U('Test/test1', array('id' => 100));
    }
}

```

### 系统跳转方法

成功跳转和失败跳转

> $this->success(跳转提示, [跳转地址, 等待时间]);
>
> $this->error(跳转提示, [跳转地址, 等待时间]);

跳转提示必须有，地址和时间可以没有。如果没有地址默认返回上一页。

```php
<?php
namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller {
    public function test(){
        echo "hello world.";
    }

    public function test1(){
        echo U('test1');
    }

    public function test4(){
        $this -> success('success', U('test'), 2);
    }

    public function test5(){
        $this -> error('failed', U('test1'), 2);
    }
}

```

![success](ThinkPHP框架.assets/1555597011373.png)

其中this指的是继承的Controller父类。success和error是父类的方法。

## 8 视图

### 视图的创建

创建的位置是分组目录下的View目录与控制器同名的目录中。TestController的test方法需要一个模板，这个模板文件test.html放置的位置是View/Test/test.html

### 视图的展示

Display的语法格式：

> $this -> display();	#展示当前控制器下与当前方法名称一致的模板文件
>
> $this -> display('模板文件名[不带后缀]');	#展示当前控制器下的指定模板文件
>
> $this -> display('View目录下的目录名/模板文件名[不带后缀]');	#展示指定控制器目录下的指定模板文件。

```php
<?php
namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller {
    public function test(){
        echo U('test');
        $this -> display();
    }

    public function test1(){
        echo U('test1');
        $this -> display('test');
    }
}
```

test()和test1()方法访问的都是View/Test/test.html文件。

```html
<html>
    <body>
        <h1>View/Test/test.html</h1>
    </body>
</html>
```

### 变量分配（初阶）

除了展示模板，还需要展示数据。这个变量还存在控制器方法中，需要将数据传递到模板中进行展示，这个过程叫做变量分配。

使用assign方法

> $this -> assign('模板中的变量名', $php中的变量名);

在模板中获取TP变量的默认格式是：**{$变量名}**

```php
<?php
namespace Admin\Controller;

use Think\Controller;

class TestController extends Controller {
    public function test(){
        // 定义变量
        $var = date('Y-m-d H:i:s', time());
        // 变量分配
        $this -> assign('var', $var);
        // 展示模板
        $this -> display();
    }
?>
```

```html
<html>
    <body>
        <h1>View/Test/test.html</h1>
        <h1>var: {$var}</h1>
    </body>
</html>
```

通过访问：http://192.168.5.128/index.php/Admin/Test/test.html得到结果如下：

![变量分配](ThinkPHP框架.assets/1555598750045.png)

TP变量的默认分隔符是{}，但是可以通过配置文件进行修改，/usr/local/nginx/html/ThinkPHP/Conf/convention.php

![默认分隔符](ThinkPHP框架.assets/1555599002391.png)

### 模板常量替换机制

在引入图片、css、js文件的时候使用特殊的常量替换复杂的路径。系统提供的常用常量如下：

- _\_MODULE\_\_：从域名后面开始一直到分组名结束的路由
- _\_CONTROLLER\_\_：从域名后面开始一直到控制器结束的路由
- _\_ACTION\_\_：从域名后面开始一直到方法名结束的路由
- _\_PUBLIC\_\_：站点更目录下的PBULIC目录的路由
- _\_SELF\_\_：从域名后面开始一直到最后的路由（如果方法没有传参，跟输出\_\_ACTION\_\_一样）

输入地址：http://192.168.5.129/index.php/Admin/Test/test6/id/100

![模板常量的值](ThinkPHP框架.assets/1555636117187.png)

模板常量是通过模板内容替换机制实现的，不是常量的定义，可以在/usr/local/nginx/html/ThinkPHP/Library/Behavior/ContenReplaceBehavior.class.php中找到定义。

![ContenReplaceBehavior.class.php](ThinkPHP框架.assets/1555636220225.png)

重点是字符串的替换。

### 简单样例

使用TP显示如下页面：

![简单样例](ThinkPHP框架.assets/1555638833771.png)

1 在/Application/Admin/Controller中创建LoginController.class.php文件：

```php
<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    public function login(){
        $this -> display();
    }
}
?>
```

2 在/Application/Admin/View/Login/中创建login.html文件，注意修改路径：href="_\_PUBLIC\_\_/Admin/css/style.css"

```html
<!DOCTYPE html>
<html lang="en" >

    <head>
      <meta charset="UTF-8">
      <title>Login Form</title>
      <link rel="stylesheet" href="__PUBLIC__/Admin/css/style.css">
    </head>

    <body>
      <body>
        <div class="login">
            <div class="login-screen">
                <div class="app-title">
                    <h1>Login</h1>
                </div>
                <div class="login-form">
                    <div class="control-group">
                    <input type="text" class="login-field" value="" placeholder="username" id="login-name">
                    <label class="login-field-icon fui-user" for="login-name"></label>
                    </div>

                    <div class="control-group">
                    <input type="password" class="login-field" value="" placeholder="password" id="login-pass">
                    <label class="login-field-icon fui-lock" for="login-pass"></label>
                    </div>

                    <a class="btn btn-primary btn-large btn-block" href="#">login</a>
                    <a class="login-link" href="#">Lost your password?</a>
                </div>
            </div>
        </div>
      </body>
    </body>
</html>
```

3 在/Public/Admin/中添加css文件，注意此时的Public是根目录下的公共资源目录，Admin目录是分组目录。

4访问http://192.168.5.129/index.php/Admin/Login/login得到页面。

### Fetch方法

> $this -> fetch()	# 获取模板的内容

dump方法

> dump($变量名)	# 打印变量内容，封装在函数库文件functions.php中

display()的功能就是fetch() + echo($var)的组合。

```php
<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    public function login(){
        // $this -> display();	展示模板页面
        $var = $this -> fetch();	// 获取模板内容
        // dump($var);	// 将变量进行打印
        echo($var);	// 将变量内容进行输出
    }
}
?>
```

### 视图中的注释

在模板中有三种注释方式：

- html注释：\<!-- 这是html注释 -->
- TP的行注释：{// 这是TP行注释}
- TP的块注释：{/\*这是TP块注释*/}

TP的注释是服务器注释，不会在浏览器源代码中被看到，html注释会被看到。在注释中不要出现大括号。

### 变量分配（高阶）

#### 一维数组

定义一个一维数组：

```php
public function test8(){
    $array = array('xixi', 'titi', 'momo', 'poip');
    $this -> assign('array', $array);
    $this -> display();
}
```

数组在模板中的输出语法：

- 中括号形式：{$array[0]}
- 点形式：{$array.key}

```html
<!DOCTYPE html>
<html>
    <body>
        View/Test/test8.html<br />
        中括号形式输出一维数组：{$array[0]} - {$array[1]} - {$array[2]} - {$array[3]}
        点形式输出一维数组：{$array.0} - {$array.1} - {$array.2} - {$array.3}
    </body>
</html>

```

输出结果：

![一维数组](ThinkPHP框架.assets/1555641143742.png)

#### 二维数组

二维数组的定义：

```php
$array = array(
    		array('00', '01', '02'),
    		array('10', '11', '12'),
    		array('20', '21', '22')
		);
```

模板输出使用点形式：array.0.0，array.1.1，array.2.2；

#### 对象

首先先创建一个对象，在/Application/Admin/Controller/中创建Student.class.php，如下：

```php
<?php
namespace Admin\Controller;

class Student{
}
?>
```

然后在/Application/Admin/Controller/TestController.class.php中添加方法test9

```php
public function test9(){
    // 实例化student对象
    $stu = new Student();
    // 给类的属性赋值
    $stu -> id = 100;
    $stu -> name = '马冬梅';
    $stu -> sex = '女';
    // dump($stu);
    $this -> assign('stu', $stu);
    $this -> display();
}
```

最后添加模板，其中TP输出对象有两种形式：箭头和冒号。

```html
<!DOCTYPE html>
<html>
    <body>
        View/Test/test9.html<br />
        箭头形式：{$stu -> id} - {$stu -> name} - {$stu -> sex};<br />
        冒号形式：{$stu : id} - {$stu : name} - {$stu : sex};
    </body>
</html>
```

访问地址http://192.168.5.129/index.php/Admin/Test/test9，得到如下结果：

![对象变量分配](ThinkPHP框架.assets/1555642616685.png)

> 这里需要注意命名空间的问题，如果对象定义不写命名空间（也不使用include、require），系统会默认现在当前空间下寻需要的元素，如果找不到就报错。当前的Student和TestController在同一个控制器中，正确。

#### 系统变量

超全局变量在模板中的使用：

- $Think.server：等价于$_SERVER，获取服务器相关信息
- $Think.get：等价于$_GET
- $Think.post：等价于$_POST
- $Think.request：等价于$_REQUEST
- $Think.cookie：等价于$_COOKIE
- $Think.session：等价于$_SESSION
- $Think.config：获取TP中的所有配置文件的总和，如果后面指定了元素，则获取指定元素。

使用语法：

> {$Think.xxx.具体元素的下标}

在TestController.class.php中定义方法进行展示

```php
    public function test10(){
        $this -> display();
    }
```

在模板中：

```html
<html>
    <body>
        $Think.get.id:{$Think.get.id}<br />
        $Think.config.DEFAULT_MODULE:{$Think.config.DEFAULT_MODULE}<br />
    </body>
</html>
```

访问地址http://192.168.5.129/index.php/Admin/Test/test10/id/1000得到结果如下：

![访问系统变量](ThinkPHP框架.assets/1555643338202.png)

### 视图中使用函数

语法格式：

> {$变量 | 函数名1  = 参数1 | 函数名2 = 参数2,  参数3, ###...}

$变量：模板变量

函数名1：需要使用的第一个函数

函数名2：需要使用的第二个函数

参数1：函数名1的参数

参数2， 参数3：函数名2的参数

###：模板变量本身

例子：将time()函数的时间戳通过date函数转换为格式化时间。

TestController.class.php

```php
    public function test11(){
        $time = time();
        $this -> assign('time', $time);
        $this -> display();
    }
```

模板：

```html
<html>
    <body>
        {$time | date = 'Y-m-d H:i:s', ###};
    </body>
</html>
```

例子：截取字符串的前五个字符，同时将其转换为大写。

> {$str | substr=0, 5 | strtoupper};

### 默认值

语法格式：

> {$变量名 | default=默认值};

当变量为空的时候，显示默认值。

### 文件包含

常用于网站的头部和尾部等公共的文件。在不同的文件中通过包含的方式进行引入。

语法：

> \<include file = '需要引入的模板文件' >

创建控制方法：

```php
     public function body(){
     	$this -> display();       
     } 
```

创建head、body、foot三个文件：

![创建文件](ThinkPHP框架.assets/1555680570787.png)

```html
<html>
    <head>
        <title>
            body
        </title>
    </head>
    <body>
        <include file='Test/head' />
        <div>do u like this</div>
        <include file='Test/foot' />
    </body>
</html>
```

除了上面的使用方法，include还可以传递值。如下：

```html
<html>
    <head>
        <title>
            body
        </title>
    </head>
    <body>
        <include file='Test/head' />
        <div>do u like this</div>
        <include file='Test/foot' title = 'PHP'/>
    </body>
</html>
```

在foot中传递了参数title，然后在foot.html中使用格式[title]进行输出。如下：

```html
<html>
    <head>
        <title>
            foot
        </title>
    </head>
    <body>
        <div>this is foot[title]</div>
    </body>
</html>
```

上面[title]的值会被替换为include中的title值：PHP

### 遍历元素

在模板中使用\<volist>标签进行元素遍历。

语法如下：

> \<volist name = '需要遍历的模板变量名' id = '当前遍历到的元素' >
>
> // 循环体
>
> \</volist>

```html
<volist name = 'array' id = 'vol'>
	{$vol}
</volist>
```

### if

语法如下：

```html
<body>
    <if condition = '$day == 1'>
    	星期一
	<elseif condition = '$day == 2' />
        星期二
    <elseif condition = '$day == 3' />
        星期三
    </if>
</body>
```

### php标签

在模板中使用PHP标签有两种格式：

- 原始PHP标签：\<?php echo 'hello'; ?>
- TP的PHP标签：\<php> echo 'hello'; \</php>

但是不建议在模板中使用PHP标签

同时可以通过配置项中的TMPL_DENY_PHP进行对原生PHP标签进行禁用，但是不建议关闭，因为有使用原生PHP的源代码。

## 9 模型

### 配置数据库连接

在/ThinkPHP/Conf/convention.php中可以找到对于数据的配置。

![数据库配置](ThinkPHP框架.assets/1555682299701.png)

但是在这里不会修改系统级别的配置，会将其复制到项目级别的config.php中进行针对每个项目配置。一般一个项目是对应一个数据库的。

路径如下：/Application/Common/Conf/config.php

样例，创建一个db_oa，配置config.php如下：

```php
<?php
return array(
	//'配置项'=>'配置值'
    //
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'db_oa',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '1',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sp_',    // 数据库表前缀
);
?>
```

创建表结构如下：

![table创建](ThinkPHP框架.assets/1555683285467.png)

### 模型的创建

模型的作用是跟数据库的数据交互。

命名规范：

> 模型名（不带前缀，首字母大写） + Model.class.php

根据数据库在项目目录/Application/Admin/Model中创建DeptModel.class.php

空模型格式如下：

```php
<?php
namespace Admin\Model;

use Think\Model;

class DeptModel extends Model{

}
?>
```

空模型也能够进行数据库表的基本操作，因为他继承了Model父类。

### 模型的实例化操作

