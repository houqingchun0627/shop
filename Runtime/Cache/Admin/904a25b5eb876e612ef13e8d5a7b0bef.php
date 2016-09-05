<?php if (!defined('THINK_PATH')) exit();?><!-- ajax展示商品列表 -->
<form action="" id="GoodsListForm" method="post" onsubmit="return false">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th><a href="javascript:sort('goods_id');">编号</a></th>
                <th><a href="javascript:sort('goods_name');">名称</a></th>
                <th><a href="javascript:sort('goods_sn');">货号</a></th>
                <th><a href="javascript:sort('cat_name');">主分类</a></th>
                <th><a href="javascript:void(0);">扩展分类</a></th>
                <th><a href="javascript:sort('brand_name');">品牌</a></th>
                <th><a href="javascript:sort('type_name');">类型</a></th>
                <th><a href="javascript:sort('goods_shop_price');">价格</a></th>
                <th><a href="javascript:sort('is_onsale');">上架</a></th>
                <!-- <th><a href="javascript:sort('is_delete');">回收站</a></th> -->
                <th><a href="javascript:sort('is_best');">精品</a></th>
                <th><a href="javascript:sort('is_new');">新品</a></th>
                <th><a href="javascript:sort('is_hot');">热销</a></th>
                <th><a href="javascript:sort('goods_sort_order');">推荐排序</a></th>
                <th><a href="javascript:sort('goods_total_stock');">库存</a></th>
                <th><a href="javascript:sort('addtime');">添加时间</a></th>
                <th><a href="javascript:void(0);">商品图片</a></th>
                <th><a href="javascript:void(0);">操作</a></th>
            </tr>
            <?php if(is_array($goodsList)): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><input type="hidden" value="<?php echo ($vo["goods_id"]); ?>" /><?php echo ($vo["goods_id"]); ?></td>
                    <td align="center" width="15%"><?php echo ($vo["goods_name"]); ?></td>
                    <td align="center"><?php echo ($vo["goods_sn"]); ?></td>
                    <td align="center"><?php echo ($vo["cat_name"]); ?></td>
                    <td align="center"><?php echo ($vo["ext_cat_name"]); ?></td>
                    <td align="center"><?php echo ($vo["brand_name"]); ?></td>
                    <td align="center"><?php echo ($vo["type_name"]); ?></td>
                    <td align="center"><?php echo ($vo["goods_shop_price"]); ?></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if(($vo["is_onsale"] == 是)): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <!-- <td align="center"><img src="/shop/Public/Admin/Images/<?php if(($vo["is_delete"] == 是)): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td> -->
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo[is_best] == 是): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo[is_new] == 是): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo[is_hot] == 是): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <td align="center"><?php echo ($vo["goods_sort_order"]); ?></td>
                    <td align="center"><?php echo ($vo["goods_total_stock"]); ?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$vo['addtime']);?></td>
                    <td class="first-cell" align="center">
                        <span><a href="<?php echo ($vo["goods_image"]); ?>" target="_brank"><img src="<?php echo ($vo["goods_thumb_smallest"]); ?>" width="30" height="30" border="0" alt="商品图片" /></a></span>
                    </td>
                    <td align="center">
                        <a href="<?php echo U('Home/Goods/goods',array('id' => $vo['goods_id']));?>" target="_blank" title="查看商品详情"><img src="/shop/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('Goods/goodsMessage',array('id' => 2,'goods_id' => $vo['goods_id']));?>" title="编辑修改商品"><img src="/shop/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                        <a href="javascript:void(0);" title="删除商品" onclick="ajaxDeleteGoods(this);"><img src="/shop/Public/Admin/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('Goods/goodsMessage',array('id' => 4,'goods_id' => $vo['goods_id']));?>" onclick="" title="加入回收站"><img src="/shop/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="20%">&nbsp;</td>
                <td align="right" nowrap="true" style="padding-right:50px;">
                    <?php echo ($page); ?>
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>
<script>
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_goodsList('goodsSearchForm',cur_page);
    });
</script>