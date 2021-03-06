<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-11 10:15:03
 */
namespace Admin\Model;

class GoodsModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'goods_name','goods_sn','goods_cat','goods_brand','goods_type',
        'goods_market_price','goods_cost_price','goods_shop_price','goods_promote_price',
        'floor_recommend','goods_sort_order','is_onsale','is_delete','is_best','is_new','is_hot',
        'is_promote','user_is_discount','promote_start_time','promote_end_time','goods_earn_jifen',
        'goods_earn_coin','goods_expense_coin','goods_image','goods_thumb_smallest',
        'goods_thumb_small','goods_thumb_medium','goods_thumb_big','goods_thumb_biggest','goods_unit',
        'goods_weight','is_real','is_free_shipping','goods_shipping_expense','goods_total_stock',
        'goods_stock_warning','goods_keywords','goods_desc','seller_note','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
        'goods_id','goods_name','goods_sn','goods_cat','goods_brand','goods_type',
        'goods_market_price','goods_cost_price','goods_shop_price','goods_promote_price',
        'floor_recommend','goods_sort_order','is_onsale','is_delete','is_best','is_new','is_hot',
        'is_promote','user_is_discount','promote_start_time','promote_end_time','goods_earn_jifen',
        'goods_earn_coin','goods_expense_coin','goods_image','goods_thumb_smallest',
        'goods_thumb_small','goods_thumb_medium','goods_thumb_big','goods_thumb_biggest','goods_unit',
        'goods_weight','is_real','is_free_shipping','goods_shipping_expense','goods_total_stock',
        'goods_stock_warning','goods_keywords','goods_desc','seller_note','last_update_time',
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
        array('goods_name','require','商品名不能为空！',1),
        array('goods_market_price','require','商品市场价不能为空！',1),
        array('goods_cat','require','商品主分类不能为空！',1),
        array('goods_brand','require','商品品牌不能为空！',1),
        array('goods_market_price','currency','商品市场价必须是金钱类型！',1),
        array('goods_shop_price','require','商品价格不能为空！',1),
        array('goods_shop_price','currency','商品价格必须是金钱类型！',1),
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
        // 设置缩略图的样式 
        $thumb = array(
            array(50,50),
            array(160,160),
            array(350,350),
            array(700,700),
            array(1000,1000),
        ); 
        $mess = uploadOne('goods_image','goods',$thumb);
        //判断商品logo是否上传成功
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
            $data['goods_image'] = IMG_PATH.$mess['image'][0];
            $data['goods_thumb_smallest'] = IMG_PATH.$mess['image'][1];
            $data['goods_thumb_small'] = IMG_PATH.$mess['image'][2];
            $data['goods_thumb_Medium'] = IMG_PATH.$mess['image'][3];
            $data['goods_thumb_big'] = IMG_PATH.$mess['image'][4];
            $data['goods_thumb_biggest'] = IMG_PATH.$mess['image'][5];
        }
        $data['addtime'] = time();
        // $data['goods_keywords'] = removeXss(I('post.goods_keywords'));
        //商品详细描述中，需要使用html标签，需要进行html信息过滤
        $data['goods_desc'] = removeXss($_POST['goods_desc']);
        //处理商品的推荐信息
        //以下是采用set字段类型来存储商品的推荐信息，缺点：无法在商品列表展示页进行点击排序
        // $data['is_recommend'] = I('post.is_recommend') ? implode(',',I('post.is_recommend')) : '';
        $data['is_best'] = "是";
        $data['is_new'] = "是";
        $data['is_hot'] = "是";
        if(empty(I('post.is_best'))){
            //如果post表单没有提交精品选项
            $data['is_best'] = "否";
        }
        if(empty(I('post.is_new'))){
            //如果post表单没有提交新品选项
            $data['is_new'] = "否";
        }
        if(empty(I('post.is_hot'))){
            //如果post表单没有提交热销选项
            $data['is_hot'] = "否";
        }
        //处理商品的促销信息
        $data['is_promote'] = '是';
        $data['user_is_discount'] = I('post.user_is_discount');
        $startTime = explode('-',str_replace(array(' ',':'),'-',I('post.promote_start_time')));
        $endTime = explode('-',str_replace(array(' ',':'),'-',I('post.promote_end_time')));
        $data['promote_start_time'] = mktime($startTime[3],$startTime[4],00,$startTime[1],$startTime[2],$startTime[0]);
        $data['promote_end_time'] = mktime($endTime[3],$endTime[4],59,$endTime[1],$endTime[2],$endTime[0]);
        //如果没有选择商品促销信息，重置商品促销数据，并且写入到数据库中
        if(empty(I('post.is_promote'))){
            $data['is_promote'] = "否";
            $data['user_is_discount'] = "否";
            $data['goods_promote_price'] = 0;
            $data['promote_start_time'] = 0;
            $data['promote_end_time'] = 0;
        }
        //判断是否设置选择了商品包邮
        if(I('post.is_free_shipping') === "是"){
            //如果包邮
            $data['goods_shipping_expense'] = 0;
        }
    }

    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
    /********************以下是处理商品表中的信息更新**************************/
        //如果当前不是自增商品的浏览历史总量操作
        echo "<pre>";
        // var_dump($data);
        // var_dump($data["goods_view_amount"]);
        // var_dump($data["goods_total_stock"]);
        // exit;
        if(empty($data["goods_view_amount"]) && count($data) > 1){
            //先获取修改的商品的goods_id值
            $goodsId = $option['where']['goods_id'];
            // 设置缩略图的样式 
            $thumb = array(
                array(50,50),
                array(160,160),
                array(350,350),
                array(700,700),
                array(1000,1000),
            ); 
            $mess = uploadOne('goods_image','goods',$thumb);
            if($mess['status'] > -1){
                if(!$mess['status']){
                    //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                    $this->error = $mess['error'];
                    return false;
                }
                //如果返回的状态码为1，表示创建成功，将url地址写入数据库中，同时将原始的图片删除
                $img = $this->field('goods_image,goods_thumb_smallest,goods_thumb_small,goods_thumb_Medium,goods_thumb_big,goods_thumb_biggest')->find($goodsId);
                deleteImg($img);
                $data['goods_image'] = IMG_PATH.$mess['image'][0];
                $data['goods_thumb_smallest'] = IMG_PATH.$mess['image'][1];
                $data['goods_thumb_small'] = IMG_PATH.$mess['image'][2];
                $data['goods_thumb_Medium'] = IMG_PATH.$mess['image'][3];
                $data['goods_thumb_big'] = IMG_PATH.$mess['image'][4];
                $data['goods_thumb_biggest'] = IMG_PATH.$mess['image'][5];
            }
            //商品详细描述中，需要使用html标签，需要进行html信息过滤
            $data['goods_desc'] = removeXss($_POST['goods_desc']);
            // $data['goods_keywords'] = removeXss(I('post.goods_keywords'));
            $data['last_update_time'] = time();
            //处理商品的推荐信息
            //以下是采用set字段类型来存储商品的推荐信息，缺点：无法在商品列表展示页进行点击排序
            // $data['is_recommend'] = I('post.is_recommend') ? implode(',',I('post.is_recommend')) : '';
            $data['is_best'] = "是";
            $data['is_new'] = "是";
            $data['is_hot'] = "是";
            if(empty(I('post.is_best'))){
                //如果post表单没有提交精品选项
                $data['is_best'] = "否";
            }
            if(empty(I('post.is_new'))){
                //如果post表单没有提交新品选项
                $data['is_new'] = "否";
            }
            if(empty(I('post.is_hot'))){
                //如果post表单没有提交热销选项
                $data['is_hot'] = "否";
            }
            //处理商品的促销信息
            $data['is_promote'] = '是';
            $data['user_is_discount'] = I('post.user_is_discount');
            $startTime = explode('-',str_replace(array(' ',':'),'-',I('post.promote_start_time')));
            $endTime = explode('-',str_replace(array(' ',':'),'-',I('post.promote_end_time')));
            // echo "<pre>";
            // var_dump($startTime);
            // var_dump($endTime);
            // exit;
            $data['promote_start_time'] = mktime($startTime[3],$startTime[4],00,$startTime[1],$startTime[2],$startTime[0]);
            $data['promote_end_time'] = mktime($endTime[3],$endTime[4],59,$endTime[1],$endTime[2],$endTime[0]);
            //如果没有选择商品促销信息，重置商品促销数据，并且写入到数据库中
            if(empty(I('post.is_promote'))){
                $data['is_promote'] = "否";
                $data['user_is_discount'] = "否";
                $data['goods_promote_price'] = 0;
                $data['promote_start_time'] = 0;
                $data['promote_end_time'] = 0;
            }
            //判断是否选择了商品包邮
            if(I('post.is_free_shipping') === "是"){
                //如果包邮
                $data['goods_shipping_expense'] = 0;
            }

        /********************以下是处理商品相册goods_gallery表中的数据*************/
            //获取表单post提交的file的数据
            $image = $_FILES["goods_gallery"];
            $imageDesc = I('post.goods_image_desc');
            $imgList = array();
            $imgName = array();
            //判断表单中是否有提交图片
            if(isset($image)){
                //如果有上传图片，进行图片的处理操作
                foreach ($image['name'] as $key => $value) {
                    //如果图片名称不为空,并且没有出现过
                    if(!empty($value) && !in_array($value,$imgName)){
                        $imgList[] = array(
                            'name'              =>  $image['name'][$key],
                            'type'              =>  $image['type'][$key],
                            'tmp_name'          =>  $image['tmp_name'][$key],
                            'error'             =>  $image['error'][$key],
                            'size'              =>  $image['size'][$key],
                            'goods_img_desc'    =>  $imageDesc[$key],
                        );
                        //将图片名称放到数组imgName中
                        $imgName[] = $value;
                    }
                }
                //将拼接好的每个图片信息赋值给$_FILES常量
                $_FILES = $imgList;
                // 设置缩略图的样式 
                $thumb = array(
                    array(50,50),
                    array(350,350),
                    array(700,700),
                );
                //循环$img数组，依次调用uploadOne()函数，进行图片上传、缩略图的制作
                foreach ($imgList as $key => $value) {
                    if($value['error'] == 0){
                        $mess = uploadOne($key,'goods',$thumb);
                        //初始化插入数据
                        $input = array();
                        //判断商品logo是否上传成功
                        if($mess['status'] > -1){
                            if(!$mess['status']){
                                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                                $this->error = $mess['error'];
                                return false;
                            }
                            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
                            $input['goods_img_ori'] = IMG_PATH.$mess['image'][0];
                            $input['goods_img_small'] = IMG_PATH.$mess['image'][1];
                            $input['goods_img_medium'] = IMG_PATH.$mess['image'][2];
                            $input['goods_img_big'] = IMG_PATH.$mess['image'][3];
                        }
                        $input['goods_id'] = $goodsId;
                        $input['goods_img_desc'] = $value['goods_img_desc'];
                        $input['addtime'] = time();
                        $info = D('GoodsGallery')->add($input);
                        if($info === false){
                            //如果添加失败
                            $this->error = "商品相册图片信息插入失败！";
                            return false;
                            exit;
                        }
                    }
                }
            }

        /*********************以下是处理商品的会员价格表中的数据*******************/
            //获取表单post提交的会员的商品的价格信息
            foreach (I('post.user_price') as $key => $value) {
                if(!empty($value)){
                    $v = explode('-',$key);
                    $info = D('UserPrice')->table('__USER_PRICE__')->field('goods_id,user_level,goods_price,goods_promote,last_update_time')->where(array('id' => array('eq',$v[0])))->save(array(
                            'goods_id'             =>  $goodsId,
                            'user_level'           =>  $v[1],
                            'goods_price'          =>  $value,
                            'goods_promote'           =>  "否",
                            'last_update_time'     =>  time(),
                        )
                    );
                    //判断是否修改成功
                    if($info === false){
                        //如果插入失败
                        $this->error = "会员-商品价格插入失败！";
                        return false;
                        exit;
                    }
                }
            }

        /*********************以下是处理商品的促销信息的数据*******************/
            //删除该商品原有的促销会员价格信息
            $info = D('UserPrice')->where(array('goods_id' => array('eq',$goodsId),'goods_promote' => array('eq','是')))->delete();
            //判断是否删除成功
            if($info === false){
                //如果删除失败
                $this->error = "商品-会员促销价格删除失败！";
                return false;
                exit;
            }
            //判断post表单中是否提交了促销信息
            if(I('post.is_promote')){
                //如果提交了商品促销信息,判断促销价格，促销开始-结束时间是否为空？
                if(empty(I('post.goods_promote_price'))){
                    $this->error = "商品促销价格不能为空！";
                    return false;
                    exit;
                }
                if(empty(I('post.promote_start_time')) || empty(I('post.promote_end_time'))){
                    $this->error = "商品促销时间不能为空！";
                    return false;
                    exit;
                }
                //获取商品促销信息的会员价格：前提是设置了会员促销价格折上折
                //判断是否设置了会员促销价格折上折
                if(I('post.user_is_discount') === "是"){
                    //获取会员级别、折扣信息
                    $userList = D('UserLevel')->field('level_id,level_discount')->select();
                    foreach ($userList as $key => $value) {
                        $info = D('UserPrice')->table('__USER_PRICE__')->add(array(
                                'goods_id'             =>  $goodsId,
                                'user_level'           =>  $value['level_id'],
                                'goods_price'          =>  I('post.goods_promote_price')*$value['level_discount']/100,
                                'goods_promote'           =>  "是",
                                'addtime'              =>  time(),
                                'last_update_time'     =>  time(),
                            )
                        );
                        //判断是否修改成功
                        if($info === false){
                            //如果插入失败
                            $this->error = "商品-会员促销价格插入失败！";
                            return false;
                            exit;
                        }
                    }
                }
            }

        /*********************以下是处理商品扩展分类表中的数据********************/
            //获取表单中提交的商品的扩展分类
            $extCat = I('post.goods_ext_cat'); 
            //清空扩展分类表单中该商品goods_id下的信息
            $where['goods_id'] = array('eq',$goodsId);
            D('ExtendCat')->table('__EXTEND_CAT__')->where($where)->delete();
            //遍历extCat集合，将获取的商品的good_id和ext_cat_id添加到extend_cat表单中
            foreach(array_unique($extCat) as $key => $value){
                if(!empty($value)){
                    $info = D('ExtendCat')->table('__EXTEND_CAT__')->field('ext_cat_id,goods_id,ext_cat_addtime')->add(array(
                            'ext_cat_id'        =>  $value,
                            'goods_id'          =>  $goodsId,
                            'addtime'           =>  time(),
                        )
                    ); 
                }     
            }

        /******************以下是处理商品-属性、库存、属性价格********************/
            //获取表单中提交的商品goods_id、属性id、属性值attr_value，并且更新到商品-属性表中
            //首先判断唯一属性和可选属性中的商品类型是否一致
            $type_id = I('post.goods_type');
            $type_id1 = I('post.goods_type1');
            $type_id2 = I('post.goods_type2');
            if($type_id !== $type_id1 || $type_id !== $type_id2){
                $this->error = "通用信息、商品属性、商品规格下，商品类型不一致！";
                return false;
            }
            //方法一：处理商品的可选属性
            // //如果类型一致,进行插入操作
            // $goodsAttr = I('post.attr_value');
            // $attrNumber = I('post.attr_number');
            // $type = D('GoodsAttr')->where(array('goods_id' => array('eq',$goodsId)))->find();
            // //如果修改了商品的类型
            // if($type_id != $type['type_id']){
            //     //如果商品的类型发生修改,则直接删除该商品下的所有的属性、库存量、价格
            //     D('GoodsAttr')->where(array('goods_id' => array('eq',$goodsId)))->delete();
            // }
            // //遍历属性值的列表，然后将数据添加到表单中
            // $i = 0;
            // //创建一个数组，用来记录新插入的数据
            // $attrArray = array();
            // foreach ($goodsAttr as $key => $value){
            //     foreach($value as $k => $v){
            //         //如果属性值被修改为空,直接删除该属性
            //         if(empty($v) && ($attrNumber[$i] !== "")){
            //             D('GoodsAttr')->delete($attrNumber[$i]);
            //         }elseif(!empty($v)){
            //             //判断是否是唯一属性
            //             //如果是可选属性，进行更新操作
            //             if($attrNumber[$i] !== ""){
            //                 if(!in_array($key.$v,$attrArray)){
            //                     //如果value不为空，执行更新操作
            //                     $mess = D('GoodsAttr')->where(array(
            //                             'id' => array('eq',$attrNumber[$i]),
            //                         ))->setField('attr_value', $v);
            //                     $attrArray[] = $key.$v;
            //                 }
            //             }else{
            //                 //如果attrNumber[]为空，执行插入操作
            //                 if(!in_array($key.$v,$attrArray)){
            //                     //如果该数据已经添加成功，则忽略,否则插入
            //                     $mess = D('GoodsAttr')->table('__GOODS_ATTR__')->add(array(
            //                             'goods_id'      =>  $goodsId,
            //                             'attr_id'       =>  $key, 
            //                             'attr_value'    =>  $v, 
            //                             'type_id'       =>  $type_id,
            //                         )
            //                     );
            //                     if($mess){
            //                         //如果插入成功
            //                         $attrArray[] = $key.$v;
            //                     }
            //                 }
            //             }
            //         }
            //         //循环完成一次，i自增一次
            //         $i++;
            //     }
            // }

            //方法二：处理商品的属性、同时可以处理商品的库存量和商品价格
            //首先处理商品-属性表中的数据
            $goodsAttr = I('post.attr_value');
            $goodsStock = I('post.goods_stock');
            $goodsPrice = I('post.goods_price');
            //遍历
            //先删除当期goods_id下的所有的商品-属性和商品-库存表中的数据
            D('GoodsAttr')->where(array('goods_id' => array('eq',$goodsId)))->delete();
            D('GoodsStock')->where(array('goods_id' => array('eq',$goodsId)))->delete();
            
            //将获取到的属性组合进行拼接，插入到商品的库存goods_stock表中
            //定义两个属性值同时为空时的数组
            $index = array();
            foreach ($goodsStock as $key => $value) {
                $num = 0;
                foreach ($value as $k => $v) {
                    if(empty($v) && empty($goodsPrice[$key][$k])){
                        $index[] = $num;
                    }
                    if(!empty($v) || !empty($goodsPrice[$key][$k])){
                        $info = D('GoodsStock')->table('__GOODS_STOCK__')->add(
                            array(
                                'goods_id' => $goodsId,
                                'attr_stock' => $v,
                                'attr_price' => $goodsPrice[$key][$k],
                                'attr_list' => rtrim($key,','),
                                'value_list' => rtrim($k,','),
                            ) 
                        );
                        if($info === false){
                            //如果插入失败
                            $this->error = "商品-库存-价格表数据插入失败！";
                            return false;
                            exit;
                        }
                    }
                    $num++;
                }
            }
            //遍历属性值的列表，然后将数据添加到表单中
            //需要用户手动删除无用的选项，否则会录入值为零的选项
            foreach ($goodsAttr as $key => $value) {
            //首先判断当前属性是否是唯一属性
                if(count($value) > 1){
                    foreach ($index as $k1 => $v1) {
                        //array_splice($value,$v1)删除原来的元素后，会重置索引，unset()不会重置索引
                        unset($value[$v1]);
                    }
                }
               foreach (array_unique($value) as  $k => $v) {
                    if(!empty($v)){
                        $info = D('GoodsAttr')->table('__GOODS_ATTR__')->add(array(
                                'goods_id'      =>  $goodsId,
                                'attr_id'       =>  $key, 
                                'attr_value'    =>  $v, 
                                'type_id'       =>  $type_id,
                            )
                        );
                        if($info === false){
                            //如果插入失败
                            $this->error = "商品-属性表数据插入失败！";
                            return false;
                            exit;
                        }
                    }
                }
            }
        }
    }
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete($option){
    /*************以下是处理商品表goods中的数据*****************************/
        //首先获取要删除的商品的goods_id
        $goodsId = $option['where']['goods_id'];
        //然后将图片原图和缩略图都删除掉
        $img = $this->field('goods_image,goods_thumb_smallest,goods_thumb_small,goods_thumb_Medium,goods_thumb_big,goods_thumb_biggest')->find($goodsId);
        //调用function函数库中的deleteImg()方法
        deleteImg($img);
    /****************以下是处理商品扩展分类表extend_cat中的数据*******************/
        //获取当前goods_id下的所有的扩展分类，并删除
        $info = D('ExtendCat')->where(array('goods_id'  =>  array('eq',$goodsId)))->delete();
        if($info === false){
            //如果删除失败
            $this->error = "商品扩展分类删除失败！";
            return false;
            exit; 
        }
    /*****************以下是处理商品属性、库存、价格表中的数据***********************/
        //获取当前goods_img_id下的所有的图片的路径
        $img = D('GoodsGallery')->field('goods_img_ori,goods_img_small,goods_img_medium,goods_img_big')->find($goodsId);
        //遍历删除所有的图片
        deleteImg($img);
        //然后删除goods_gallery表中的数据
        $info = D('GoodsGallery')->where(array('goods_id' => array('eq',$goodsId)))->delete();
        if($info === false){
            //如果删除失败
            $this->error = "商品相册信息删除失败！";
            return false;
            exit;
        }

    /*****************以下是处理商品属性、库存、价格表中的数据***********************/
    //后期优化：先判断当前商品的价格和库存量有没有进行处理，处理完成才可以删除！！！
        //这种方法，默认删除商品的所有的属性、库存、价格等相关信息
        $info = D('GoodsAttr')->where(array('goods_id'  =>  array('eq',$goodsId)))->delete();
        if($info === false){
            //如果删除失败
            $this->error = "商品信息删除失败！";
            return false;
            exit; 
        }
        //删除商品-库存-价格表goods_stock中的数据
        $info = D('GoodsStock')->where(array('goods_id'  =>  array('eq',$goodsId)))->delete();
        if($info === false){
            //如果删除失败
            $this->error = "商品库存、价格删除失败！";
            return false;
            exit;
        }
    /*****************以下是处理商品-会员价格表中的数据***********************/
        $info = D('UserPrice')->where(array('goods_id'  =>  array('eq',$goodsId)))->delete();
        if($info === false){
            //如果删除失败
            $this->error = "商品会员-价格删除失败！";
            return false;
            exit;
        }
    } 

    //钩子函数：_after_insert(),插入数据库之后执行
    protected function _after_insert(&$data , $option){
    
    /********************以下是处理商品扩展分类********************************/
        //添加成功后，获取返回的商品的goods_id
        $goodsId = $data['goods_id'];
        //获取表单中提交的商品的扩展分类的列表
        $extCat = I('post.goods_ext_cat');
        //遍历extCat集合，将获取的商品的good_id和ext_cat_id添加到extend_cat表单中
        foreach(array_unique($extCat) as $key => $value){
            if(!empty($value)){
                $info = D('ExtendCat')->table('__EXTEND_CAT__')->field('ext_cat_id,goods_id,ext_cat_addtime')->add(array(
                        'ext_cat_id'        =>  $value,
                        'goods_id'          =>  $goodsId,
                        'addtime'           =>  time(),
                    )
                );
                if($info === false){
                    //如果添加失败
                    $this->error = "商品扩展分类插入失败！";
                    return false;
                    exit;
                }
            }
        }
    /********************以下是处理商品相册goods_gallery表中的数据*************/
        //获取表单post提交的file的数据
        $image = $_FILES["goods_gallery"];
        $imageDesc = I('post.goods_image_desc');
        $imgList = array();
        $imgName = array();
        //判断表单中是否有提交图片
        if(isset($image)){
            //如果有上传图片，进行图片的处理操作
            foreach ($image['name'] as $key => $value) {
                //如果图片名称不为空,并且没有出现过
                if(!empty($value) && !in_array($value,$imgName)){
                    $imgList[] = array(
                        'name'              =>  $image['name'][$key],
                        'type'              =>  $image['type'][$key],
                        'tmp_name'          =>  $image['tmp_name'][$key],
                        'error'             =>  $image['error'][$key],
                        'size'              =>  $image['size'][$key],
                        'goods_img_desc'    =>  $imageDesc[$key],
                    );
                    //将图片名称放到数组imgName中
                    $imgName[] = $value;
                }
            }
            //将拼接好的每个图片信息赋值给$_FILES常量
            $_FILES = $imgList;
            // 设置缩略图的样式 
            $thumb = array(
                array(50,50),
                array(350,350),
                array(700,700),
            );
            //循环$img数组，依次调用uploadOne()函数，进行图片上传、缩略图的制作
            foreach ($imgList as $key => $value) {
                if($value['error'] == 0){
                    $mess = uploadOne($key,'goods',$thumb);
                    //初始化插入数据
                    $input = array();
                    //判断商品logo是否上传成功
                    if($mess['status'] > -1){
                        if(!$mess['status']){
                            //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                            $this->error = $mess['error'];
                            return false;
                        }
                        //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
                        $input['goods_img_ori'] = IMG_PATH.$mess['image'][0];
                        $input['goods_img_small'] = IMG_PATH.$mess['image'][1];
                        $input['goods_img_medium'] = IMG_PATH.$mess['image'][2];
                        $input['goods_img_big'] = IMG_PATH.$mess['image'][3];
                    }
                    $input['goods_id'] = $goodsId;
                    $input['goods_img_desc'] = $value['goods_img_desc'];
                    $input['addtime'] = time();
                    $info = D('GoodsGallery')->add($input);
                    if($info === false){
                        //如果添加失败
                        $this->error = "商品相册图片信息插入失败！";
                    }
                }
            }
        }
    /*********************以下是处理商品的会员价格表中的数据*******************/
        //获取表单post提交的会员的商品的价格信息
        foreach (I('post.user_price') as $key => $value) {
            if(!empty($value)){
                $v = explode('-',$key);
                $info = D('UserPrice')->table('__USER_PRICE__')->field('goods_id,user_level,goods_price,last_update_time,addtime')->add(array(
                        'goods_id'           =>  $goodsId,
                        'user_level'         =>  $v[1],
                        'goods_price'        =>  $value,
                        'last_update_time'   =>  time(),
                        'addtime'            =>  time(),
                    )
                );
                if($info === false){
                    //如果插入失败
                    $this->error = "会员-商品价格插入失败！";
                    return false;
                    exit;
                }
            }
        }
    /*********************以下是处理商品的促销信息的数据*******************/
        //判断post表单中是否提交了促销信息
        if(I('post.is_promote')){
            //如果提交了商品促销信息,判断促销价格，促销开始-结束时间是否为空？
            if(empty(I('post.goods_promote_price'))){
                $this->error = "商品促销价格不能为空！";
                return false;
                exit;
            }
            if(empty(I('post.promote_start_time')) || empty(I('post.promote_end_time'))){
                $this->error = "商品促销时间不能为空！";
                return false;
                exit;
            }
            //获取商品促销信息的会员价格：前提是设置了会员促销价格折上折
            //判断是否设置了会员促销价格折上折
            if(I('post.user_is_discount') === "是"){
                //获取会员级别、折扣信息
                $userList = D('UserLevel')->field('level_id,level_discount')->select();
                foreach ($userList as $key => $value) {
                    $info = D('UserPrice')->table('__USER_PRICE__')->add(array(
                            'goods_id'             =>  $goodsId,
                            'user_level'           =>  $value['level_id'],
                            'goods_price'          =>  I('post.goods_promote_price')*$value['level_discount']/100,
                            'goods_promote'           =>  "是",
                            'addtime'              =>  time(),
                            'last_update_time'     =>  time(),
                        )
                    );
                    //判断是否修改成功
                    if($info === false){
                        //如果插入失败
                        $this->error = "商品-会员促销价格插入失败！";
                        return false;
                        exit;
                    }
                }
            }
        }

    /******************以下是处理商品的属性、库存、属性价格********************/
        //获取表单中提交的商品属性id和属性值，并且插入到商品-属性表中
        //首先判断唯一属性和可选属性中的商品类型是否一致
        $type_id = I('post.goods_type');
        $type_id1 = I('post.goods_type1');
        $type_id2 = I('post.goods_type2');
        if($type_id !== $type_id1 || $type_id !== $type_id2){
            $this->error = "通用信息、商品属性、商品规格下，商品类型不一致！";
            exit;
        }
        //方法一：处理商品的属性,商品库存和商品价格需要另外进行处理
        //如果类型一致,进行插入操作
        // $goodsAttr = I('post.attr_value');
        // echo "<pre>";
        // var_dump($goodsAttr);
        // exit;
        // //遍历属性值的列表，然后将数据添加到表单中
        // foreach ($goodsAttr as $key => $value) {
        // //首先判断当前属性是否是唯一属性
        //    foreach (array_unique($value) as  $k => $v) {
        //         if(!empty($v)){
        //             D('GoodsAttr')->table('__GOODS_ATTR__')->add(array(
        //                     'goods_id'      =>  $goodsId,
        //                     'attr_id'       =>  $key, 
        //                     'attr_value'    =>  $v, 
        //                     'type_id'       =>  $type_id,
        //                 )
        //             );
        //         }
        //     } 
        // }
        
        //方法二：处理商品的属性、同时可以处理商品的库存量和商品价格
        //首先获取商品的属性值,并将属性值插入到商品-属性goods_attr表中
        $goodsAttr = I('post.attr_value');
        $goodsStock = I('post.goods_stock');
        $goodsPrice = I('post.goods_price');
        //将获取到的属性组合进行拼接，插入到商品的库存goods_stock表中
        //定义两个属性值同时为空时的数组
        $index = array();
        foreach ($goodsStock as $key => $value) {
            $num = 0;
            foreach ($value as $k => $v) {
                if(empty($v) && empty($goodsPrice[$key][$k])){
                    $index[] = $num;
                }
                if(!empty($v) || !empty($goodsPrice[$key][$k])){
                    $info = D('GoodsStock')->table('__GOODS_STOCK__')->add(
                        array(
                            'goods_id' => $goodsId,
                            'attr_stock' => $v,
                            'attr_price' => $goodsPrice[$key][$k],
                            'attr_list' => rtrim($key,','),
                            'value_list' => rtrim($k,','),
                        ) 
                    );
                    if($info === false){
                        //如果插入失败
                        $this->error = "商品-库存-价格表数据插入失败！";
                        return false;
                        exit;
                    }
                }
                $num++;
            }
        }
        //遍历属性值的列表，然后将数据添加到表单中
        //需要用户手动删除无用的选项，否则会录入值为零的选项
        foreach ($goodsAttr as $key => $value) {
        //首先判断当前属性是否是唯一属性
            if(count($value) > 1){
                foreach ($index as $k1 => $v1) {
                    array_splice($value,$v1);
                }
            }
           foreach (array_unique($value) as  $k => $v) {
                if(!empty($v) && !in_array($v,$arr)){
                    $info = D('GoodsAttr')->table('__GOODS_ATTR__')->add(array(
                            'goods_id'      =>  $goodsId,
                            'attr_id'       =>  $key, 
                            'attr_value'    =>  $v, 
                            'type_id'       =>  $type_id,
                        )
                    );
                    if($info === false){
                        //如果插入失败
                        $this->error = "商品-属性表数据插入失败！";
                        return false;
                        exit;
                    }
                }
            } 
        }
    }
    //钩子函数：_after_update(),更新数据库之后执行
    protected function _after_update(&$data , $option){
        

    }
    //钩子函数：_after_delete(),删除数据库之后执行
    protected function _after_delete($option){
        

    }
 
