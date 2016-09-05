<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-22 10:56:00
 */
namespace Admin\Model;

class BrandModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'brand_name','brand_image','brand_logo','brand_site_url','brand_desc','is_show','is_promote','brand_sort','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
        'brand_id','brand_name','brand_image','brand_logo','brand_site_url','brand_desc','is_show','is_promote','brand_sort','last_update_time',
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
        array('brand_name','require','品牌名不能为空！',1),
        array('brand_site_url','url','请输入正确的网址！',2), 
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
        //生成商品品牌的缩略图，用于前台首页的商品品牌推荐
        $thumb = array(
            array(98,35),
        ); 
        $mess = uploadOne('brand_image','brand',$thumb);
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
            $data['brand_image'] = IMG_PATH.$mess['image'][0];
            $data['brand_logo'] = IMG_PATH.$mess['image'][1];
        }
        //获取表单中提交的商品的分类
        foreach (array_unique(I('post.goods_cat')) as $key => $value) {
            if(!empty($value)){
                //如果不是空
                $catList .= $value.",";
            }
        }
        $data['cat_id'] = rtrim($catList,',');
        $data['addtime'] = time();
        $data['brand_desc'] = removeXss($_POST['brand_desc']);
    } 
    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
        //设置生成logo缩略图的配置
        $thumb = array(
            //生成商品品牌的缩略图，用于前台首页的商品品牌推荐
            array(98,35),
        ); 
        $mess = uploadOne('brand_image','brand',$thumb);
        if($mess['status'] > -1){
            //如果状态相应码大于-1，表示有进行图片上传操作
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //获取当前品牌的id值
            $brandId = $option['where']['brand_id'];
            $brandImg = $this->field('brand_image,brand_logo')->find($brandId);
            //删除之前的旧图片
            deleteImg($brandImg);
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
            $data['brand_image'] = IMG_PATH.$mess['image'][0];
            $data['brand_logo'] = IMG_PATH.$mess['image'][1];
        }
        //获取表单中提交的商品的分类
        foreach (array_unique(I('post.goods_cat')) as $key => $value) {
            if(!empty($value)){
                //如果不是空
                $catList .= $value.",";
            }
        }
        $data['cat_id'] = rtrim($catList,',');
        $data['brand_desc'] = removeXss($_POST['brand_desc']);
        $data['last_update_time'] = time();
    } 
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete($option){
        //删除前，先获取要删除的图片的brand_id值
        $brandId = $option['where']['brand_id'];
        $brandImg = $this->field('brand_image,brand_logo')->find($brandId);
        //删除之前的旧图片
        deleteImg($brandImg);
    } 
    //钩子函数：_after_insert(),插入数据库之后执行
    protected function _after_insert(&$data , $option){
        

    }
    //钩子函数：_after_update(),更新数据库之后执行
    protected function _after_update(&$data , $option){
        

    }
    //钩子函数：_after_delete(),删除数据库之后执行
    protected function _after_delete($option){
        

    }

    /**
     * 获取前台的商品分类楼层的商品品牌展示的数据
     */
    public function getFloorBrand($catList){
        //获取所有的品牌的所对应的cat_id
        $brandCat = $this->field("brand_id,brand_name,brand_logo,cat_id")->where(array('is_recommend' => array('eq',"是")))->select();
        //定义商品品牌推荐的数组
        $recBrand = array();
        //遍历所有的商品品牌的列表
        foreach ($brandCat as $key => $value) {
            $flag = 0;
            foreach (explode(",",$value["cat_id"]) as $k => $v) {
                if(in_array($v,$catList) && $flag < 9){
                    //只要该品牌下的所属的分类id，有一个在catList内，就在首页展示
                    $recBrand[] = array($value["brand_id"],$value["brand_name"],$value["brand_logo"]);
                    $flag++;
                }
            }
        }
        return $recBrand;
    }
    
}
