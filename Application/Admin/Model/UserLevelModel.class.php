<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-12 13:04:37
 */
namespace Admin\Model;

class UserLevelModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'level_name','jifen_top','jifen_bottom','level_discount','level_comment','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
        'level_id','level_name','jifen_top','jifen_bottom','level_discount','level_comment','last_update_time',
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
        //           self::MODEL_UPDATE    或者2   编辑数据时候验证 
        //           self::MODEL_BOTH      或者3   全部情况下验证（默认）
        array('level_name','require','会员等级名称不能为空！',1),
        array('jifen_bottom','require','积分下限不能为空！',1),
        array('jifen_bottom','require','积分下限必须是数字',1),
        array('jifen_top','require','积分上限不能为空！',1),
        array('jifen_top','number','积分上限必须是数字！',1),
        array('level_discount','number','会员折扣必须是数字！',2),
        array('level_discount',array(1,100),'会员折扣率必须在1-100之间！',0,'between',2)
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
        // 过滤会员附加描述中的xss字段
        $data['level_comment'] = removeXss(I('post.level_comment'));
        $data['addtime'] = time();
    } 
    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
        //过滤会员附加描述中的xss字段
        $data['level_comment'] = removeXss(I('post.level_comment'));
        $data['last_update_time'] = time();
    } 
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete($option){


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
