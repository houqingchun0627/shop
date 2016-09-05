<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 10:39:06
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-08 13:58:09
 */

namespace Home\Controller;

class CartController extends \Think\Controller {
    public function cart(){
        //判断当前用户购物车中商品数量
        if(empty($this->getUserCart("2"))){
            //如果购物车中商品数量为空，提示错误，并跳转
            $url = U('Home/Goods/index');
            echo "<script type='text/javascript'>alert('购物车中还没有商品，赶紧选购吧！');window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
            //首先获取当前用户购物车中的商品信息，联表查询获取商品的详细新，返回给模版进行展示
           $cartGoodsList = $this->getUserCart("3");

           //将购物车中的商品信息赋值到模版中输出
           $this->assign(array(
                    "cartGoodsList"     =>  $cartGoodsList,
            ));
           $this->display("cart");
        }
    }

    public function cart2(){
        //注册页面，手动验证用户是否登录
        if(!($userId = session("user_id")) && !session("user_reset_id")){
            //如果session中user_id不存在，表示用户未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //判断当前用户购物车中商品数量
        if(empty($this->getUserCart("2","1"))){
            //如果购物车中商品数量为空，提示错误，并跳转
            $url = U('Home/Cart/cart');
            echo "<script type='text/javascript'>alert('购物车中还没有要结算的商品，请先选择！');window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
            //首先获取当前用户购物车中的商品信息，联表查询获取商品的详细新，返回给模版进行展示
           $cartGoodsList = $this->getUserCart("3",$isSelectList="1");
           //获取用户的收货人信息
           $goodsConsignee = D("Admin/UserAddress")->field("id,is_default_address,goods_consignee,consignee_tel,consignee_address_province,consignee_address_city,consignee_address_country,consignee_address_town,consignee_address_detail")->where(array('user_id' => array('eq',$userId)))->select();          
           //循环收货人信息，获取详细的收获人地址信息
           foreach ($goodsConsignee as $key => &$value) {
                foreach ($value as $k => &$v) {
                    if($k === "consignee_address_province" || $k === "consignee_address_city" ||
                    $k === "consignee_address_country" || $k === "consignee_address_town"){
                        $v = getAddress($v);
                   }
                }
           }
           //将购物车中的商品信息赋值到模版中输出
           $this->assign(array(
                    "cartGoodsList"     =>  $cartGoodsList["cartGoodsInfo"],
                    "amount"            =>  $cartGoodsList["amount"],
                    "totalPrice"        =>  $cartGoodsList["totalPrice"],
                    "goodsConsignee"    =>  $goodsConsignee,
            ));
           $this->display("cart2");
        }
    }

    public function cart3(){
        //注册页面，手动验证用户是否登录
        if(!($userId = session("user_id")) && !session("user_reset_id")){
            //如果session中user_id不存在，表示用户未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //获取订单的order_id
        if($orderId = I("get.order_id")){
            //获取订单的状态
            $orderStatus = D("Admin/Order")->where(array('user_id' => array('eq',$userId),'order_id' => array('eq',$orderId)))->getField("order_status");
            if($orderStatus === "待付款"){
                $orderMess = "订单提交成功，请在24小时内完成支付，否则订单将自动取消！";
            }
            if($orderStatus === "待发货"){
                $orderMess = "订单提交成功，我们将尽快处理你的订单！";
            }
            if($orderStatus === null){
                $orderMess = "对不起，你无权查看该订单！";
            }
           //将购物车中的商品信息赋值到模版中输出
           $this->assign(array(
                    "orderMess"    =>  $orderMess,
            ));
           $this->display("cart3");
           exit;
        }
        $url = U('Home/Goods/index');
        echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
        exit;
    }
    /**
     * ajax动态添加商品到购物车
     */
    public function ajaxAddGoodsToCart(){
        $goodId         = I("post.goods_id");
        $userId         = session("user_id");
        $sessionId      = cookie("PHPSESSID");
        $goodsAttrName = rtrim(I("post.attr_name_list"),",");
        $goodsAttrId    = rtrim(I("post.attr_id_list"),",");
        $goodsAttrValue = rtrim(I("post.attr_value_list"),",");
        $goodsAmount    = I("post.goods_amount");
        //获取ajax提交的post数据
        $data                       = I("post.");
        $data["user_id"]            = $userId;
        $data["user_session_id"]    = $sessionId;
        $data["attr_name_list"]     = $goodsAttrName;
        $data["attr_id_list"]       = $goodsAttrId;
        $data["attr_value_list"]    = $goodsAttrValue;
        $data["goods_price"]        = ltrim(I('post.goods_price'),"￥");
        $data["goods_add_time"]     = time();
        //拼接搜索条件
        $where = array(
                "goods_id"             =>  $goodId,
                "attr_id_list"         =>  $goodsAttrId,
                "attr_value_list"      =>  $goodsAttrValue,
            );
        //首先判断当前操作是插入还是更新
        if(empty($userId)){
            //如果用户未登录，利用sessionId进行查询
            $where["user_session_id"] = $sessionId;
            $data["user_id"]          = 0;
        }else{
            //如果用户已经登录，利用userId进行查询
            $where["user_id"] = $userId;
        }
        $oriCart = D("Admin/Cart")->field("id,goods_amount")->where($where)->find();
        if($oriCart === null){
            //如果数据库中没有该商品，执行添加操作
            $info = D("Admin/Cart")->add($data);
        }else{
            //如果数据库中有该商品，执行更新操作
            $data["id"] = $oriCart["id"];
            $data["goods_amount"] = $oriCart["goods_amount"] + $goodsAmount;
            $info = D("Admin/Cart")->save($data);
        }
        //将数据插入到cart数据库中
        if($info !== false){
            //如果添加成功
            exit("加入购物车成功");
        }else{
            exit("加入购物车失败");
        }
    }
    /**
     * ajax动态获取当前用户的购物车中的信息
     */
    public function ajaxGetUserCartInfo(){
        //调用私有方法，获取当前用户的购物车中的信息
        $cartGoods = $this->getUserCart("1");
        //将购物车中信息赋值到模版中
        $this->assign(array(
            "cartGoodsAmount"   =>  $cartGoods["amount"],
            "cartGoodsInfo"     =>  $cartGoods["cartList"],
            "totalPrice"        =>  $cartGoods["totalPrice"],
        ));
        $this->display("ajaxGetUserCartInfo");
    }
    /**
     * ajax动态获取当前用户的购物车中商品中的数量
     */
    public function ajaxGetUserCartGoodsNumber(){
        $total = $this->getUserCart("2");
        echo $total === null ? 0 : $total;
    }
    /**
     * ajax动态获取当前用户的购物车中商品中的数量
     */
    public function ajaxDeleteCartGoods(){
        $info = D("Admin/Cart")->delete(I('get.id'));
        if($info){
            //如果删除成功
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    /**
     * 私有方法，用来处理获取购物车的信息
     */
    private function getUserCart($id,$isSelectList="0"){
        //判断要执行的操作：1：获取购物车中商品信息(导航头部展示)  2：获取购物车中的商品数量  3：获取商品的详细信息(购物车页面展示，参数：isSelectList表示是否是获取结算页面的商品信息)
        //首先判断，当前cookie中存储的user_cart_sesion_id字段和当前的sessinId是否一致
        $sessionId = cookie("PHPSESSID");
        $oldSessionId = cookie("user_cart_session_id");
        if($sessionId !== $oldSessionId){
            //如果不一致，将新的sessionId更新到数据库中
            $info = D("Admin/Cart")->where(array('user_session_id' => array('eq',$oldSessionId),'user_id' => array('eq',"0")))->setField("user_session_id", $sessionId);
            // 将新的sessionId更新到cookie中的user_cart_session_id字段,设置有效期为一个月
            cookie("user_cart_session_id",$sessionId,30*24*3600);
        }
        //判断当前用户是否登录，如果登录，利用user_id从数据库中取出信息，否则，利用sessonId从数据库中取出信息
        if($userId = session("user_id")){
            //如果用户已经登录
            $where = array('user_id' => array('eq',$userId));
        }else{
            //如果用户未登录
            $sessionId = cookie("PHPSESSID");
            $where = array('user_session_id' => array('eq',$sessionId),'user_id' => array('eq',"0"));
        }
        //判断是否是获取商品结算页面的商品信息
        if($isSelectList === "1"){
            $where["is_select"] = array('eq',"是") ;
        }
        //获取满足条件的购物车中商品的数量
        $amount = D("Admin/Cart")->field('sum(goods_amount) goods_amount')->where($where)->select();
        //如果是获取用户的购物车中的信息
        if($id === "1"){
            //获取满足条件的购物车中的信息
            $cartList = D("Admin/Cart")->alias("a")->field("a.id,a.goods_id,a.goods_price,a.goods_amount,b.goods_name,b.goods_thumb_small")->join(array(
                'LEFT JOIN __GOODS__ b ON a.goods_id = b.goods_id'
            ))->where($where)->order("a.goods_add_time DESC")->select();
            //获取商品的总价格
            $price = D("Admin/Cart")->where($where)->getField("goods_price,goods_amount");
            $totalPrice = 0;
            foreach ($price as $key => $value) {
                $totalPrice += $key * $value;
            }
            return array("amount" => $amount["0"]["goods_amount"] , "cartList" => $cartList , "totalPrice" => $totalPrice);
        }
        //如果是获取用户的购物车中商品数量
        if($id === "2"){
            return $amount["0"]["goods_amount"];
        }
        //如果是获取商品的详细信息
        if($id === "3"){
            //连接cart表和goods表，获取商品的详细信息
            $cartGoodsList = D("Admin/Cart")->alias("a")->field("a.id,a.goods_id,a.attr_name_list goods_spec_attr,a.attr_id_list goods_price,a.attr_value_list goods_stock,a.goods_amount,b.goods_name,b.goods_thumb_small")->join(array(
                'LEFT JOIN __GOODS__ b ON a.goods_id = b.goods_id'
            ))->where($where)->order("a.goods_add_time DESC")->select();
            //遍历输出，拼装商品的价格信息
            //获取商品的总价格
            $totalPrice = 0;
            foreach ($cartGoodsList as $key => &$value) {
                if($value["goods_spec_attr"] !== ""){
                    //拼接商品的spec_attr属性，用于页面输出商品规格
                    $goodsAttrName = explode(',',$value["goods_spec_attr"]);
                    $goodsAttrValue = explode(',',$value["goods_stock"]);
                    $val = array();
                    for($i=0;$i<count($goodsAttrName);$i++){
                        $val[$goodsAttrName[$i]] = $goodsAttrValue[$i];
                    }
                    $value["goods_spec_attr"] = $val;
                }
                //获取商品的价格、库存量信息
                $goodsPriceStock = $this->calCartGoodsPriceStock($value["goods_id"],$value["goods_price"],$value["goods_stock"]);
                $value["goods_price"] = $goodsPriceStock["goods_price"];

                $value["goods_stock"] = $goodsPriceStock["goods_stock"];
                //判断商品的库存量是否为0，如果为0，则无法选择该商品，进行结算步骤
                if($value["goods_stock"] == "0"){
                    //如果商品库存量为0，将cart表中的is_select字段值设置为“否”
                    D("Admin/Cart")->where(array('id' => array('eq',$value["id"])))->setField("is_select","否");
                    //判断是否是订单结算步骤
                    if($isSelectList === "1"){
                        //如果是获取订单结算页面的商品信息，将商品从cartGoodsList数组中删除，不进行结算
                        unset($cartGoodsList[$key]);
                    }
                    //跳出本次循环
                    continue;
                }
                //判断是否是获取商品提交订单时的商品信息，获取订单全部商品的总价格
                if($isSelectList === "1"){
                    $value["goods_total_price"] = $value["goods_amount"] * $value["goods_price"];
                    $totalPrice += $value["goods_total_price"];
                }                
                //判断商品的购买数量是否大于商品库存量，如果大于，将商品库存量赋值给购买数量
                if($value["goods_amount"] > $value["goods_stock"]){
                    $value["goods_amount"] = $value["goods_stock"];
                }
            }
            //判断是否是获取订单结算页面的商品信息，如果是，返回相关订单商品信息
            if($isSelectList === "1"){
                $cartGoodsList = array("cartGoodsInfo" => $cartGoodsList , "amount" => $amount["0"]["goods_amount"] , "totalPrice" => $totalPrice);
            }
            return $cartGoodsList;
        }
    }
    /**
     * 私有方法，用来计算购物车中的商品的价格、库存量信息
     */
    private function calCartGoodsPriceStock($goodsId,$attrIdList = "",$attrValueList = ""){
        //获取该goods_id下的商品的价格信息
        $goodsInfo = D("Admin/Goods")->field("goods_shop_price,is_promote,goods_promote_price,promote_start_time,promote_end_time,user_is_discount")->find($goodsId);
        //商品本店价格
        $shop_price = (float)$goodsInfo["goods_shop_price"];
        //首先获取session中user的会员id，联表查询，获取该会员级别的会员折扣率
        $userLevel = session("user_level");
        if(isset($userLevel)){
            //如果用户已经登录
            $userDiscount = D("UserLevel")->find(session("user_level"));
            $discount = (float)$userDiscount['level_discount'];
            //获取当前goods_id和user_level下的商品的会员价格
            $userInfo = D("UserPrice")->where(array('goods_id' => array('eq',$goodsId),'user_level' => array('eq',$userLevel)))->find();
            $user_price = (float)$userInfo["goods_price"];
        }else{
            //如果用户未登录
            $user_price = $shop_price;
            $discount   = 100;
        }
        //商品是否促销
        $isPromote = $goodsInfo["is_promote"];
        //商品的促销价格
        $promote_price = (float)$goodsInfo["goods_promote_price"];
        //促销开始时间
        $startTime = $goodsInfo["promote_start_time"];
        //促销结束时间
        $endTime = $goodsInfo["promote_end_time"];
        //会员价格是否享受折上折
        $userDiscount = $goodsInfo["user_is_discount"];
        //判断当前商品是否有spec_attr属性
        if($attrIdList === "" && $attrValueList === ""){
            //如果商品没有spec_attr属性
            $attrPrice = 0;
            $goodsStock = $goodsStock = D("Admin/Goods")->where(array('goods_id' => array('eq',$goodsId)))->getField('goods_total_stock');
        }else{
            //如果商品有spec_attr属性
            //获取当前所选择的商品属性组合所对应的属性价格、属性库存量
            $attrList = D("GoodsStock")->where(array("goods_id" => array('eq',$goodsId),"attr_list" => array('eq',$attrIdList),"value_list" => array('eq',$attrValueList)))->find();
            $attrPrice = (float)$attrList["attr_price"];
            $goodsStock = $attrList["attr_stock"];
        }
        // //判断该商品是否在促销时间内
        if($isPromote === "是" && time() > $startTime && time() < $endTime){
            //如果商品在促销期间内，则按促销价格计算
            if($userDiscount === "是"){
                //如果会员促销折上折
                $shopPrice = $shop_price + $attrPrice;
                $userPrice = $user_price + $attrPrice * $discount/100;
                $promotePrice = ($promote_price + $attrPrice) * $discount/100;
                $goodsPrice = min($shopPrice,$userPrice,$promotePrice);
            }else{
                //如果会员不享受促销折上折
                $shopPrice = $shop_price + $attrPrice;
                $userPrice = $user_price + $attrPrice * $discount/100;
                $promotePrice = $promote_price + $attrPrice * $discount/100; 
                $goodsPrice = min($shopPrice,$userPrice,$promotePrice);
            }
        }else{
            //如果商品无促销，或者促销信息已经过期
            $shopPrice = $shop_price + $attrPrice;
            $userPrice = $user_price + $attrPrice * $discount/100; 
            $goodsPrice = min($shopPrice,$userPrice);
        }
        //将商品的价格信息返回
        return array("goods_price" => $goodsPrice,"goods_stock" => $goodsStock);
    }
    /**
     * ajax验证商品的购买数量
     */
    public function ajaxCheckGoodsAmount($pid = "0",$val = "0"){
    //参数：$id = 0 表示在cart1页面动态验证用户改变商品的数量，$id = 1表示对用户提交的cart1页面进行验证
        //获取当前商品在购物车中的主键id
        $id = I("get.id");
        if($pid === "1"){
            $id = $val;
        }
        //获取商品的购买数量
        $amount = I("get.amount");
        //首先判断当前商品是否有spec_attr,如果没有，直接查询goods表，如果有，查询goods_stock表
        $isHasSpecAttr = D("Admin/Cart")->field('user_id,goods_id,attr_id_list,attr_value_list')->cache(true)->find($id);
        //判断商品是否有spec_attr属性
        if(empty($isHasSpecAttr["attr_id_list"])){
            //如果没有spec_attr属性
            $goodsStock = D("Admin/Goods")->where(array('goods_id' => array('eq',$isHasSpecAttr["goods_id"])))->getField('goods_total_stock');
        }else{
            //如果有spec_attr属性
            $goodsStock = D("Admin/GoodsStock")->where(array('goods_id' => array('eq',$isHasSpecAttr["goods_id"]),'attr_list' => array('eq',$isHasSpecAttr["attr_id_list"]),'value_list' => array('eq',$isHasSpecAttr["attr_value_list"])))->getField('attr_stock');
        }
        if($pid === "1"){
            return $goodsStock;
        }
        $goodsAmount = $amount > $goodsStock ? $goodsStock : $amount;
        //如果商品的库存量为0
        if($goodsStock == "0"){
            $goodsAmount = 1;
        }
        //更新购物车中商品的购买数量
        $info = D("Admin/Cart")->where(array('id' =>array('eq',$id)))->setField("goods_amount",$goodsAmount);
        //判断当前商品数量是否超过商品库存量
        if($amount > $goodsStock){
            //如果商品购买数量大于商品库存量
            exit($goodsStock);
        }
        //如果商品购买数量不大于商品库存量
        exit("-1");
    }
    /**
     * ajax将已经勾选的商品添加到数据库中
     */
    public function ajaxCheckIsSelectGoods(){
        $isSelectList = rtrim(I('get.id'),",");
        //首先验证用户有没有篡改表单，提交了商品goods_stock为0的商品
        $selectGoodsIdList = "";
        foreach (explode(",",$isSelectList) as $key => $value) {
            //如果有提交商品库存量为0的商品，将其删除
            if("0" != $this->ajaxCheckGoodsAmount("1",$value) ){
                $selectGoodsIdList .= $value .",";
            }
        }
        $isSelectList = rtrim($selectGoodsIdList,",");
        if($userId = session("user_id")){
            //如果用户已经登录
            $where = array('user_id' => array('eq',$userId));
        }else{
            //如果用户未登录
            $sessionId = cookie("PHPSESSID");
            $where = array('user_session_id' => array('eq',$sessionId),'user_id' => array('eq',"0"));
        }
        //首先将该user_id或者user_session_id下的所有的商品的is_select字段值设为"否"
        $info = D("Admin/Cart")->where($where)->setField("is_select","否");
        $info = D("Admin/Cart")->where(array('id' => array('in',$isSelectList)))->setField("is_select","是");
        //如果设置成功
        if($info){
            $url = U("Home/Cart/cart2");
            exit($url);
        }
    }
    /**
     * ajax动态计算商品的价格，用于处理用户从购物车提交订单时
     */
    public function ajaxGetOrderPrice($data){

    }
}
