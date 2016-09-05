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

<!-- 引入顶部导航栏 -->
<!-- 引入页面样式文件 -->
<link rel="stylesheet" href="/shop/Public/Home/Style/cart2.css" type="text/css">

<div style="clear:both;"></div>
<!-- 头部 start -->
<div class="header w1210 bc mt15">
	<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
	<div class="logo w1210">
		<h1 class="fl"><a href="<?php echo U('Goods/index');?>"><img src="/shop/Public/Home/Images/logo.png" alt="京东商城"></a></h1>
		<!-- 头部搜索 start -->
		<div class="search fl">
			<div class="search_form">
				<div class="form_left fl"></div>
				<form action="" name="serarch" method="" class="fl" onsubmit="return false">
					<input type="text" id="keyword_search" class="txt" name="qkey" placeholder="请输入搜索关键字" value="" /><input type="submit" class="btn" value="搜索" onClick="if($('#keyword_search').val() !='') goods_keywords_search();"/>
				</form>
				<div class="form_right fl"></div>
			</div>
			
			<div style="clear:both;"></div>

			<div class="hot_search">
				<strong>热门搜索:</strong>
				<a href="">D-Link无线路由</a>
				<a href="">休闲男鞋</a>
				<a href="">TCL空调</a>
				<a href="">耐克篮球鞋</a>
			</div>
		</div>
		<!-- 头部搜索 end -->

		<!-- 用户中心 start-->
		<div class="user fl">
			<dl>
				<dt>
					<em></em>
					<a href="<?php echo U('User/index');?>" target="_blank">用户中心</a>
					<b></b>
				</dt>
				<dd>
					<div class="prompt" id="is_login">
						
					</div>
					<div class="uclist mt10" id="user_info">
						
					</div>
					<div style="clear:both;"></div>
					<div class="viewlist mt10">
						<h3>最近浏览的商品：<a href="<?php echo U('Home/User/history');?>" title="" style="color: #005ea7;margin-left: 128px;text-decoration: none;">查看更多>></a></h3>
						<ul id="nav_goods_history">
							
						</ul>
					</div>
				</dd>
			</dl>
		</div>
		<!-- 用户中心 end-->

		<!-- 购物车 start -->
		<div class="header_cart cart fl">
			<dl>
				<dt>
					<a href="<?php echo U('Cart/cart');?>">去购物车结算</a>
					<span class="shopping-num">
						<em id="cart_quantity">0</em>
					</span>
					<b></b>
				</dt>
				<dd>
					<div class="prompt user_cart_goods">
						<!-- ajax动态获取购物车中商品的详细信息 -->
					</div>
				</dd>
			</dl>
		</div>
		<!-- 购物车 end -->
	</div>
	<!-- 头部上半部分 end -->
	
	<div style="clear:both;"></div>

	<!-- 导航条部分 start -->
	<div class="nav w1210 bc mt10">
		<!--  商品分类部分 start-->
		<!-- 非首页，需要添加cat1类 -->
		<div class="category fl <?php if($pageConfig['catConfig'] === '1') echo 'cat1'; ?>"> 
			<!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
			<div class="cat_hd <?php if($pageConfig['catConfig'] === '1') echo 'off'; ?>">  
				<h2>全部商品分类</h2>
				<em></em>
			</div>
			
			<div class="cat_bd <?php if($pageConfig['catConfig'] === '1') echo 'none'; ?>">
				<!-- 遍历商品分类，循环输出 -->
				<?php if(is_array($catList)): $k = 0; $__LIST__ = $catList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><!-- 第一个一级分类class加item1 -->
				    <div class="cat <?php if($k == 1): ?>item1<?php endif; ?>">
					<h3><a href="<?php echo U('Goods/goodsList',array('cat_id' => $v['cat_id']),FALSE);?>" target="_blank"><?php echo ($v["cat_name"]); ?></a> <b></b></h3>
					    <div class="cat_detail">
					    	<!-- 第一个二级分类class加dl_1st -->
					    	<?php if(is_array($v['children'])): $k1 = 0; $__LIST__ = $v['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($k1 % 2 );++$k1;?><dl <?php if($k1 == 1): ?>class="dl_1st"<?php endif; ?>>
									<dt><a href="<?php echo U('Goods/goodsList',array('cat_id' => $v1['cat_id']),FALSE);?>" target="_blank"><?php echo ($v1["cat_name"]); ?></a></dt>
									<dd>
										<?php if(is_array($v1['children'])): $k2 = 0; $__LIST__ = $v1['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($k2 % 2 );++$k2;?><a href="<?php echo U('Goods/goodsList',array('cat_id' => $v2['cat_id']),FALSE);?>" target="_blank"><?php echo ($v2["cat_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
									</dd>
								</dl><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>

		</div>
		<!--  商品分类部分 end--> 

		<div class="navitems fl">
			<ul class="fl">
				<li class="current"><a href="<?php echo U('Goods/index');?>">首页</a></li>
				<li><a href="">电脑频道</a></li>
				<li><a href="">家用电器</a></li>
				<li><a href="">品牌大全</a></li>
				<li><a href="">团购</a></li>
				<li><a href="">积分商城</a></li>
				<li><a href="">夺宝奇兵</a></li>
			</ul>
			<div class="right_corner fl"></div>
		</div>
	</div>
	<!-- 导航条部分 end -->
</div>
<!-- 头部 end-->
<div style="clear:both;"></div>

<!-- 页面的javascript代码 -->
<script type="text/javascript">
/************处理用户通过关键字搜索商品*********************/
	function goods_keywords_search(){
		var keywords = $("#keyword_search").val();
		var url = "<?php echo U('Home/Goods/goodsList','',FALSE);?>/qkey/"+keywords;
		//利用js代码，进行get请求跳转
 		location.href = url ;
	}
/***************处理用户浏览商品历史记录********************************/
	function ajaxGetNavGoodsHistory(){
        //ajax动态判断用户是否登录，如果已经登录，获取用户的user_id和user_name
        $.ajax({
            type : "GET",
            url : "<?php echo U('Home/Goods/ajaxGetNavHistory', '', FALSE); ?>",
            success : function(data){
                $("#nav_goods_history").append(data);
            }
        });
	}
/***************处理用户购物车中的商品详细信息********************************/
	function ajaxGetUserCart(){
        //ajax动态判断用户是否登录，如果已经登录，获取用户的user_id和user_name
        $.ajax({
            type : "GET",
            url : "<?php echo U('Home/Cart/ajaxGetUserCartInfo', '', FALSE); ?>",
            success : function(data){
            	$(".user_cart_goods").html("");
                $(".user_cart_goods").append(data);
            }
        });        
	}
/***************处理用户购物车中的商品详细信息********************************/
	function ajaxGetUserCartGoodsNumber(){
        //ajax动态判断用户是否登录，如果已经登录，获取用户的user_id和user_name
        $.ajax({
            type : "GET",
            url : "<?php echo U('Home/Cart/ajaxGetUserCartGoodsNumber', '', FALSE); ?>",
            success : function(data){
            	$("#cart_quantity").html("");
                $("#cart_quantity").html(data);
            }
        });
	}
/***************处理ajax删除购物车中的信息********************************/
	function ajaxDeleteCartGoods(id){
        //ajax动态判断用户是否登录，如果已经登录，获取用户的user_id和user_name
        if(confirm("确定删除该商品？")){
	        $.ajax({
	            type : "GET",
	            url : "<?php echo U('Home/Cart/ajaxDeleteCartGoods', '', FALSE); ?>/id/"+id,
	            success : function(data){
	            	//给客户端返回添加结果
	            	alert(data);
	            	if(data === "删除成功"){
		            	//触发页面顶部购物车信息，更新添加的数据
		            	ajaxGetUserCart();
		    			ajaxGetUserCartGoodsNumber();
	            	}
	            }
	        });
        }
	}
/**********************处理用户浏览商品记录***************************/
    $(document).ready(function() {
    	//触发动态获取用户商品浏览历史记录
    	ajaxGetNavGoodsHistory();
    	//触发动态获取用户购物车中的信息
    	ajaxGetUserCart();
    	ajaxGetUserCartGoodsNumber();
    });
</script>

<!-- 引入页面样式文件 -->
<link rel="stylesheet" href="/shop/Public/Home/Style/order2.css" type="text/css">
<link rel="stylesheet" href="/shop/Public/Home/Style/ajax_page.css" type="text/css">

<div style="clear:both;"></div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
  <!-- 面包屑导航 start -->
  <div class="breadcrumb crumb w1210">
    <h2>当前位置：<a href="<?php echo U('Goods/index');?>">首页</a> >
      <a href="<?php echo U('User/index');?>">用户中心</a> >
      <?php echo ($pageConfig["pageName"]); ?>
    </h2>
  </div>

	<!-- 引入左侧导航栏 -->
	<!-- 引入页面样式文件 -->
<link rel="stylesheet" href="/shop/Public/Home/Style/home.css" type="text/css">
<script type="text/javascript" src="/shop/Public/Home/Js/home.js"></script>

<!-- 左侧导航菜单 start -->
<div class="menu fl">
    <h3>我的账户</h3>
    <div class="menu_wrap">
        <dl>
            <dt>账户中心 <b></b></dt>
            <dd <?php if($pageConfig["sideBarTheme"] == 'index'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/index');?>">基本信息</a></dd>
            <!-- <dd><b>.</b><a href="<?php echo U('User/extra');?>">账户余额</a></dd>
            <dd><b>.</b><a href="<?php echo U('User/expense');?>">消费记录</a></dd> -->
            <dd <?php if($pageConfig["sideBarTheme"] == 'jifen'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/jifen');?>">我的积分</a></dd>
            <dd <?php if($pageConfig["sideBarTheme"] == 'address'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/address');?>">收货地址</a></dd>
        </dl>
        <dl>
            <dt>订单中心 <b></b></dt>
            <dd <?php if($pageConfig["sideBarTheme"] == 'order'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/order');?>">我的订单</a></dd>
            <dd <?php if($pageConfig["sideBarTheme"] == 'comment'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/comment');?>">我的评价</a></dd>
            <!-- <dd><b>.</b><a href="<?php echo U('User/cancelOrder');?>">取消订单记录</a></dd> -->
            <dd <?php if($pageConfig["sideBarTheme"] == 'backOrder'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/backOrder');?>">返修/退换货</a></dd>
            <!-- <dd><b>.</b><a href="<?php echo U('User/complaint');?>">我的投诉</a></dd> -->
            <!-- <dd><b>.</b><a href="<?php echo U('User/group');?>">我的团购</a></dd> -->
        </dl>
        <dl>
            <dt>收藏中心 <b></b></dt>
            <dd <?php if($pageConfig["sideBarTheme"] == 'history'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/history');?>">浏览历史记录</a></dd>
            <dd <?php if($pageConfig["sideBarTheme"] == 'collection'): ?>class="cur"<?php endif; ?>><b>.</b><a href="<?php echo U('User/collection');?>">我的收藏</a></dd>
        </dl>
    </div>
</div>
<!-- 左侧导航菜单 end -->

	<!-- 右侧内容区域 start -->
    <div class="layout pa-to-10 fo-fa-ar">
        <div class="fr wi940">
            <div class="he50 wddd">
                <div class="fl ddd-h2">
                    <h2><span>我的订单</span></h2>
                </div>
            </div>
            <div class="wddd-li">
                <ul>
                    <li class="wddd-red" id="ALL"><a href="javascript:check_field('ALL');">全部 <span id="ALL_ORDER" class="check_order">↓</span></a></li>
                    <li id="FINISHED"><a href="javascript:check_field('FINISHED');">已完成 <span id="FINISHED_ORDER" class="check_order"></span></a></li>
                    <li id="WAITPAY"><a href="javascript:check_field('WAITPAY');">待付款 <span id="WAITPAY_ORDER" class="check_order"></span></a></li>
                    <li id="WAITSEND"><a href="javascript:check_field('WAITSEND');">待发货 <span id="WAITSEND_ORDER" class="check_order"></span></a></li>
                    <li id="WAITRECEIVE"><a href="javascript:check_field('WAITRECEIVE');">待收货 <span id="WAITRECEIVE_ORDER" class="check_order"></span></a></li>
                    <li id="WAITCCOMMENT"><a href="javascript:check_field('WAITCCOMMENT');">待评价 <span id="WAITCCOMMENT_ORDER" class="check_order"></span></a></li>
                    <!-- 查看字段 -->
                    <input type="hidden" id="checked_order_field" name="checked_order_field" size="16" value="ALL" />
                    <!-- 排序方式 -->
                    <input type="hidden" id="checked_order_order" name="checked_order_field" size="16" value="DESC" />
                </ul>
            </div>
            <div id="right_sideBar" class="wddd-js ov-in">
                <!-- ajax动态获取用户的商品订单 -->
            </div>
        </div>
    </div>
	<!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>

<!-- 引入底部导航栏  -->
<!-- 引入页面样式文件 -->
<link rel="stylesheet" href="/shop/Public/Home/Style/bottomnav.css" type="text/css">

<div style="clear:both;"></div>
<!-- 底部导航 start -->
<div class="bottomnav w1210 bc mt10">
	<div class="bnav1">
		<h3><b></b> <em>购物指南</em></h3>
		<ul>
			<li><a href="">购物流程</a></li>
			<li><a href="">会员介绍</a></li>
			<li><a href="">团购/机票/充值/点卡</a></li>
			<li><a href="">常见问题</a></li>
			<li><a href="">大家电</a></li>
			<li><a href="">联系客服</a></li>
		</ul>
	</div>
	
	<div class="bnav2">
		<h3><b></b> <em>配送方式</em></h3>
		<ul>
			<li><a href="">上门自提</a></li>
			<li><a href="">快速运输</a></li>
			<li><a href="">特快专递（EMS）</a></li>
			<li><a href="">如何送礼</a></li>
			<li><a href="">海外购物</a></li>
		</ul>
	</div>
	
	<div class="bnav3">
		<h3><b></b> <em>支付方式</em></h3>
		<ul>
			<li><a href="">货到付款</a></li>
			<li><a href="">在线支付</a></li>
			<li><a href="">分期付款</a></li>
			<li><a href="">邮局汇款</a></li>
			<li><a href="">公司转账</a></li>
		</ul>
	</div>

	<div class="bnav4">
		<h3><b></b> <em>售后服务</em></h3>
		<ul>
			<li><a href="">退换货政策</a></li>
			<li><a href="">退换货流程</a></li>
			<li><a href="">价格保护</a></li>
			<li><a href="">退款说明</a></li>
			<li><a href="">返修/退换货</a></li>
			<li><a href="">退款申请</a></li>
		</ul>
	</div>

	<div class="bnav5">
		<h3><b></b> <em>特色服务</em></h3>
		<ul>
			<li><a href="">夺宝岛</a></li>
			<li><a href="">DIY装机</a></li>
			<li><a href="">延保服务</a></li>
			<li><a href="">家电下乡</a></li>
			<li><a href="">京东礼品卡</a></li>
			<li><a href="">能效补贴</a></li>
		</ul>
	</div>
</div>
<!-- 底部导航 end -->
<div style="clear:both;"></div>

<!-- 页面的js代码 -->
<script type="text/javascript">
/****************ajax动态获取用户的商品订单********************/
    //默认展示第一页
    $(document).ready(function(){
        // ajax 加载商品的用户评论
        ajax_get_user_order(1);

    });
    // ajax 抓取页面form为表单id  page为当前第几页
    function ajax_get_user_order(page){
        cur_page = page; //当前页面 保存为全局变量
        //获取当前要搜索的字段
        var field = $("#checked_order_field").val();
        var order = $("#checked_order_order").val();
        $.ajax({
            type : "POST",
            data : {field: field , order: order},
            url : "<?php echo U('Home/User/ajaxGetUserOrder','',FALSE) ?>/p/"+page,
            // data : $('#'+formId).serialize(),// 将提交的表单id的内容进行序列化操作
            success: function(data){
                //先清除原始的数据
                 $("#right_sideBar").html('');
                //将ajax返回的数据，拼接在ajax_get_user_order后面
                $("#right_sideBar").append(data);
            }
        });
    }
    // 处理点击获取要查看的字段、排序方式的方法
    function check_field(field){
        //将排序字段传递到搜索表单中的隐藏字段中
       $("#checked_order_field").val(field);
       //获取当前被选中的字段
       var curField = $("li[class='wddd-red']").attr("id");
       //判断当前选中的字段是否与点击字段是否一致
       if(curField === field){
        //如果当前点击字段与选中字段一致   
            //对当前的排序方式字段进行取反操作
            var v = ($("#checked_order_order").val() === 'DESC') ? 'ASC' : 'DESC';        
       }else{
        //如果当前点击字段与选中字段不一致
            //不对当前的排序方式字段进行取反操作
            var v = ($("#checked_order_order").val() === 'ASC') ? 'ASC' : 'DESC';        
       }
       var order = v === "DESC" ? "↓" : "↑" ;
       //将取反后的字段值重新赋值给自身
       $("#checked_order_order").val(v);
       //为当前选中的字段添加class：wddd-red
       $("li[class='wddd-red']").removeClass("wddd-red");
       $("#"+field).addClass("wddd-red");
       //给当前选中的字段添加箭头标记
       $(".check_order").html("");
       $("#"+field+"_ORDER").html(order);
       //重新调用ajax_get_user_order方法，获取新的表单展示数据
       ajax_get_user_order(cur_page);
    }
</script>
    
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