<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-12 13:05:52
 */
namespace Admin\Model;

class TypeModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'type_name','type_group','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
        'type_id','type_name','type_group','last_update_time',
    );
    // 自动验证定义
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
        array('type_name','require','商品分类不能为空！',1),
    ); 
    // 自动完成定义 
    protected $_auto     =   array(
       

    );
    // 字段映射定义 
    protected $_map      =   array(
        
        
    ); 
    // 命名范围定义 
    protected $_scope    =   array(
        

    );
    //钩子函数：_before_insert(),插入数据库之前执行
    protected function _before_insert(&$data , $option){     
        //将type_addtime字段添加到$data中
        $data['addtime'] = time();
        $data['type_group'] = removeXss(I('post.type_group'));
    } 
    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
        $data['type_group'] = removeXss(I('post.type_group'));
        $data['last_update_time'] = time();
    } 
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete($option){
        //删除商品分类之前，先判断该type类下是否还有商品属性对应，是否仍有商品类型对应
        if(false){
            //如果商品分类下仍然存在属性，提醒用户先修改属性到其他分类下
            $this->error = "当前分类下有属性对应，请先移动属性到其他分类下！";
            return false;
        }
        if(false){
            //如果商品分类仍有商品对应
            $this->error = "当前属性仍有商品对应，请先移动商品属性到其他属性下！";
            return false;
        }
        //如果商品分类下不存在属性，就可以直接删除
        
    } 
    //钩子函数：_after_insert(),插入数据库之后执行
    protected function _after_insert(&$data , $option){
        

    }
    //钩子函数：_after_update(),更新数据库之后执行
    protected function _after_update(&$data , $option){
        

    }
    //钩子函数：_after_delete(),删除数据库之后执行
    protected function _after_delete($option){
        

    }
}
