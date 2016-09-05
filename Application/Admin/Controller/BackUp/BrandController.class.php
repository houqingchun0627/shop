<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 11:01:47
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-06-25 15:34:15
 */
namespace Admin\Controller;

class BrandController extends \Think\Controller {

    /**
     * 商品品牌的列表：brandList()
     * @return [type] [description]
     */
    public function brandList(){
        //创建商品品牌的model对象
        $this->display('goodsBrand');
    }

    /**
     * 商品品牌的操作方法：brandHandler()
     * 
     */
    public function brandHandler(){
        $brandModel = new \Admin\Model\BrandModel();
        //首先判断是否是get请求
        // if(IS_GET){
        //     //如果是get请求，然后接收传递的id参数，判断具体操作
        //     echo "这是测试get方式提交";
        //     // exit;
        //     $id = I('get.id');
        //     // echo "id=$id";
        //     // exit;
        //     if(isset($id) && ((1 == $id) || (2 == $id))){
        //         //如果id等于1,表示是添加操作,等于2是修改操作
        //         //从数据中获取原始的品牌信息，并在表单中展示
        //         echo "这是商品修改的方法";
        //         $brandList = $brandModel ->find(I('get.brandId'));
        //         $this->assign('brandList', $brandList);
        //         $this->display('_goodsBrand');
        //         exit;
        //     }elseif(isset($id) && (3 == $id)){
        //         //如果id等于3，表示是直接删除操作
                
        //         echo "直接删除操作！";
        //         exit;
        //     }elseif(isset($id) && (4 == $id)){
        //         //如果id等于4，表示是加入购物车操作
        //         echo "放入回收站操作！";
        //         exit;
        //     }else{
        //         //如果没有提交id值，表示url地址被篡改，提示url地址错误，并跳转到商品品牌列表页
        //         $this->error('url地址错误',U('brandList'),3);
        //     }
            if(IS_GET){
            if(1 == I('get.id') || 2 == I('get.id')){
                //如果是get传递的参数是1或者2，表示是添加、修改操作
                $brandList = $brandModel->find(I('get.brand_id'));
                $this->assign('brandList', $brandList);
                $this->display('_goodsBrand');
                exit;
            }elseif(3 == I('get.id')){
                //如果get传递的参数是3，表明是直接删除操作
                if(false !== $brandModel->delete(I('get.brand_id'))){
                    //如果删除成功
                    $this->success('商品删除成功！', U('brandList'));
                    exit;
                }
                //如果删除失败，获取失败的原因
                $error = $brandModel->getError();
                $this->error($error);
            }elseif(4 == I('get.id')){
                //如果get传递的参数是4，表明是加入回收站操作
                
            }else{
                $this->error('你输入的地址错误！',U('brandList'));
                exit;
            }
        }
        //下面是处理post表单提交的操作
        echo "暂停一下";
        var_dump();
        exit;
        if(I('post.brand_id')){
            if($brandModel->create(I('post.') , 2)){ //create接受两个参数，data和type(1:插入，2:修改)
                // echo "这是create方法<br/>";
                if(false !== $brandModel->save()){
                    // echo "这是save方法<br/>";
                    //如果插入成功
                    $this->success('商品修改成功！', U('brandList'));
                    exit;    
                }
                //添加失败，获取错误信息
                $error = $brandModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsHandler'), 3);
                exit;
            }
            //创建失败，获取失败信息
            $error = $brandModel->getError();
            $this->error($error, U('brandHandler'), 3);
            exit;
        }else{
            //如果没有传递goods_id的参数，则表明是添加操作
            if($brandModel->create(I('post.') , 1)){ //create接受两个参数，data和type(1:插入，2:修改)
                //如果创建成功
                if(false !== $brandModel->add()){
                    //如果插入成功
                    $this->success('商品添加成功！', U('brandList'));
                    exit;    
                }
                //添加失败，获取错误信息
                $error = $brandModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsHandler'), 3);
                exit;
            }
            //创建失败，获取失败信息
            $error = $brandModel->getError();
            $this->error($error);   //如果添加失败，返回上一个页面
            // $this->error($error, U('goodsHandler'), 3);
        }
    }
    
}
