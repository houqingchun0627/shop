<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-04 14:09:52
 */
namespace Admin\Model;

class UserAddressModel extends \Think\Model {

    /**
     * 过滤插入字段
     */
    protected $insertFields = array(
        "user_id","is_default_address","goods_consignee","consignee_postcode","consignee_tel",
        "consignee_address_province","consignee_address_city","consignee_address_country",
        "consignee_address_town","consignee_address_detail","addtime",
    );

    /**
     * 过滤更新字段
     */
    protected $updateFields = array(
        "id","user_id","is_default_address","goods_consignee","consignee_postcode","consignee_tel",
        "consignee_address_province","consignee_address_city","consignee_address_country",
        "consignee_address_town","consignee_address_detail","last_update_time",
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
        array('goods_consignee','require','收货人姓名不能为空！',1),
        array('consignee_tel','require','收货人手机号码不能为空！',1),
        array('consignee_tel','number','收货人手机号码格式错误！',1),
        array('consignee_address_province','require','收货人省份地址不能为空！',1),
        array('consignee_address_province','number','收货人省份地址格式错误！',1),
        array('consignee_address_city','require','收货人市级城市不能为空！',1),
        array('consignee_address_city','number','收货人市级城市格式错误！',1),
        array('consignee_address_country','require','收货人县区地址不能为空！',1),
        array('consignee_address_country','number','收货人县区地址格式错误！',1),
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
        //从session中获取当前操作的user_id
        $userId = session("user_id");
        //首先判断当前的收货人数量是否超过5个
        $amount = $this->where(array('user_id' => array('eq',$userId)))->count();
        if($amount >4){
            $this->error = "对不起，你的收货人地址不能超过5个";
            return false;
        }
        //将userId值赋值到$data中，插入数据库中
        $data["user_id"] = session("user_id");
        $data["addtime"] = time();
    } 
    
    /**
     * 钩子函数：_before_update(),更新数据库之前执行
     * 
     */
    protected function _before_update(&$data , $option){
        //从session中获取当前操作的user_id
        $userId = session("user_id");
        //首先判断当前的收货人数量是否超过5个
        $amount = $this->where(array('user_id' => array('eq',$userId)))->count();
        if($amount >4){
            $this->error = "对不起，你的收货人地址不能超过5个";
            return false;
        }
        $data["user_id"] = session("user_id");
        $data["last_update_time"] = time();
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
