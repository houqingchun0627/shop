<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-05 13:22:04
 */
namespace Admin\Model;

class CatModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'cat_name','parent_id','cat_path','cat_level','cat_sort','cat_group','cat_desc','cat_keywords','is_navbar','is_show','is_recommend','cat_image','cat_logo','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
        'cat_id','cat_name','parent_id','cat_path','cat_level','cat_sort','cat_group','cat_desc','cat_keywords','is_navbar','is_show','is_recommend','cat_image','cat_logo','last_update_time',
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
        array('cat_name','require','商品分类名称不能为空！',1),
        array('parent_id','require','上级分类必须选择！',1),
        array('cat_sort','require','商品分类不能为空！',1),
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
            // array(150,150),
            array(350,350),
        ); 
        $mess = uploadOne('cat_image','cat',$thumb);
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
            $data['cat_image'] = IMG_PATH.$mess['image'][0];
            $data['cat_logo'] = IMG_PATH.$mess['image'][1];
        }
        $data['addtime'] = time();
        $data['cat_keywords'] = removeXss($_POST['cat_keywords']);
        $data['cat_desc'] = removeXss($_POST['cat_desc']);
        //获取用户选择的parent_id值
        // $parentId = $data['parent_id'];
    //获取cat_level方法一：通过parent_id来生成
        // $level = $this->field('cat_level')->find($parentId);
        // $data['cat_level'] =  isset($level) ? (int)$level['cat_level']+1 : 0;
    }
    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
        // 设置缩略图的样式 
        $thumb = array(
            // array(150,150),
            array(350,350),
        ); 
        $mess = uploadOne('cat_image','cat',$thumb);
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中，同时将原始的图片删除
            //获取修改的商品的属性id:cat_id值
            $catId = $option['where']['cat_id'];
            $img = $this->field('cat_image,cat_logo')->find($catId);
            deleteImg($img);
            $data['cat_image'] = IMG_PATH.$mess['image'][0];
            $data['cat_logo'] = IMG_PATH.$mess['image'][1];
        }
        $data['cat_keywords'] = I('post.cat_keywords');
        $data['cat_desc'] = removeXss($_POST['cat_desc']);
        $data['last_update_time'] = time();
        // 获取表单中的cat_id值
        $catId = $option['where']['cat_id'];
        //获取用户选择的parent_id值
        $parentId = $data['parent_id'];
        $data['cat_level'] = $this->getLevel($catId);
        $data['cat_path']= $this->getParent($catId);
        //首先判断当前分类是否进行修改操作
        $model = new \Think\Model();
        $where['cat_id'] = $catId;
        $oriParentId = $model->table('__CAT__')->field('parent_id')->where($where)->find();
        // 判断要操作的分类下是否要子分类
        if($this->getChildren($catId) && ($oriParentId['parent_id'] != $parentId)){
            //如果当前分类下存在子分类,并且原始的parent_id与表单提交的parent_id不同时
            if(empty($_POST['remove_child'])){
                //如果没有提交remove_child字段
                $this->error = "该分类下还存在子分类，请先处理子分类！！！";
                return false;
            }
            //如果有提交remove_child字段，表示将所有子类一起移动到指定的分类下
            //更新所有子类的信息
            // $childList = $this->getChildren($catId);    
        }
    } 
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete1($option){
        //首先获取要删除的cat_id
        $catId = $option['where']['cat_id'];
        //然后后去该id下的所有的子孙id的集合
        $list = $this->getChildren($catId);
        $catList = implode(',',$list);
        if($catList){
            $model = D('Goods');
            //说明：此方法有问题：因为model基础控制器中的主键默认是id,而这里设的是cat_id
            $map['cat_id'] = array('in',$catList);
            $model->table('__CAT__')->where($map)->delete();
        }
        //处理当前分类下的子分类
        if($this->getChildren($catId)){
            //如果当前分类下存在子分类
            if(empty($_POST['remove_child'])){
                //如果没有提交remove_child字段
                $this->error = "该分类下还存在子分类，请先删除子分类！！！";
                return false;
            }
            //如果有提交remove_child字段，表示将所有子类一起移动到指定的分类下
        }
    }
    //钩子函数2：_before_delete():拼接$option
    protected function _before_delete(&$option){
        //首先获取要删除的cat_id
        $catId = $option['where']['cat_id'];
        //然后去该id下的所有的子孙id的集合
        $list = $this->getChildren($catId);
        $list[] = $catId;
        //拼接option
        $option['where']['cat_id'] = array(
            0 =>  "IN",
            1 =>  implode(',' , $list),
        );
        //处理当前分类下的子分类
        if($this->getChildren($catId)){
            //如果当前分类下存在子分类
            if(empty($_POST['remove_child'])){
                //如果没有提交remove_child字段
                $this->error = "该分类下还存在子分类，请先删除子分类！！！";
                return false;
            }
            //如果有提交remove_child字段，表示将所有子类一起移动到指定的分类下
        }
    }

    //钩子函数：_after_insert(),插入数据库之后执行
    protected function _after_insert(&$data , $option){
        //如果插入成功后，通过返回信息，获取cat_id值
        $catId = $data['cat_id'];
        if(!empty($catId)){
        //获取cat_level方法二：通过cat_id，调用getLevel()方法获取
            $where['cat_id'] = $catId;
            $arr = array(
                'cat_level' =>  $this->getLevel($catId),
                'cat_path'  =>  $this->getParent($catId),
            );
            //创建模型类对象，并更新字段值
            $model = new \Think\Model();
            $info = $model->table('__CAT__')->field('cat_level,cat_path')->where($where)->save($arr);
        }
    }
    //钩子函数：_after_update(),更新数据库之后执行
    protected function _after_update(&$data , $option){
        //更新完成后，同步更新所有的子类的信息
        $catId = $data['cat_id'];
        $childList = $this->getChildren($catId);
        $where['cat_id'] = $catId;
        $arr = array(
            'cat_level' =>  $this->getLevel($catId),
            'cat_path'  =>  $this->getParent($catId),
        );
        $model = new \Think\Model();
        $info = $model->table('__CAT__')->field('cat_level,cat_path')->where($where)->save($arr);
        foreach ($childList as $key => $value) {
            //遍历子类集合，依次更新到数据库
            $where['cat_id'] = $value;
            $arr = array(
                'cat_level' =>  $this->getLevel($value),
                'cat_path'  =>  $this->getParent($value),
            );
            //创建模型类对象，并更新字段值
            $info = $model->table('__CAT__')->field('cat_level,cat_path')->where($where)->save($arr);
            if($info === false){
                $this->error = "商品分类信息更新失败！";
                return false;
                exit;
            }
        }
    }
    //钩子函数：_after_delete(),删除数据库之后执行
    protected function _after_delete($option){
        

    }
    /**
     * 商品分类的树状展示getLevel()
     * 
     */
    public function getLevel($id){
        //获取该模型下的所有列表
        $data = $this->getTree();
        //遍历数组，获取该id的level值并返回
        foreach ($data as $key => $value) {
            if($value['cat_id'] == $id){
                return (int)$value['level'];
            }
        }
        return 0;
    }

    /**
     * 商品分类的树状展示getTree()
     * @param $catLevel 要获取到最大层级的cat
     */
    public function getTree($catLevel = 0){
        //获取该模型下的所有列表
        $list = $this->select();
        //调用_tree()方法，获取树状结构，并返回
        return $this->_tree($list,0,0,true,$catLevel);
    }

    private function _tree($list,$parentId = 0,$level = 0,$option = false,$catLevel = 0){
        //创建一个静态数组，用来存放所有的子孙的id
        static $tree = array();
        if($option){
            //如果选择初始化操作，就对静态数组进行初始化操作
            $tree = array();
        }
        //遍历list数组，对其中的元素进行排序
        foreach ($list as $key => $value) {
            //如果没有设置要获取的cat级别
            if($value['parent_id'] == $parentId && $catLevel === 0){
                $value['level'] = $level;   //将该id所处的级别存入tree中
                $tree[] = $value;
                //递归调用_tree()方法，获取该id下的所有的子孙id
                $this->_tree($list,$value['cat_id'],$level+1);
            }
            // 如果设置了要获取到的cat的级别
            if($value['parent_id'] == $parentId && $catLevel !== 0 && $level < $catLevel){
                $value['level'] = $level;   //将该id所处的级别存入tree中
                $tree[] = $value;
                //递归调用_tree()方法，获取该id下的所有的子孙id
                $this->_tree($list,$value['cat_id'],$level+1,false,$catLevel);
            }
        }
        //将获取的树状结构返回
        return $tree;
    }

    /**
     * 获取指定分类下的所有的子孙分类
     * 
     */
    public function getChildren($id){
        //获取商品分类的所有列表
        $list = $this->select();
        //调用_children()方法，获取所有的子类组成的数组,并返回
        return $this->_children($list,$id,true);
    }
    private function _children($list,$id,$option = false){
        //创建一个静态数组，用来存放该分类下的子孙的id
        static $child = array();
        //判断是否选择了初始化操作
        if($option){
            //如果选择初始化操作，就对静态数组进行初始化操作
            $child = array();
        }
        //遍历所有的分类列表
        foreach ($list as $key => $value) {
            if($value['parent_id'] == $id){
                //如果分类列表中的id与要搜索的分类id相等
                $child[] = $value['cat_id'];
                $this->_children($list,$value['cat_id']);
            }
        }
        //将获得的id返回
        return $child;
    }

    /**
     * 获取指定id的上级的id,并返该id的完整的路径，相邻id之间用下划线_连接
     * 
     */
    public function getParent($id){
        //获取商品分类的所有列表
        $list = $this->select();
        //调用_children()方法，获取所有的子类组成的数组,并返回
        $parent = $this->_parent($list,$id,true);
        $parent[] = '0';
        //返回数组形式
        // return array_reverse($parent);
        //返回字符串链接
        return implode('_',array_reverse($parent));
    }
    private function _parent($list,$id,$option = false){
        //创建一个静态数组，用来存放该分类下的子孙的id
        static $parent = array();
        //判断是否选择了初始化操作
        if($option){
            //如果选择初始化操作，就对静态数组进行初始化操作
            $parent = array();
        }
        $parentId = $this->table('__CAT__')->field('parent_id')->find($id);
        //遍历所有的分类列表
        foreach ($list as $key => $value) {
            if($value['cat_id'] == $parentId['parent_id']){
                //如果分类列表中的id与要搜索的分类id的上级id[parent_id]相等
                // $parent[] = $value['cat_id'];
                $parent[$value['cat_name']] = $value['cat_id'];
                $this->_parent($list,$value['cat_id']);
            }
        }
        return $parent;
    }

    public function _search($id){
        //当id是主分类时，获取当前主分类id下的所有的子分类
        $catList = $this->getChildren($id);
        $catList[] = $id;
        //获取子孙分类id下的所有的商品的goods_id
        $where['goods_cat'] = array('in',implode(',',$catList));
        $goodsList = D("goods")->table('__GOODS__')->field('goods_id')->where($where)->select();
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
/*------------------------------------------------------------------------------------*/
/*****************************以下是前台商品分类模块处理**********************************/
/*------------------------------------------------------------------------------------*/
    /**
     * 前台首页获取商品的三级分类并展示
     */
    public function getCatList(){
        //首先判断静态缓存中有没有
        if($cache = S("threeLevelCat")){
            //如果有静态缓存，直接读取缓存值
            return $cache;
        }else{
            //如果没有静态缓存，设置静态缓存
            //获取所有的商品分类
            $list = $this->field("cat_id,cat_name,parent_id")->select();
            $catList = array();
            //遍历循环商品的分类，获取各个级别的商品的分类
            foreach ($list as $k => $v) {
                if($v["parent_id"] === "0"){
                    //如果是顶级分类
                    foreach ($list as $k1 => $v1) {
                        if($v1["parent_id"] === $v["cat_id"]){
                            //如果是二级分类
                            foreach($list as $k2 => $v2){
                                //如果是三级分类
                                if($v2["parent_id"] === $v1["cat_id"]){
                                    $v1["children"][] = $v2;
                                }
                            }
                            $v["children"][] = $v1;
                        }
                    }
                    $catList[] = $v;
                }
            }
            //将缓存写入到静态缓存中，并设置有效期为一天
            S('threeLevelCat',$catList,array('type'=>'xcache','expire'=>86400));
            return $catList;
        }
    }

    /**
     * 前台首页获取楼层商品分类的推荐数据
     */
    public function getFloorData(){
        //首先判断静态缓存中有没有
        if($cache = S("floorCatData")){
            //如果有静态缓存，直接读取缓存值
            return $cache;
        }else{
            //如果没有静态缓存，设置静态缓存
            //获取所有的商品分类
            $list = $this->field("cat_id,cat_name,parent_id,is_recommend,cat_logo")->select();
            $catList = array();
            //遍历循环商品的分类，获取各个级别的商品的分类
            foreach ($list as $k => $v) {
                if($v["parent_id"] === "0" && $v["is_recommend"] === "是"){
                    //如果是顶级分类
                    $index = 0;
                    foreach ($list as $k1 => $v1) {
                        if($v1["parent_id"] === $v["cat_id"]){
                            if($v1["is_recommend"] == "是" ){
                                if($index <5){
                                    //获取当前cat_id下的所有子类的商品
                                    $goodsList = $this->_search($v["cat_id"]);
                                    $v1["rec_goods"] = D("Goods")->field('goods_id,goods_name,goods_shop_price,goods_thumb_medium')->where(array('goods_id' => array('in',implode(',',$goodsList)),'is_recommend' => array('eq',"是"),'is_onsale' => array('eq',"是")))->limit(8)->select();
                                    //如果是二级分类，并且是楼层推荐的分类
                                    $v["rec_cat"][] = $v1;
                                    $index++;
                                }
                            }else{
                                //如果是二级分类，但是不是楼层推荐的分类
                                $v["unrec_cat"][] = $v1;
                            }   
                        }
                        //获取当前顶级分类的推荐的商品品牌
                        $childCatList= $this->getChildren($catId);
                        $childCatList[] = $v["cat_id"];
                        $brand = new \Admin\Model\BrandModel();
                        $v["rec_brand"]  = $brand->getFloorBrand($childCatList);
                    }
                    $catList[] = $v;
                }
            }
            //将缓存写入到静态缓存中，并设置有效期为一天
            S('floorCatData',$catList,array('type'=>'xcache','expire'=>86400));
            // var_dump($catList);
            // exit;
            return $catList;
        }
    }
    /**
     * 前台商品页面的面包屑导航
     */
    public function breadNav($catId){
        //获取商品分类的所有列表
        if($breadNav = S("breadNav-".$catId)){
            //判断该面包屑导航信息是否已经缓存
            return $breadNav;
        }else{
            //如果不存在，获取该cat_id下的面包屑导航信息，并存入缓存
            $list = $this->select();
            $catIdInfo = $this->field("cat_id,cat_name")->find($catId);
            //调用_parent()方法，获取所有的子类组成的数组,并返回
            $catList = $this->_parent($list,$catId,true);
            $catList = array_reverse($catList);
            $catList[$catIdInfo["cat_name"]] = $catIdInfo["cat_id"];
            //将缓存写入到静态缓存中，并设置有效期为一天
            S("breadNav-".$catId,$catList,array('type'=>'xcache','expire'=>86400));
            return $catList;
        }
    }
    
    /**
     * 通过cat_id和price获取商品的搜索条件
     */
    public function getSearchConditionByGoodsId($gList,$priceSection = 8){
        //联表查询，获取当前cat_id下的商品的所有的品牌信息
        $searchBrand = D("Goods")->alias("a")->field("DISTINCT b.brand_id,b.brand_name")->join(array(
            "LEFT JOIN __BRAND__ b ON a.goods_brand = b.brand_id "
        ))->where(array("a.goods_id" => array('in',implode(',',$gList))))->select();
        //获取当前商品的价格信息
        $goodsPrice = D("Goods")->field("MAX(goods_shop_price) max_price,MIN(goods_shop_price) min_price")->where(array("goods_id" => array('in',implode(',',$gList))))->find();
        //处理商品的价格分段展示,默认按8段进行分割
        $perPrice = ceil(($goodsPrice["max_price"] - $goodsPrice["min_price"])/($priceSection - 1));
        $searchPrice = array();
        $searchPrice[] = "0--".(int)$goodsPrice["min_price"];
        $start = $goodsPrice["min_price"] + 1;
        for($i=0;$i<$priceSection-2;$i++){
            $end = $start + $perPrice;
            $searchPrice[] = $start."--".$end;
            $start = $end + 1;
        }
        $searchPrice[] = $start."--".(int)$goodsPrice["max_price"];
        //处理商品的属性：可选属性和唯一属性
        $searchAttr = D("GoodsAttr")->alias("a")->field("DISTINCT a.attr_id,a.id,b.attr_name,GROUP_CONCAT(DISTINCT a.attr_value) attr_value")->join(array(
            "LEFT JOIN __ATTR__ b ON a.attr_id = b.attr_id "
        ))->where(array('a.goods_id' => array("in",implode(',',$gList))))->group("b.attr_id")->order("b.attr_unique desc")->select();
        //将满足条件的商品的品牌、价格、属性以数组的形式返回
        return array(
                        "brand"    =>   $searchBrand,
                        "price"    =>   $searchPrice,
                        "attr"     =>   $searchAttr,
                    );
    }
}
