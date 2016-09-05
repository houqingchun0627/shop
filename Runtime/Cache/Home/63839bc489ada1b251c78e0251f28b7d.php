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
<link rel="stylesheet" href="/shop/Public/Home/Style/index.css" type="text/css">
<script type="text/javascript" src="/shop/Public/Home/Js/index.js"></script> 

<div style="clear:both;"></div>

<!-- 综合区域 start 包括幻灯展示，商城快报 -->
<div class="colligate w1210 bc mt10">
	<!-- 幻灯区域 start -->
	<div class="slide fl">
		<div class="area">
			<div class="slide_items">
				<ul>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide1.jpg" alt="" /></a></li>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide2.jpg" alt="" /></a></li>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide3.jpg" alt="" /></a></li>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide4.jpg" alt="" /></a></li>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide5.jpg" alt="" /></a></li>
					<li><a href=""><img src="/shop/Public/Home/Images/index_slide6.jpg" alt="" /></a></li>
				</ul>
			</div>
			<div class="slide_controls">
				<ul>
					<li class="on">1</li>
					<li>2</li>
					<li>3</li>
					<li>4</li>
					<li>5</li>
					<li>6</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 幻灯区域 end-->

	<!-- 快报区域 start-->
	<div class="coll_right fl ml10">
		<div class="ad"><a href=""><img src="/shop/Public/Home/Images/ad.jpg" alt="" /></a></div>
		
		<div class="news mt10">
			<h2><a href="">更多快报&nbsp;></a><strong>网站快报</strong></h2>
			<ul>
				<li class="odd"><a href="">电脑数码双11爆品抢不停</a></li>
				<li><a href="">买茶叶送武夷山旅游大奖</a></li>
				<li class="odd"><a href="">爆款手机最高直降1000</a></li>
				<li><a href="">新鲜褚橙全面包邮开售！</a></li>
				<li class="odd"><a href="">家具家装全场低至3折</a></li>
				<li><a href="">买韩束，志玲邀您看电影</a></li> 
				<li class="odd"><a href="">美的先行惠双11快抢悦</a></li>
				<li><a href="">享生活 疯狂周期购！</a></li>
			</ul>

		</div>
		
		<div class="service mt10">
			<h2>
				<span class="title1 on"><a href="">话费</a></span>
				<span><a href="">旅行</a></span>
				<span><a href="">彩票</a></span>
				<span class="title4"><a href="">游戏</a></span>
			</h2>
			<div class="service_wrap">
				<!-- 话费 start -->
				<div class="fare">
					<form action="">
						<ul>
							<li>
								<label for="">手机号：</label>
								<input type="text" name="phone" value="请输入手机号" class="phone" />
								<p class="msg">支持移动、联通、电信</p>
							</li>
							<li>
								<label for="">面值：</label>
								<select name="" id="">
									<option value="">10元</option>
									<option value="">20元</option>
									<option value="">30元</option>
									<option value="">50元</option>
									<option value="" selected>100元</option> 
									<option value="">200元</option>
									<option value="">300元</option>
									<option value="">400元</option>
									<option value="">500元</option>
								</select>
								<strong>98.60-99.60</strong>
							</li>
							<li>
								<label for="">&nbsp;</label>
								<input type="submit" value="点击充值" class="fare_btn" /> <span><a href="">北京青春怒放独家套票</a></span>
							</li>
						</ul>
					</form>
				</div>
				<!-- 话费 start -->

				<!-- 旅行 start -->
				<div class="travel none">
					<ul>
						<li>
							<a href=""><img src="/shop/Public/Home/Images/holiday.jpg" alt="" /></a>
							<a href="" class="button">度假查询</a>
						</li>
						<li>
							<a href=""><img src="/shop/Public/Home/Images/scenic.jpg" alt="" /></a>
							<a href="" class="button">景点查询</a>
						</li>
					</ul>
				</div>
				<!-- 旅行 end -->
					
				<!-- 彩票 start -->
				<div class="lottery none">
					<p><img src="/shop/Public/Home/Images/lottery.jpg" alt="" /></p>
				</div>
				<!-- 彩票 end -->

				<!-- 游戏 start -->
				<div class="game none">
					<ul>
						<li><a href=""><img src="/shop/Public/Home/Images/sanguo.jpg" alt="" /></a></li>
						<li><a href=""><img src="/shop/Public/Home/Images/taohua.jpg" alt="" /></a></li>
						<li><a href=""><img src="/shop/Public/Home/Images/wulin.jpg" alt="" /></a></li>
					</ul>
				</div>
				<!-- 游戏 end -->
			</div>
		</div>

	</div>
	<!-- 快报区域 end-->
