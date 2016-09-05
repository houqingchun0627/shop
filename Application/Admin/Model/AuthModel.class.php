<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 13:31:46
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-12 13:02:04
 */
namespace Admin\Model;

class AuthModel extends \Think\Model {

    /**
     * 过滤插入字段
     */
    protected $insertFields = array(
        'auth_name','auth_pid','is_show','auth_controller','auth_action','auth_path','auth_level','auth_desc','addtime',
    );

    /**
     * 过滤更新字段
     */
    protected $updateFields = array(
        'auth_id','auth_name','auth_pid','is_show','auth_controller','auth_action','auth_path','auth_level','auth_desc','last_update_time',
    );
    
    /**
     * 自动验证定义
     */
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
        array('auth_name','require','权限名称不能为空！',1),
    ); 
    
    /**
     * 自动完成定义
     */ 
    protected $_auto     =   array(
       

    );

    /**
     * 字段映射定义
     */ 
    protected $_map      =   array(
        
        
    ); 

    /**
     * 命名范围定义
     */ 
    protected $_scope    =   array(
        

    );
    
    /**
     * 钩子函数：_before_insert(),插入数据库之前执行
     * 
     */
    protected function _before_insert(&$data , $option){     
        //首先获取用户输入的数据
        $authPid = $data['auth_pid'];
        $authController = $data['auth_controller'];
        $authAction = $data['auth_action'];
        //判断是否是顶级权限
        if(empty($authPid)){
            if((!empty($authController)) || (!empty($authAction))){
                //如果是顶级权限，并且控制器或者方法不为空
                $this->error = "顶级权限控制器和方法应为空！";
                return false;
                exit;
            }
        }else{
            if((empty($authController)) || (empty($authAction))){
                //如果不是顶级权限，并且控制器或者方法为空
                $this->error = "权限控制器和方法不能为空！";
                return false;
                exit;
            }
        }
        //将用户输入的信息进行拼接处理
        $data['auth_controller'] = ucfirst(I('post.auth_controller'));   //将控制器的首字母转换成大写
        // 权限全路径auth_path、权限层级auth_level在插入后，获取auth_id,然后执行更新操作
        $data['auth_desc'] = removeXss(I('post.auth_desc'));
        $data['addtime'] = time();
    } 
    
    /**
     * 钩子函数：_before_update(),更新数据库之前执行
     * 
     */
    protected function _before_update(&$data , $option){
        //首先获取用户输入的数据
        $authId = $option['where']['auth_id'];
        $authPid = $data['auth_pid'];
        $authController = $data['auth_controller'];
        $authAction = $data['auth_action'];
        //判断是否是顶级权限
        if(empty($authPid)){
            if((!empty($authController)) || (!empty($authAction))){
                //如果是顶级权限，并且控制器或者方法不为空
                $this->error = "顶级权限控制器和方法应为空！";
                return false;
                exit;
            }
        }else{
            if((empty($authController)) || (empty($authAction))){
                //如果不是顶级权限，并且控制器或者方法为空
                $this->error = "权限控制器和方法不能为空！";
                return false;
                exit;
            }
        }
        //将用户输入的信息进行拼接处理
        $data['auth_controller'] = ucfirst(I('post.auth_controller'));   //将控制器的首字母转换成大写
        // 权限全路径auth_path、权限层级auth_level在插入后，获取auth_id,然后执行更新操作
        $data['auth_desc'] = removeXss(I('post.auth_desc'));
        $data['auth_path'] = $this->getParent($authId);
        $data['auth_level'] = $this->getLevel($authId);
        $data['last_update_time'] = time();
    }
    
    /**
     * 钩子函数：_before_delete(),删除数据库之前执行
     * 
     */
    protected function _before_delete($option){


    } 
    
    /**
     * 钩子函数：_after_insert(),插入数据库之后执行
     * 
     */
    protected function _after_insert(&$data , $option){
        //如果插入成功后，通过返回信息，获取auth_id值
        $authId = $data['auth_id'];
        //判断权限是否插入成功，如果插入成功，执行更新操作
        if(!empty($authId)){
            $where['auth_id'] = $authId;
            $arr = array(
                'auth_level' =>  $this->getLevel($authId),
                'auth_path'  =>  $this->getParent($authId),
            );
            //创建模型类对象，并更新字段值
            $model = new \Think\Model();
            $info = $model->table('__AUTH__')->field('auth_level,auth_path')->where($where)->save($arr);
            if($info === false){
                //如果auth_path、auth_level信息更新插入失败
                $this->error = "权限全路径、权限层级插入失败！";
                return false;
                exit;
            }
        }
    }
    
    /**
     * 钩子函数：_after_update(),更新数据库之后执行
     * 
     */
    protected function _after_update(&$data , $option){
        

    }

    /**
     * 钩子函数：_after_delete(),删除数据库之后执行
     * 
     */
    protected function _after_delete($option){
        

    }

    /**
     * 获取权限的层级getLevel()
     * 
     */
    public function getLevel($id){
        //获取该模型下的所有列表
        $data = $this->getTree();
        //遍历数组，获取该id的level值并返回
        foreach ($data as $key => $value) {
            if($value['auth_id'] == $id){
                return (int)$value['level'];
            }
        }
        return 0;
    }

    /**
     * 管理员权限的树状展示getTree()
     * 
     */
    public function getTree($list=""){
        //如果没有传递list参数
        if(empty($list)){
           //获取该模型下的所有列表
           $list = $this->select();
        }
        //调用_tree()方法，获取树状结构，并返回
        return $this->_tree($list,0,0,true);
    }

    private function _tree($list,$parentId = 0,$level = 0,$option = false){
        //创建一个静态数组，用来存放所有的子孙的id
        static $tree = array();
        if($option){
            //如果选择初始化操作，就对静态数组进行初始化操作
            $tree = array();
        }
        //遍历list数组，对其中的元素进行排序
        foreach ($list as $key => $value) {
            if($value['auth_pid'] == $parentId){
                $value['level'] = $level;   //将该id所处的级别存入tree中
                $tree[] = $value;
                //递归调用_tree()方法，获取该id下的所有的子孙id
                $this->_tree($list,$value['auth_id'],$level+1);
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
        $parentId = $this->table('__AUTH__')->field('auth_pid')->find($id);
        //遍历所有的分类列表
        foreach ($list as $key => $value) {
            if($value['auth_id'] == $parentId['auth_pid']){
                //如果分类列表中的id与要搜索的分类id的上级id[parent_id]相等
                $parent[] = $value['auth_id'];
                $this->_parent($list,$value['auth_id']);
            }
        }
        return $parent;
    }
    
}
