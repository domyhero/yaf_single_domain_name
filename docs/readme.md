[TOC]

### 一、注意事项 ###

#### 1、目录可写设置 ####

​	由于首页需要静态化，所以 sites/frontend目录需要设置为可写权限。

#### 2、资源跨域 ####

​	由于静态资源文件需要在前台域下访问。所以，会出现跨域限制访问的问题。所以，需要在 nginx 或 apache 。

for Apache:

1)  httpd.conf去掉`LoadModule headers_module modules/mod_headers.so ` 前面的 # 号。 

2) 在对应的域名主机配置中添加如下代码：

```
<IfModule mod_headers.c>
	   Header set Access-Control-Allow-Origin "*"
</IfModule>
```

完整示例代码：

```
<VirtualHost *:80>
    ServerAdmin webmaster@dummy-host2.example.com
    DocumentRoot "D:\workspace\osc_git\phpgold\statics"
    ServerName dev-statics.phpgold.com
	<IfModule mod_headers.c>
	   Header set Access-Control-Allow-Origin "*"
	</IfModule>
    ErrorLog "logs/dev-statics.phpgold.com-error.log"
    CustomLog "logs/dev-statics.phpgold.com-access.log" common
</VirtualHost>
```



for Nginx:

```
location ~* \.(eot|otf|ttf|woff)$ {
    add_header Access-Control-Allow-Origin *;
}
```



### 二、正式环境部署 ###

​	由于在日常的开发中，会存在开发环境、测试环境、正式环境。每个环境对应的域名、数据库、其他配置的不同之处。因此，在升级更新代码的时候，就变得非常的繁琐。于是，本程序开发了一个部署工具来解决这个问题。

#### 1、运行部署工具 ####

​	部署工具在项目根据目录下，名叫 deplop.php 文件。要执行该文件，需要当前系统环境中已经增加了 php 到系统环境变量中。

```
php deploy.php
```

​	在命令行执行如下命令，会提示你选择要部署的环境。有三个值可供选择：dev or test or product。根据自己的环境输入对应的值即可自动完成代码环境部署。

​	

### 三、网站静态化###

#### 1、文章列表页静态化####



#### 2、文章详情页静态化####