/*------------------------------------------------------------------------------------*/
/*****************************以下是后台商品模块处理**********************************/
/*------------------------------------------------------------------------------------*/
    /**
     * 商品列表页面的搜索方法，包含：搜索、分页
     * 
     */
    public function ajaxSearch($search,$per = 10){
    //利用自定义的Ajax分页类实现Ajax无刷新搜索
        /*-----------------搜索search开始---------------------------*/
        //初始化搜索条件
        $where = array();
        //商品名称
        if($goodsName = $search["goods_name"]){
            //如果商品名称存在
            $where['a.goods_name'] = array('like' , "%$goodsName%");
        }
        //商品关键字
        if($Keywords = $search["goods_keywords"]){
            //如果商品名称存在
            $where['a.goods_keywords'] = array('like' , "%$Keywords%");
        }
        //商品分类
        if($catId = $search["goods_cat"]){
            //如果商品品牌存在
            //$where['a.goods_cat'] = array('eq',$gc);
            //调用search()方法，进行搜索
            $catList = $this->_search($catId);
            $where['a.goods_id'] = array('in',implode(',',$catList));
        }
        //商品品牌
        if($gb = $search["goods_brand"]){
            //如果商品品牌存在
            $where['a.goods_brand'] = array('eq',$gb);
        }
        //商品类型
        if($gt = $search["goods_type"]){
            //如果商品品牌存在
            $where['a.goods_type'] = array('eq',$gt);
        }
        //商品价格
        $fp = $search["goods_start_price"];
        $tp = $search["goods_end_price"];
        if($fp && $tp){
            //如果两个都存在
            $where['a.goods_shop_price'] = array('between' ,array($fp,$tp));
        }elseif($fp){
            //如果只能在fp
            $where['a.goods_shop_price'] = array('egt',$fp);
        }elseif($tp){
            $where['a.goods_shop_price'] = array('elt',$tp);
        }
        //是否上架
        if($isOnsale = $search["is_onsale"]){
            $where['a.is_onsale'] = array('eq',$isOnsale);
        }
        //是否推荐
        $isPromote = $search["is_recommend"];
        if($isPromote){
            $where['a.is_recommend'] = array('like' ,"%$isPromote%");
        }
        //是否精品
        if($isBest = $search["is_best"]){
            $where['a.is_best'] = array('eq' ,$isBest);
        }
        //是否新品
        if($isNew = $search["is_new"]){
            $where['a.is_new'] = array('eq' ,$isNew);
        }
        //是否热销
        if($isHot = $search["is_hot"]){
            $where['a.is_hot'] = array('eq' ,$isHot);
        }
        //是否加入回收站
        if($isDelete = $search["is_delete"]){
            $where['a.is_delete'] = array('eq' ,$isDelete);
        }
        //添加时间
        $fa = $search["start_addtime"];
        $ta = $search["end_addtime"];
        // 获取搜索开始时间
        if($fa){
            //如果搜索开始时间存在
            $ft = explode('-',$fa);
            $ftDayTime = explode(" ",$ft["2"]);
            $ftYear = $ft["0"];
            $ftMonth = $ft["1"];
            $ftDay = $ftDayTime["0"];
            $ftHour = explode(":",$ftDayTime["1"])["0"];
            $ftMinute = explode(":",$ftDayTime["1"])["1"]; 
        }
        //获取搜索结束时间
        if($ta){
            //如果搜索结束时间存在
            $tt = explode('-',$ta);
            $ttDayTime = explode(" ",$tt["2"]);
            $ttYear = $tt["0"];
            $ttMonth = $tt["1"];
            $ttDay = $ttDayTime["0"];
            $ttHour = explode(":",$ttDayTime["1"])["0"];
            $ttMinute = explode(":",$ttDayTime["1"])["1"];
        }
        //将搜索时间赋值到where搜索条件
        if($fa && $ta){
            $where['a.addtime'] =array('between',array(mktime($ftHour,$ftMinute,00,$ftMonth,$ftDay,$ftYear),mktime($ttHour,$ttMinute,59,$ttMonth,$ttDay,$ttYear)));
        }elseif($fa){
            $ft = explode('-',$fa);
            $where['a.addtime'] = array('egt' ,mktime($ftHour,$ftMinute,00,$ftMonth,$ftDay,$ftYear));
        }elseif($ta){
            $tt = explode('-',$ta);
            $where['a.addtime'] = array('elt' ,mktime($ttHour,$ttMinute,59,$ttMonth,$ttDay,$ttYear));
        }
    /*-----------------排序order开始-----------------------------*/
        //获取商品排序的字段
        $order = $search["search_sort_field"].' '.$search["search_sort_order"];
    /*----------------获取分页数据开始----------------------------*/
        // 获取满足要求的总记录数
        $count = D("Goods")->table('__GOODS__')->alias('a')->where($where)->count();
    /********方式一：利用ThinkPHP中的Page类和limit方法*****************/
        // 实例化分页类 传入总记录数和每页显示的记录数
        $page = new \Think\AjaxPage($count,$per);
        //获取满足条件的分页数据：where、order、page等条件
        $list = $this->table('__GOODS__')->alias('a')->field('a.*,b.brand_name as brand_name,c.cat_name as cat_name,GROUP_CONCAT(e.cat_name SEPARATOR "<br />") as ext_cat_name,f.type_name as type_name')->
                join('LEFT JOIN __BRAND__ b ON a.goods_brand = b.brand_id')->
                join('LEFT JOIN __CAT__ c ON a.goods_cat = c.cat_id')->
                join('LEFT JOIN __EXTEND_CAT__ d ON a.goods_id = d.goods_id')->
                join('LEFT JOIN __CAT__ e ON e.cat_id = d.ext_cat_id')->
                join('LEFT JOIN __TYPE__ f ON f.type_id = a.goods_type')->
                group('goods_id')->
                where($where)->order($order)->limit($page->firstRow.','.$page->listRows)->select();

    /*-----------------设置ThinkPHP中的分页的样式--------------------------------*/
        // $page->setConfig('prev', '【上一页】');
        // $page->setConfig('next', '【下一页】');
        // $page->setConfig('first', '【首页】');
        // $page->setConfig('last', '【末页】');        
        // $page->setConfig('theme', '共 %TOTAL_ROW% 条记录,当前是 %NOW_PAGE%/%TOTAL_PAGE% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page->lastSuffix=false;    //最后一页是否显示总页数
        $page->rollPage=3;          //分页栏每页显示的页数
        $pageString = $page->show();// 分页显示输出
    /*-----------------返回数据------------------------------------*/
        return array(
            'list'  =>  $list,
            'page'  =>  $pageString,
        );
    }

    /**
     * 定义商品分类搜索的方法，可以按主分类和扩展分类进行搜索
     * @return $glist 返回当前cat_id下的所有的子类id及其扩展分类id下的所有的goods_id
     */
    public function _search($id){
        //当id是主分类时，获取当前主分类id下的所有的子分类
        $catList = D('Cat')->getChildren($id);
        $catList[] = $id;
        //获取子孙分类id下的所有的商品的goods_id
        $where['goods_cat'] = array('in',implode(',',$catList));
        $goodsList = $this->table('__GOODS__')->field('goods_id')->where($where)->select();
        //当id是扩展分类时，获取当前扩展分类id下的所有的商品的goods_id
        $condition['ext_cat_id'] = array('eq',$id);
        $extGoodsList = D('ExtendCat')->table('__EXTEND_CAT__')->field('goods_id')->where($condition)->select();
        //将主分类中的goods_id和扩展分类中的goods_id进行合并
        if(isset($goodsList) && isset($extGoodsList)){
            //如果goodsList和extGoodsList都存在，进行数组的合并
            $list = array_merge($goodsList,$extGoodsList);
        }elseif(!isset($goodsList) ){
            //如果goodsList不存在
            $list = $extGoodsList;
        }else{
            //如果extGoodsList不存在
            $list = $goodsList;
        }
        //将的到的list二维数组转换为一维数组
        $glist = array();
        foreach ($list as $key => $value) {
            if(!in_array($value['goods_id'], $glist)){
                $glist[] = $value['goods_id'];
            }
        }
        //将得到的以为数组返回
        return $glist;
    }

    /**
     * ajax获取商品库存、价格的属性的展示效果
     * 
     */
    public function ajaxGoodsAttr($typeId){
        //根据商品的类型id，获取当前type_id下的所有的属性
        $attrList = D('Attr')->field('attr_id,attr_name,optional_list')->where(array('type_id' => array('eq',$typeId),'attr_unique' => array('eq',"可选属性")))->select();
        if($attrList != null){
            return $this->_goodsAttr($attrList,0,true);
        }
    }
    private function _goodsAttr($list,$k=0,$ini=false){
        static $array=array();
        static $sub = array();
        $count = count($list);
        $time = 1;
        if($ini){
            //如果初始化设置为真，则进行初始化操作,默认是首次进行初始化操作
            for($m=0;$m<$count;$m++){
                $sub[$m] = 0;
            } 
        }
        for($i=$k+1;$i<$count;$i++){
            //获取每一次循环的次数
            $time *=count(explode(',',$list[$i]["optional_list"]));
        }
        $arr = explode(',',$list[$k]["optional_list"]);
        foreach ($arr as $key => $value) {
            for($j=$sub[$k];$j<$sub[$k]+$time;$j++){
                $array[$j][] = $value;
            }
            //循环完成后，更改下标
            $sub[$k] = $sub[$k] + $time;
            if(isset($list[$k+1])){
                $this->_goodsAttr($list,$k+1);
            }
        }
        return $array;
    }

    /**
     * ajax获取商品的属性，并且拼接成html语句，在控制器中返回
     * @param $gid string 商品的goods_id
     * @param $tid string 商品的类型的type_id
     * @return $str string 返回的是拼接的html代码
     */
    public function ajaxGetSimpAttr($gid,$tid){
        //联表attr和goods_attr表查询，获取当前type_id和goods_id下的所有的属性和属性值
        $attrList = D("Attr")->table("__ATTR__")->alias('a')->field("a.attr_id,a.attr_name,a.type_id,a.attr_unique,a.input_method,a.optional_list,b.attr_value")->join(array("LEFT JOIN __GOODS_ATTR__ b ON a.attr_id = b.attr_id AND b.goods_id = $gid"))->where(array('a.type_id' => array('eq',$tid),'a.attr_unique' => array('eq',"唯一属性")))->select();
        //定义拼接html字符串
        $str = "";
        //遍历数组，拼接字符串数据
        foreach ($attrList as $key => $value) {
            $str .= '<tr><td class="attr">'.$value["attr_name"].'：</td><td><ul class="ulstyle"><li>';
            //判断属性值是否存在
            $val = $value["attr_value"] === null ? "" : $value["attr_value"];
            //判断属性值录入方式
            if($value['input_method'] === "1"){
                //如果属性值录入方式是手动录入
                $str .= '<input type="text" name="attr_value['.$value["attr_id"].'][]" value="'.$val.'" />';
            }
            if($value['input_method'] === "2"){
                //如果属性值录入方式是select下拉框选择
                $str .= '<select name="attr_value['.$value["attr_id"].'][]"><option value="">--请选择--</option>';
                foreach (explode(",",$value["optional_list"]) as $k1 => $v1) {
                    $str .= '<option value="'.$v1.'"';
                    if ($val === $v1) {
                        $str .= '  selected="selected"';
                    }
                    $str .= '>'.$v1.'</option>';
                }
                $str .= '</select>';
            }
            if($value["input_method"] === "3"){
                //如果属性值录入方式是多行文本框
                $str .= '<textarea cols="40" rows="3" name="attr_value['.$value["attr_id"].'][]">'.$val.'</textarea>';
            }
            $str .= '</li></ul></td></tr>'; 
        } 
        return $str;
    }
    /**
     * ajax获取商品的属性，并且拼接成html语句，在控制器中返回
     * @param $gid string 商品的goods_id
     * @param $tid string 商品的类型的type_id
     * @return $str string 返回的是拼接的html代码
     */
    public function ajaxGetSpecAttr($gid,$tid){
        //获取当前type_id下的所有可选属性的展示数组
        $specList = D("Goods")->ajaxGoodsAttr($tid);
        //获取当前goods_id下的所有属性组合的商品库存和商品价格
        $goodsStock = D("GoodsStock")->where(array('goods_id' => array('eq',$gid)))->select();
        //获取当前type_id下所有的可选属性的属性名
        $attrName = D("Attr")->field('attr_name,attr_id')->where(array('type_id' => $tid,'attr_unique' => "可选属性"))->select();
        //首先拼接属性类型名称，用于制作表头文件
        if(count($attrName) > 0){
            $str .= '<tr>';
            foreach ($attrName as $key => $value) {
                $str .= '<td align="center">'.$value["attr_name"].'</td>';
            }
            $str .= '<th>属性价格</th><th>商品库存</th><th>操作</th></tr>';
        }
        //处理商品的属性信息和库存、价格
        foreach ($specList as $key => $value) {
            $str .= '<tr>';
            $aList = "";
            $vList = "";
            foreach ($value as $k => $v) {
                //展示商品的可选属性值
                $aList .= $attrName[$k]["attr_id"].",";
                $vList .= $v.",";
                $str .= '<td align="center"><input type="hidden" name="attr_value['.$attrName[$k]["attr_id"].'][]" value="'.$v.'" />'.$v.'</td>';
            }
            //添加商品的库存和价格text文本框
            $stockVal = "";
            $priceVal = "";
            $attrId = "0";
            foreach ($goodsStock as $k2 => $v2) {
                if($v2["value_list"] === rtrim($vList,",")){
                    $stockVal = $v2["attr_stock"];
                    $priceVal = $v2["attr_price"];
                    $attrId = $v2["id"];
                }
            }
            $str .= '<td align="center"><input type="text" onpaste="this.value=this.value.replace(/[^\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\d.]/g,\'\')" class="attr_price" name="goods_price['.$aList.']['.$vList.']" value="'.$priceVal.'"/><td align="center"><input type="text" onpaste="this.value=this.value.replace(/[^\d.]/g,\'\')" onkeyup="this.value=this.value.replace(/[^\d.]/g,\'\')" class="attr_stock" name="goods_stock['.$aList.']['.$vList.']" value="'.$stockVal.'"/></td><td align="center"><a onclick="ajaxAddDelete(this,'.$attrId.');" href="javascript:void(0)" style="color:red;">[-]</a></td></tr>';
        }
        return $str;
    }

