<?php if (!defined('THINK_PATH')) exit();?><!-- ajax获取用户的商品订单 -->
<!-- 合并订单start -->
<div class="merge-list">
    <?php if(is_array($userOrderGoodsList)): $i = 0; $__LIST__ = $userOrderGoodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--订单列表start-->
        <div class="ma-0--1">
            <div class="o-info o-inff">
                <div class="fl">
                    <input class="o-ch ve-al-mi" type="checkbox">
                    <span class=" ma-ri-15 co-888 fo-si-14">下单时间：<?php echo date("Y-m-d H:i:s",$vo["order_add_time"]);?></span>
                    <span class="ma-ri-15 co-888 fo-si-14">订单号：<a class="co-36c" href="<?php echo U('User/orderDetail',array('order_id' => $vo['order_id']));?>"><?php echo ($vo["order_sn"]); ?></a></span>
                </div>
            </div>
            <!-- 订单表头信息start -->
            <div class="list-group-title">
                <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th class="col-pro-img wi120 borsdjk"></th>
                        <th class="col-pro" style="width: 345px;">商品详情</th>
                        <th class="col-price" style="width: 120px;">商品规格</th>
                        <th class="col-price">单价/元</th>                        
                        <th class="col-quty">数量</th>
                        <th class="col-pay wi139">小计/元</th>
                        <th class="col-operate">订单状态</th>
                    </tr>
                </table>
            </div>
            <!-- 订单表头信息end -->
            <!-- 订单详情start -->
            <div class="o-pro">
                <table border="0" cellpadding="0" cellspacing="0">
                    <?php foreach($vo["order_goods"] as $fkey => $v): ?>
                        <tr>
                            <td class="col-pro-img">
                                <p>
                                    <a title="<?php echo ($v["goods_name"]); ?>" href="<?php echo U('Home/Goods/goods',array('id' => $v['goods_id']));?>" target="_blank">
                                        <img alt="<?php echo ($v["goods_name"]); ?>" src="<?php echo ($v["goods_thumb_small"]); ?>">
                                    </a>
                                </p>
                            </td>
                            <td class="col-pro-info te-al-le">
                                <p class="p-name">
                                    <a title="<?php echo ($v["goods_name"]); ?>" target="_blank" href="<?php echo U('Home/Goods/goods',array('id' => $v['goods_id']));?>"><?php echo ($v["goods_name"]); ?></a>
                                </p>
                            </td>
                            <td class="col-price" style="text-align: left;">
                                <?php $attrNameList = explode(",",$v["goods_attr_name"]); ?>
                                <?php $attrValueList = explode(",",$v["goods_attr_value"]); ?>
                                <?php if(is_array($attrNameList)): $key = 0; $__LIST__ = $attrNameList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v2): $mod = ($key % 2 );++$key;?><span style="display: block;">
                                        <?php if($v2 != ''): echo ($v2); echo ($attrValueList[$key-1]); endif; ?>
                                    </span><?php endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                            <td class="col-price"><em>¥</em><span><?php echo ($v["goods_price"]); ?></span></td>
                            <td class="col-quty"><?php echo ($v["goods_amount"]); ?></td>
                            <td rowspan="1" class="col-pay">
                                <p>
                                    <em>¥</em><span><?php echo ($v["goods_total_price"]); ?></span>
                                </p>
                            </td>
                            <!-- 合并单元列，只在第一件商品中显示 -->
                            <?php if($fkey == '0'): ?><td rowspan="<?php echo ($vo["goods_count"]); ?>" class="col-pay"><?php echo ($vo["order_status"]); ?></td><?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <!-- 商品订单价格信息start -->
                    <tr>
                        <td colspan="6" class="te-al-le litz-pa">
                            <p>商品总价 :
                                <span class="co-red"><em>¥</em><?php echo ($vo["goods_ori_price"]); ?></span>
                            </p>
                            <p>金币抵扣 :
                                <span class="co-red"><em>¥</em><?php echo ($vo["order_expense_coin"]); ?></span>
                            </p>
                            <p>商品邮费 :
                                <span class="co-red"><em>¥</em><?php echo ($vo["goods_shipping_expense"]); ?></span>
                            </p>
                            <p>邮费优惠 :
                                <span class="co-red"><em><?php if($vo['goods_shipping_discount'] != '0'): ?>-&nbsp;<?php endif; ?>¥</em><?php echo ($vo["goods_shipping_discount"]); ?></span>
                            </p>
                            <p>应付金额（含运费）:
                                <span class="co-red fo-si-18"><em>¥</em><?php echo ($vo["goods_final_price"]); ?></span>
                            </p>
                        </td>
                        <!-- 商品订单价格信息end -->

                        <!-- 商品订单操作start -->
                        <td rowspan="1" class="col-operate">
                            <!-- 如果订单状态是：待付款 -->
                            <?php if($vo["order_status"] == '待付款'): ?><p class="p-link">
                                    <a href="javascript:void(0);" onclick="cancel_order(<?php echo ($vo["order_id"]); ?>);">取消订单</a>
                                </p>
                                <p class="p-button">
                                    <a class="button-operate-pay di-in-bl hb-merge" href="<?php echo U('User/pay',array('order_id' => $vo['order_id']));?>" target="_blank">
                                        <span>立即支付</span>
                                    </a>
                                </p>
                                <p class="p-link">
                                    <a href="<?php echo U('User/orderDetail',array('order_id' => $vo['order_id']));?>">订单详情</a>
                                </p><?php endif; ?>
                            <!-- 如果订单状态是：待发货 -->
                            <?php if($vo["order_status"] != '待付款'): ?><p class="p-link">
                                    <a href="<?php echo U('User/orderService',array('id' => '1','order_id' => $vo['order_id']));?>" target="_blank">
                                        <span>申请退款</span>
                                    </a>
                                </p>
                                <p class="p-button">
                                    <a class="button-operate-pay di-in-bl hb-merge" href="<?php echo U('User/orderDetail',array('order_id' => $vo['order_id']));?>">订单详情</a>
                                </p>
                                <p class="p-link">
                                    <a href="<?php echo U('User/orderService',array('id' => '4','order_id' => $vo['order_id']));?>" target="_blank">
                                        <span>申请售后</span>
                                    </a>
                                </p><?php endif; ?>
                        </td>
                        <!-- 商品订单操作end -->
                    </tr>
                </table>
            </div>
            <!-- 订单详情end -->
        </div>
       <!--订单列表end--><?php endforeach; endif; else: echo "" ;endif; ?>
</div>

<!-- 用户商品订单的分页start -->
<div class="merge" style="float: right;margin-right: 20px;">
    <div class="dataTables_paginate paging_simple_numbers">
        <!-- 显示分页数据 -->
        <?php echo ($page); ?>
    </div>
</div>
<!-- 用户商品订单的分页end -->

<!-- 页面的js代码 -->
<script>
    //ajax动态删除用户的订单
    function cancel_order(id){
        if(confirm("确定取消订单？")){
            $.ajax({
                type : "GET",
                url : "<?php echo U('Home/User/ajaxDeleteUserOrder', '', FALSE); ?>/id/"+id,
                success : function(data){
                    //给客户端返回添加结果
                    alert(data);
                    //刷新页面
                    ajax_get_user_order(cur_page);
                    return;
                }
            });
        }
    }    
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_user_order(cur_page);
    });
</script>