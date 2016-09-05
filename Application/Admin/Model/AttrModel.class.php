<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-12 13:15:08
 */
namespace Admin\Model;

class AttrModel extends \Think\Model {
    //过滤插入字段
    protected $insertFields = array(
        'attr_name','type_id','is_query','attr_unique','input_method','optional_list','attr_image','attr_logo','attr_desc','addtime',
    );
    //过滤更新字段
    protected $updateFields = array(
       'attr_id','attr_name','type_id','is_query','attr_unique','input_method','optional_list','attr_image','attr_logo','attr_desc','last_update_time',
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
        array('attr_name','require','属性名不能为空！',1),
        array('type_id','require','所属分类不能为空！',1),
        array('is_query','require','检索方式必须选择！',1),
        array('attr_unique','require','属性值唯一性不能为空！',1),
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
        //设置生成logo缩略图的配置
        $thumb = array(
            array(50,50),
        ); 
        $mess = uploadOne('attr_image','attr',$thumb);
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中
            $data['attr_image'] = IMG_PATH.$mess['image'][0];
            $data['attr_logo'] = IMG_PATH.$mess['image'][1];
        }
        $data['addtime'] = time();
        $data['attr_desc'] = removeXss(I('post.attr_desc'));
        //判断属性录入方式
        if(I('post.input_method') === '2'){
            //如果属性录入方式为：从下面列表总选择
            $data['optional_list'] = removeXss(I('post.optional_list'));
        }else{
            $data['optional_list'] = '';
        }
    } 
    //钩子函数：_before_update(),更新数据库之前执行
    protected function _before_update(&$data , $option){
        //设置生成logo缩略图的配置
        $thumb = array(
            array(50,50),
        ); 
        $mess = uploadOne('attr_image','attr',$thumb);
        if($mess['status'] > -1){
            if(!$mess['status']){
                //如果返回的状态为0，表示创建失败，将错误信息赋给当前对象
                $this->error = $mess['error'];
                return false;
            }
            //如果返回的状态码为1，表示创建成功，将url地址写入数据库中，同时将原始的图片删除
            //获取修改的商品的属性id:attr_id值
            $attrId = $option['where']['attr_id'];
            $img = $this->field('attr_image,attr_logo')->find($attrId);
            deleteImg($img);
            $data['attr_image'] = IMG_PATH.$mess['image'][0];
            $data['attr_logo'] = IMG_PATH.$mess['image'][1];
        }
        $data['attr_desc'] = removeXss(I('post.attr_desc'));
        $data['last_update_time'] = time();
        //判断属性录入方式
        if(I('post.input_method') === '2'){
            //如果属性录入方式为：从下面列表总选择
            $data['optional_list'] = removeXss(I('post.optional_list'));
        }else{
            $data['optional_list'] = '';
        }
    } 
    //钩子函数：_before_delete(),删除数据库之前执行
    protected function _before_delete($option){
        //删除前，应该先判断，是否有商品的属性指向该属性
        if(false){
            //如果商品属性仍有商品对应
            $this->error = "当前属性仍有商品对应，请先移动商品属性到其他属性下！";
            return false;
        }
        //如果商品分类下不存在属性，就可以直接删除
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
     * 获取具体分类id下的所有的属性
     * 
     */
    public function getAttr($tId,$uId,$goodsId = ''){
        //判断uId和tId的值
        if($uId =='1'){
            $where = array(
                'attr_unique' => array('eq','唯一属性'),
                'type_id'   => array('eq',$tId),
                // 'goods_id'  => array('eq',$goodsId),
            );
            
        }elseif($uId == '2'){
            $where = array(
                'attr_unique' => array('eq','可选属性'),
                'type_id'   => array('eq',$tId),
                // 'goods_id'  => array('eq',$goodsId),
            );
        }else{
            $where = array(
                'attr_unique' => array('eq','可选属性'),
                'type_id'   => array('eq',''),
                // 'goods_id'  => array('eq',$goodsId),
            );
        }
        //将属性表attr和商品-属性表goods_attr进行联表查询
        return $this->field('attr_id,attr_name,attr_unique,optional_list,type_id')->where($where)->select();
    } 
}