/*------------------------------------------------------------------------------------*/
/*****************************以下是前台商品模块处理**********************************/
/*------------------------------------------------------------------------------------*/
    /**
     * ajax获取商品的价格信息
     */
    public function ajaxGetGoodsPrice($data){
        //获取ajax传递来的数据
        $goodsId = $data["goods_id"];   //当前商品的goods_id
        $curAttrIdList = rtrim($data["cur_attr_id_list"],",");    //当前选中的属性attr_id的列表集合
        $curAttrValList = rtrim($data["cur_attr_val_list"],",");  //当前选中的属性attr_value的列表集合 
        //组装拼接当前选中的属性的数组
        $checkedAttrIdList = array();
        $arr = explode(',',$curAttrValList);
        $arr2 = explode(',',$curAttrIdList);
        for($i=0;$i<count($arr2);$i++){
            $checkedAttrIdList[$arr2[$i]] = $arr[$i];
        }
        //获取当前单击的属性的所有的可能属性组合
        $attrList = D("GoodsAttr")->field("id,attr_id,attr_value,goods_id")->where(array("goods_id" => array('eq',$goodsId),"attr_id" => array('in',$curAttrIdList)))->select();
        $goodsAttr = array();
        //将二维数组转换成为一维数组
        foreach ($attrList as $key => $value) {
            $goodsAttr[$value["attr_id"]][$value["id"]] = $value["attr_value"];
        }
        //获取当前可以被选中的所有的组合列表
        $flagArr = array();
        foreach ($goodsAttr as $key => $value) {
            foreach ($value as $k => $v) {
                $tempArr =  $checkedAttrIdList;
                $tempArr[$key] = $v;
                $flagArr[] = trim(implode(',',$tempArr),",");
            }
        }
        //获取商品属性-库存表中的商品属性组合
        $attrInfo = D("GoodsStock")->field("goods_id,value_list")->where(array('goods_id' => array('eq',$goodsId)))->select();
        $simpAttrList = array();
        foreach ($attrInfo as $key => $value) { //遍历数组，将二维数组转换成为一维数组
            $simpAttrList[] = $value["value_list"];
        }
        //遍历组装的属性数组，查找不再范围内的属性，返回属性的id值
        $showAttrList = array();
        $hideAttrList = array();
        $tempHideAttr = array();
        foreach ($flagArr as $key => $value) {
            if(in_array($value,$simpAttrList)){
                foreach (explode(',',$value) as $k => $v) {
                    $showAttrList[] = $v;
                }
            }else{
                $tempHideAttr[] = $value;
            }
        }
        foreach($tempHideAttr as $key => $value){
            foreach (explode(',',$value) as $k => $v) {
                if(!in_array($v,array_unique($showAttrList))){
                    foreach($goodsAttr as $k1 => $v1){
                        foreach ($v1 as $k2 => $v2) {
                            if($v === $v2){
                                $hideAttrList[] = $k2;
                            } 
                        }
                    }  
                }
            }
        }
        //获取该goods_id下的商品的价格信息
        $goodsInfo = $this->field("goods_shop_price,is_promote,goods_promote_price,promote_start_time,promote_end_time,user_is_discount")->find($goodsId);
        //商品本店价格
        $shop_price = (float)$goodsInfo["goods_shop_price"];
        //首先获取session中user的会员id，联表查询，获取该会员级别的会员折扣率
        $userLevel = session("user_level");
        if(isset($userLevel)){
            //如果用户已经登录
            $userDiscount = D("UserLevel")->find(session("user_level"));
            $discount = (float)$userDiscount['level_discount'];
            //获取当前goods_id和user_level下的商品的会员价格
            $userInfo = D("UserPrice")->where(array('goods_id' => array('eq',$goodsId),'user_level' => array('eq',$userLevel)))->find();
            $user_price = (float)$userInfo["goods_price"];
        }else{
            //如果用户未登录
            $user_price = $shop_price;
            $discount = 100;
        }
        //商品是否促销
        $isPromote = $goodsInfo["is_promote"];
        //商品的促销价格
        $promote_price = (float)$goodsInfo["goods_promote_price"];
        //促销开始时间
        $startTime = $goodsInfo["promote_start_time"];
        //促销结束时间
        $endTime = $goodsInfo["promote_end_time"];
        //会员价格是否享受折上折
        $userDiscount = $goodsInfo["user_is_discount"];
        //获取当前所选择的商品属性组合所对应的属性价格、属性库存量
        $attrStockList = D("GoodsStock")->where(array("goods_id" => array('eq',$goodsId),"value_list" => array('eq',$curAttrValList)))->find();
        $attrPrice = (float)$attrStockList["attr_price"];
        $attrStock = (float)$attrStockList["attr_stock"];
        // //判断该商品是否在促销时间内
        if($isPromote === "是" && time() > $startTime && time() < $endTime){
            //如果商品在促销期间内，则按促销价格计算
            if($userDiscount === "是"){
                //如果会员促销折上折
                $shopPrice = $shop_price + $attrPrice;
                $userPrice = $user_price + $attrPrice * $discount/100;
                $promotePrice = ($promote_price + $attrPrice) * $discount/100;
                $goodsPrice = min($shopPrice,$userPrice,$promotePrice);
            }else{
                //如果会员不享受促销折上折
                $shopPrice = $shop_price + $attrPrice;
                $userPrice = $user_price + $attrPrice * $discount/100;
                $promotePrice = $promote_price + $attrPrice * $discount/100; 
                $goodsPrice = min($shopPrice,$userPrice,$promotePrice);
            }
            //将商品的本店价格、会员价格、促销价格、购买价格返回
            return array(
                            "shop_price"    =>  $shopPrice,
                            "user_price"    =>  $userPrice,
                            "promote_price" =>  $promotePrice,
                            "goods_price"   =>  $goodsPrice,
                            "attr_stock"    =>  $attrStock,
                            "attrList"      =>  $hideAttrList,
                        );
        }else{
            //如果商品无促销，或者促销信息已经过期
            $shopPrice = $shop_price + $attrPrice;
            $userPrice = $user_price + $attrPrice * $discount/100; 
            $goodsPrice = min($shopPrice,$userPrice);
            //将商品的本店价格、会员价格、促销价格、购买价格返回
            return array(
                            "shop_price"    =>  $shopPrice,
                            "user_price"    =>  $userPrice,
                            "goods_price"   =>  $goodsPrice,
                            "attr_stock"    =>  $attrStock,
                            "attrList"      =>  $hideAttrList,
                        );
        }

    }


}
