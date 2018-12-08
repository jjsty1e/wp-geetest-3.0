# wp-geetest@3.0

老版本的geetest的wordpress插件已经不能用了，主要原因是极验验证的接口从2.0升级到3.0的原因，
如果你也在wp博客中使用geetest，希望能帮到你。

![](assets/geetest.gif)

## 功能

- 注册验证 ✅
- 登录验证 ✅
- 评论验证（几乎可以屏蔽100%的spam内容）✅

## 使用方法：

    cd /path/to/worpress-blog/wp-content/plugins
    git clone git@github.com:Jaggle/wp-geetest-3.0.git
    

> 或者你用ftp上传也行
    

修改权限：

    chown -R [你web进程的用户] wp-geetest-3.0


然后到wordpress后台`已安装的插件`页面启用后，填入你在[GEETEST 极验验证](http://www.geetest.com/)官网申请的public_key和private_key即可。

## 声明

本插件由[GEETEST 极验验证](http://www.geetest.com/)以GPL协议发布，本人仅对该插件做了
稍许修复。

    
