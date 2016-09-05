<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-07-07 17:52:13
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-01 21:45:10
 */
namespace Admin\Controller;

class BasicController extends \Think\Controller {
    
    //创建构造方法，引入初始化操作
    public function __construct(){
        //引入父类controller的构造方法
        parent::__construct();
        $this->logCheck();
        $this->caCheck();
    }
    //创建私有的方法，验证用户是否登录
    private function logCheck(){
        //获取用户当前操作的controller-action
        $controller = CONTROLLER_NAME;
        $action = ACTION_NAME;
        $ca = CONTROLLER_NAME."-".ACTION_NAME;
        $publicCa = array(
            //这是公共的controller-action,以-连接
            "Admin-login","Admin-captcha","Admin-logout",
        );
        if(!in_array($ca,$publicCa)){
            //获取session中的amin_id
            if(!session("admin_id")){
                //如果session中admin_id不存在，表示还未登录，直接跳转到登录页面
                //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
                $url = U('Admin/login');
                echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
                exit;
            } 
        }
    }
    //创建私有方法，验证用户的权限，防止翻墙操作
    private function caCheck(){
        //获取session中用户的admin_role
        $adminId = session("admin_id");
        //获取用户当前操作的controller-action
        $controller = CONTROLLER_NAME;
        $action = ACTION_NAME;
        $ca = CONTROLLER_NAME."-".ACTION_NAME;
        $publicCa = array(
            //这是公共的controller-action,以-连接
            "Admin-login","Admin-index","Admin-header","Admin-menu","Admin-main","Admin-captcha","Admin-logout",
        );
        $caList = D('Role')->field('role_auth_ca')->find(session("admin_role"));
        $list = array_merge($publicCa , explode(',', $caList["role_auth_ca"]));
        //判断当前操作ca是否合法，同时给超级管理员赋予绝对权限
        if((session("admin_id") !== "1") && !In_array($ca,$list)){
            //如果属于翻墙操作，提示错误信息，并跳转到index页面
            $this->error("你无权执行此操作！",U("admin/index"),3);
            exit;
        }
    }
}
