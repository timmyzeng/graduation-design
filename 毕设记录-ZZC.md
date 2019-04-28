# 毕设记录-ZZC

## 19.3.15 选择环境及部署方案

[点击跳转](./选择环境及部署方案.md)

## 19.3.16 安装虚拟机并配置基本

使用VM14，安装Linux虚拟机，版本centos 7。

配置centos 7：更新内核、开启ssh连接、安装vimplus（vim配置）、更换yum源为ali源、安装wget、安装git

更新内核

> sudo yum update （耗时较长）

## 19.3.17 安装并测试LNMP环境：Nginx+Mysql+PHP

[点击跳转](./安装并测试LNMP环境.md)

## 19.4.10 更新PHP.md

关于图片的上传，遇到问题权限不足，需要修改目录的权限为0755

## 19.4.13 更新PHP.md

1. 文件处理函数：file_put_contents；file_get_contents
2. 文件读取流程
3. gd2图片处理库
4. 正则表达式

## 19.4.15 更新PHP.md

正则表达式进行用户注册

## 19.4.16 更新PHP.md

1. session进行记录保存
2. 安装phpMyAdmin

## 19.4.17 更新PHP.md

### PHP扩展xdebug

因为使用var_dump()或者print_r()函数在浏览器中显示的是字符串形式，这样不方便观察结果。此时需要扩展xdebug的帮忙。

### PHP链接数据库

这里主要使用了mysqli进行连接，其中这个是PHP7.0以上使用的。

进行了一个数据库查询的实战练习。包括连接数据库、设置字符集、选择数据库、书写sql语句、发送sql请求、查看处理返回结果、关闭数据库连接。

在这里有一个之前接触较少的是进行分页操作。

## 19.4.18 整理PHP.md

[点击跳转](./PHP.md)

## 19.4.19-19.4.26

学习如何使用ThinkPHP框架，参考PHP中文网上的视频资料。

[点击跳转](./ThinkPHP框架.md)

## 19.4.26-19.4.28

学习使用jQuery以及Boostrap，参考资料为菜鸟教程、PHP中文网上的视频。

[点击跳转](./Bootstrap.md)