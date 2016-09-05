<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-25 18:03:32
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-11 09:49:48
 */
namespace Admin\Controller;

class UserController extends \Admin\Controller\BasicController {

    /**
     * 会员列表展示的方法
     * 
     */
    public function userMessage(){
        $userModel = D('User');
        if(IS_GET){
            //首先获取get传递的id值
            $id = I('get.id');
            if(!empty($id)){
                //如果id值存在,表示是CRUD操作
                //获取cat_id值
                $userId = I('get.user_id');
                if((1 == $id) || (2 == $id)){
                    //如果id为1，表示是添加操作，如果是2，表示是修改操作
                    //获取商品分类对象列表
                    $userList = $userModel->find($userId);
                    //获取所有的分类列表树状展示
                    // $user = $userModel->getTree();
                    $this->assign(array(
                            'catList'   =>  $catList,
                            // 'cat'       =>  $cat,
                        )
                    );
                    $this->display('_user');
                }elseif(3 == $id){
                    //如果id为3，表示是直接删除操作
                    //获取当前传递的cat_id值
                    $userId = I('get.user_id');
                    //在模型的_before_delete()钩子函数中删除
                    if($userModel->delete($userId)){
                        //如果删除成功，给出提示，跳转到分类列表页
                        $this->success('商品分类删除成功！',U('UserMessage'));
                        exit;
                    }
                    //如果删除失败，给出提示，跳转到分类列表页
                    $error = $userModel->getError();
                    $this->error($error,U('UserMessage'));
                }elseif(4 == $id){
                    //如果id为4，表示是加入回收站操作
                    
                }else{
                    //如果是其他值，表示url地址被修改
                    $this->error('url地址错误！',U('UserMessage'),3);
                }
                exit;
            }
            //如果id不存在，表示是展示list页面
            //获取所有的分类列表树状展示
            $userList = $userModel->select();
            $this->assign(array(
                    'userList'   =>  $userList,
                )
            ); 
            $this->display('user');
            exit;
        }
        //如果是post表单提交
        if(IS_POST){
            //如果是post表单提交操作
            $userId = I('user_id');
            $info = I('post.');
            //判断表单cat_id是否为空
            if(empty($userId)){
                //如果user_id值为空，则表明是添加操作
                if($info = $userModel->create(I('post.') , 1)){ //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $userModel->add()){
                        //如果插入成功
                        $this->success('商品分类添加成功！', U('UserMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $userModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $userModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
            }else{
                //如果user_id值为不为空，表示是修改操作
                if($userModel->create(I('post.') , 2)){ //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $userModel->save()){
                        //如果插入成功
                        $this->success('商品分类修改成功！', U('UserMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $userModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $userModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                exit;
            }
        }
        $this->display('user');
    }

    /**
     * 会员等级管理的方法
     * 
     */
    public function userLevel(){
        //判断get方式是否提交id参数：无：展示表单，1：添加表单，2：修改表单，3：直接删除，4：加入回收站
        $levelModel = D('UserLevel');
        $levelList = $levelModel->select();
        if(IS_GET){
            //判断是否传参id
            $id = I('get.id');
            //如果存在id值，则是具体操作：添加、修改、删除、回收
            if(!empty($id)){
                $levelId = I('get.level_id');
                if((1 == $id) || (2 == $id)){
                    //如果id值等于1，表示是添加操作,等于2，表示是修改操作
                    $levelList = $levelModel->find($levelId);
                    $this->assign(array(
                            'levelList' =>  $levelList,
                        )
                    );
                    $this->display('_userLevel');
                    exit;
                }elseif(3 == $id){
                    //如果id等于3，表示是直接删除操作
                    if($levelModel->delete($levelId)){
                        //如果删除成功，返回true,否则返回false
                        $this->success('会员等级删除成功！',U('userLevel'));
                        exit;
                    }
                    //否则表示删除成功
                    $error = $levelModel->getError();
                    $this->error($error,U('userLevel'),3);
                    exit;
                }elseif(4 == $id){
                    //如果id等于4，表示是加入回收站操作


                    exit;
                }else{
                    //如果是其他的url,表示url地址被篡改
                    $this->error('你输入的url地址错误！',U('User/userLevel'),3);
                    exit;
                }
            }
            //如果没有传递id值，表示是展示会员等级列表的操作
            $this->assign(array(
                    'levelList' =>  $levelList,
                )
            );
            $this->display('userLevel');
            exit;
        }
        //下面是对post提交的表单进行处理
        if(IS_POST){
            //如果是post提交表单
            //获取提交表单中的level_id字段
            $levelId = I('post.level_id');
            if(empty($levelId)){
                //如果levelId为空，表示是添加操作
                if($levelModel->create(I('post.') , 1)){
                    //如果创建成功,返回true,否则返回false
                    if( $mess = $levelModel->add()){
                        //如果添加成功，返回的是添加的字段的主键id值
                        $this->success('会员等级添加成功！',U('userLevel'));
                        exit;
                    }
                    //如果添加失败，返回的是false
                    echo "<pre>";
                    var_dump($mess);
                    exit;
                    $error = $levelModel->getError();
                    $this->error($error);
                    // $this->error($error,U('userLevel',array('id',1)),3);
                    exit;
                }
                //如果创建失败
                $error = $levelModel->getError();
                $this->error($error);
                exit;
            }else{
                //如果levelId不为空，表示是修改操作
                if($info = $levelModel->create(I('post.'),2)){
                    //如果创建成功，返回true,否则返回false
                    if(false !== $levelModel->save()){
                        //如果修改保存成功，返回受影响的行数，否则返回true
                        //如果返回值为0，表示修改成功，而false才表示修改失败
                        $this->success('会员等级修改成功！',U('userLevel'));
                        exit;
                    }
                    //如果添加失败，返回的是false
                    $error = $levelModel->getError();
                    $this->error($error);
                    // $this->error($error,U('userLevel',array('id',1)),3);
                    exit;
                }
                //如果创建失败
                $error = $levelModel->getError();
                $this->error($error);
                exit;
            }
        }
        //如果表单提交失败，返回上一个页面
        $this->error('表单提交失败！');
    }

    /**
     *会员留言处理的方法
     * 
     */
    public function userComment(){

        echo "会员评论模块待补充！";
    }
}
