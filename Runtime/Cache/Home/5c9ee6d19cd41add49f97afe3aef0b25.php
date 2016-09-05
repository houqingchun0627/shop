<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>京东商城</title>
    <!-- 公共模块样式 -->
    <link rel="stylesheet" href="/shop/Public/Home/Style/base.css" type="text/css">
    <link rel="stylesheet" href="/shop/Public/Home/Style/global.css" type="text/css">
    <link rel="stylesheet" href="/shop/Public/Home/Style/header.css" type="text/css">
    <!-- <link rel="stylesheet" href="/shop/Public/Home/Style/bottomnav.css" type="text/css"> -->
    <link rel="stylesheet" href="/shop/Public/Home/Style/footer.css" type="text/css">

    <script type="text/javascript" src="/shop/Public/Home/Js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/shop/Public/Home/Js/header.js"></script>   
</head>
<body>
    <!-- 顶部导航 start -->
    <div class="topnav">
        <div class="topnav_bd w1210 bc">
            <div class="topnav_left">
                
            </div>
            <div class="topnav_right fr">
                <ul>
                    <li id="user_login"></li>
                    <li class="line" id="line1">|</li>
                    <li id="user_order"></li>
                    <li class="line">|</li>
                    <li>客户服务</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 顶部导航 end -->
    <div style="clear:both;"></div>

    <!-- 引入布局文件 -->

<!-- 引入页面样式文件 -->
<link rel="stylesheet" href="/shop/Public/Home/Style/login.css" type="text/css">

<style>
	.onShow {
		background-image: url("/shop/Public/Home/Images/onShow.gif");
		background-position: 9px 9px;
	}
	.onCorrect {
	    background-color: #e9ffeb;
	    background-image: url("/shop/Public/Home/Images/onCorrect.gif");
	    border-color: #cdefd0;
	    color: #080;
	}
	.onError {
	    background-color: #fff2e9;
	    background-image: url("/shop/Public/Home/Images/onError.gif");
	    border-color: #f3dbcb;
	    color: #c00;
	}
	.ui-message {
	    background-color: #fcfcfc;
	    background-position: 8px 8px;
	    background-repeat: no-repeat;
	    border: 1px solid #eee;
	    border-radius: 2px;
	    color: #666;
	    float: left;
	    font-family: Arial,Helvetica,sans-serif;
	    font-size: 12px;
	    width: 130px;
	    height: 31px;
	    line-height: 31px;
	    overflow: hidden;
	    padding: 0 10px 0 30px;
	}

	.fn-clear::after {
	    clear: both;
	    content: " ";
	    display: block;
	    font-size: 0;
	    height: 0;
	    visibility: hidden;
	}
</style>
	
<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
	<div class="logo w990">
		<h2 class="fl"><a href="<?php echo U('Goods/index');?>"><img src="/shop/Public/Home/Images/logo.png" alt="京东商城"></a></h2>
	</div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10">
	<div class="login_hd">
		<h2>用户登录</h2>
		<b></b>
	</div>
	<div class="login_bd">
		<div class="login_form fl">
			<form id="loginForm" action="" method="post" onsubmit="return false">
				<ul>
					<li class="fn-clear">
						<label for="">用户名：</label>
						<input type="text" id="user_name" class="txt" name="user_name" onblur="checkLogin(this,0);" style="float:left;"/>
						<span id="user_name_msg" class="login_msg ui-message onShow ">请输入用户名</span>
					</li>
					<li class="fn-clear">
						<label for="">密码：</label>
						<input type="password" id="user_password" class="txt" name="user_password"  onblur="checkLogin(this,0);" style="float:left;padding-right:6px"/>
						<span id="user_password_msg" class="login_msg ui-message onShow ">请输入登录密码</span>
					</li>
					<li class="checkcode fn-clear">
						<label for="">验证码：</label>
						<input id="captcha" id="captcha" name="captcha" type="text"  onblur="checkLogin(this,0);" style="float:left;margin-right:30px;">
                        <img id="imgVerify" src="<?php echo U('captcha');?>" onclick="fleshVerify();" alt="看不清，换一张？" style="float:left;padding-right:16px"/>
						<span id="captcha_msg" class="login_msg ui-message onShow ">验证码，不区分大小写</span>
					</li>
					<li>
						<label for="">&nbsp;</label>
						<input type="checkbox" class="chb"/> 保存登录信息
						<span style="margin-left:25px;"><a href="<?php echo U('forgetPassword');?>">忘记密码?</a></span>
						<span style="margin-left:25px;"><a href="javascript:fleshVerify();">验证码，看不清？换一张</a></span>
						
					</li>						
					<li>
						<label for="">&nbsp;</label>
						<input type="submit" value="" class="login_btn" onclick="checkLogin(this,1);"/>
					</li>
				</ul>
			</form>

			<div class="coagent mt15">
				<dl>
					<dt>使用合作网站登录商城：</dt>
					<dd class="qq"><a href=""><span></span>QQ</a></dd>
					<dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
					<dd class="yi"><a href=""><span></span>网易</a></dd>
					<dd class="renren"><a href=""><span></span>人人</a></dd>
					<dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
					<dd class=""><a href=""><span></span>百度</a></dd>
					<dd class="douban"><a href=""><span></span>豆瓣</a></dd>
				</dl>
			</div>
		</div>
		
		<div class="guide fl">
			<h3>还不是商城用户</h3>
			<p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

			<a href="regist.html" class="reg_btn">免费注册 >></a>
		</div>

	</div>
