<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 11:01:47
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-15 18:57:03
 */
namespace Admin\Controller;

class GoodsController extends \Admin\Controller\BasicController {

    /**
     * 商品管理的方法：展示display、增加Add、删除Delete、修改Update
     * @return [type] [description]
     */
    public function goodsMessage(){
        //获取商品模型的对象
        $goodsModel = D('Goods');
        //首先判断是否是提交表单
        if(IS_GET){
            //如果是get请求，表示是对商品表的展示、添加、修改、删除、回收站操作
            $id = I('get.id');
            if(!empty($id)){
                $goodsId = I('get.goods_id');
                //如果id值不为空，表示是添加、修改、删除、回收站操作
                if((1 == $id) || (2 == $id)){
                    //如果id值是1表示是添加，id值是2表示是修改操作
                    $goodsList = $goodsModel->find($goodsId);
                    //获取当前goods_id下的所有的商品的会员价格
                    $gid = empty($goodsId) ? 0 : $goodsId;
                    $userPrice = D('UserLevel')->alias("a")->field("a.level_id,a.level_name,a.level_discount,b.goods_price,b.id")->join(array(
                        'LEFT JOIN __USER_PRICE__ b ON b.user_level = a.level_id AND b.goods_id = '.$gid
                    ))->select();
                    //获取当前goods_id下的商品扩展分类列表
                    $extCatList = D('ExtendCat')->field('ext_cat_id')->where(array(
                        'goods_id'  =>  array('eq',$goodsId),))->select();
                    //获取当前goods_id下的商品相册的图片信息
                    $goodsGallery = D('GoodsGallery')->field('goods_img_id,goods_id,goods_img_ori,goods_img_small,goods_img_desc')->where(array('goods_id' => array('eq',$goodsId)))->select();
                    //获取商品的分类的下拉列表
                    // $catList = D('Cat')->select();     //使用buildSelect()方法代替
                    //获取商品的品牌的下拉列表
                    // $brandList = D('Brand')->select();   //使用buildSelect()方法代替
                    //获取会员等级、填写会员价格
                    $levelList = D('UserLevel')->field("level_id,level_name,level_discount")->select(); 
                    //将商品的信息赋值到模版中使用
                    $this->assign(array(
                            'goodsList'     =>  $goodsList,
                            'extCatList'    =>  $extCatList,
                            'goodsGallery'  =>  $goodsGallery,
                            'userPrice'     =>  $userPrice,
                            // 'levelList'     =>  $levelList,
                            // 'catList'    =>  $catList,       //使用buildSelect()方法代替
                            // 'brandList'  =>  $brandList,     //使用buildSelect()方法代替
                        )
                    );
                    $this->display('_goods');
                }elseif(3 == $id){
                    //如果get传递的参数是3，表明是直接删除操作
                    if(false !== $goodsModel->delete(I('get.goods_id'))){
                        //如果删除成功
                        $this->success('商品删除成功！', U('goodsMessage'));
                        exit;
                    }
                    //如果删除失败，获取失败的原因
                    $error = $goodsModel->getError();
                    $this->error($error);
                }elseif(4 == $id){
                    //如果get传递的参数是4，表明是加入回收站操作
                    
                }else{
                    //如果是其他的id值，表示url地址被篡改
                    $this->error('你输入的地址错误！',U('goodsMessage'));
                }
                exit; 
            }
            //如果id值为空，表示是展示商品列表操作
            //调用model模型中的search方法，分页显示商品列表，传递参数：每页显示的条数
            $data = $goodsModel->searchLimit(5);
            // $goodsList = $goodsModel->select();
            // $brandModel = D('Brand');    //使用buildSelect()方法代替
            // $brandList = $brandModel->select();
            $this->assign(array(
                    'goodsList' =>  $data['list'], 
                    'page'      =>  $data['page'],
                    // 'brandList' =>  $brandList,  //使用buildSelect()方法代替
                )
            );
            $this->display('goods');
            exit;
        }
        //下面是对post表单提交数据进行处理
        if(IS_POST){
            //如果是post表单提交操作
            //取消系统执行时间30s的限制
            set_time_limit(0);
            $goodsId = I('goods_id');
            //判断表单goods_id是否为空
            if(empty($goodsId)){
                //如果goods_id值为空，则表明是添加操作
                if($goodsModel->create(I('post.') , 1)){ //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $goodsModel->add()){
                        //如果插入成功
                        $this->success('商品添加成功！', U('goodsMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $goodsModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('goodsMessage',array('id' = > 1)), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $goodsModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
                // $this->error($error, U('goodsMessage',array('id' => 2,'goods_id' => $goodsId)), 3);
            }else{
                //如果goods_id值为空，表示是添加操作
                if($goodsModel->create(I('post.') , 2)){ //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $goodsModel->save()){
                        //如果插入成功
                        $this->success('商品修改成功！', U('goodsMessage'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $goodsModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('goodMessage'), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $goodsModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsMessage'), 3);
                exit;
            }
        }
    }

    /**
     * 商品分类的操作方法：goodsCat()
     * @return [type] [description]
     */
    public function goodsCat(){
        $catModel = D('Cat');
        if(IS_GET){
            //首先获取get传递的id值
            $id = I('get.id');
            if(!empty($id)){
                //如果id值存在,表示是CRUD操作
                //获取cat_id值
                $catId = I('get.cat_id');
                if((1 == $id) || (2 == $id)){
                    //如果id为1，表示是添加操作，如果是2，表示是修改操作
                    //获取商品分类对象列表
                    $catList = $catModel->find($catId);
                    //获取所有的分类列表树状展示
                    $cat = $catModel->getTree();
                    $this->assign(array(
                            'catList'   =>  $catList,
                            'cat'       =>  $cat,
                        )
                    );
                    $this->display('_goodsCat');
                }elseif(3 == $id){
                    //如果id为3，表示是直接删除操作
                    //获取当前传递的cat_id值
                    $catId = I('get.cat_id');
                // 在模型的_before_delete()钩子函数中删除
                    if($catModel->delete($catId)){
                        //如果删除成功，给出提示，跳转到分类列表页
                        $this->success('商品分类删除成功！',U('goodsCat'));
                        exit;
                    }
                    //如果删除失败，给出提示，跳转到分类列表页
                    $error = $catModel->getError();
                    $this->error($error,U('goodsCat'));
                }elseif(4 == $id){
                    //如果id为4，表示是加入回收站操作
                    
                }else{
                    //如果是其他值，表示url地址被修改
                    $this->error('url地址错误！',U('goodsCat'),3);
                }
                exit;
            }
            //如果id不存在，表示是展示list页面
            //获取所有的分类列表树状展示
            $catList = $catModel->getTree();
            // $catList = $catModel->select();
            
            $this->assign(array(
                    'catList'   =>  $catList,
                )
            ); 
            $this->display('goodsCat');
            exit;
        }
        //如果是post表单提交
        if(IS_POST){
            //如果是post表单提交操作
            $catId = I('cat_id');
            $info = I('post.');
            //判断表单cat_id是否为空
            if(empty($catId)){
                //如果cat_id值为空，则表明是添加操作
                if($info = $catModel->create(I('post.') , 1)){ //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $catModel->add()){
                        //如果插入成功
                        $this->success('商品分类添加成功！', U('goodsCat'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $catModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    exit;
                }
                //创建失败，获取失败信息
                $error = $catModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
                // $this->error($error, U('goodCat',array('id' => 2,'cat_id' => $catId)), 3);
            }else{
                //如果cat_id值为不为空，表示是修改操作
                if($catModel->create(I('post.') , 2)){ //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $catModel->save()){
                        //如果插入成功
                        $this->success('商品分类修改成功！', U('goodsCat'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $catModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('catHandler'), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $catModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsCat'), 3);
                exit;
            }
        }
    }

    /**
     * 商品品牌的操作方法：goodsBrand()
     * 
     */
    public function goodsBrand(){
        //创建商品品牌的model对象
        $brandModel = D('Brand');
        //首先判断是否是get请求,get请求表示是品牌操作
        if(IS_GET){
            //如果是get请求，然后接收传递的id参数，判断具体操作
            $id = I('get.id');
            if(!empty($id)){
                //如果id值不为空，表示是对商品品牌的处理操作
                if((1 == $id) || (2 == $id)){
                    //如果id等于1,表示是添加操作,等于2是修改操作
                    $brandList = $brandModel->find(I('get.brand_id'));
                    $this->assign('brandList', $brandList);
                    $this->display('_goodsBrand');
                }elseif(3 == $id){
                    //如果id等于3，表示是直接删除操作
                    $info = $brandModel->delete(I('get.brand_id'));
                    if(false !== $info){
                        //如果返回信息不为false,则表示删除成功
                        $this->success('商品品牌删除成功！',U('goodsBrand'));
                        exit;
                    }
                    //如果删除成功，获取控制器中的错误信息
                    $error = $brandModel->getError();
                    $this->error($error);
                }elseif(4 == $id){
                    //如果id等于4，表示是加入购物车操作
                    echo "放入回收站操作！";


                }else{
                    //如果不是以上id值，表示url地址被篡改
                    $this->error('url地址错误',U('goodsBrand'),3);
                }
                exit;
            }
            //如果id值为空，表示没有传递id的值，是商品品牌展示操作
            $brandList = $brandModel->select();
            $this->assign(array(
                    'brandList' =>  $brandList,
                )
            );
            $this->display('goodsBrand');
        }
        // 下面对提交的表单中的数据进行处理
        if(IS_POST){
            // 判断表单中是否存在brand_id字段值，如果非空是修改操作，否则是添加操作
            $brandId = I('post.brand_id');
            if(empty($brandId)){
                //添加操作
                if($brandModel->create(I('post.'),1)){  //create方法中的第二个参数：1
                    //如果返回值为true,表明创建成功
                    if(false !== $brandModel->add()){
                        //如果返回值不为false,表明添加成功
                        $this->success('商品品牌添加成功！',U('goodsBrand'));
                        exit;
                    }
                    //否则表示添加失败,获取模型中失败的原因
                    $error = $brandModel->getError();
                    $this->error($error);
                    exit;
                }
                //创建失败,获取模型中失败的原因
                $error = $brandModel->getError();
                $this->error($error);
            }else{
                //修改操作
                if($brandModel->create(I('post.'),2)){  //create方法中的第二个参数：2
                    //如果返回值为true,表明创建成功
                    if(false !== $brandModel->save()){
                        //如果返回值不为false,表明修改成功
                        $this->success('商品品牌修改成功！',U('goodsBrand'));
                        exit;
                    }
                    //否则表示添加失败,获取模型中失败的原因
                    $error = $brandModel->getError();
                    $this->error($error);
                    exit;
                }
                //创建失败,获取模型中失败的原因
                $error = $brandModel->getError();
                $this->error($error);
            }
        }
    }

    /**
     * 商品类型的操作方法：goodsType()
     * @return [type] [description]
     */
    public function goodsType(){
        $typeModel = D('Type');
        if(IS_GET){
            //首先获取get传递的id值
            $id = I('get.id');
            if(!empty($id)){
                //如果id值存在,表示是CRUD操作
                //获取type_id值
                $typeId = I('get.type_id');
                if((1 == $id) || (2 == $id)){
                    //如果id为1，表示是添加操作，如果是2，表示是修改操作
                    //获取商品分类对象列表
                    $typeList = $typeModel->find($typeId);
                    $this->assign(array(
                            'typeList'   =>  $typeList,
                        )
                    );
                    $this->display('_goodsType');
                }elseif(3 == $id){
                    //如果id为3，表示是直接删除操作
                    //获取当前传递的type_id值
                    $typeId = I('get.type_id');
                    if($typeModel->delete($typeId)){
                        //如果删除成功，给出提示，跳转到分类列表页
                        $this->success('商品类型删除成功！',U('goodsType'));
                        exit;
                    }
                    //如果删除失败，给出提示，跳转到分类列表页
                    $error = $typeModel->getError();
                    $this->error($error,U('goodsType'));
                }elseif(4 == $id){
                    //如果id为4，表示是加入回收站操作
                    
                }else{
                    //如果是其他值，表示url地址被修改
                    $this->error('url地址错误！',U('goodsType'),3);
                }
                exit;
            }
            //如果id不存在，表示是展示list页面
            $typeList = $typeModel->select();
            //获取当前类型下的属性数量
            $attrCount = D('Attr')->where(array('type_id' => $typeId))->count();
            $this->assign(array(
                    'typeList'   =>  $typeList,
                )
            ); 
            $this->display('goodsType');
            exit;
        }
        //如果是post表单提交
        if(IS_POST){
            //如果是post表单提交操作
            $typeId = I('type_id');
            //判断表单type_id是否为空
            if(empty($typeId)){
                //如果type_id值为空，则表明是添加操作
                if($info = $typeModel->create(I('post.') , 1)){ 
                    //create接受两个参数，data和type(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $typeModel->add()){
                        //如果插入成功
                        $this->success('商品类型添加成功！', U('goodstype'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $typeModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('goodtype',array('id' => 1)), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $typeModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
                // $this->error($error, U('goodtype',array('id' => 2,'type_id' => $typeId)), 3);
            }else{
                //如果type_id值为不为空，表示是修改操作
                if($typeModel->create(I('post.') , 2)){ //create接受两个参数，data和type(1:插入，2:修改)
                    if(false !== $typeModel->save()){
                        //如果插入成功
                        $this->success('商品类型修改成功！', U('goodsType'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $typeModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('typeHandler'), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $typeModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsCat'), 3);
                exit;
            }
        }
    }

    /**
     * 商品属性的操作：attributeHandler()
     * @return [type] [description]
     */
    public function goodsAttr(){
        $attrModel = D('Attr');
        if(IS_GET){
            //首先获取get传递的id值
            $id = I('get.id');
            if(!empty($id)){
                //如果id值存在,表示是CRUD操作
                //获取attr_id值
                $attrId = I('get.attr_id');
                if((1 == $id) || (2 == $id)){
                    //如果id为1，表示是添加操作，如果是2，表示是修改操作
                    //获取商品属性对象列表
                    $attrList = $attrModel->find($attrId);
                    $this->assign(array(
                            'attrList'   =>  $attrList,
                        )
                    );
                    $this->display('_goodsAttr');
                }elseif(3 == $id){
                    //如果id为3，表示是直接删除操作
                    //获取当前传递的attr_id值
                    $attrId = I('get.attr_id');
                    if($attrModel->delete($attrId)){
                        //如果删除成功，给出提示，跳转到分类列表页
                        $this->success('商品属性删除成功！',U('goodsAttr'));
                        exit;
                    }
                    //如果删除失败，给出提示，跳转到分类列表页
                    $error = $attrModel->getError();
                    $this->error($error,U('goodsAttr'));
                }elseif(4 == $id){
                    //如果id为4，表示是加入回收站操作
                    
                }else{
                    //如果是其他值，表示url地址被修改
                    $this->error('url地址错误！',U('goodsAttr'),3);
                }
                exit;
            }
            //如果id不存在，表示是展示list页面
            $attrList = $attrModel->select();
            $this->assign(array(
                    'attrList'   =>  $attrList,
                )
            ); 
            $this->display('goodsAttr');
            exit;
        }
        //如果是post表单提交
        if(IS_POST){
            //如果是post表单提交操作
            $attrId = I('attr_id');
            //判断表单attr_id是否为空
            if(empty($attrId)){
                //如果attr_id值为空，则表明是添加操作
                if($info = $attrModel->create(I('post.') , 1)){ //create接受两个参数，data和attr(1:插入，2:修改)
                    //如果创建成功
                    if(false !== $attrModel->add()){
                        //如果插入成功
                        $this->success('商品属性添加成功！', U('goodsAttr'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $attrModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('goodattr',array('id' => 1)), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $attrModel->getError();
                $this->error($error);   //如果添加失败，返回上一个页面
                // $this->error($error, U('goodattr',array('id' => 2,'attr_id' => $attrId)), 3);
            }else{
                //如果attr_id值为不为空，表示是修改操作
                if($attrModel->create(I('post.') , 2)){ //create接受两个参数，data和attr(1:插入，2:修改)
                    if(false !== $attrModel->save()){
                        //如果插入成功
                        $this->success('商品属性修改成功！', U('goodsAttr'));
                        exit;    
                    }
                    //添加失败，获取错误信息
                    $error = $attrModel->getError();
                    $this->error($error);   //如果添加失败，直接返回上一个页面
                    // $this->error($error, U('attrHandler'), 3);
                    exit;
                }
                //创建失败，获取失败信息
                $error = $attrModel->getError();
                $this->error($error);   //如果添加失败，直接返回上一个页面
                // $this->error($error, U('goodsCat'), 3);
                exit;
            }
        }
    }

    /**
     * 商品评论的操作：commentHandler()
     * 
     */
    public function goodsComment(){

        $this->display('_goodsCommment');
    }

/********************以下是采用商品库存与商品属性独立操作*****************/
    /**
     * ajax动态获取商品的属性(唯一、可选)
     * 用于添加、修改商品的属性
     */
    //获取商品的唯一属性，并组装成html代码，在展示页面用于添加、修改商品
    public function ajaxGetSimpAttr(){
        $data = D("Goods")->ajaxGetSimpAttr(I('get.gid'),I('get.tid'));
        exit($data);
    }

    public function ajaxGetSpecAttr(){
        $data = D("Goods")->ajaxGetSpecAttr(I('get.gid'),I('get.tid'));
        exit($data);
    }

    /**
     * ajax动态删除商品属性、库存、价格数据库中的数据
     * 使用在商品添加、修改页面中，动态删除商品可选属性对应的库存消息
     */
    public function ajaxDeleteStock(){
        if(D('GoodsStock')->delete(I('get.attrid'))){
            echo json_encode("1");
        }else{
            echo json_encode("0");
        }
    }

    /**
     * ajax动态删除商品相册goods_gallery表中的数据
     * 使用在添加、修改商品相册时
     */
    public function ajaxDeleteImg(){
        //获取当前goods_img_id下的所有的图片的路径
        $img = D('GoodsGallery')->field('goods_img_ori,goods_img_small,goods_img_medium,goods_img_big')->find(I('get.imgId'));
        //遍历删除所有的图片
        deleteImg($img);
        //然后删除goods_gallery表中的数据
        $info = D('GoodsGallery')->delete(I('get.imgId'));
        if($info === false){
            //如果删除失败
            echo json_encode(0);
            exit;
        }
        echo json_encode(1);
    }

/********************以下是采用商品库存与商品属性联表操作*****************/
    /**
     * Ajax动态获取商品的属性(唯一、可选)
     */
    public function ajaxGetAttr(){
        //首先根据提交的类型的id值，获取该类型下的所有的属性
        if(I('get.uid') == '1'){
            //如果是唯一属性
            $where = array(
                'a.type_id' => array('eq',I('get.tid')),
                'a.attr_unique' => array('eq','唯一属性'),
            );
        }elseif(I('get.uid') == '2'){
            //如果是可选属性
            $where = array(
                'a.type_id' => array('eq',I('get.tid')),
                'a.attr_unique' => array('eq','可选属性'),
            );
        }else{
            //url地址错误
            $data = "url地址错误！";
            echo json_encode($data);
            return false;
        }
        //联表查询，获取当前goods_id的type_id下的所有属性，并将该goods_id下的属性值填充
        $data = D('Attr')->alias('a')->field('a.attr_id,a.attr_name,a.type_id,a.attr_unique,a.optional_list,GROUP_CONCAT(b.id) as id,GROUP_CONCAT(b.attr_value) as attr_value')->
        join("LEFT JOIN __GOODS_ATTR__ b ON a.attr_id = b.attr_id AND b.goods_id = ".I('get.gid')
        )->where($where)->group('attr_id')->select();
        echo json_encode($data);
    }

    /**
     * ajax动态获取商品的可选属性，并且与商品的价格、库存量相结合输出
     */
    public function ajaxGetSpec(){
        //获取当前的商品类型的集合
        $attrList = D("Goods")->ajaxGoodsAttr(I('get.tid'));
        //获取所有的商品类型的名称
        $attrName = D("Attr")->field('attr_name,attr_id')->where(array('type_id' => I('get.tid'),'attr_unique' => "可选属性"))->select();
        $attrStock = D('GoodsStock')->where(array('goods_id' => array('eq',I('get.gid'))))->select();
        //获取当前商品的商品的库存量和价格集合
        echo json_encode(array($attrName,$attrList,$attrStock));
    }

    /**
     * Ajax删除商品goods_attr表中的商品(可选)属性：用来操作goods_stock表
     */
    public function ajaxDeleteSpec(){
        //获取ajax传递来的属性的id值
        if(D('GoodsAttr')->delete(I('get.hval'))){
            echo json_encode("删除成功！");
        }else{
            echo json_encode("删除失败！");
        }
    }

/********************以下是商品列表展示页面ajax操作*******************************/
    /**
     * ajax获取商品列表，并在前台进行展示
     * 用于商品展示列表页
     */
    public function ajaxGoodsList(){
        $data = D("Goods")->ajaxSearch(I('post.'),5);
        $this->assign(array(
                // 'goodsList'     =>      $goodsList,
                'goodsList'     =>      $data['list'], 
                'page'          =>      $data['page'],
        ));
        $this->display("ajaxGoodsList");
    }

    /**
     * ajax删除当前goods_id下的商品表中的信息
     * 用于商品展示列表页，动态删除商品
     */
    public function ajaxDeleteGoods(){
        $info = D("Goods")->delete(I('get.goods_id'));
        //如果不是超级管理员，执行删除操作
        if($info === false){
            //如果删除失败
            $error = D("Goods")->getError();
            echo json_encode("2");
        }else{
            echo json_encode("1");
        }
    }

    /**
     * ajax动态提交商品添加、修改表单
     * 使用在商品编辑、修改页面的动态提交
     */
    public function ajaxAddEditGoods(){
        
    }
    
}
