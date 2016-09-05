<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-07-21 11:47:31
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-07-21 11:55:18
 */

namespace Home\Controller;

class BaseController extends \Think\Controller {
    public function __construct(){
        parent::__construct();
        $this->getNav();
    }
    /**
     * 导航页数据的获取与展示
     */
    public function getNav(){
        //获取商品的分类的列表
        $catList = D("Admin/Cat")->getCatList();
        $this->assign("catList" , $catList);
    }
}
