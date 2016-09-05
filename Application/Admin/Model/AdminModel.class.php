<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-15 12:24:46
 */
namespace Admin\Model;

class AdminModel extends \Think\Model {

    /**
     * 过滤插入字段
     */
    protected $insertFields = array(
        'admin_username','admin_password','admin_role','admin_mail','admin_tel','admin_qq','last_login_time','last_login_ip','addtime',
    );

    /**
     * 过滤更新字段
     */
    protected $updateFields = array(
        'admin_id','admin_username','admin_password','admin_role','admin_mail','admin_tel','admin_qq','last_login_time','last_login_ip','last_update_time',
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
        //           self::MODEL_UPDATE    或者2   编辑数据时候验证 
        //           self::MODEL_BOTH      或者3   全部情况下验证（默认）
        array('admin_username','require','管理员名称不能为空！',1),
        array('admin_password1','require','首次密码不能为空！',0,'regex',1),
        array('admin_password2','require','确认密码不能为空！',0,'regex',1),
        array('admin_password1','password','两次密码不一致！',0,'confirm'),
        // array('admin_role','require','管理员所属用户组不能为空！',1),
        array('admin_mail','require','管理员邮箱不能为空',1),
        array('admin_mail','email','邮箱格式不正确！',1),
    
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
    /*****************以下是处理管理员的信息插入操作******************/
        //获取用户输入的管理员的密码
        $adminPass1 = I('post.admin_password1');
        $adminPass2 = I('post.admin_password2');
        //判断密码是否为空
        if(empty($adminPass1)){
            $this->error = "首次密码不能为空！";
            return false;
            exit;
        }
        if(empty($adminPass2)){
            $this->error = "确认密码不能为空！";
            return false;
            exit;
        }
        //如果两次输入的密码不一样，提示错误，并退出
        if($adminPass1 !== $adminPass2){
            $this->error = "两次密码不一致！";
            return false;
            exit;
        }
        //如果两次密码一致，则将数据插入到数据库中
        $data['admin_password'] = md5(md5($adminPass1));
        $data['last_login_ip'] = get_client_ip();
        $data['last_login_time'] = time();
        $data['addtime'] = time();
    }
    
    /**
     * 钩子函数：_before_update(),更新数据库之前执行
     * 
     */
    protected function _before_update(&$data , $option){
        $adminId = $option['where']['admin_id'];
        $adminPass = I('post.old_password');
        //首先获取用户密码，若为空，表示不修改密码，否则用户执行修改密码操作
        if($adminPass){
            //如果用户修改原始密码
            //从数据库中获取管理员的密码，并比较用户输入的是否和数据库的一致
            $password =  $this->field('admin_password')->find($adminId);
            if(md5(md5($adminPass)) !== $password['admin_password']){
                //如果用户输入的密码和原始密码不一致
                $this->error = "原始密码输入错误！";
                return false;
                exit;
            }else{
                //如果用户输入密码与原始密码一致，继续执行修改操作
                //获取用户输入的管理员的密码
                $adminPass1 = I('post.admin_password1');
                $adminPass2 = I('post.admin_password2');
                //判断密码是否为空
                if(empty($adminPass1)){
                    $this->error = "首次密码不能为空！";
                    return false;
                    exit;
                }
                if(empty($adminPass2)){
                    $this->error = "确认密码不能为空！";
                    return false;
                    exit;
                }
                //如果两次输入的密码不一样，提示错误，并退出
                if($adminPass1 !== $adminPass2){
                    $this->error = "两次密码不一致！";
                    return false;
                    exit;
                }
                if(md5(md5($adminPass1)) === $password['admin_password']){
                    $this->error = "新密码不能与原始密码相同！";
                    return false;
                    exit;
                }
                //如果两次密码一致，则将数据插入到数据库中
                $data['admin_password'] = md5(md5($adminPass1));
                $data['last_update_time'] = time();
                $data['last_login_ip'] = get_client_ip();
                $data['last_login_time'] = time();
            }
        }else{
            //如果用户没有修改原始密码
            $data['last_login_ip'] = get_client_ip();
            $data['last_login_time'] = time(); 
        }
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
