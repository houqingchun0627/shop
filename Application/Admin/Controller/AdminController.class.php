<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 09:57:45
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-01 21:41:30
 */

namespace Admin\Controller;

class AdminController extends \Admin\Controller\BasicController {

    /**
     * 后台首页的展示页面
     * 
     */
    public function index(){
        
        $this->display('index');
    }

    /**
     * 后台管理员登录页面
     * 
     */
    public function login(){
        //登录页面，手动验证用户是否登录
        if(session("admin_id")){
            //如果session中admin_id存在，表示已经登录，直接跳转到后台首页
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Admin/Admin/index');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
            //如果是get方式提交,展示登录页面
            $this->display('login');
            exit;
        }
        if(IS_POST){
            //获取用户的输入的用户名和密码
            $username = I('post.username');
            $password = I('post.password');
            $captcha = I('post.captcha');
            //以下操作是防止翻墙操作，非法url篡改
            $usernameReg = preg_match("/^[a-zA-Z0-9_\@]{4,}$/",$username);
            $passwordReg = preg_match("/^[a-zA-Z0-9_\@\#\+\-]{4,}$/",$password);
            $captchaReg = preg_match("/^[a-zA-Z0-9_]{4}$/",$captcha);
            //用户名格式错误
            if(!$usernameReg){
                echo json_encode(array('re_code' => "0",'mess' => "请确认帐号格式",'focus' =>"1"));
                return false;
            }
            //密码格式错误
            if(!$passwordReg){
                echo json_encode(array('re_code' => "0",'mess' => "请确认密码格式",'focus' =>"2"));
                return false;
            }
            //验证码格式错误
            if(!$captchaReg){
                echo json_encode(array('re_code' => "0",'mess' => "请确认验证码格式",'focus' =>"3"));
                return false;
            }
            //如果用户帐号、密码、验证码格式都正确，则验证帐号、密码、验证码是否正确
            //首先判断验证码是否正确：调用check()方法
            $verifyImg = new \Think\Verify();
            //验证码错误
            if(!($verifyImg->check($captcha))){
                //如果用户验证码输入错误
                echo json_encode(array('re_code' => "1",'mess' => "验证码输入错误",'focus' =>"3"));
                return false;
            }
            //如果验证码输入正确，然后验证帐号和密码是否正确
            $info = D('Admin')->where(array('admin_username' => array('eq',$username)))->find();
            //用户名不存在
            if($info === null){
                //如果用户名不存在
                echo json_encode(array('re_code' => "1",'mess' => "用户名不存在",'focus' =>"1"));
                return false;
            }
            //密码错误
            if($info["admin_password"] !== md5(md5($password))){
                //如果用户名存在，但是密码错误
                echo json_encode(array('re_code' => "1",'mess' => "密码错误",'focus' =>"2"));
                return false;
            }
            //以下内容待完善
            //密码错误次数过多，帐号锁定..........
            //帐号异常,帐号异地登录.........
            //帐号异常,帐号已经登录.........
            //帐号异常,请联系客服解决.......
            //验证成功，将管理员的admin_id、admin_role写入到session中
            session('admin_id',$info["admin_id"]);
            session('admin_name',$info['admin_username']);
            session('admin_role',$info['admin_role']);
            //同时更新yt_admin数据表中的last_login和last_time字段
            $info = D("Admin")->where(array('admin_id' => array('eq',$info["admin_id"])))->save(array('last_login_time' => time(),'last_login_ip' => get_client_ip()));
            if(false !== $info){
                //如果字段last_login、last_ip更新成功！
                //将要跳转的页面的url地址返回
                echo json_encode(array('re_code' => "2" , 'mess' => U('index')));
                return true;
            }
        }  
    }

    /**
     * 后台管理员登出页面
     * 
     */
    public function logout(){
        //清空session信息，并且跳转到登录首页
        session("admin_id",null);
        session("admin_name",null);
        session("admin_role",null);
        $this->success('退出成功！',U('login'),2);
    }