</div>
<!-- -综合区域 end -->

<div style="clear:both;"></div>

<!-- 导购区域 start -->
<div class="guide w1210 bc mt15">
	<!-- 导购左边区域 start -->
	<div class="guide_content fl">
		<h2>
			<span class="on">疯狂抢购</span>
			<span>热卖商品</span>
			<span>精品推荐</span>
			<span>新品上架</span>
			<span class="last">猜您喜欢</span>
		</h2>
		
		<div class="guide_wrap">
			<!-- 疯狂抢购 start-->
			<div class="crazy">
				<ul>
					<?php if(is_array($promoteList)): $i = 0; $__LIST__ = $promoteList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<dl>
								<dt><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><img src="<?php echo ($vo["goods_thumb_medium"]); ?>" alt="" /></a></dt>
								<dd class="hide_name"><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><?php echo ($vo["goods_name"]); ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo ($vo["goods_promote_price"]); ?></strong></dd>
							</dl>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>	
			</div>
			<!-- 疯狂抢购 end-->

			<!-- 热卖商品 start -->
			<div class="hot none">
				<ul>
					<?php if(is_array($hotList)): $i = 0; $__LIST__ = $hotList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<dl>
								<dt><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><img src="<?php echo ($vo["goods_thumb_medium"]); ?>" alt="" /></a></dt>
								<dd class="hide_name"><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><?php echo ($vo["goods_name"]); ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo ($vo["goods_shop_price"]); ?></strong></dd>
							</dl>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<!-- 热卖商品 end -->

			<!-- 推荐商品 atart -->
			<div class="recommend none">
				<ul>
					<?php if(is_array($bestList)): $i = 0; $__LIST__ = $bestList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<dl>
								<dt><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><img src="<?php echo ($vo["goods_thumb_medium"]); ?>" alt="" /></a></dt>
								<dd class="hide_name"><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><?php echo ($vo["goods_name"]); ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo ($vo["goods_shop_price"]); ?></strong></dd>
							</dl>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<!-- 推荐商品 end -->
		
			<!-- 新品上架 start-->
			<div class="new none">
				<ul>
					<?php if(is_array($newList)): $i = 0; $__LIST__ = $newList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<dl>
								<dt><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><img src="<?php echo ($vo["goods_thumb_medium"]); ?>" alt="" /></a></dt>
								<dd class="hide_name"><a href="<?php echo U('goods',array('id' => $vo['goods_id']));?>"><?php echo ($vo["goods_name"]); ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo ($vo["goods_shop_price"]); ?></strong></dd>
							</dl>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<!-- 新品上架 end-->

			<!-- 猜您喜欢 start -->
			<div class="guess none">
				<ul>
					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/guess1.jpg" alt="" /></a></dt>
							<dd><a href="">Thinkpad USB光电鼠标</a></dd>
							<dd><span>售价：</span><strong> ￥39.00</strong></dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/guess2.jpg" alt="" /></a></dt>
							<dd><a href="">宜客莱（ECOLA）电脑散热器</a></dd>
							<dd><span>售价：</span><strong> ￥89.00</strong></dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/guess3.jpg" alt="" /></a></dt>
							<dd><a href="">巴黎欧莱雅男士洁面膏 100ml</a></dd>
							<dd><span>售价：</span><strong> ￥30.00</strong></dd>
						</dl>
					</li>
				</ul>
			</div>
			<!-- 猜您喜欢 end -->

		</div>

	</div>
	<!-- 导购左边区域 end -->
	
	<!-- 侧栏 网站首发 start-->
	<div class="sidebar fl ml10">
		<h2><strong>网站首发</strong></h2>
		<div class="sidebar_wrap">
			<dl class="first">
				<dt class="fl"><a href=""><img src="/shop/Public/Home/Images/viewsonic.jpg" alt="" /></a></dt>
				<dd><strong><a href="">ViewSonic优派N710 </a></strong> <em>首发</em></dd>
				<dd>苹果iphone 5免费送！攀高作为全球智能语音血压计领导品牌，新推出的黑金刚高端智能电子血压计，改变传统测量方式让血压测量迈入一体化时代。</dd>
			</dl>

			<dl>
				<dt class="fr"><a href=""><img src="/shop/Public/Home/Images/samsung.jpg" alt="" /></a></dt>
				<dd><strong><a href="">Samsung三星Galaxy</a></strong> <em>首发</em></dd>
				<dd>电视百科全书，360°无死角操控，感受智能新体验！双核CPU+双核GPU+MEMC运动防抖，58寸大屏打造全新视听盛宴！</dd>
			</dl>
		</div>
		

	</div>
	<!-- 侧栏 网站首发 end -->
	
