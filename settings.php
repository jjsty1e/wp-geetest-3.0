<?php   
  function _checked($value){
    if($value == "1"){
       echo "checked";
    }
  }
?>
<div class="wrap">
   <a name="geetest"></a>
   <h2>极验设置</h2>   
   
   <form id="setting_form" method="post" action="options.php">
      <?php settings_fields('geetest_options_group'); ?>
      <h3>KEY</h3>
      <p>请访问<a href="http://www.geetest.com" title="注册">geetest极验验证</a>点击注册申请自己的key.<h3>如果是在登陆页面安装验证，请确保填入正确的公钥和私钥，避免造成管理员无法登陆。</h3></p>
      
      <table class="form-table">
	  	<tr valign="top">
            <th scope="row">您的极验公钥</th>
            <td>
               <input id="input_public_key" type="text" name="geetest_options[public_key]" size="40" value="<?php echo $this->options['public_key']; ?>" />
            </td>
         </tr>
         <tr valign="top">
            <th scope="row">您的极验私钥</th>
            <td>
               <input id="input_public_key"  type="text" name="geetest_options[private_key]" size="40" value="<?php echo $this->options['private_key']; ?>" />               
            </td>
         </tr>
      </table>
      <h3>评论设置</h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row">开启</th>
            <td>
               <input type="checkbox" id ="geetest_options[show_in_comments]" name="geetest_options[show_in_comments]" value="1" <?php _checked($this->options['show_in_comments']); ?> />
               <label for="geetest_options[show_in_comments]">评论使用验证码</label>
            </td>
         </tr>
      </table>
      <h3>登陆设置</h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row">开启</th>
            <td>
               <input type="checkbox" id ="geetest_options[show_in_login]" name="geetest_options[show_in_login]" value="1" <?php _checked($this->options['show_in_login']); ?> />
               <label for="geetest_options[show_in_login]">登陆使用验证码</label>
            </td>
         </tr>
      </table>      
      <h3>注册设置</h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row">开启</th>
            <td>
               <input type="checkbox" id ="geetest_options[show_in_registration]" name="geetest_options[show_in_registration]" value="1" <?php _checked($this->options['show_in_registration']); ?> />
               <label for="geetest_options[show_in_registration]">注册使用验证码</label>
            </td>
         </tr>
      </table>
      <h3>英文版验证</h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row">开启</th>
            <td>
               <input type="checkbox" id ="geetest_options[lang_options]" name="geetest_options[lang_options]" value="1" <?php _checked($this->options['lang_options']); ?> />
               <label for="geetest_options[lang_options]">使用英文版验证(不选中则是中文版)</label>
            </td>
         </tr>
      </table>
      <p class="submit"><input type="submit" class="button-primary" title="保存更改" value="保存更改 &raquo;" /></p>
   </form>
   
   <?php do_settings_sections('geetest_options_page'); ?>
</div>
<script type="text/javascript">
    var show_in_login = document.getElementById('geetest_options[show_in_login]');
    var setting_form = document.getElementById('setting_form');
    var input_public_key = document.getElementById('input_public_key');
    var input_private_key = document.getElementById('input_private_key');
    setting_form.onsubmit=function(){
        if(show_in_login.checked == true){
            var a =  window.confirm("你设置了登陆验证码。\n请确保输入正确的公钥和私钥。\n以免造成管理员无法登陆的麻烦。");
            if(a){
                 if(input_public_key.value=="" || input_private_key.value=="" ){
                       alert("你未设置正确的公钥和私钥。");
                       return false;
                }
              return true;
            }else{
              return false;
            } 
        }
    }
</script>