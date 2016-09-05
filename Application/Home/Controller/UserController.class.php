<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 10:24:50
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-09 14:24:20
 */

namespace Home\Controller;

class UserController extends \Home\Controller\BaseController {
    public function test(){
        echo "<pre>";
        var_dump(cookie());
        var_dump(session());
        exit;
    }
    /**
     * 会员注册页面
     */
    public function regist(){
        //注册页面，手动验证用户是否登录
        if(session("user_id")){
            //如果session中admin_id存在，表示已经登录，直接跳转到后台首页
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/Goods/index');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是

                ),
        ));
        $this->display('regist');
    }
    /**
     * 会员注册信息验证
     */
    public function checkReg(){
        //遍历数据，判断数据的正确性
        $flag = I('post.flag');
        foreach (I('post.') as $key => $value){
            //如果是验证用户密码信息，如果是注册提交表单中的信息，不进行验证
            if($key === "user_password" && $flag !== "2"){
                $user = D("User")->field("user_id,user_name,user_password,user_level")->where(array('user_name' => array('eq',I('post.user_name'))))->find();
                //判断用户登录密码是否正确
                if($user["user_password"] === md5(md5($value)) && $flag === "3"){
                    //如果密码正确,并且是用户登录onblur事件
                    echo json_encode(array('item' => $key,'code' => "1"));
                    return true;
                }
                if($user["user_password"] !== md5(md5($value))){
                    //如果密码错误，并且是用户登录提交onsubmit事件
                    echo json_encode(array('item' => $key,'code' => "3"));
                    return false;
                }
            }
            //如果不是验证码验证：用户名、手机号码、电子邮箱
            if($key !== "captcha" && $key !== "flag" && $key !== "user_password"){
                //获取当前查询的字段在数据库中个数
                $count = D("User")->where(array($key => array('eq',$value)))->count();
                //用户注册提交表单，信息在数据库中已经存在，报错误信息
                if($flag === "2" && $count > 0){
                    //如果该字段数量大于0，并且是用户注册提交表单
                    echo json_encode(array('item' => $key,'code' => "3"));
                    return false;
                    exit;
                }
                //用户登录提交表单，信息在数据库中不存在，报错误信息
                if($flag === "4" && $count === 0){
                    //如果该字段数量大于0，并且是用户登录提交表单
                    echo json_encode(array('item' => $key,'code' => "3"));
                    return false;
                    exit;
                }
                //如果不是提交表单操作
                if($flag === "1" || $flag === "3"){
                    if($count > 0){
                        //如果该字段未被注册
                        echo json_encode(array('item' => $key,'code' => "3"));
                        return true;
                        exit;
                    }
                    echo json_encode(array('item' => $key,'code' => "1"));
                    return true;
                }
            }else if($key === "captcha"){
                //如果是验证码验证
                if($this->authcode(strtoupper($value)) !== session($this->authcode("ThinkPHP.CN"))["verify_code"]){
                    //如果验证码不正确
                    echo json_encode(array('item' => $key,'code' => "3"));
                    return false;
                    exit;
                }
                //如果验证码验证成功，并且是onblur事件
                if($flag === "1" || $flag === "3"){
                    //如果验证码验证成功，并且是onblur事件
                    echo json_encode(array('item' => $key,'code' => "1"));
                    return true;
                    exit;
                }
                //判断是登录操作，还是注册操作
                if($flag === "2"){
                    //如果是注册操作，将注册信息：用户名、密码、电子邮箱、手机号码、注册时间、上次登录时间、上次登录等信息写入到数据库
                    $userRegName = I('post.user_name');
                    $userRegPass = I('post.user_password');
                    $userRegMail = I('post.user_mail');
                    $mailToken = $userRegName . $userRegPass . $userRegMail . time();
                    $info = D("User")->field('user_name,user_password,user_mail,mail_validate_token,user_tel,reg_time,last_login_time,last_login_ip')->add(array(
                        'user_name'             =>  $userRegName,
                        'user_password'         =>  md5(md5($userRegPass)),
                        'user_mail'             =>  $userRegMail,
                        'mail_validate_token'   =>  $this->authcode($mailToken),
                        'user_tel'              =>  I('post.user_tel'),
                        'reg_time'              =>  time(),
                        'last_login_time'       =>  time(),
                        'last_login_ip'         =>  get_client_ip(),
                    ));
                    //如果插入成功，返回主键的id值：$info = $user_id
                    //获取当前cookie中的用户的浏览记录，插入数据库中
                    $goodsIdHistory = cookie('goods_history');
                    foreach ($goodsIdHistory as $k => $v) {
                         D("UserHistory")->field("user_id,goods_id,goods_view_time")->add(array(
                                "user_id"           =>  $info,
                                "goods_id"          =>  $v,
                                "goods_view_time"   =>  time(),
                        ));
                    }
                    //获取当前cookie中的用户的购物车信息，插入数据库中
                    
                    
                    
                    //判断是否更新成功
                    if($info !== false){
                        //如果插入成功，将user_id、user_level信息写入到session中，销毁当前的验证码
                        session($this->authcode("ThinkPHP.CN"),null);
                        session('user_id',$info);
                        session('user_name',I('post.user_name'));
                        session('user_level',1);
                        echo json_encode(array('item' => $key,'code' => "1",'url' => U('Home/Goods/index')));
                        return true;
                        exit;
                    }
                }
                if($flag === "4"){
                    $userId = $user["user_id"];
                    //如果是登录操作，将登录信息：上次登录时间、上次登录ip等信息更新写入到数据库
                    $info = D("User")->field('last_login_time,last_login_ip')->where(array('user_id' => array('eq',$user["user_id"])))->save(array(
                        'last_login_time'   =>  time(),
                        'last_login_ip'     =>  get_client_ip(),
                    ));
                    //获取当前cookie中的用户的浏览记录，插入数据库中
                    $goodsIdHistory = cookie('goods_history');
                    foreach ($goodsIdHistory as $k2 => $v2) {
                        //判断当前goods_id是否在数据库中
                        $count = D("UserHistory")->where(array('user_id' => array('eq',$userId),'goods_id' => array('eq',$v2)))->count();
                        if($count > 0){
                            //如果大于0，表示，数据库中已经存在，更新数据库中浏览信息
                            $info = D("UserHistory")->where(array('user_id' => array('eq',$userId),'goods_id' => array('eq',$goodsId)))->setField("goods_view_time",time());
                        }else{
                            //如果数据库中不存在该数据，直接插入新数据
                            $info = D("UserHistory")->field("user_id,goods_id,goods_view_time")->add(array(
                                    "user_id"           =>  $userId,
                                    "goods_id"          =>  $v2,
                                    "goods_view_time"   =>  time(),
                                ));
                        }
                    }
                    //获取当前cookie中的用户的购物车信息，更新数据库中
                    //如果是未登录时添加的商品，更新user_id字段
                    $mess = D("Admin/Cart")->where(array('user_id' => array('eq',"0"),'user_session_id' => array('eq',cookie("PHPSESSID"))))->setField(array("user_id" => $userId));
                    //如果是以前添加的商品，更新user_session_id字段
                    $mess = D("Admin/Cart")->where(array('user_id' => array('eq',$userId),'user_session_id' => array('neq',cookie("PHPSESSID"))))->setField(array("user_session_id" => cookie("PHPSESSID")));
                    //如果更新成功,销毁当前的session中的验证码，将用户信息写入到session中
                    if($info !== false){
                        //如果插入成功，将user_id、user_level信息写入到session中，
                        //销毁当前的验证码
                        session($this->authcode("ThinkPHP.CN"),null);
                        //首先销毁session中的原始数据
                        session("user_id",null);
                        session("user_level",null);
                        //写入新的用户信息
                        session('user_id',$user["user_id"]);
                        session('user_name',$user["user_name"]);
                        session('user_level',$user["user_level"]);
                        echo json_encode(array('item' => $key,'code' => "1",'url' => U('Home/Goods/index')));
                        return true;
                        exit;
                    }
                }
            }
        }
    }
    /**
     * 获取会员注册验证码信息，可用于session中数据的加密算法
     */
    private function authcode($str){
        $seKey = "ThinkPHP.CN";
        $key = substr(md5($seKey), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
    /**
     * 会员登录页面
     */
    public function login(){
        //登录页面，手动验证用户是否登录
        if(session("user_id")){
            //如果session中admin_id存在，表示已经登录，直接跳转到后台首页
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/Goods/index');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是
                ),
        ));
        $this->display('login');
    }
    /**
     * 会员退出登录
     */
    public function logout(){
        //清空session信息，并且跳转到登录首页
        session("user_id",null);
        session("user_level",null);
        session("user_name",null);
        $this->success('退出成功！',U('login'),2);
    }
    /**
     * 会员忘记密码
     * 
     */
    public  function forgetPassword(){
        //如果是提交重置密码的注册邮箱
        if(IS_POST){
            //接收客户端传递的参数：1：用户验证邮箱   2：用户修改邮箱
            $userMail = I('post.reg_mail');
            $optId = I('post.id');
            //如果是忘记密码，重置密码的操作
            if($optId == "2"){
                $retMess = array();     //定义返回服务器端验证的信息数组
                //从数据库中获取当前邮箱是否有效
                $userRegMail = D("Admin/User")->field("user_id,user_mail_validate")->where(array('user_mail' => array('eq',$userMail)))->find();
                //如果当前邮箱无效
                if(!$userRegMail){
                    $retMess["retCode"]     = 0;
                    $retMess["retMess"][]   = "当前邮箱无效，请确定你的注册邮箱，然后重试";
                    echo json_encode($retMess);
                    exit;                    
                }
                //如果当前邮箱有效可用
                //将当前用户的user_reset_id写入到session中
                session("user_forget_id",$userRegMail["user_id"]);
                $ret = $this->handleMail($optId);
                //将服务器信息赋值到$retMess数组中
                $retMess["retCode"]     = 1;
                $retMess["retMess"][]   = $ret;
                //如果邮件发送成功，跳转到用户中心页面
                if($ret === "验证邮件已经发送，请登录邮箱点击链接进行验证！"){
                    $retMess["url"]     = U('Home/User/index');
                }
                echo json_encode($retMess);
                exit;
            }
        }
        //如果当前为请求显示页面
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是
                ),
        ));
        $this->display("forgetPassword");
    }
    /**
     * 用户修改用户密码
     */
    public function modifyPass(){
        //用户修改密码页面，手动验证用户是否登录
        if(!session("user_id") && !session("user_reset_id")){
            //如果session中user_id不存在，表示用户未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
        //如果是提交了修改邮箱的请求
        //将页面配置信息赋值给模版
            $this->assign(array(
                'pageConfig'    =>  array(
                        'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是
                    ),
            ));
            $this->display("modifyPass");
            exit;
        }
        if(IS_POST){
            // 获取用户的post表单提交的数据
            $oldPass = I('post.old_pass');
            $newPass = I('post.new_pass');
            $confirmPass = I('post.confirm_pass');
            //从数据库中获取当前用户的原始的邮箱
            $userId = session("forget_password")==="是" ? session("user_reset_id") : session("user_id");
            if($userId){
                $retMess = array();     //定义返回服务器端验证的信息数组
            /******************以下是服务器端验证表单提交的数据：格式start******************/
                //如果用户是通过通过修改客户端数据，进行翻墙操作，需要进行客户端验证
                /*******************这里省略，后续待完善********************/



            /******************以上是服务器端验证表单提交的数据：格式end******************/
            
            //判断当前是用户修改密码操作，还是用户忘记密码，重置密码操作
                //如果是获取用户提交重置密码的表单
                if((session("user_reset_id") !== "") && (session("forget_password") === "是")){
                //如果是用户忘记密码，重置密码操作，注意，需要判断是否是重置密码操作，防止恶意操作
                    /******************如果用户提交的数据没有问题**************/
                    //如果原始密码填写正确，将新密码更新到数据库中
                    $info = D("Admin/User")->where("user_id = ".$userId)->setField("user_password",md5(md5($confirmPass)));
                    //如果重置成功
                    if($info){
                        //清空session中forget_password字段
                        session("user_reset_id",null);
                        session("forget_password",null);
                        //返回服务器端信息
                        $retMess["retCode"]     = 3;
                        $retMess["retMess"][]   = "密码重置成功";
                        $retMess["url"]         = U('Home/User/index');
                        echo json_encode($retMess);
                        exit;
                    }
                    $retMess["retCode"]     = 2;
                    $retMess["retMess"][]   = "密码重置失败，请重试！";
                    echo json_encode($retMess);
                    exit;
                }
                //如果是用户修改密码操作
                /******************如果用户提交的数据没有问题**************/
                $oriPass = D("Admin/User")->where("user_id = ".$userId)->getField("user_password");
                //如果用户提交的原始密码与数据库中的密码不一致
                if(md5(md5($oldPass)) !== $oriPass){
                    //如果用户提交的原始邮箱与数据库中的注册邮箱不一致，提示错误
                    $retMess["retCode"]     = 1;
                    $retMess["retMess"][]   = "对不起，原始密码填写错误";
                    echo json_encode($retMess);
                    exit;
                }
                //如果原始密码填写正确，将新密码更新到数据库中
                $info = D("Admin/User")->where("user_id = ".$userId)->setField("user_password",md5(md5($confirmPass)));
                //如果修改成功
                if($info){
                    $retMess["retCode"]     = 3;
                    $retMess["retMess"][]   = "密码修改成功";
                    $retMess["url"]         = U('Home/User/index');
                    echo json_encode($retMess);
                    exit;
                }
                $retMess["retCode"]     = 2;
                $retMess["retMess"][]   = "密码修改失败，请重试！";
                echo json_encode($retMess);
                exit;
            }
        }
    }
    /**
     * 用户验证邮箱操作
     */
    public function validMail(){
        //用户验证、修改邮箱页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示用户未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //接收客户端传递的参数：1：用户验证邮箱   2：用户修改邮箱
        $optId = I('get.id');
        if($optId == "1"){
            //如果是验证邮箱的操作
            $ret = $this->handleMail($optId);
            //将结果信息返回给客户端
            exit($ret);
        }
    }
    /**
     * 用户修改邮箱
     */
    public function modifyMail(){
        //用户验证、修改邮箱页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示用户未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
        //如果是提交了修改邮箱的请求
        //将页面配置信息赋值给模版
            $this->assign(array(
                'pageConfig'    =>  array(
                        'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是
                    ),
            ));
            $this->display("modifyMail");
            exit;
        }
        if(IS_POST){
            //如果是提交了修改邮箱的表单
            // 获取用户的post表单提交的数据
            $oldMail = I('post.old_mail');
            $newMail = I('post.new_mail');
            //从数据库中获取当前用户的原始的邮箱
            $userId = session("user_id");
            if($userId){
                $retMess = array();     //定义返回服务器端验证的信息数组
                //如果用户是通过通过修改客户端数据，进行翻墙操作，需要进行客户端验证
                /*******************这里省略，后续待完善********************/


                /******************如果用户提交的数据没有问题**************/
                //如果用户新旧邮箱相同
                if($oldMail === $newMail){
                    $retMess["retCode"]     = 5;
                    $retMess["retMess"][]   = "新邮箱不能与原始邮箱相同";
                    echo json_encode($retMess);
                    exit;
                }
                //获取当前用户的数据库中的邮箱信息
                $oriMail = D("Admin/User")->field("user_mail,user_mail_validate")->find($userId);
                //如果原始邮箱填写错误
                if($oldMail !== $oriMail["user_mail"]){
                    //如果用户提交的原始邮箱与数据库中的注册邮箱不一致，提示错误
                    $retMess["retCode"]     = 1;
                    $retMess["retMess"][]   = "对不起，原始邮箱填写错误";
                    echo json_encode($retMess);
                    exit;
                }                
                //如果用户未验证邮箱，先提示用户验证邮箱
                if($oriMail["user_mail_validate"] === "否"){
                    //如果用户未验证邮箱，先提醒用户进行邮箱验证，并发送验证邮件
                    $retMess["retCode"]     = 0;
                    $retMess["retMess"][]   = "对不起，你还未验证邮箱，请先验证邮箱";
                    $retMess["retMess"][]   = $this->handleMail(1);
                    $retMess["url"]         = U('Home/User/index');
                    echo json_encode($retMess);
                    exit;
                }
                //如果用户已经验证了邮箱，但是用户提交的新邮箱已经被注册使用
                $newMailIsReg = D("Admin/User")->where(array('user_mail' => array('eq',$newMail)))->count();
                //判断新邮箱是否已经被注册使用
                if($newMailIsReg > 0){
                    //如果已经被使用，返回错误信息
                    $retMess["retCode"]     = 1;
                    $retMess["retMess"][]   = "对不起，新邮箱".$newMail."已经被注册使用";
                    echo json_encode($retMess);
                    exit;
                }
                //如果原始邮箱填写正确，将新邮箱更新到数据库中
                $info = D("Admin/User")->where("user_id = ".$userId)->setField("user_mail",$newMail);
                //如果修改成功
                if($info){
                    $retMess["retCode"]     = 3;
                    $retMess["retMess"][]   = "邮箱修改成功";
                    $retMess["url"]         = U('Home/User/index');
                    echo json_encode($retMess);
                    exit;
                }
                $retMess["retCode"]     = 2;
                $retMess["retMess"][]   = "邮箱修改失败，请重试！";
                echo json_encode($retMess);
                exit;
            }
        }
    }
    /**
     * 会员验证操作操作：验证、修改
     */
    private function handleMail($optId){
        //首先判断当前操作类型：1：用户验证邮箱，2：用户忘记密码，邮箱重置密码
        //如果是验证邮箱操作
        if($optId == "1"){
            //从session中获取当前用户的user_id
            $userId = session("user_id");
            //查询会员user表，获取用户的注册信息
            $userInfo = D("Admin/User")->field("user_id,user_name,user_password,reg_time,mail_validate_token,user_mail,user_mail_validate")->find($userId);
            if($userInfo["user_mail_validate"] === "是"){
                //如果用户已经完成邮箱验证，提示错误信息，防止恶意操作
                return "用户已经完成邮箱验证，请勿重复验证！";
            }
            $option = $this->authcode("验证邮箱");
        }
        //如果是重置密码操作
        if($optId == "2"){
            $userId = session("user_forget_id");
            //销毁session中的user_forget_id字段
            session("user_forget_id",null);
            //查询会员user表，获取用户的注册信息
            $userInfo = D("Admin/User")->field("user_id,user_name,user_password,reg_time,mail_validate_token,user_mail,user_mail_validate")->find($userId);
            $option = $this->authcode("重置密码");
        }

        //拼接验证的url地址，
        //格式：http://shop.php.com/index.php/Home/User/validateMail/param1/md5(用户名)/param2/md5(密码)/param3/md5(邮箱)/param4/mail_validate_token/param5/option
        $md5UserName = $this->authcode($userInfo["user_name"]);
        $md5UserPass = $this->authcode($userInfo["user_password"]);
        $md5UserMail = $this->authcode($userInfo["user_mail"]);
        $valDeadLine = time() + 7*24*3600;   //默认有效期为7天
        $token = $userInfo["mail_validate_token"];
        //利用参数，生成邮箱验证的url路径
        $urlPram = array(
                "param1"    =>  $md5UserName,
                "param2"    =>  $md5UserPass,
                "param3"    =>  $md5UserMail,
                "param4"    =>  $token,
                "param5"    =>  $option,
            );
        $valUrl = "http://shop.php.com".urldecode(U("Home/User/validateMail",$urlPram));

        //调用mail服务器，发送邮件到用户的邮箱，进行邮箱验证
        $mailTittle = 'LYTshop商城 用户验证邮箱地址链接邮件';
        $mailContent = '<p>这封邮件是由 LYTshop商城 发送的。</p><p>您收到这封邮件，是由于 您在注册LYTshop商城时使用了该邮箱，并且点击了邮箱验证操作。如果您并没有进行上述操作，请忽略这封邮件。您不需要退订或进行其他进一步的操作。</p><br>----------------------------------------------------------------------<br><strong>新用户注册说明</strong><br>----------------------------------------------------------------------<br><br><p>如果您是 LYTshop商城 的新用户，或在修改您的注册 Email 时使用了本地址，我们需要对您的地址有效性进行验证以避免垃圾邮件或地址被滥用。</p><p>您只需点击下面的链接即可进行用户邮箱验证，以下链接有效期为7天。过期可以重新请求发送一封新的邮件验证：<br><a target="_blank" href="'.$valUrl.'">'.$valUrl.'</a><br>(如果上面不是链接形式，请将该地址手工粘贴到浏览器地址栏再访问)</p><p>感谢您的访问，祝您使用愉快！</p>';

        $userMail = $userInfo["user_mail"];
        // var_dump($valUrl);
        //调用sendMail方法发送邮件
        $flag = sendMail($userMail,$mailTittle,$mailContent);
        // $flag = true;
        //如果发送成功，更新该链接的有效期到数据库，同时将结果返回给客户端，提示用户下一步操作
        if($flag){
            $info = D("Admin/User")->where(array("user_id" => array('eq',$userId)))->setField('mail_validate_deadline',$valDeadLine);
            //如果数据更新到数据库，返回信息到客户端
            if($info){
                return "验证邮件已经发送，请登录邮箱点击链接进行验证！";
            }else{
                return "系统繁忙，请稍后点击重试！";
            } 
        }
        //如果邮箱发送失败
        return "系统繁忙，请稍后点击重试！";
    }
    /**
     * 会员点击邮箱验证链接，完成邮箱验证的方法
     * 
     */
    public function validateMail(){
        //获取用户get请求提交的参数
        $userName = I('get.param1');
        $userPass = I('get.param2');
        $userMail = I('get.param3');
        $token = I('get.param4');
        $option = I('get.param5');

        //查询用户User数据表，找到满足当前验证令牌token的字段的用户的信息
        $userInfo = D("Admin/User")->field("user_id,user_name,user_password,user_mail,mail_validate_deadline")->where(array('mail_validate_token' => $token))->find();
        //首先验证，当前的url地址是否有效
        if($userInfo === null){
            //如果当前url地址无效：从数据库中获取的用户信息为空
            $url = U('Home/User/index');
            echo "<script type='text/javascript'>alert('当前链接无效，请重新获取链接地址！'); window.top.location.href ='$url'</script>";
            exit;            
        }
        //然后判断该验证的连接是否过期
        if($userInfo["mail_validate_deadline"] < time()){
            //如果当前url链接过期，提示用户重新进行验证,并跳转到会员中心
            $url = U('Home/User/index');
            echo "<script type='text/javascript'>alert('当前链接已经过期，请重新获链接地址！'); window.top.location.href ='$url'</script>";
            exit;
        }
        //如果没有过期，验证用户的信息的正确性
        $md5UserName = $this->authcode($userInfo["user_name"]);
        $md5UserPass = $this->authcode($userInfo["user_password"]);
        $md5UserMail = $this->authcode($userInfo["user_mail"]);

        //如果是验证邮箱操作
        if($option === $this->authcode("验证邮箱")){
            if($userName === $md5UserName && $userPass === $md5UserPass && $userMail === $md5UserMail){
                //如果用户信息验证成功，更新数据到数据库，并跳转到用户中心页面
                //将邮箱验证的最后期限重置为0，目的：当前链接只生效一次，防止重复验证使用
                $flag = D("Admin/User")->where(array("user_id" => array('eq',$userInfo["user_id"])))->save(array('user_mail_validate' => "是",'mail_validate_deadline' => 0));

                if($flag){
                    //如果数据更新成功
                    $url = U('Home/User/index');
                    echo "<script type='text/javascript'>alert('邮箱验证成功！'); window.top.location.href ='$url'</script>";
                    exit; 
                }
            }else{
                //如果用户信息验证失败
                $url = U('Home/User/index');
                echo "<script type='text/javascript'>alert('邮箱验证失败，请重新获取验证链接地址进行验证！'); window.top.location.href ='$url'</script>";
                exit;
            }
        }
        //如果是重置密码操作
        if($option === $this->authcode("重置密码")){
            if($userName === $md5UserName && $userPass === $md5UserPass && $userMail === $md5UserMail){
                //如果用户信息验证成功，更新数据到数据库，并跳转到用户中心页面
                //将邮箱验证的最后期限重置为0，目的：当前链接只生效一次，防止重复验证使用
                $flag = D("Admin/User")->where(array("user_id" => array('eq',$userInfo["user_id"])))->save(array('user_mail_validate' => "是",'mail_validate_deadline' => 0));
                //判断数据是否更新成功
                if($flag){
                    //如果数据更新成功
                    //将用户的user_id写入到session中，使用户有权限进行修改密码
                    session("user_reset_id",$userInfo["user_id"]);
                    session("forget_password","是");
                    //生成跳转链接，跳转到修改密码页面
                    $url = U('Home/User/modifyPass');
                    echo "<script type='text/javascript'>alert('验证成功，请修改密码！'); window.top.location.href ='$url'</script>";
                    exit; 
                }
            }else{
                //如果用户信息验证失败
                $url = U('Home/User/index');
                echo "<script type='text/javascript'>alert('邮箱验证失败，请重新获取验证链接地址进行验证！'); window.top.location.href ='$url'</script>";
                exit;
            }
        }

        
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
     * 会员个人管理中心首页：包含个人信息、订单信息、收货地址、退货、评价等 
     */
    public function index(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //从数据库中获取当前用户的信息
        $userInfo = D("Admin/User")->alias("a")->field("a.user_id,a.user_name,a.user_mail,a.user_tel,a.user_mail_validate,a.user_tel_validate,a.user_jifen,a.user_coin,a.user_level,b.level_name")->join(array(
                "LEFT JOIN __USER_LEVEL__ b ON a.user_level = b.level_id"
            ))->find($userId);
        // echo "<pre>";
        // var_dump($userInfo);
        // exit;
        //将数据传递到模版中
        $this->assign(array(
            //页面配置信息
            'pageConfig'            =>  array(
                'catConfig'     =>  "1" ,            //顶部商品分类导航是否折叠，0：否   1：是
                'sideBarTheme'  =>  "index",         //设置侧边栏被选中的样式
                'pageName'      =>  "基本信息",      //设置面包屑导航的显示页面的名称
            ),
            'userInfo'          =>  $userInfo,
        ));       
        $this->display('index');
    }
    /**
     * 会员收获地址信息
     */
    public function address(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //如果是获取收货人信息
        if(IS_GET){
            //获取当前用户的user_id下的所有的address信息
            $userAddressList = D("Admin/UserAddress")->field("id,goods_consignee,consignee_postcode,consignee_tel,consignee_address_province,consignee_address_city,consignee_address_country,consignee_address_town,consignee_address_detail")->where(array('user_id' => array('eq',$userId)))->order("id asc")->select();
            //获取当前用户已经添加的收获人地址总数
            $userAddressAmount = D("Admin/UserAddress")->where(array('user_id' => array('eq',$userId)))->count();
            //将数据传递到模版中
            $this->assign(array(
                'pageConfig'    =>  array(
                        'catConfig'     =>  "1" ,                //顶部商品分类导航是否折叠，0：否   1：是
                        'sideBarTheme'  =>  "address",           //设置侧边栏被选中的样式
                        'pageName'      =>  "收货人地址",        //设置面包屑导航的显示页面的名称
                    ),
                'userAddressList'       =>  $userAddressList,    //设置用户所有的收货人地址信息
                'userAddressAmount'     =>  $userAddressAmount,  //设置用户已经添加的收货人信息的数量
            ));
            $this->display('address');
        }
        //如果是提交了添加/修改收货人信息表单
        if(IS_POST){
            $optId = I('id');
            //创建收货人地址对象
            $addressModel = D("Admin/UserAddress");
            //判断表单optId是否为空
            if(empty($optId)){
                //如果optId值为空，则表明是添加操作
                if($addressModel->create(I('post.') , 1)){ 
                //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $addressModel->add()){
                        //如果插入成功
                        $this->success('收获地址添加成功！', U('User/address'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = ($addressModel->getError()) === "" ? "收获地址添加失败！" : ($addressModel->getError());
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = ($addressModel->getError()) === "" ? "收获地址添加失败！" : ($addressModel->getError());
                $this->error($error);   //如果添加失败，返回上一个页面
            }else{
                //如果optId值不为空，表示是修改操作
                if($addressModel->create(I('post.') , 2)){ 
                    //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $addressModel->save()){
                        //如果插入成功
                        $this->success('收获地址修改成功！', U('User/address'));
                        exit;
                    }
                    //添加失败，获取错误信息
                    $error = ($addressModel->getError()) === "" ? "收获地址修改失败！" : ($addressModel->getError());
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = ($addressModel->getError()) === "" ? "收获地址修改失败！" : ($addressModel->getError());
                $this->error($error);   //如果添加失败，直接返回上一个页面
                exit;
            }
        }
    }
    /**
     * ajax动态获取会员的收货人信息
     */
    public function ajaxGetUserAddress(){
        //获取当前操作的层级
        $level = I("get.level");
        //获取当前操作的id值
        $parentId = I("get.parentId");
        //返回当前操作的id下的下一级别的信息
        $address = addressSelect($level+1,$parentId);
        exit($address);
    }
    /**
     * ajax动态处理会员的收货人信息
     */
    public function ajaxHandlerUserAddress(){
        //用户中心页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
            //获取用户的操作类型：1：添加 2：编辑 3：删除 4：设为默认收货地址
            $optId = I('get.oid');
            //获取要操作的地址的主键id值
            $addressId = I('get.id');
            if($optId == "2"){
                //如果是编辑操作，获取当前address_id下的地址信息
                $useAddress = D("Admin/UserAddress")->field("id,goods_consignee,consignee_postcode,consignee_tel,consignee_address_province,consignee_address_city,consignee_address_country,consignee_address_town,consignee_address_detail")->find($addressId);
                //获取当前收货人的地址信息，并保存
                $province   = $useAddress["consignee_address_province"];
                $city       = $useAddress["consignee_address_city"];
                $contry     = $useAddress["consignee_address_country"];
                $town       = $useAddress["consignee_address_town"];
                //获取收货人的select下拉菜单html代码，并且重新赋值给数组
                $useAddress["consignee_address_province"] = addressSelect("1","0",$province);
                $useAddress["consignee_address_city"] = addressSelect("2",$province,$city);
                $useAddress["consignee_address_country"] = addressSelect("3",$city,$contry);
                $useAddress["consignee_address_town"] = addressSelect("4",$contry,$town);
                echo json_encode($useAddress);
                exit;
            }
            if($optId == "3"){
                //如果是删除操作，直接数据库中当前商品的信息
                $info = D("Admin/UserAddress")->delete($addressId);
                //判断是否删除成功，并将结果返回给客户端
                if($info){
                    //如果删除成功
                    exit("删除成功");
                }
                //如果删除失败
                exit("删除失败，请重试"); 
            }
            if($optId == "4"){
                //如果是设为默认收货地址
                //先取消当前被设为默认的收货地址
                $info = D("Admin/UserAddress")->where(array('is_default_address' => array('eq',"是")))->setField(array("is_default_address" => "否"));
                //设置当前address_id为默认收货地址
                $info = D("Admin/UserAddress")->where(array('id' => array('eq',$addressId)))->setField(array("is_default_address" => "是","last_update_time" => time()));
                //判断是否更新成功，并将结果返回给客户端
                if($info){
                    //如果更新成功
                    exit("设置成功");
                }
                //如果更新失败
                exit("设置失败，请重试");
            }
            //将数据传递到模版中
            $this->assign(array(
                'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,               //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "address",           //设置侧边栏被选中的样式
                    'pageName'      =>  "收货人地址",       //设置面包屑导航的显示页面的名称
                ),
            ));
            $this->display('address');
        }

    }

    /**
     * 会员订单信息
     */
    public function order(){
        //用户中心页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,           //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "order",        //设置侧边栏被选中的样式
                    'pageName'      =>  "我的订单",     //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('order');
    }

    /**
     * ajax将用户购物车中的信息加入到订单中
     */
    public function ajaxAddGoodsToOrder(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_POST){
            $orderModel = D("Admin/Order");
            //获取用户插入order数据表中的数据
            $orderData = D("Admin/Cart")->field("user_id,sum(goods_amount*goods_price) as goods_ori_price,attr_name_list as goods_attr_name")->where(array('user_id' => array('eq',$userId),'is_select' => array('eq',"是")))->group("user_id")->select();
            $data = array_merge($orderData["0"],I("post."));
            //判断用户是否是重复提交订单
            if(empty($data)){
                //如果已经提交了订单，提示错误，并跳转
                $url = U("Home/Cart/cart");
                echo json_encode(array("recode" => "2","info" =>"当前操作已经失效！","url" => $url));
                exit;
            }
            //创建添加操作
            if($orderModel->create($data, 1)){ 
                //create接受两个参数，data和type(1:插入，2:修改)
                //如果创建成功
                if(false !== ($orderId = $orderModel->add())){
                    //如果插入成功
                    $url = U("Home/Cart/cart3",array('order_id' => $orderId));
                    echo json_encode(array("recode" => "1","info" => $url));
                    exit;
                }
                //添加失败，获取错误信息
                $error = $orderModel->getError();
                echo json_encode(array("recode" => "0","info" => $error));
                exit;
            }
            //创建失败，获取失败信息
            $error = $orderModel->getError();
            echo json_encode(array("recode" => "0","info" => $error));
            exit;
        }
    }

    /**
     * ajax获取用户的订单信息
     */
    public function ajaxGetUserOrder(){
    /*----------------获取分页数据开始----------------------------*/
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //获取要展示评论的字段：全部、已评价、待评价
        $field = I('post.field');
        switch ($field) {
            case 'ALL':
                                $field = "已完成,待付款,待发货,待收货,待评价";
                                break;
            case 'FINISHED':
                                $field = "已完成";
                                break;
            case 'WAITPAY':
                                $field = "待付款";
                                break;
            case 'WAITSEND':
                                $field = "待发货";
                                break;
            case 'WAITRECEIVE':
                                $field = "待收货";
                                break;
            case 'WAITCCOMMENT':
                                $field = "待评价";
                                break;                                
        }
        //获取商品评论排序的方式
        $order = I('post.order');
        // 获取满足要求的总记录数
        $count = D("Admin/Order")->where(array('user_id' => array('eq',$userId),'order_status' => array('in',$field)))->count();
        $per = 1;   //默认每页显示显示5条数据
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new \Think\AjaxPage($count,$per);
        //获取满足条件的分页数据
        $userOrderGoodsList = D("Admin/Order")->alias("a")->field("a.order_id,a.order_sn,a.order_add_time,a.order_status,a.goods_ori_price,a.goods_shipping_expense,a.goods_shipping_discount,a.order_expense_coin,a.goods_final_price,count(b.order_id) as goods_count")->join(array(
            "LEFT JOIN __ORDER_GOODS__ b ON a.order_id = b.order_id"
        ))->where(array('a.user_id' => array('eq',$userId),'a.order_status' => array('in',$field)))->group('a.order_id')->order('a.order_add_time '.$order)->limit($page->firstRow.','.$page->listRows)->select();
        //循环userOrderGoodsList数组，遍历order_goods表，找到该订单下的所有商品的详细信息
        foreach ($userOrderGoodsList as $key => &$value) {
            $value["order_goods"] = D("Admin/OrderGoods")->alias('a')->field("a.*,b.goods_name,b.goods_thumb_small")->join(array(
                "LEFT JOIN __GOODS__ b ON a.goods_id = b.goods_id"
            ))->where(array('a.order_id' => array('eq',$value["order_id"])))->select();
        }
    /*-----------------设置分页的样式--------------------------------*/
        // $page->setConfig('prev', '【上一页】');
        // $page->setConfig('next', '【下一页】');
        // $page->setConfig('first', '【首页】');
        // $page->setConfig('last', '【末页】');
        // $page->setConfig('theme', '共 %TOTAL_ROW% 条记录,当前是 %NOW_PAGE%/%TOTAL_PAGE% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page->lastSuffix=false;        //最后一页是否显示总页数
        $page->rollPage=3;              //分页栏每页显示的页数
        $pageString = $page->show();    // 分页显示输出

        //将数据赋值到模版中输出
        $this->assign(array(
            'userOrderGoodsList'         =>      $userOrderGoodsList,
            'page'                       =>      $pageString,
        ));
        $this->display("ajaxGetUserOrder");
    }

    /**
     * ajax删除用户的订单信息
     */
    public function ajaxDeleteUserOrder(){
    /*----------------获取分页数据开始----------------------------*/
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        if(IS_GET){
            //获取用户要删除的订单的order_id
            $orderId = I("get.id");
            //获取当前order_id的user_id
            $user = D("Admin/Order")->where(array('order_id' => array('eq',$orderId)))->getField("user_id");
            if($userId !== $user){
                //如果当前前品不属于请求用户，提示错误信息
                exit("对不起，你无权取消当前订单！");
            }
            //删除当前order_id下order数据表中的数据
            $info = D("Admin/Order")->where(array('user_id' => array('eq',$userId),'order_id' => array('eq',$orderId),'order_status' => array('eq',"待付款")))->delete();
            if($info){
                //删除order_id下的order_goods表中的订单商品的信息
                $info = D("Admin/OrderGoods")->where(array('order_id' => array('eq',$orderId)))->delete();
                if($info){
                    exit("订单取消成功！");
                }else{
                    exit("订单取消失败！");
                }
            }else{
                //如果删除数量为0，表示不存在该订单
                exit("订单取消失败！");
            }
        }
    }

    /**
     * 会员订单售后服务：1：申请退款  2：申请退货  3：申请换货  4：申请维修 5：其他事项
     */
    public function orderDetail(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        echo "这是会员订单详情页面，展示订单详情，物流进度，其他信息等";
        exit;
        //接收get请求提交的order_id，判断该订单是否是该用户所有
        $orderId = I('get.order_id');
        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,           //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "order",        //设置侧边栏被选中的样式
                    'pageName'      =>  "我的订单",     //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('order');
    }

    /**
     * 会员订单售后服务：1：申请退款  2：申请退货  3：申请换货  4：申请维修 5：其他事项
     */
    public function orderService(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        echo "这是会员订单售后服务，1：申请退款  2：申请退货  3：申请换货  4：申请维修 5：其他事项";
        exit;
        //接收get请求提交的id和order_id，判断该订单是否是该用户所有
        $id = I('get.id');
        $orderId = I('get.order_id');
        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,           //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "order",        //设置侧边栏被选中的样式
                    'pageName'      =>  "我的订单",     //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('order');
    }

    /**
     * 会员订单支付方法
     */
    public function pay(){
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        echo "这是订单支付模块";
        exit;
        //接收get请求提交的order_id，判断该订单是否是该用户所有
        $orderId = I('get.order_id');
        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,           //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "order",        //设置侧边栏被选中的样式
                    'pageName'      =>  "我的订单",     //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('order');
    }

    /**
     * 会员评论模块
     */
    public function comment(){
        //用户中心页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,           //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "comment",      //设置侧边栏被选中的样式
                    'pageName'      =>  "我的评论",     //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('comment');
    }
    /**
     * ajax获取用户商品浏览的历史记录
     */
    public function ajaxGetGoodsComment(){
    /*----------------获取分页数据开始----------------------------*/
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        //获取要展示评论的字段：全部、已评价、待评价
        $field = I('post.field');
        switch ($field) {
            case 'ALL':
                                $field = "待评价,已完成";
                                break;
            case 'FINISHED':
                                $field = "已完成";
                                break;
            case 'WAITCOMMENT':
                                $field = "待评价";
                                break;
        }
        //获取商品评论排序的方式
        $order = I('post.order');
        // 获取满足要求的总记录数
        $count = D("Admin/Order")->where(array('user_id' => array('eq',$userId),'order_status' => array('in',$field)))->count();
        $per = 1;   //默认每页显示显示5条数据
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new \Think\AjaxPage($count,$per);
        //获取满足条件的分页数据
        $UserGoodsCommentList = D("Admin/Order")->alias("a")->field("a.order_id,a.order_sn,a.order_add_time,a.order_status")->join(array(
            "LEFT JOIN __ORDER_GOODS__ b ON a.order_id = b.order_id"
        ))->where(array('a.user_id' => array('eq',$userId),'a.order_status' => array('in',$field)))->group('a.order_id')->order('a.order_add_time '.$order)->limit($page->firstRow.','.$page->listRows)->select();
        //循环userOrderGoodsList数组，遍历order_goods表，找到该订单下的所有商品的详细信息
        foreach ($UserGoodsCommentList as $key => &$value) {
            $value["order_goods"] = D("Admin/OrderGoods")->alias('a')->field("a.*,b.goods_name,b.goods_thumb_small")->join(array(
                "LEFT JOIN __GOODS__ b ON a.goods_id = b.goods_id"
            ))->where(array('a.order_id' => array('eq',$value["order_id"])))->select();
        }        
    /*-----------------设置分页的样式--------------------------------*/
        // $page->setConfig('prev', '【上一页】');
        // $page->setConfig('next', '【下一页】');
        // $page->setConfig('first', '【首页】');
        // $page->setConfig('last', '【末页】');
        // $page->setConfig('theme', '共 %TOTAL_ROW% 条记录,当前是 %NOW_PAGE%/%TOTAL_PAGE% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page->lastSuffix=false;        //最后一页是否显示总页数
        $page->rollPage=3;              //分页栏每页显示的页数
        $pageString = $page->show();    // 分页显示输出

        //将数据赋值到模版中输出
        $this->assign(array(
            'UserGoodsCommentList'      =>      $UserGoodsCommentList,
            'page'                  =>      $pageString,
        ));
        $this->display("ajaxGetGoodsComment");
    }
    /**
     * 会员留言模块
     */
    public function history(){
        //用户中心页面，手动验证用户是否登录
        if(!session("user_id")){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }

        //将数据传递到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                    'catConfig'     =>  "1" ,               //顶部商品分类导航是否折叠，0：否   1：是
                    'sideBarTheme'  =>  "history",          //设置侧边栏被选中的样式
                    'pageName'      =>  "商品浏览历史记录", //设置面包屑导航的显示页面的名称
                ),
        ));
        $this->display('history');
        exit;
    }
    /**
     * ajax获取用户商品浏览的历史记录
     */
    public function ajaxGetGoodsHistory(){
    /*----------------获取分页数据开始----------------------------*/
        //用户中心页面，手动验证用户是否登录
        if(!($userId = session("user_id"))){
            //如果session中user_id不存在，表示还未登录，直接跳转到登录页面
            //最佳方案：使用JS进行跳转,可以预防session失效的问题：出现在main界面中出现登录框
            $url = U('Home/User/login');
            echo "<script type='text/javascript'>window.top.location.href ='$url'</script>";
            exit;
        }
        // 获取满足要求的总记录数
        $count = D("Admin/UserHistory")->where(array('goods_view_time' => array('egt',(time()-30*24*3600)),'user_id' => array('eq',$userId)))->count();
        $per = 1;   //默认每页显示显示5条数据
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new \Think\AjaxPage($count,$per);
        //获取满足条件的分页数据
        $goodsHistoryList = D("Admin/UserHistory")->alias('a')->field('a.goods_id,b.goods_name,b.goods_shop_price,b.goods_thumb_small,a.goods_view_time')->join(array(
                'LEFT JOIN __GOODS__ b ON a.goods_id = b.goods_id'
        ))->where(array('a.goods_view_time' => array('egt',(time()-30*24*3600))))->order('a.goods_view_time DESC')->limit($page->firstRow.','.$page->listRows)->select();
    /*-----------------设置分页的样式--------------------------------*/
        // $page->setConfig('prev', '【上一页】');
        // $page->setConfig('next', '【下一页】');
        // $page->setConfig('first', '【首页】');
        // $page->setConfig('last', '【末页】');        
        // $page->setConfig('theme', '共 %TOTAL_ROW% 条记录,当前是 %NOW_PAGE%/%TOTAL_PAGE% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page->lastSuffix=false;        //最后一页是否显示总页数
        $page->rollPage=3;              //分页栏每页显示的页数
        $pageString = $page->show();    // 分页显示输出

        //将数据赋值到模版中输出
        $this->assign(array(
            'goodsHistoryList'      =>      $goodsHistoryList,
            'page'                  =>      $pageString,
        ));
        $this->display("ajaxGetGoodsHistory");
    }
    /**
     * ajax动态删除商品的浏览历史记录
     */
    public function ajaxDeleteGoodsHistory(){
        $info = D("Admin/UserHistory")->where('goods_id = '.I('get.gid'))->delete();
        if($info !== false){
            //如果删除成功
            exit("删除成功");
        }
    }
    /**
     * 商品头部用户信息ajax实时获取用户的信息
     */
    public function ajaxGetUserIsLogin(){
        $userId = empty(session("user_id")) ? "" : session("user_id");
        $userName = empty(session("user_name")) ? "" : session("user_name");
        echo json_encode(array("user_id" => $userId , "user_name" => $userName));
    }

}