</div>
<!-- 导购区域 end -->

<div style="clear:both;"></div>

<?php if(is_array($floorData)): $i = 0; $__LIST__ = $floorData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--1F 电脑办公 start -->
<div class="floor1 floor w1210 bc mt10">
	<!-- 1F 左侧 start -->
	<div class="floor_left fl">
		<!-- 商品分类信息 start-->
		<div class="cate fl">
			<h2><?php echo ($vo["cat_name"]); ?></h2>
			<div class="cate_wrap">
				<ul>
					<?php if(is_array($vo['unrec_cat'])): $i = 0; $__LIST__ = $vo['unrec_cat'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('goodsList',array('cat_id' => $v1['cat_id']));?>"><b>.</b><?php echo ($v1["cat_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				<p><a href="<?php echo U('goodsList',array('cat_id' => $vo['cat_id']));?>"><img src="<?php echo ($vo["cat_logo"]); ?>" alt="" /></a></p>
			</div>

		</div>
		<!-- 商品分类信息 end-->

		<!-- 商品列表信息 start-->
		<div class="goodslist fl">
			<h2>
				<?php if(is_array($vo['rec_cat'])): $k2 = 0; $__LIST__ = $vo['rec_cat'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($k2 % 2 );++$k2;?><span <?php if($k2 == 1): ?>class="on"<?php endif; ?>><?php echo ($v2["cat_name"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
			</h2>
			<div class="goodslist_wrap">
				<?php if(is_array($vo['rec_cat'])): $k2 = 0; $__LIST__ = $vo['rec_cat'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($k2 % 2 );++$k2;?><div <?php if($k2 > 1): ?>class="none"<?php endif; ?>>
						<ul>
							<?php if(is_array($v2['rec_goods'])): $i = 0; $__LIST__ = $v2['rec_goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v3): $mod = ($i % 2 );++$i;?><li>
									<dl>
										<dt><a href="<?php echo U('goods',array('id' => $v3['goods_id']));?>"><img src="<?php echo ($v3["goods_thumb_medium"]); ?>" alt="" /></a></dt>
										<dd class="hide_name"><a href="<?php echo U('goods',array('id' => $v3['goods_id']));?>"><?php echo ($v3["goods_name"]); ?></a></dd>
										<dd><span>售价：</span><strong> ￥<?php echo ($v3["goods_shop_price"]); ?></strong></dd>
									</dl>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<!-- 商品列表信息 end-->
	</div>
	<!-- 1F 左侧 end -->
	
	<!-- 右侧 start -->
	<div class="sidebar fl ml10">
		<!-- 品牌旗舰店 start -->
		<div class="brand">
			<h2><a href="">更多品牌&nbsp;></a><strong>品牌旗舰店</strong></h2>
			<div class="sidebar_wrap">
				<ul>
					<?php if(is_array($vo['rec_brand'])): $i = 0; $__LIST__ = $vo['rec_brand'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v4): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('brand',array('id' => $v4['0']));?>"><img src="<?php echo ($v4["2"]); ?>" alt="<?php echo ($v4["1"]); ?>" title="<?php echo ($v4["1"]); ?>" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		</div>
		<!-- 品牌旗舰店 end -->
		
		<!-- 分类资讯 start -->
		<div class="info mt10">
			<h2><strong>分类资讯</strong></h2>
			<div class="sidebar_wrap">
				<ul>
					<li><a href=""><b>.</b>iphone 5s土豪金大量到货</a></li>
					<li><a href=""><b>.</b>三星note 3低价促销</a></li>
					<li><a href=""><b>.</b>thinkpad x240即将上市</a></li>
					<li><a href=""><b>.</b>双十一来临，众商家血拼</a></li>
				</ul>
			</div>
			
		</div>
		<!-- 分类资讯 end -->
		
		<!-- 广告 start -->
		<div class="ads mt10">
			<a href=""><img src="/shop/Public/Home/Images/canon.jpg" alt="" /></a>
		</div>
		<!-- 广告 end -->
	</div>
	<!-- 右侧 end -->

</div>
<!--1F 电脑办公 start --><?php endforeach; endif; else: echo "" ;endif; ?>

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