    /**
     * 验证码生成的工具类
     * 
     */
    public function captcha(){
        $config = array(
                    'fontSize'  =>  15,              // 验证码字体大小(px)
                    'useCurve'  =>  true,            // 是否画混淆曲线
                    'useNoise'  =>  true,            // 是否添加杂点  
                    'imageH'    =>  30,              // 验证码图片高度
                    'imageW'    =>  110,             // 验证码图片宽度
                    'length'    =>  4,               // 验证码位数
                    'fontttf'   =>  '4.ttf',         // 验证码字体，不设置随机获取
        );
        //生成验证码
        $verifyImg = new \Think\Verify($config);
        //输出到页面中
        echo $verifyImg->entry();
    }
    /**
     * 首页侧边栏的展示
     */
    public function menu(){
        //获取当前管理员的角色id下的所有权限
        $roleList = D('Role')->field('role_auth_list')->find(session('admin_role'));
        //获取所有的可显示的权限列表，并在首先的菜单栏中展示
        $pidList = D('Auth')->field('auth_id')->where(array('auth_level' => array('eq','0'),'is_show' => array('eq','是')))->select();
        //将二维数组转换成为一维数组
        $list = array("0");
        foreach ($pidList as $key => $value) {
            $list[] = $value['auth_id'];
        }
        $authList = D('Auth')->field('auth_id,auth_pid,auth_name,auth_controller,auth_action,auth_path,auth_level')->where(array('is_show' => array('eq','是'),'auth_id' => array('in',$roleList["role_auth_list"]),'auth_pid' => array('in',implode(',',$list))))->select();
        //给给管理员分配绝对的权限
        if(session('admin_id') === "1" && session('admin_role') === "0"){
            //如果admin_id为1,并且角色id为0
           $authList = D('Auth')->field('auth_id,auth_pid,auth_name,auth_controller,auth_action,auth_path,auth_level')->where(array('is_show' => array('eq','是'),'auth_pid' => array('in',implode(',',$list))))->select();
        }
        //调用getTree()方法获取树状结构
        $treeList = D('Auth')->field('auth_name,auth_controller,auth_action,auth_path,auth_level')->getTree($authList);
        $this->assign(array(
                //treeList中的数据在index.html中的侧边栏menu.html中进行输出展示
                'treeList'  =>  $treeList,
                //后续还要获取服务器端的信息，并在main.html页面中进行输出展示  
            )
        );
        $this->display('menu');
    }
    /**
     * 后台管理员管理方法
     * 
     */
    public function adminMessage(){
        //获取管理员模型的对象
        $adminModel = D('Admin');
        //首先判断是否是提交表单
        if(IS_GET){
            //如果是get请求，表示是对管理员列表的展示、添加、修改、删除、回收站操作
            $id = I('get.id');
            if(!empty($id)){
                $adminId = I('get.admin_id');
                //如果id值不为空，表示是添加、修改、删除、回收站操作
                if((1 == $id) || (2 == $id)){
                    //如果id值是1表示是添加，id值是2表示是修改操作
                    $adminList = $adminModel->field('admin_password',true)->find($adminId);
                    $this->assign(array(
                            'adminList'     =>  $adminList,
                        )
                    );
                    $this->display('_admin');
                }elseif(3 == $id){
                    //如果get传递的参数是3，表明是直接删除操作
                    if(false !== $adminModel->delete(I('get.admin_id'))){
                        //如果删除成功
                        $this->success('管理员删除成功！', U('adminMessage'));
                        exit;
                    }
                    //如果删除失败，获取失败的原因
                    $error = $adminModel->getError();
                    $this->error($error);
                }elseif(4 == $id){
                    //如果get传递的参数是4，表明是加入回收站操作
                    
                }else{
                    //如果是其他的id值，表示url地址被篡改
                    $this->error('你输入的地址错误！',U('adminMessage'));
                }
                exit; 
            }
            //如果id值为空，表示是展示管理员列表操作,过滤掉管理员的密码信息
            // $adminList = $adminModel->field('admin_password',true)->select();
            $adminList = $adminModel->alias('a')->field('a.admin_id,a.admin_username,a.admin_mail,a.admin_qq,a.admin_tel,a.last_login_time,a.last_login_ip,a.addtime,b.role_name')->join('LEFT JOIN __ROLE__ b ON a.admin_role = b.role_id')->select();
            $this->assign(array(
                    'adminList' =>  $adminList,
                )
            );
            $this->display('admin');
            exit;
        }
        //下面是对post表单提交数据进行处理
        if(IS_POST){
            //如果是post表单提交操作
            $adminId = I('post.admin_id');
            //判断表单admin_id是否为空
            if(empty($adminId)){
                //如果admin_id值为空，则表明是添加操作
                if($adminModel->create(I('post.') , 1)){ 
                    //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $adminModel->add()){
                        //如果插入成功
                        $this->success('管理员添加成功！', U('adminMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $adminModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $adminModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
            }else{
                //如果admin_id值不为空，表示是修改操作
                if($adminModel->create(I('post.') , 2)){ 
                //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $adminModel->save()){
                        //如果插入成功
                        $this->success('管理员修改成功！', U('adminMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $adminModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $adminModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                exit;
            }
        }
    }

    /**
     * 后台角色管理方法
     * 
     */
    public function roleMessage(){
        //获取角色的对象
        $roleModel = D('Role');
        //首先判断是否是提交表单
        if(IS_GET){
            //如果是get请求，表示是对角色表的展示、添加、修改、删除、回收站操作
            $id = I('get.id');
            if(!empty($id)){
                $roleId = I('get.role_id');
                //如果id值不为空，表示是添加、修改、删除、回收站操作
                if((1 == $id) || (2 == $id)){
                    //如果id值是1表示是添加，id值是2表示是修改操作
                    //获取当前角色role_id下的所有的权限
                    $roleList = $roleModel->find($roleId);
                    //获取所有的权限，暂时只考虑二级，无限级太麻烦
                    $topAuth = D('Auth')->field('auth_id,auth_name,auth_pid,auth_controller,auth_action')->where(array('auth_level' => array('eq','')))->select();
                    $secondAuth = D('Auth')->field('auth_id,auth_name,auth_pid,auth_controller,auth_action')->where(array('auth_level' => array('eq','1')))->select();
                    $this->assign(array(
                            'roleList'     =>  $roleList,
                            'authList'     =>  explode(',',$roleList["role_auth_list"]),
                            'topAuth'      =>  $topAuth,
                            'secondAuth'   =>  $secondAuth,
                        )
                    );
                    $this->display('_role');
                }elseif(3 == $id){
                    //如果get传递的参数是3，表明是直接删除操作
                    if(false !== $roleModel->delete(I('get.role_id'))){
                        //如果删除成功
                        $this->success('角色删除成功！', U('roleMessage'));
                        exit;
                    }
                    //如果删除失败，获取失败的原因
                    $error = $roleModel->getError();
                    $this->error($error);
                }elseif(4 == $id){
                    //如果get传递的参数是4，表明是加入回收站操作
                    
                }else{
                    //如果是其他的id值，表示url地址被篡改
                    $this->error('你输入的地址错误！',U('roleMessage'));
                }
                exit; 
            }
            //如果id值为空，表示是展示角色列表操作
            $roleList = $roleModel->select();
            $this->assign(array(
                    'roleList' =>  $roleList,
                )
            );
            $this->display('role');
            exit;
        }
        //下面是对post表单提交数据进行处理
        if(IS_POST){
            //如果是post表单提交操作
            $roleId = I('role_id');
            //判断表单admin_id是否为空
            if(empty($roleId)){
                //如果admin_id值为空，则表明是添加操作
                if($roleModel->create(I('post.') , 1)){ //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $roleModel->add()){
                        //如果插入成功
                        $this->success('角色添加成功！', U('roleMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $roleModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $roleModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
            }else{
                //如果role_id值不为空，表示是修改操作
                if($roleModel->create(I('post.') , 2)){ 
                //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $roleModel->save()){
                        //如果插入成功
                        $this->success('管理员修改成功！', U('roleMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $roleModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $roleModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                exit;
            }
        }
    }

    /**
     * 后台权限管理方法
     * 
     */
    public function authMessage(){
        //获取权限模型的对象
        $authModel = D('Auth');
        //首先判断是否是提交表单
        if(IS_GET){
            //如果是get请求，表示是对权限列表的展示、添加、修改、删除、回收站操作
            $id = I('get.id');
            if(!empty($id)){
                $authId = I('get.auth_id');
                //如果id值不为空，表示是添加、修改、删除、回收站操作
                if((1 == $id) || (2 == $id)){
                    //如果id值是1表示是添加，id值是2表示是修改操作
                    $authList = $authModel->find($authId);
                    $this->assign(array(
                            'authList'     =>  $authList,
                        )
                    );
                    $this->display('_auth');
                }elseif(3 == $id){
                    //如果get传递的参数是3，表明是直接删除操作
                    if(false !== $authModel->delete(I('get.auth_id'))){
                        //如果删除成功
                        $this->success('权限删除成功！', U('authMessage'));
                        exit;
                    }
                    //如果删除失败，获取失败的原因
                    $error = $authModel->getError();
                    $this->error($error);
                }elseif(4 == $id){
                    //如果get传递的参数是4，表明是加入回收站操作
                    
                }else{
                    //如果是其他的id值，表示url地址被篡改
                    $this->error('你输入的地址错误！',U('authMessage'));
                }
                exit; 
            }
            //如果id值为空，表示是展示权限列表操作
            //调用getTree()方法，树状展示所有的权限
            $authList = $authModel->getTree();
            $this->assign(array(
                    'authList' =>  $authList,
                )
            );
            $this->display('auth');
            exit;
        }
        //下面是对post表单提交数据进行处理
        if(IS_POST){
            //如果是post表单提交操作
            $authId = I('auth_id');
            //判断表单admin_id是否为空
            if(empty($authId)){
                //如果admin_id值为空，则表明是添加操作
                if($authModel->create(I('post.') , 1)){ 
                    //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $authModel->add()){
                        //如果插入成功
                        $this->success('商品添加成功！', U('authMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $authModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $authModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
            }else{
                //如果auth_id值不为空，表示是修改操作
                if($authModel->create(I('post.') , 2)){ 
                //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $authModel->save()){
                        //如果插入成功
                        $this->success('权限修改成功！', U('authMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $authModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $authModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                exit;
            }
        }
    }

    /**
     * ajax删除管理员
     * 
     */
    public function ajaxDelete(){
        //判断是否是超级管理员、并且超级管理员无法删除
        if(I('get.admin_id') === "1"){
            //如果admin_id为1，表示是超级管理员
            echo json_encode("0");
            exit;
        }
        //如果不是超级管理员，执行删除操作
        if(false === D("Admin")->delete(I('get.admin_id'))){
            //如果删除失败
            echo json_encode("2");
        }else{
            echo json_encode("1");
        }
    }
}
