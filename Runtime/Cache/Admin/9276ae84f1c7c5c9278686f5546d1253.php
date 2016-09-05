<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/shop/Public/Admin/Style/general.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/main.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/ajax_page.css"rel="stylesheet"  type="text/css">
<!-- 引入时间插件 -->
<link href="/shop/Public/Plugins/datetimepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" src="/shop/Public/Plugins/datetimepicker/jquery-1.7.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/shop/Public/Plugins/datetimepicker/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/shop/Public/Plugins/datetimepicker/datepicker-zh_cn.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/shop/Public/Plugins/datetimepicker/time/jquery-ui-timepicker-addon.min.css" />
<script type="text/javascript" src="/shop/Public/Plugins/datetimepicker/time/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="/shop/Public/Plugins/datetimepicker/time/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<!-- 引入行高亮显示 -->
<script type="text/javascript" src="/shop/Public/Admin/Js/tron.js"></script>

</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('Goods/goodsMessage',array('id' => 1));?>">添加商品</a></span>
    <span class="action-span1"><a href="<?php echo U('Admin/Admin/index');?>" target="_top">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品列表 </span>
    <div style="clear:both"></div>
</h1>
<div id="goods_search" class="form-div">
    <form action="" id="goodsSearchForm" method="post" onsubmit="return false">
        <img src="/shop/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 分类 -->
        <span  style="margin-left:0px">分类：</span>
            <?php echo buildSelect('Cat','goods_cat','cat_id','cat_name',I('get.goods_cat'),'全部','','goods_search_select');?>
        <!-- 品牌 -->
        <span  style="margin-left:15px">品牌：</span>
            <!-- 调用buildSelect()方法 -->
            <?php echo buildSelect('Brand','goods_brand','brand_id','brand_name',I('get.goods_brand'),"全部",'','goods_search_select');?>
        <!-- 类型 -->
        <span  style="margin-left:15px;">类型：</span>
            <?php echo buildSelect('Type','goods_type','type_id','type_name',I('get.goods_type'),'全部','','goods_search_select');?>
        <!-- 上架 -->
        <span  style="margin-left:15px">上架：</span>
            <select name="is_onsale" class="goods_search_select">
                <option value=''>全部</option>
                <option value="是" <?php if(I('get.is_onsale') == '是'): ?>selected="selected"<?php endif; ?>>上架</option>
                <option value="否" <?php if(I('get.is_onsale') == '否'): ?>selected="selected"<?php endif; ?>>下架</option>
            </select>
        <!-- 回收站 -->
        <span  style="margin-left:15px">回收站：</span>
            <select name="is_delete" class="goods_search_select">
                <option value=''>全部</option>
                <option value="是" <?php if(I('get.is_delete') == '是'): ?>selected="selected"<?php endif; ?>>是</option>
                <option value="否" <?php if(I('get.is_delete') == '否'): ?>selected="selected"<?php endif; ?>>否</option>
            </select>
        <!-- 推荐 -->
        <span  style="margin-left:15px">推荐：</span>
            <input type="checkbox" class="goods_search_check" name="is_best" value="是" /> 精品 
            <input type="checkbox" class="goods_search_check" name="is_new" value="是" /> 新品 
            <input type="checkbox" class="goods_search_check" name="is_hot" value="是" /> 热销
        <!-- 排序字段 -->
        <input type="hidden" name="search_sort_field" size="16" value="goods_id" />
        <!-- 排序方式 -->
        <input type="hidden" name="search_sort_order" size="16" value="asc" /><br/><br/>
        <!-- 商品名 -->
        <span  style="margin-left:30px">商品名：</span>
            <input type="text" class="goods_search_text" name="goods_name" size="16" value="<?php echo I('get.goods_name');?>" />
        <!-- 关键字 -->
        <span  style="margin-left:15px">关键字：</span>
            <input type="text" class="goods_search_text" name="goods_keywords" value="<?php echo I('get.goods_keywords');?>" size="16" />
        <!-- 价格区间 -->
        <span  style="margin-left:15px">价格区间：</span>
        <input type="text" id="goods_start_price" class="goods_search_text goods_search_condition" name="goods_start_price" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo I('get.goods_start_price');?>" size="12"/>~~
        <input type="text" id="goods_end_price" class="goods_search_text goods_search_condition" name="goods_end_price" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" value="<?php echo I('get.goods_end_price');?>" size="12" />
        <!-- 添加时间 -->
        <span  style="margin-left:15px">添加时间：</span>
        <input type="text" id="fa" class="goods_search_text goods_search_condition" name="start_addtime" value="<?php echo I('get.start_addtime');?>" size="20" />~~
        <input type="text" id="ta" class="goods_search_text goods_search_condition" name="end_addtime" value="<?php echo I('get.end_addtime');?>" size="20" /><br /><br />
        
        <!-- 排序方式 -->
        <!-- <span  style="margin-left:30px">排序方式：</span>
        <input type="radio" name="order" value="goodsId_asc" <?php if((I('get.order') == 'goodsId_asc') OR (I('get.order') == '')): ?>checked="checked"<?php endif; ?> />按添加时间升序
        <input type="radio" name="order" value="goodsId_desc" <?php if(I('get.order') == 'goodsId_desc'): ?>checked="checked"<?php endif; ?> />按添加时间降序
        <input type="radio" name="order" value="price_asc" <?php if(I('get.order') == 'price_asc'): ?>checked="checked"<?php endif; ?> />按商品价格升序
        <input type="radio" name="order" value="price_desc" <?php if(I('get.order') == 'price_desc'): ?>checked="checked"<?php endif; ?> />按商品价格降序<br /><br /> -->
        
        <!-- 搜索按钮 -->
        <input type="submit" onclick="ajax_get_goodsList('goodsSearchForm',1);" value=" 开始搜索 " class="button" />
        <input type="submit" onclick="clearSearch();" value=" 重置条件 " class="button" />
    </form>