</div>
<!-- 登录主体部分end -->

<div style="clear:both;"></div>
<!-- 页面的javascript部分start -->
<script>
/********************点击刷新验证码**********************/
	function fleshVerify(){
      	//重载验证码
      	$('#imgVerify').attr('src',"<?php echo U('captcha','',FALSE); ?>/r/"+Math.random());
  	}
/********************远程ajax验证会员登录信息**************/
  	//注册信息验证方法
  	var validator={
		'user_name':[['用户名不能为空'],['该用户名不存在'],['用户名格式错误'],['该用户名可用']],
		'user_password':[['登录密码不能为空'],['登录密码填写正确'],['登录密码格式错误'],['登录密码错误']],
		'captcha':[['验证码不能为空'],['验证码填写正确'],['验证码格式错误'],['验证码错误']]
	};
  	function checkLogin(i,c){
  		//获取当前input输入框的id属性
  		var id = $(i).attr('id');
  		var info = true;
  		var data = {};
  		//如果是onblur事件
  		if(c == 0){
  			info = validate(id);
  			data[id] = $(i).val();
  			if(id === "user_password"){
			  	data["user_name"] = $("#user_name").val();
			}
			data["flag"] = "3";
  		}
  		//如果是提交表单操作
  		if(c == 1){
  			//如果是submit提交注册表单数据
			// var data = $('#reg_form').serialize();
			for (var k in validator){
			  	var sign = validate(k);
				data[k] = $("#"+k).val();
				if(!sign){
			  		//如果格式错误
			  		info = false;
			  	}	
			}
			data["flag"] = "4";
  		}
  		//判断是否验证成功
  		if(info){
  			//如果格式验证成功，执行ajax验证唯一性
  			// 执行ajax进行用户验证
		    $.ajax({
		       type: "POST",
		       // data: {user_name:user_name,user_tel:user_tel,user_mail:user_mail,captcha:captcha},
		       data: data,
		       url: "<?php echo U('Home/User/checkReg','',FALSE) ?>",
		       dataType: "json",
		       success: function(mess){
			        // 获取返回的验证信息
			        var msg = validator[mess.item][mess.code];
			        // 如果验证失败
			        if(mess.code !== "1"){
			        	if(mess.item === "user_name"){
				        	//如果用户名已经被注册：表示存在
				        	$("#"+mess.item+"_msg").html(msg).removeClass('onError').addClass('onCorrect');
				        	return true;
				        }
			        	//如果验证失败
			        	$("#"+mess.item+"_msg").html(msg).removeClass('onCorrect').addClass('onError');
			        	return false;
			        }
			        //如果验证成功，并且是onsubmit事件
			        if(mess.code === "1" && c == 1){
		                //如果返回的状态码为1，并且是提交操作，表示全部验证成功，执行跳转操作
		                window.top.location.href= mess.url;
		                return true;
		            }
					// 如果验证成功，并且不是onsubmit事件
					if(mess.item === "user_name"){
			        	//如果用户名已经被注册：表示存在
			        	$("#"+mess.item+"_msg").html(msg).removeClass('onError').addClass('onError');
			        	return true;
				    }
			        $("#"+mess.item+"_msg").html(msg).removeClass('onError').addClass('onCorrect');
			        return true;
		        }
		    });
  		}
  	}
  	function validate(item){
  		//获取当前input输入框的属性值
  		// var msg;
  		var val = $("#"+item).val();
  		var msg1 = validator[item][0];	//为空
  		var msg2 = validator[item][1];	//填写正确
  		var msg3 = validator[item][2];	//格式错误
  		//获取当前input输入框的value属性值
	    var usernameReg=new RegExp("^[a-zA-Z0-9_\@]{3,20}$");
	    var passwordReg=new RegExp("^[a-zA-Z0-9_\@\#\+\-]{6,20}$");
	    var captchaReg=new RegExp("^[a-zA-Z0-9_]{4}$");
	    //验证input输入框是否为空 
	    if(val === ""){
	    	var msg = msg1;
	    }else{
	    	//如果是验证用户名的格式正确性
		    if(item === "user_name" && !usernameReg.test(val)){
		    	//用户名格式错误
		    	var msg = msg3;
		    }
		    //如果是验证登录密码的格式正确性
		    if(item === "user_password" && !passwordReg.test(val)){
		    	//登录密码格式错误
		    	var msg = msg3;
		    }
		    //如果是验证验证码的格式正确性
		    if(item === "captcha"  && !captchaReg.test(val)){
		    	//如果验证码格式错误
		    	var msg = msg3;
		    }
	    }
	    //判断是否有错误信息
	    if(msg !== undefined){
	    	// $("#"+id).val("");
	        // $("#"+id)[0].focus();
	        $("#"+item+"_msg").html(msg).removeClass('onCorrect').addClass('onError');
	        return(false);
	    }
	    return true;
  	}
