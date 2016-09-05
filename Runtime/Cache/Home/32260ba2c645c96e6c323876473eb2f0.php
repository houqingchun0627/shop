<?php if (!defined('THINK_PATH')) exit();?><!-- ajax动态获取用户购物车中的商品信息 -->
<!-- 判断当前购物车是否为空 -->
<?php if($cartGoodsAmount == null): ?>购物车中还没有商品，赶紧选购吧！
<?php else: ?>
    <div class="ri ris" style="display:block;">
        <div class="commod">
            <ul>
                <?php if(is_array($cartGoodsInfo)): $i = 0; $__LIST__ = $cartGoodsInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="goods">
                        <div>
                            <div class="p-img">
                                <a href="<?php echo U('Goods/goods',array('id' => $vo['goods_id']),FALSE);?>">
                                    <img src="<?php echo ($vo["goods_thumb_small"]); ?>" alt="<?php echo ($vo["goods_name"]); ?>">
                                </a>
                             </div>
                             <div class="p-name">
                                <a href="<?php echo U('Goods/goods',array('id' => $vo['goods_id']),FALSE);?>">
                                    <span class="p-slogan"><?php echo ($vo["goods_name"]); ?></span>
                                    <span class="p-promotions hide"></span>
                                </a>
                             </div>
                             <div class="p-status">
                                <div class="p-price">
                                    <b>¥&nbsp;<?php echo ($vo["goods_price"]); ?></b>
                                    <em>x</em>
                                    <span><?php echo ($vo["goods_amount"]); ?></span>
                                </div>
                                <div class="p-tags"></div>
                             </div>
                             <a href="javascript:;" class="icon-minicart-del" title="删除" onclick="ajaxDeleteCartGoods(<?php echo ($vo["id"]); ?>);">删除</a> 
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="settle">
            <p>共<em><?php echo ($cartGoodsAmount); ?></em>件商品，金额合计<b>¥&nbsp;<?php echo ($totalPrice); ?></b></p>
            <a class="js-button" href="<?php echo U('Cart/cart');?>">去结算</a>
        </div>
    </div><?php endif; ?>