</div>
<div id="ajax_goods_list" class="" >
    <!-- ajax动态展示商品列表 -->
</div>
<div id="footer">
共执行 7 个查询，用时 0.028849 秒，Gzip 已禁用，内存占用 3.219 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
<script>
/****************ajax获取商品的列表页面goodsList展示***********************/
//当页面加载完成后，自动触发提交ajax_get_table()方法，获取默认的商品列表展示
    //默认展示第一页
    $(document).ready(function(){
        // ajax 加载商品列表
        ajax_get_goodsList('goodsSearchForm',1);

    });
    // ajax 抓取页面form为表单id  page为当前第几页
    function ajax_get_goodsList(formId,page){
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type : "POST",
            url : "<?php echo U('Goods/ajaxGoodsList','',FALSE) ?>/p/"+page,//+tab,
            data : $('#'+formId).serialize(),// 将提交的表单id的内容进行序列化操作
            success: function(data){
                //先清除原始的数据
                 $("#ajax_goods_list").html('');
                //将ajax返回的数据，拼接在ajax_goods_list后面
                $("#ajax_goods_list").append(data);
            }
        });
    }

// 处理点击排序的方法
    function sort(field){
        //将排序字段传递到搜索表单中的隐藏字段中
       $("input[name='search_sort_field']").val(field);
       //对当前的排序方式字段进行取反操作
       var v = ($("input[name='search_sort_order']").val() == 'desc')? 'asc' : 'desc'; 
       //将取反后的字段值重新赋值给自身            
       $("input[name='search_sort_order']").val(v);
       //重新调用ajax_get_table方法，获取新的表单展示数据
       //传递参数：表单的id值、当前页面的值
       ajax_get_goodsList('goodsSearchForm',cur_page);
    }
    
/****************导入datepicker时间插件***********************/
// 添加时间插件
    $.timepicker.setDefaults($.timepicker.regional['zh-CN']);  // 设置使用中文 
    $("#fa").datetimepicker();
    $("#ta").datetimepicker();

/****************展开收缩功能*********************************/
    // 展开收缩
    

/******************重置商品搜索的条件*******************************/
    function clearSearch(){
        $(".goods_search_check").prop("checked",false);
        $(".goods_search_select option").prop("selected",false);
        $(".goods_search_text").val("");
        $("input[name='search_sort_field']").val("goods_id");
        $("input[name='search_sort_order']").val("asc");
    }

/*****************验证商品搜索的价格区间、添加时间区间的正确性*******************************/
    //验证商品搜索的价格区间
    $(".goods_search_condition").blur(function() {
        //判断商品的促销价格的正确性 
        var pid = $(this).attr("id");
        var startPrice = parseFloat($("#goods_start_price").val());
        var endPrice = parseFloat($("#goods_end_price").val());
        if(startPrice > endPrice){
            //提示错误信息，并清空内容
            alert("开始价格不能大于结束价格！");
            $("#"+pid).val("");
            $("#"+pid).focus()[0];
        }
        //判断商品搜索的添加开始时间-结束时间
        //获取当前操作的id
        var aid = $(this).attr("id");
        var startTime = $("#fa").val().replace(/-/g, "");
        var startTime = startTime.replace(/:/g,"");
        var startTime = parseInt(startTime.replace(/ /g,""));
        var endTime = $("#ta").val().replace(/-/g, "");
        var endTime = endTime.replace(/:/g,"");
        var endTime = parseInt(endTime.replace(/ /g,""));
        if(startTime > endTime){
            //提示错误信息，并清空内容
            alert("搜索结束时间不能在开始时间之前！");
            $("#"+aid).val("");
        }
    });

/******************ajax删除商品*******************************/
    // ajax删除商品
    function ajaxDeleteGoods(a){
        if(confirm("是否确定删除？")){
            //如果用户确定删除
            var goodsId = $(a).parent('td').siblings('td').children('input:hidden').val();
            var tr = $(a).parent('td').parent('tr');
            //ajax删除操作
            $.ajax({
                type : "GET" ,
                url : "<?php echo U("ajaxDeleteGoods","",FALSE); ?>/goods_id/"+goodsId ,
                dataType : "json" ,
                success : function(data){
                    if(data === "0"){
                        alert("超级管理员无法删除！");
                        return false;
                    }
                    if(data === "1"){
                        tr.remove();
                        alert("删除成功！");
                        //移除该管理员显示
                        return false;
                    }
                    if(data === "2"){
                        alert("删除失败！");
                        return false;
                    }
                }
            });
        }
    }
</script>
</body>
</html>