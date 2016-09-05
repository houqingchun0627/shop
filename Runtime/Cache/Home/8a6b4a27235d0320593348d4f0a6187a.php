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
<link rel="stylesheet" href="/shop/Public/Home/Style/list.css" type="text/css">
<link rel="stylesheet" href="/shop/Public/Home/Style/common.css" type="text/css">
<script type="text/javascript" src="/shop/Public/Home/Js/list.js"></script>

<style type="text/css">
	#cur_search_condition{margin:10px;padding:5px;padding-right:0;}
	#cur_search_condition span{border:1px solid #F00;padding:5px;margin:5px;}
	#cur_search_condition span a:hover{background:#F00;color:#FFF;padding:5px;margin-right:0;}
	/*设置分页当前页的样式*/
	.page span.current{
	    border: 1px solid #ccc;
	    border-radius: 3px;
	    font-size: 14px;
	    padding: 4px 10px 5px;
	    background: #005aa0 none repeat scroll 0 0;
    	color: #fff;
    	text-decoration: none;
	}

</style>
<div style="clear:both;"></div>

<!-- 列表主体 start -->
<div class="list w1210 bc mt10">
	<!-- 面包屑导航 start -->
	<div class="breadcrumb">
		<h2>当前位置：<a href="">首页</a> > <a href="">电脑、办公</a></h2>
	</div>
	<!-- 面包屑导航 end -->

	<!-- 左侧内容 start -->
	<div class="list_left fl mt10">
		<!-- 分类列表 start -->
		<div class="catlist">
			<h2>电脑、办公</h2>
			<div class="catlist_wrap">
				<div class="child">
					<h3 class="on"><b></b>电脑整机</h3>
					<ul>
						<li><a href="">笔记本</a></li>
						<li><a href="">超极本</a></li>
						<li><a href="">平板电脑</a></li>
					</ul>
				</div>

				<div class="child">
					<h3><b></b>电脑配件</h3>
					<ul class="none">
						<li><a href="">CPU</a></li>
						<li><a href="">主板</a></li>
						<li><a href="">显卡</a></li>
					</ul>
				</div>

				<div class="child">
					<h3><b></b>办公打印</h3>
					<ul class="none">
						<li><a href="">打印机</a></li>
						<li><a href="">一体机</a></li>
						<li><a href="">投影机</a></li>
						</li>
					</ul>
				</div>

				<div class="child">
					<h3><b></b>网络产品</h3>
					<ul class="none">
						<li><a href="">路由器</a></li>
						<li><a href="">网卡</a></li>
						<li><a href="">交换机</a></li>
						</li>
					</ul>
				</div>

				<div class="child">
					<h3><b></b>外设产品</h3>
					<ul class="none">
						<li><a href="">鼠标</a></li>
						<li><a href="">键盘</a></li>
						<li><a href="">U盘</a></li>
					</ul>
				</div>
			</div>
			
			<div style="clear:both; height:1px;"></div>
		</div>
		<!-- 分类列表 end -->
			
		<div style="clear:both;"></div>	

		<!-- 新品推荐 start -->
		<div class="newgoods leftbar mt10">
			<h2><strong>新品推荐</strong></h2>
			<div class="leftbar_wrap">
				<ul>
					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/list_hot1.jpg" alt="" /></a></dt>
							<dd><a href="">美即流金丝语悦白美颜新年装4送3</a></dd>
							<dd><strong>￥777.50</strong></dd>
						</dl>
					</li>

					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/list_hot2.jpg" alt="" /></a></dt>
							<dd><a href="">领券满399减50 金斯利安多维片</a></dd>
							<dd><strong>￥239.00</strong></dd>
						</dl>
					</li>

					<li class="last">
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/list_hot3.jpg" alt="" /></a></dt>
							<dd><a href="">皮尔卡丹pierrecardin 男士长...</a></dd>
							<dd><strong>￥1240.50</strong></dd>
						</dl>
					</li>
				</ul>
			</div>
		</div>
		<!-- 新品推荐 end -->

		<!--热销排行 start -->
		<div class="hotgoods leftbar mt10">
			<h2><strong>热销排行榜</strong></h2>
			<div class="leftbar_wrap">
				<ul>
					<li></li>
				</ul>
			</div>
		</div>
		<!--热销排行 end -->

		<!-- 最近浏览 start -->
		<div class="viewd leftbar mt10">
			<h2><a href="javascript:get_history_goods(1);">清空</a><strong>最近浏览过的商品</strong></h2>
			<div id="history_goods" class="leftbar_wrap">
			</div>
		</div>
		<!-- 最近浏览 end -->
	</div>
	<!-- 左侧内容 end -->

	<!-- 列表内容 start -->
	<div class="list_bd fl ml10 mt10">
		<!-- 热卖、促销 start -->
		<!-- <div class="list_top"> -->
			<!-- 热卖推荐 start -->
			<!-- <div class="hotsale fl">
				<h2><strong><span class="none">热卖推荐</span></strong></h2>
				<ul>
					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/hpG4.jpg" alt="" /></a></dt>
							<dd class="name"><a href="">惠普G4-1332TX 14英寸笔记本电脑 （i5-2450M 2G 5</a></dd>
							<dd class="price">特价：<strong>￥2999.00</strong></dd>
							<dd class="buy"><span>立即抢购</span></dd>
						</dl>
					</li>

					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/list_hot3.jpg" alt="" /></a></dt>
							<dd class="name"><a href="">ThinkPad E42014英寸笔记本电脑</a></dd>
							<dd class="price">特价：<strong>￥4199.00</strong></dd>
							<dd class="buy"><span>立即抢购</span></dd>
						</dl>
					</li>

					<li>
						<dl>
							<dt><a href=""><img src="/shop/Public/Home/Images/acer4739.jpg" alt="" /></a></dt>
							<dd class="name"><a href="">宏碁AS4739-382G32Mnkk 14英寸笔记本电脑</a></dd>
							<dd class="price">特价：<strong>￥2799.00</strong></dd>
							<dd class="buy"><span>立即抢购</span></dd>
						</dl>
					</li>
				</ul>
			</div> -->
			<!-- 热卖推荐 end -->

			<!-- 促销活动 start -->
			<!-- <div class="promote fl">
				<h2><strong><span class="none">促销活动</span></strong></h2>
				<ul>
					<li><b>.</b><a href="">DIY装机之向雷锋同志学习！</a></li>
					<li><b>.</b><a href="">京东宏碁联合促销送好礼！</a></li>
					<li><b>.</b><a href="">台式机笔记本三月巨惠！</a></li>
					<li><b>.</b><a href="">富勒A53g智能人手识别鼠标</a></li>
					<li><b>.</b><a href="">希捷硬盘白色情人节专场</a></li>
				</ul>

			</div> -->
			<!-- 促销活动 end -->
		<!-- </div> -->
		<!-- 热卖、促销 end -->
		
		<div style="clear:both;"></div>
		<!-- 商品筛选 start -->
		<div id="cur_search_condition">
			当前搜索条件：
			<?php foreach($curSearchConditon as $key => $value) : ?>
			    <span>
					<?php echo ($key); ?>：<?php echo ($value["value"]); ?>
					<a href="<?php echo ($value["url"]); ?>" title="">X</a>
				</span>
			<?php endforeach ;?>
		</div>
		<div class="filter mt10">
			<h2>
				<!-- <a href="javascript:reset_search();">重置筛选条件</a>  -->
				<strong>商品筛选</strong>
			</h2>
			<div class="filter_wrap">
				<!-- 处理商品的品牌 -->
				<?php if(!isset($searchConditionList['brand_id']) AND ($reqGoodsList != false)): ?><dl>
						<dt class="hide-dl">品牌：</dt>
						<?php if(is_array($searchCondition['brand'])): $i = 0; $__LIST__ = $searchCondition['brand'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dd><a href="/shop/index.php/Home/Goods/goodsList/cat_id/10.html/brand_id/<?php echo ($vo["brand_id"]); ?>_<?php echo ($vo["brand_name"]); ?>"><?php echo ($vo["brand_name"]); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>

<!-- 						<div class="f-ext">
			                 <a  class="f-check brand" href="javascript:;"><i></i>多选</a> 
			            </div>
			            <div class="g-btns">
			            	<a  href="javascript:;" class="u-confirm" onClick="submitMoreFilter('brand',this);">确定</a>
			                <a href="javascript:;" class="u-cancel brand">取消</a>
			            </div> -->

					</dl><?php endif; ?>
				<!-- 处理商品的价格 -->
				<?php if(!isset($searchConditionList['price']) AND ($reqGoodsList != false)): ?><dl class="hide-dl">
						<dt>价格：</dt>
						<?php if(is_array($searchCondition['price'])): $i = 0; $__LIST__ = $searchCondition['price'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dd><a href="/shop/index.php/Home/Goods/goodsList/cat_id/10.html/price/<?php echo ($vo); ?>"><?php echo ($vo); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>

						<li class="m-pricebox">
							<form action="/shop/index.php/Home/Goods/goodsList/cat_id/10.html" method="post" id="price_form" onsubmit="return false">
								<input type="text" class="u-pri-start" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="start_price" id="start_price" /> -
								<input type="text" class="u-pri-end" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"  name="end_price" id="end_price" />
								<span style="cursor:pointer;" class="z-btn ensure03 u-btn-pri" href="javascript:;" onClick="if($('#start_price').val() !='' && $('#end_price').val() !='' ) goods_search_price();" >确认</span>
							</form>      
						</li>

					</dl><?php endif; ?>	
				<!-- 处理商品的属性 -->
				<?php if(is_array($searchCondition['attr'])): $i = 0; $__LIST__ = $searchCondition['attr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $attrName = "attr_".$vo["attr_id"];?>
					<?php if(!isset($searchConditionList[$attrName])): ?><dl class="hide-dl">
							<dt><?php echo ($vo["attr_name"]); ?>：</dt>
							<?php $attrList = explode(',',$vo["attr_value"]);?>
							<?php if(is_array($attrList)): $i = 0; $__LIST__ = $attrList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dd><a href="/shop/index.php/Home/Goods/goodsList/cat_id/10.html/attr_<?php echo ($vo["attr_id"]); ?>/<?php echo ($vo["attr_name"]); ?>_<?php echo ($v); ?>"><?php echo ($v); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>

<!-- 							<div class="f-ext">
								<a  class="f-check " href="javascript:;"><i></i>多选</a>
							</div>
							<div class="g-btns">
								<a  href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
								<a href="javascript:;" class="u-cancel ">取消</a>
							</div> -->

						</dl><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				<a  class="f-out-more" href="javascript:;" style="float: right;padding-right: 20px;padding-top: 5px;">更多选项<i></i></a>
			</div>
		</div>
		<!-- 商品筛选 end -->
		
		<div style="clear:both;"></div>

		<!-- 排序 start -->
		<div class="sort mt10">
			<dl>
				<dt>选择排序：</dt>

				<dd id="goods_id" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">默认 <span id="goods_id_sort"></span></a>
				</dd>

				<dd id="goods_shop_price" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按价格 <span id="goods_shop_price_sort"></span></a>
				</dd>

				<dd id="goods_view_amount" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按浏览量 <span id="goods_view_amount_sort"></span></a>
				</dd>
				
				<dd id="goods_sale_amount" class="search_condition" onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按销量 <span id="goods_sale_amount_sort"></span></a>
				</dd>

				<dd id="goods_comment_amount" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按评论数 <span id="goods_comment_amount_sort"></span></a>
				</dd>

				<dd id="goods_comment_score_sort" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按评分 <span id="goods_comment_score_sort"></span></a>
				</dd>

				<dd id="addtime" class="search_condition " onclick="goods_sort_order(this);">
					<a href="javascript:void(0);">按上架时间 <span id="addtime_sort"></span></a>
				</dd>
			</dl>
		</div>
		<!-- 排序 end -->
		
		<div style="clear:both;"></div>

		<!-- 商品列表 start-->
		<div id="goods_search_list" class="goodslist mt10">
			<ul>
				<?php if(is_array($reqGoodsList)): $i = 0; $__LIST__ = $reqGoodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
						<dl>
							<dt>
								<a href="<?php echo U('Goods/goods',array('id' => $vo['goods_id']));?>"><img src="<?php echo ($vo["goods_thumb_small"]); ?>" alt="" /></a>
							</dt>
							<dd>
								<a href="<?php echo U('Goods/goods',array('id' => $vo['goods_id']));?>"><?php echo ($vo["goods_name"]); ?></a>
							</dd>
							<dd>
								<strong>￥<?php echo ($vo["goods_shop_price"]); ?></strong>
							</dd>
							<dd>
								<a href="<?php echo U('Goods/goods',array('id' => $vo['goods_id']));?>"><em>已有<?php echo ($vo["goods_comment_amount"]); ?>人评价</em></a>
							</dd>
						</dl>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>

			</ul>
		</div>
		<!-- 商品列表 end-->

		<!-- 分页信息 start -->
		<div class="page mt20" style="font-size: 14px;">
			<?php echo ($pageShow); ?>
		</div>
		<!-- 分页信息 end -->
	</div>
	<!-- 列表内容 end -->
</div>
<!-- 列表主体 end-->

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

<!-- 页面的javascript代码 -->
 <script type="text/javascript">
 /*******************获取用户的最近的浏览商品的历史记录**********************/
	function get_history_goods(c){
		var oid = c === undefined ? 0 : c ;
		//ajax动态获取商品浏览历史数据
		$.ajax({
            type : "GET",
            url : "<?php echo U('Home/Goods/ajaxGetHistoryGoods', '', FALSE); ?>/oid/"+oid,
            success : function(data){
                //将用户浏览的历史商品展示出来
                $("#history_goods").html("");
                $("#history_goods").append(data);
            }
        });
	}
 /************处理商品筛选排序*********************/
 	//处理页面加载完毕，默认选中排序字段和默认选中排序方式
 	function goods_search(){
 		var sort = "<?php echo ($searchConditionList['sort']); ?>" == "" ? "goods_id" : "<?php echo ($searchConditionList['sort']); ?>";
 		var order = "<?php echo ($searchConditionList['sort_order']); ?>" == "" ? "↓" : "<?php echo ($searchConditionList['sort_order']); ?>";
 		//如果get的url参数中有包含sort_order参数
 		if(order !== "↓"){
 			order = "<?php echo ($searchConditionList['sort_order']); ?>" == "desc" ? "↓" : "↑";
 		}
 		//判断当前字段是否存在
 		$("#"+sort).addClass("cur");
 		$("#"+sort+"_sort").html(order);
 	}

 	//处理用户点击选择排序方式
 	function goods_sort_order(a){
 		//获取当前的选中的选项
 		var sort = $(a).attr("id");
 		var curUrl = "<?php echo ($searchConditionList['url']); ?>";
 		var curSort = "<?php echo ($searchConditionList['sort']); ?>" === "" ? "goods_id" : "<?php echo ($searchConditionList['sort']); ?>";
 		var curOrder = "<?php echo ($searchConditionList['sort_order']); ?>" === "" ? "desc" : "<?php echo ($searchConditionList['sort_order']); ?>";

 		//判断当前的操作，拼接get请求的url地址
 		if(sort == curSort){
 			//如果用户点击的字段和url中的字段一致
 			var order = curOrder === "desc" ? "asc" : "desc" ;
 			var url = curUrl +"/sort/"+ sort +"/sort_order/"+ order;
 		}else{
 			//如果用户点击的字段和当前url字段不一致
 			var url = curUrl +"/sort/"+sort +"/sort_order/"+ curOrder;
 		}
 		//利用js代码，进行get请求跳转
 		location.href = url ;
 	}
/************处理用户自定义商品搜索价格*********************/
	function goods_search_price(){
		var startPrice = $("#start_price").val();
		var endPrice = $("#end_price").val();
		var curUrl = "<?php echo ($searchConditionList['url']); ?>";
		var curSort = "<?php echo ($searchConditionList['sort']); ?>" === "" ? "goods_id" : "<?php echo ($searchConditionList['sort']); ?>";
 		var curOrder = "<?php echo ($searchConditionList['sort_order']); ?>" === "" ? "desc" : "<?php echo ($searchConditionList['sort_order']); ?>";
		var url = curUrl +"/price/"+startPrice+"--"+endPrice+"/sort/"+curSort+"/sort_order/"+curOrder;
		//利用js代码，进行get请求跳转
 		location.href = url ;
	}
/************更多类别属性筛选*********************/
	$('.f-out-more').click(function(){
		$('.hide-dl').each(function(i,o){
			if(i>7){
				var attrdisplay = $(o).css('display');
				if(attrdisplay == 'none'){
					$(o).css('display','block');
				}
				if(attrdisplay == 'block'){
					$(o).css('display','none');
				}	
			}
		});
		if($(this).hasClass('checked')){
			//如果当前是展开状态
			$(this).removeClass('checked').html('收起');
		}else{
			//如果当前是收起状态
			$(this).addClass('checked').html('更多选项');
		}
	})
/************************多选框************************/
$('.f-check').click(function(){
	var _this = this;
	var st = 0;
	//关闭前一个多选框
	if(cancelBtn != null){
		$(cancelBtn).parent().siblings('ul').removeClass('z-show-more');
		$(cancelBtn).parent().siblings('ul').find('li >a').each(function(i,o){
			$(o).removeClass('select selected');
			$(o).attr('href',$(o).data('href'));
			$(o).children('i').removeClass('selected').css('display','');
			$(o).unbind('click');
		});		
		$(cancelBtn).parent().siblings('.f-ext').show().children('a').removeClass('checked');
		$(cancelBtn).parent().hide();
		$(cancelBtn).siblings().removeClass('u-confirm01');
	}
	cancelBtn = $(_this).parent().siblings('div').find('.u-cancel');
	
	//打开多选框
	$(_this).addClass('checked');
	$(_this).siblings().addClass('checked');
	$(_this).parent().siblings('.g-btns').show();
	$(_this).parent().siblings('ul').addClass('z-show-more');
	$(_this).parent().siblings('ul').find('li>a').each(function(i,o){
		$(o).addClass('select');
		$(o).children('i').css('display','inline');
		$(o).attr('href','javascript:;');
		$(o).bind('click',function(){			
			if($(o).hasClass('selected')){
				$(o).removeClass('selected');
				$(o).children('i').removeClass('selected');
				st--;
			}else{
				$(o).addClass('selected');
				$(o).children('i').addClass('selected');
				$(_this).parent().siblings('.g-btns').children('.u-confirm').addClass('u-confirm01');
				st++;
			}
			//如果没有选中项,确定按钮点不了
			if(st==0){
				$(_this).parent().siblings('.g-btns').children('.u-confirm').removeClass('u-confirm01');
			}
		});
	});
	$(_this).parent().hide();
})
/************************取消多选************************/
$('.g-btns .u-cancel').each(function(){
	$(this).click(function(){
		$(this).parent().siblings('ul').removeClass('z-show-more');
		$(this).parent().siblings('ul').find('li >a').each(function(i,o){
			$(o).removeClass('select selected');
			$(o).attr('href',$(o).data('href'));
			$(o).children('i').removeClass('selected').css('display','');
			$(o).unbind('click');
		});
		$(this).parent().siblings('.f-ext').show().children('a').removeClass('checked');
		$(this).parent().hide();
		$(this).siblings().removeClass('u-confirm01');
	});
})

/************************点击多选确定按钮************************/
// t 为类型  是品牌 还是 规格 还是 属性
// btn 是点击的确定按钮用于找位置
	 get_parment = <?php echo json_encode($_GET); ?>;	
	function submitMoreFilter(t,btn){
		// 没有被勾选的时候
		if(!$(btn).hasClass("u-confirm01"))
			return false;
			
		// 获取现有的get参数		
		var key = ''; // 请求的 参数名称
		var val = new Array(); // 请求的参数值
		$(btn).parent().siblings(".f-list").find("li > a.selected").each(function(){
		   key = $(this).data('key');
		   val.push($(this).data('val'));
		});
		//parment = key+'_'+val.join('_');

		// 品牌
		if(t == 'brand'){
			get_parment.brand_id = val.join('_');
		}
		// 规格
		if(t == 'spec'){
			if(get_parment.hasOwnProperty('spec')){		
				get_parment.spec += '@'+key+'_'+val.join('_');
			}else{		
				get_parment.spec = key+'_'+val.join('_');
			}		
		}
		// 属性
		if(t == 'attr'){
			if(get_parment.hasOwnProperty('attr')){		
				get_parment.attr += '@'+key+'_'+val.join('_');
			}else{		
				get_parment.attr = key+'_'+val.join('_');
			}		
		}
		// 组装请求的url	
		var url = '';
		for( var k in get_parment ){ 
			url += "&"+k+'='+get_parment[k];
		} 
		location.href ="/index.php?m=Home&c=Goods&a=goodsList"+url;
	}
/***************页面加载完成，触发相关事件*******************/		
    $(document).ready(function(){
    	//当页面加载完毕后,获取商品浏览历史记录
    	get_history_goods();
    	//当页面加载完成，触发筛选条件，默认筛选条件是收起状态
    	$('.f-out-more').trigger('click');
    	//当页面加载完成，触发选中当前的选择条件
    	goods_search();
    });
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