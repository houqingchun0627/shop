<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-08 16:16:19
 */
namespace Admin\Model;

class OrderModel extends \Think\Model {

    /**
     * 过滤插入字段
     */
    protected $insertFields = array(
        "order_sn","user_id","goods_consignee","goods_ori_price","goods_shipping_expense",
        "order_expense_coin","goods_final_price","goods_shipping_method","goods_shipping_time",
        "order_invoice_type","order_invoice_header","goods_pay_method","order_status",
        "order_add_time","order_pay_time","order_shipping_time","order_confirm_time","user_note",
        "seller_note","goods_shipping_discount",
    );

    /**
     * 过滤更新字段
     */
    protected $updateFields = array(
        "order_id","order_sn","user_id","goods_consignee","goods_ori_price","goods_shipping_expense",
        "order_expense_coin","goods_final_price","goods_shipping_method","goods_shipping_time",
        "order_invoice_type","order_invoice_header","goods_pay_method","order_status",
        "order_add_time","order_pay_time","order_shipping_time","order_confirm_time","user_note",
        "seller_note","goods_shipping_discount",
    );
    
    /**
     * 自动验证定义
     */
    protected $_validate =   array(
        // 验证语法：array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        // 验证规则：require 字段必须、email 邮箱、url URL地址、currency 货币、number 数字，可以自定义验证方法进行验证
        // 错误提示：字符串的错误提示信息
        // 验证条件：self::EXISTS_VALIDATE 或者0   存在字段就验证（默认） 
        //           self::MUST_VALIDATE   或者1   必须验证 
        //           self::VALUE_VALIDATE  或者2   值不为空的时候验证        
        // 附加规则：规则太多，查手册
        // 验证时间：self::MODEL_INSERT    或者1   新增数据时候验证 
        //          self::MODEL_UPDATE     或者2   编辑数据时候验证 
        //          self::MODEL_BOTH       或者3   全部情况下验证（默认）
        array('user_id','require','用户id不能为空！',1),
        array('goods_consignee','require','收货人不能为空',1),
        array('goods_consignee','number','收货人填写错误！',1),
        array('order_expense_coin','number','抵扣金币填写错误',1),
        array('goods_shipping_method','require','订单快递不能为空',1),
        array('goods_pay_method','require','订单支付方式不能为空！',1),  
    );
    
    /**
     * 自动完成定义
     */ 
    protected $_auto     =   array(
       

    );

    /**
     * 字段映射定义
     */ 
    protected $_map      =   array(
        
        
    ); 

    /**
     * 命名范围定义
     */ 
    protected $_scope    =   array(
        

    );
    
    /**
     * 钩子函数：_before_insert(),插入数据库之前执行
     * 
     */
    protected function _before_insert(&$data , $option){     
        //处理订单的order_sn编号
        $userId = $data["user_id"];
        $data["order_sn"] = date("YmdHis",time()).str_pad($userId,6,"0", STR_PAD_LEFT);
        //处理订单的邮费信息
        $userLevel = session("user_level");
        $goodsOriPrice = $data["goods_ori_price"];
        $data["goods_shipping_discount"] = 0.00;
        $goodsShipping = $data["goods_shipping_method"];
        if($goodsShipping === "京东快递"){
            $data["goods_shipping_expense"] = 10.00;
            if((($userLevel == "1") && ($goodsOriPrice > 199)) || (($userLevel == "2") && ($goodsOriPrice > 129)) || (($userLevel == "3") && ($goodsOriPrice > 79))){
                $data["goods_shipping_discount"] = 10.00;
            }
        }
        if($goodsShipping === "普通快递"){
            $data["goods_shipping_expense"] = 20.00;
        }
        if($goodsShipping === "顺丰特快"){
            $data["goods_shipping_expense"] = 30.00;
        }
        if($goodsShipping === "平邮"){
            $data["goods_shipping_expense"] = 40.00;
        }
        //处理订单的最终价格 = 商品原始价格总和 + 运费 - 邮费优惠 - 优惠抵用券
        $data["goods_final_price"] = $data["goods_ori_price"] + $data["goods_shipping_expense"]- $data["goods_shipping_discount"] - $data["order_expense_coin"];
        //处理订单的order_sn编号
        $data["order_sn"] = date("YmdHis",time()).str_pad($userId,6,"0", STR_PAD_LEFT);
        //处理订单的状态
        if($data["goods_pay_method"] === "货到付款"){
            $data["order_status"] = "待发货";
        }
        //处理订单的添加时间
        $data["order_add_time"] = time();
        // echo "<pre>";
        // var_dump($data);
        // exit;
    } 
    
    /**
     * 钩子函数：_before_update(),更新数据库之前执行
     * 
     */
    protected function _before_update(&$data , $option){


    } 
    
    /**
     * 钩子函数：_before_delete(),删除数据库之前执行
     * 
     */
    protected function _before_delete($option){


    } 
    
    /**
     * 钩子函数：_after_insert(),插入数据库之后执行
     * 
     */
    protected function _after_insert(&$data , $option){
        $orderId = $data["order_id"];
        $userId = $data["user_id"];
        //获取用户插入order_goods表中的数据
        $orderGoodsData = D("Admin/Cart")->field("goods_id,attr_name_list as goods_attr_name,attr_id_list as goods_attr_id,attr_value_list as goods_attr_value,goods_amount,goods_price,(goods_amount*goods_price) as goods_total_price")->where(array('user_id' => array('eq',$userId),'is_select' => array('eq',"是")))->select();
        foreach ($orderGoodsData as $key => $value) {
            $value["order_id"] = $orderId;
            $info = D("Admin/OrderGoods")->add($value);
        }
        //修改商品-库存goods_stock表中的attr_stock字段的值
        foreach ($orderGoodsData as $key => $value) {
            if($value["goods_attr_id"] !== "" && $value["goods_attr_value"] !== ""){
                $info = D("Admin/GoodsStock")->where(array('goods_id' => array('eq',$value["goods_id"]),'attr_list' => array('eq',$value["goods_attr_id"]),'value_list' => array('eq',$value["goods_attr_value"])))->setDec('attr_stock',$value["goods_amount"]);
            }
        }
        //修改商品goods表中goods_total_stock字段的值
        $goodsData = D("Admin/Cart")->field("goods_id,sum(goods_amount) as goods_amount")->where(array('user_id' => array('eq',$userId),'is_select' => array('eq',"是")))->group("goods_id")->select();
        foreach ($goodsData as $k => $v) {
            $info = D("Admin/Goods")->where(array('goods_id' => array('eq',$v["goods_id"])))->setDec("goods_total_stock",$v["goods_amount"]);
        }
        // //将已经提交订单中的商品从购物车中删除
        $info = D("Admin/Cart")->where(array('user_id' => array('eq',$userId),'is_select' => array('eq',"是")))->delete();
    }
    
    /**
     * 钩子函数：_after_update(),更新数据库之后执行
     * 
     */
    protected function _after_update(&$data , $option){
        

    }

    /**
     * 钩子函数：_after_delete(),删除数据库之后执行
     * 
     */
    protected function _after_delete($option){
        

    }
    
}
