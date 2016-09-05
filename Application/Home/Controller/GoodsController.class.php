<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 10:24:50
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-03 15:12:45
 */

/******************这是前台首页商品的控制器******************************/

namespace Home\Controller;

class GoodsController extends \Home\Controller\BaseController {
    public function test(){

        echo "<pre>";
        // var_dump($info)
        // session(null);
        var_dump(session());
        exit;
    }
    public function index(){
        //获取首页促销商品列表
        $promoteList = D("Admin/Goods")->field('goods_id,goods_name,goods_promote_price,goods_thumb_medium')->where(array('is_onsale' => array('eq',"是"),'is_promote' => array('eq',"是"),'promote_start_time' => array('elt',time()),'promote_end_time' => array('egt',time())))->order("goods_sort_order asc")->limit(5)->select();

        //将数据赋值到模版中
        $this->assign(array(
            'pageConfig'    =>  array(
                'catConfig' =>  "0" ,    //顶部商品分类导航是否折叠，0：否   1：是

            ),
            'promoteList'   =>  $promoteList,
            'hotList'       =>  $this->getRecGoods('is_hot'),
            'bestList'      =>  $this->getRecGoods('is_best'),
            'newList'       =>  $this->getRecGoods('is_new'),
            'floorData'     =>  D("Admin/Cat")->getFloorData(), 
        ));
        $this->display('index');
    }
    /**
     * 商品详情页
     * @return [type] [description]
     */
    public function goods(){
        if(IS_GET){
            //如果是get请求,并且有传递goods_id
            $goodsId = I('get.id');
            if($goodsId){
                //获取当前goods_id下的信息
                $goodsInfo = D("Admin/Goods")->find($goodsId);
                //获取当前goods_id下的商品唯一属性
                $simpleAttr = D("Admin/GoodsAttr")->table("__GOODS_ATTR__")->alias("a")->field("b.attr_name,a.attr_value")->join(array(
                        "LEFT JOIN __ATTR__ b ON a.attr_id = b.attr_id"
                    ))->where(array('a.goods_id' => array('eq',$goodsId),'b.attr_unique' => array('eq',"唯一属性")))->select();
                //获取当前goods_id下的商品的可选属性
                $attrList = D("Admin/GoodsAttr")->table("__GOODS_ATTR__")->alias("a")->field("a.id,a.attr_id,b.attr_name,a.attr_value")->join(array(
                        "LEFT JOIN __ATTR__ b ON a.attr_id = b.attr_id"
                    ))->where(array('a.goods_id' => array('eq',$goodsId),'b.attr_unique' => array('eq',"可选属性")))->select();
                $multAttr = array();
                foreach ($attrList as $key => $value) {
                    $multAttr[$value["attr_id"]]["attr_name"] = $value["attr_name"];
                    $multAttr[$value["attr_id"]]["attr_value"][] = array($value["id"],$value["attr_value"]);
                }
                //获取当前goods_id下的商品的相册的详细信息
                $goodsGallery = D("Admin/GoodsGallery")->field("goods_img_ori,goods_img_small,goods_img_medium,goods_img_big")->where(array('goods_id' => array('eq',$goodsId)))->select();
                //获取商品的相关分类
                //思路一：获取当前商品的扩展分类所有的分类
                $relateCatRec = D("Admin/ExtendCat")->alias("a")->field("b.cat_id,b.cat_name")->join(array('LEFT JOIN __CAT__ b ON a.ext_cat_id = b.cat_id AND b.cat_id !='.$goodsInfo["goods_cat"]))->where(array('a.goods_id' => array('eq',$goodsId)))->select();
                //思路二：获取当前商品主分类下的所有的子分类
                
                //获取商品的同类品牌
                $brandCatList = D("Admin/Brand")->field("brand_id,brand_name,cat_id")->order("brand_sort")->limit(9)->select();
                $goodsCatBrand = array();
                foreach ($brandCatList as $key => $value) {
                    if(in_array($goodsInfo["goods_cat"],explode(',',$value["cat_id"]))){
                        $val = array(
                            "brand_id" => $value["brand_id"],
                            "brand_name" => $value["brand_name"],
                        );
                        $goodsCatBrand[] = $val;
                    }
                }
                //获取商品的热销推荐
                $hotGoodsRec = D("Admin/Goods")->field("goods_id,goods_name,goods_shop_price,goods_thumb_small")->where(array("goods_cat" => array("eq",$goodsInfo["goods_cat"]),"is_recommend" => array('eq',"是"),"is_hot" => array("eq","是")))->order("goods_sort_order")->limit(5)->select();
                //获取商品的精品推荐
                $bestGoodsRec = D("Admin/Goods")->field("goods_id,goods_name,goods_shop_price,goods_thumb_small")->where(array("goods_cat" => array("eq",$goodsInfo["goods_cat"]),"is_recommend" => array('eq',"是"),"is_best" => array("eq","是")))->order("goods_sort_order")->limit(5)->select();
                // echo "<pre>";
                // var_dump($relateCatRec);
                // // var_dump($bestGoodsRec);
                // exit;

                //将页面信息传递到模版
                $this->assign(array(
                'pageConfig'    =>  array(
                    'catConfig' =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是

                ),
                'goodsInfo'     =>  $goodsInfo,
                'breadNav'      =>  D("Admin/Cat")->breadNav($goodsInfo["goods_cat"]),
                'simpleAttr'    =>  $simpleAttr,
                'multAttr'      =>  $multAttr,
                'goodsGallery'  =>  $goodsGallery,
                'relateCatRec'  =>  $relateCatRec,
                'goodsCatBrand' =>  $goodsCatBrand,
                'hotGoodsRec'   =>  $hotGoodsRec,
                'bestGoodsRec'  =>  $bestGoodsRec,
            ));
            $this->display('goods'); 
            }
        }
    }
    public function goodsList(){
        //获取当前get请求的详细的url地址
        // $url = $_SERVER['REQUEST_URI'];
        // $url = $_SERVER['PHP_SELF'];
        //判断当前的缓存中是否有满足条件的缓存               
        $cacheKey = md5($_SERVER['REQUEST_URI']);
        if($searchData = S($cacheKey)){
            //如果存在缓存，直接从缓存中获取数据
            var_dump("缓存");
            $searchCondition        = $searchData["searchCondition"];
            $searchConditionList    = $searchData["searchConditionList"];
            $curSearchConditon      = $searchData["curSearchConditon"];
            $reqGoodsList           = $searchData["reqGoodsList"];
            $pageShow               = $searchData["pageShow"];
        }else{
        //如果没有缓存，先获取，然后存入缓存中
            //获取get请求提交的所有搜索条件的集合
            $searchConditionList = I('get.');   //当前的所有搜索条件集合            
            //去除get请求中的sort和sort_order字段
            $sortUrl = $searchConditionList;
            unset($sortUrl["sort"]);
            $searchConditionList["url"] = $this->filterUrl($sortUrl,"sort_order");
            $catId = I('get.cat_id');           //当前的商品分类cat_id
            $price = I('get.price');            //当前的商品价格price
            $brand = I('get.brand_id');         //当前的商品品牌brand_id
            $attrList = array();                //当前的商品的属性集合attrList
            $sort = I('get.sort') ? I('get.sort') : "goods_id"; //默认按照goods_id进行排序
            $sortOrder = I('get.sort_order') ? I('get.sort_order') : "DESC" ;  //默认按照降序排列
            //处理当前搜索条件，用于在模版中显示，供用户点击选择
            $curSearchConditon = array();
            foreach ($searchConditionList as $key => $value) {
                if($key !== "cat_id"){
                    if($key === "brand_id"){
                        //如果是品牌,获取当前品牌的属性值和过滤后的url地址
                        $attrVal = explode('_',$value);
                        $val["value"] = $attrVal["1"];
                        $val["url"] = $this->filterUrl($searchConditionList,"brand_id");
                        $curSearchConditon["品牌"] = $val;
                    }
                    if($key === "price"){
                        //如果是商品价格
                        $val["value"] = $value;
                        $val["url"] = $this->filterUrl($searchConditionList,"price");
                        $curSearchConditon["价格"] = $val;
                    }
                    if(substr($key,0,5) === "attr_"){
                        //如果是商品属性
                        $attrVal = explode('_',$value);
                        $val["value"] = $attrVal["1"];
                        $val["url"] = $this->filterUrl($searchConditionList,$key);
                        $curSearchConditon[$attrVal["0"]] = $val;
                        //处理get请求的属性信息，用户查找满足条件的商品goods_id
                        $attrList[substr($key,5)] = $attrVal["1"];
                    }
                }
            }
            //如果是按关键字搜索
            if($qkey = I('get.qkey')){
            //如果get请求有提交qkey搜索关键字，则进行关键字搜索
            //调用sphinix获取匹配的goods_id的数组集合
                // 引入sphinxapi.php文件
                require(COMMON_PATH."Common/Util/SphinxApi.php");
                //创建SphinxClient对应
                $sphinx = new \SphinxClient();
                //设置sphinx服务器数据
                $sphinx->SetServer('localhost',9312);
                //调用sphinx对象的query()方法，进行查询
                //说明：第一个参数是要查询的关键字，第二个参数是shpinx中创建的索引，*默认为所有的索引
                $qList = $sphinx->query($qkey,'goods');
                $reqGoodsIdList = array_keys($qList["matches"]);
            }
            //如果是按cat_id搜索
            if(I('get.cat_id')){
            //如果get请求没有提交qkey搜索关键字，但是有cat_id，则是进行cat_id进行搜索
                //满足cat_id条件的goods_id集合
                //判断当前的cat_id是否有缓存
                $catKey = md5("catGoodsSearch".$catId);
                if($catCache = S($catKey)){
                    $reqGoodsIdList = $catCache;
                }else{
                    $reqGoodsIdList = D("Admin/Cat")->_search($catId);
                    S($catKey,$reqGoodsIdList,array('type'=>'xcache','expire'=>86400));
                }
            }

            //获取当前的搜索条件下的满足条件的goods_id的集合  
            //首先处理满足价格要求的商品的goods_id
            if($brand){
                //如果商品品牌存在
                $goodsBrand = explode("_",$brand);
                $reqBrandGoodsList = D("Admin/Goods")->where(array('goods_brand' => array('eq',$goodsBrand["0"])))->getField("goods_id", true);
                $reqGoodsIdList = array_intersect($reqGoodsIdList, $reqBrandGoodsList);
            }
            //然后处理满足价格信息的商品goods_id
            if($price){
                $goodsPrice = explode("--",$price);
                $reqPriceGoodsList = D("Admin/Goods")->where(array('goods_shop_price' => array('between',array(trim($goodsPrice['0']," "),trim($goodsPrice['1']," ")))))->getField("goods_id", true);
                $reqGoodsIdList = array_intersect($reqGoodsIdList, $reqPriceGoodsList);
            }
            //然后处理满足商品属性信息的商品goods_id
            if($attrList){
                foreach ($attrList as $key => $value) {
                    $reqAttrGoodsList[] = D("Admin/GoodsAttr")->where(array('attr_id' => array('eq',$key),'attr_value' => array('eq',$value)))->getField("goods_id");
                }
                $reqGoodsIdList = array_intersect($reqGoodsIdList, $reqAttrGoodsList);
            }
            
            //获取满足当前条件的goods_id下所有商品的品牌、价格、属性信息，用于搜索页展示搜索条件
            $searchCondition = D("Admin/Cat")->getSearchConditionByGoodsId($reqGoodsIdList);
            // 实例化分页类 传入总记录数和每页显示的记录数
            $count = count($reqGoodsIdList);
            $per = 1;  //默认每页显示20条记录
            $page = new \Think\Page($count,$per);
            //处理满足条件的商品，并在搜索页下面的商品展示页面进行展示
            $reqGoodsList = D("Admin/Goods")->field("goods_id,goods_name,goods_shop_price,goods_thumb_small")->where(array('goods_id' => array('in',implode(',',$reqGoodsIdList))))->order($sort.'  '.$sortOrder)->limit($page->firstRow.','.$page->listRows)->select();
            //设置page分页的样式
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page->setConfig('first', '首页');
            $page->setConfig('last', '末页');        
            $page->setConfig('theme', '共【%TOTAL_ROW%】条记录，当前是：第【%NOW_PAGE%】页 / 共【%TOTAL_PAGE%】页 %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
            $page->lastSuffix=false;        //最后一页是否显示总页数
            $page->rollPage=3;              //分页栏每页显示的页数
            $pageShow = $page->show();      // 分页显示输出

            //将当前数据写入到缓存中
            $searchData = array(
                'searchCondition'     => $searchCondition,       //搜索条件，用户供用户点击选择
                'searchConditionList' => $searchConditionList,  //get请求提交的所有筛选条件，
                                                                //用于筛选过的不再显示在选择栏中
                'curSearchConditon'   => $curSearchConditon,    //当前搜索条件，用于显示用户选择的条件
                'reqGoodsList'        => $reqGoodsList,           //满足筛选条件的商品的信息
                'pageShow'            => $pageShow,               //page分页信息展示
            );
            var_dump("未缓存");
            S($cacheKey,$searchData,array('type'=>'xcache','expire'=>86400));
        }
        //将数据赋值给模版，在模版中进行显示
        $this->assign(array(
            'pageConfig'    =>  array(
                'catConfig'     =>  "1" ,    //顶部商品分类导航是否折叠，0：否   1：是
                
            ),
            'searchCondition'     => $searchData["searchCondition"],   //搜索条件，用户供用户点击选择
            'searchConditionList' => $searchData["searchConditionList"],//get请求提交的所有筛选条件，
                                                                        //用于筛选过的不再显示在选择栏中
            'curSearchConditon'   => $searchData["curSearchConditon"],  //当前搜索条件,显示已经搜索条件
            'reqGoodsList'        => $searchData["reqGoodsList"],       //满足筛选条件的商品的信息
            'pageShow'            => $searchData["pageShow"],           //page分页信息展示
        ));
        $this->display('goodsList');
        exit;
    }

    /**
     * 私有方法，用来处理当前当前搜索条件url(过滤掉自身参数)
     */
    private function filterUrl($url,$paramName){
        unset($url["url"]);
        unset($url[$paramName]);
        return urldecode(U("Home/Goods/goodsList",$url,FALSE));
    }

    /**
     * 私有方法，用来获取推荐商品：is_best、is_hot、is_new
     */
    private function getRecGoods($recType){
        return D("Admin/Goods")->field('goods_id,goods_name,goods_shop_price,goods_thumb_medium')->where(array('is_onsale' => array('eq',"是"),$recType => array('eq',"是")))->order("goods_sort_order asc")->limit(5)->select();
    }

/**********************************************************************************/
/*******************以下是前台ajax动态获取商品信息相关的方法**********************/
/*********************************************************************************/
    /**
     * 导航头部ajax实时获取导航栏的商品的浏览历史记录
     */
    public function ajaxGetNavHistory(){
        //获取用户浏览记录中的商品的信息
        if($userId = session("user_id")){
            //如果用户已经登录，直接从数据库中读取信息
            $userGoodsHistory = D("UserHistory")->where(array('user_id' => array('eq',$userId)))->order("goods_view_time DESC")->getField("goods_id",true);
        }else{
            //如果用户未登录，从cookie中获取用户的浏览历史记录
            $userGoodsHistory = cookie('goods_history');
        }

        $str = "";
        $flag = 0;
        foreach ($userGoodsHistory as $key => $value) {
            //导航栏只显示5条浏览记录
            if($flag > 3){
                break;
            }
            //获取该商品的商品信息
            $goodsHistory = D("Goods")->field('goods_id,goods_thumb_smallest')->where(array('is_onsale' => array('eq',"是"),))->find($value);
            //拼接输出html字符串
            $str .= '<li><a href="'.U("Home/Goods/goods",array("id" => $goodsHistory["goods_id"])).'"><img src="'.$goodsHistory["goods_thumb_smallest"].'" alt="" /></a></li>';
            $flag++;
        }
        exit($str);
    }

    /**
     * 商品详情页ajax获取商品的浏览历史记录
     */
    public function ajaxGetHistoryGoods(){
        //判断当前是商品详情页，还是商品搜索页
        if(I("get.optId") === "0"){
        //如果是商品详情页，需要将浏览历史记录写入到cookie中或者数据库中
            $goodsId = I('get.gid');
            //修改商品表中的商品浏览总数字段
            $info = D("Admin/Goods")->field("goods_view_amount")->where('goods_id = ' .$goodsId)->setInc('goods_view_amount');
            //判断用户当前是否登录
            if($userId = session("user_id")){
            //如果用户登录，浏览历史记录写入到数据库中
                //首先判断当前商品是否已经在数据库中
                $count = D("Admin/UserHistory")->where(array('user_id' => array('eq',$userId),'goods_id' => array('eq',$goodsId)))->count();
                if($count > 0){
                    //如果大于0，表示，数据库中已经存在，更新数据库中浏览信息
                    $info = D("Admin/UserHistory")->where(array('user_id' => array('eq',$userId),'goods_id' => array('eq',$goodsId)))->setField("goods_view_time",time());
                }else{
                    //如果数据库中不存在该数据，直接插入新数据
                    $info = D("Admin/UserHistory")->field("user_id,goods_id,goods_view_time")->add(array(
                            "user_id"           =>  $userId,
                            "goods_id"          =>  $goodsId,
                            "goods_view_time"   =>  time(),
                        ));
                }
            }else{
                //如果用户未登录，浏览历史记录写入到cookie中
                $goodsIdHistory = cookie('goods_history');
                //判断cookie中是否有商品浏览历史记录
                if(empty($goodsIdHistory)){
                    //如果cookie中不存在用户浏览的商品的记录
                    $goodsIdHistory = array();
                    $goodsIdHistory[] = $goodsId;
                }else{
                    //如果cookie中存在用户浏览的商品的记录
                    if(count($goodsIdHistory) >99){
                        //如果用户浏览记录大于100，清除最后一条记录
                        array_pop($goodsIdHistory);
                    }
                    array_unshift($goodsIdHistory,$goodsId);
                }
                // 将该商品的goods_id存入用户cookie中，并设置一个月的有效期
                cookie('goods_history',array_unique($goodsIdHistory),2592000);                
            }
        }
        
        $str = "";
        //判断是清除操作，还是清空操作
        if(I('get.oid') == 0){
            //如果是获取操作
            //获取用户浏览记录中的商品的信息
            if($userId = session("user_id")){
                //如果用户已经登录，直接从数据库中读取信息
                //首先删除一个月之前的记录，只保留一个月的浏览历史记录
                $info = D("Admin/UserHistory")->where(array('user_id' => array('eq',$userId),'goods_view_time' => array('lt',time()-30*24*3600)))->delete();
                //然后获取满足条件的商品的信息
                $userGoodsHistory = D("Admin/UserHistory")->where(array('user_id' => array('eq',$userId)))->order("goods_view_time DESC")->getField("goods_id",true);
            }else{
                //如果用户未登录，从cookie中获取用户的浏览历史记录
                $userGoodsHistory = cookie('goods_history');
            }

            //定义输出浏览历史记录的个数
            $start = 0;
            $end = I('get.times') ? count($userGoodsHistory) : 7;   //如果是用户浏览历史记录，显示全部
            foreach ($userGoodsHistory as $key => $value) {
                if($start > $end){
                    break;
                }
                //获取该商品的商品信息
                $goodsHistory = D("Admin/Goods")->field('goods_id,goods_name,goods_shop_price,goods_thumb_small')->where(array('is_onsale' => array('eq',"是"),))->find($value);
                //拼接输出html字符串
                $str .= '<dl><dt><a href="'.U("Home/Goods/goods",array("id" => $goodsHistory["goods_id"])).'"><img src="'.$goodsHistory["goods_thumb_small"].'" alt="" /></a></dt><dd class="hide_name"><a href="'.U("Home/Goods/goods",array("id" => $goodsHistory["goods_id"])).'">'.$goodsHistory["goods_name"].'</a></dd></dl>';
                //每循环完成一次，自增1
                $start++ ;
            }
        }
        if(I('get.oid') == 1){
            //如果是清空操作
            if($userId = session("user_id")){
                $info = D("Admin/UserHistory")->where(array('user_id' => array('eq',$userId)))->delete();
            }else{
                cookie("goods_history",null);
            }   
        }
        exit($str);
    }


    /**
     * 商品详情页ajax实时获取商品的价格
     */
    public function ajaxGetGoodsPrice(){
        //获取传递来的商品信息
        echo json_encode(D("Admin/Goods")->ajaxGetGoodsPrice(I("post.")));
    }



}