</script>
<!-- 页面的javascript部分end -->
    
    <div style="clear:both;"></div>
    <!-- 底部导航 end -->
    <div style="clear:both;"></div>
    <!-- 底部版权 start -->
    <div class="footer w1210 bc mt10">
        <p class="links">
            <a href="">关于我们</a> |
            <a href="">联系我们</a> |
            <a href="">人才招聘</a> |
            <a href="">商家入驻</a> |
            <a href="">千寻网</a> |
            <a href="">奢侈品网</a> |
            <a href="">广告服务</a> |
            <a href="">移动终端</a> |
            <a href="">友情链接</a> |
            <a href="">销售联盟</a> |
            <a href="">京东论坛</a>
        </p>
        <p class="copyright">
             © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
        </p>
        <p class="auth">
            <a href=""><img src="/shop/Public/Home/Images/xin.png" alt="" /></a>
            <a href=""><img src="/shop/Public/Home/Images/kexin.jpg" alt="" /></a>
            <a href=""><img src="/shop/Public/Home/Images/police.jpg" alt="" /></a>
            <a href=""><img src="/shop/Public/Home/Images/beian.gif" alt="" /></a>
        </p>
    </div>
    <!-- 底部版权 end -->
    <!-- 页面的javascript代码 -->
    <script type="text/javascript">
        $(document).ready(function() {
            //ajax动态判断用户是否登录，如果已经登录，获取用户的user_id和user_name
            $.ajax({
                type : "GET",
                url : "<?php echo U('User/ajaxGetUserIsLogin', '', FALSE); ?>",
                dataType : "json",
                success : function(data){
                    var userName = data['user_name'];
                    var userId = data['user_id'];
                    if(userName === ""){
                        //如果用户名为空，表示未登录
                        var userLogin = '您好，欢迎来到京东！[<a href="<?php echo U('User/login'); ?>">登录</a>] [<a href="<?php echo U('User/regist'); ?>">免费注册</a>]';
                        var isLogin = '您好，你尚未登录，请先<a href="<?php echo U('User/login'); ?>">登录</a>';
                        $("#line1").text("");
                    }else{
                        //如果用户名不为空，表示已经登录
                        var userLogin = '您好，<a href="<?php echo U('User/index'); ?>">'+userName+'</a>&nbsp;&nbsp;&nbsp; [<a href="<?php echo U('User/logout'); ?>">退出</a>]';
                        var userOrder = '<a href="<?php echo U('User/order'); ?>" target="_blank">我的订单</a>';
                        var isLogin = '您好，<a href="<?php echo U('User/index'); ?>">'+userName+'</a>';
                        var userInfo = '<ul class="list1 fl"><li><a href="<?php echo U('User/index'); ?>">用户信息></a></li><li><a href="<?php echo U('User/address'); ?>">收货地址></a></li><li><a href="<?php echo U('User/order'); ?>">我的订单></a></li><li><a href="<?php echo U('User/comment'); ?>">我的评论></a></li></ul><ul class="fl"><li><a href="<?php echo U('User/message'); ?>">我的留言></a></li><li><a href="<?php echo U('User/collection'); ?>">我的收藏></a></li><li><a href="<?php echo U('User/hongbao'); ?>">我的红包></a></li><li><a href="<?php echo U('User/account'); ?>">资金管理></a></li></ul>';
                        
                        $("#user_info").html(userInfo);
                        $("#user_order").html(userOrder);
                    }
                    $("#user_login").html(userLogin);
                    $("#is_login").html(isLogin);
                }
            });
        });
    </script>
    </body>
</html>