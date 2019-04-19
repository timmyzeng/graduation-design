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

