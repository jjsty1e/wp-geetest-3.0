============
ReadMe
============



开发流程
===========

1. 在本地git目录下开发插件。
#. 本地wordpress做好单元测试。
#. 构思好新的发布版本号，写到readme.txt的 Stable tag位置。
    例如   Stable tag: 5.0.4
#. 修改main.php里面的 注释部分的 版本号为最新。 5.0.4



发布流程
============

1. 将git下的源码复制到wordpress 插件的svn目录下。
#. 提交代码到wordpress插件库服务器。
#. 创建tag,比如：5.0.4
#. 在testcenter上搭建wordpress进行安装测试


常见发布版本问题
=====================

1. 发布插件版本问题
	http://codex.wordpress.org/Writing_a_Plugin

	Troubleshooting:
	The Plugin's page on wordpress.org still lists the old version. Have you updated the 'stable tag' field in the trunk folder? Just creating a tag and updating the readme.txt in the tag folder is not enough!
	The Plugin's page offers a zip file with the new version, but the button still lists the old version number and no update notification happens in your WordPress installations. Have you remembered to update the 'Version' comment in the main PHP file?
	For other problems check Otto's good write-up of common problems: The Plugins directory and readme.txt files


2. 在线安装时的ftp输入问题
    http://jingyan.baidu.com/article/d2b1d1026d46e95c7e37d400.html


官方开发FQA
================

https://wordpress.org/plugins/about/faq/

非官方的中文参考
========================
http://blog.wpjam.com/article/listing-your-plugin-at-the-wordpressorg-plugin-directory/