<?php
/**
 * @Author: leiyongtao
 * @Date:   2016-06-22 20:24:13
 * @Last Modified by:   leiyongtao
 * @Last Modified time: 2016-08-03 18:45:42
 */
/**
 * 移除xss脚本攻击
 */
function removeXss($data){
    require_once './Public/Plugins/HtmlPurifier/HTMLPurifier.auto.php';
    $_clean_xss_config = HTMLPurifier_Config::createDefault();
    $_clean_xss_config->set('Core.Encoding', 'UTF-8');
    // 设置保留的标签
    $_clean_xss_config->set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
    $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    $_clean_xss_config->set('HTML.TargetBlank', TRUE);
    $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
    // 执行过滤
    return $_clean_xss_obj->purify($data);
}

/**
 * 展示图片的方法
 * 
 */
function showImg($url){

}

/**
 * 删除图片的方法
 * 
 */
function deleteImg($img){
    //循环删除每个对象
    foreach($img as $value){
        unlink('.'.$value);
    }
}

/**
 * 上传图片,并生成缩略图的方法
 * @param $config array() 上传文件进行的配置选项
 * @param $uploadPath array() 对上传文件的路径的配置
 * @param $thumb array() 生成的缩略图的配置选项
 * 
 */
function uploadOne($fileName ,$modelName, $thumb = array()){
    //一次只上传一张图片
    if(isset($_FILES[$fileName]) && 0 === $_FILES[$fileName]['error']){
        //如果接收到的file中的error类型是0表示上传成功
        $config = C('UPLOAD_IMG');
        $conf = C(strtoupper($modelName).'_THUMB');
        $config['savePath'] = $conf['imgSavePath'];
        // 实例化上传类
        $upload = new \Think\Upload($config);
        // 上传文件
        $info = $upload->upload(array($fileName => $_FILES[$fileName]));
        $imageSaveName = $info[$fileName]['savename'];
        $imageSavePath = $info[$fileName]['savepath'];
        
        $imagePath = $imageSavePath.$imageSaveName;
        //设置原图文件的上传全路径
        $ret['image'][0] = $imagePath;
        $thumbSavePath = $conf['thumbSavePath'].date('Y-m-d').'/'; 
        // 递归创建缩略图目录
        mkdir(UPLOAD_PATH.$thumbSavePath ,0777, true);
        //判断是否上传成功
        if(!$info) {
            // 如果上传失败，将错误信息返回
            return array(
                'status'    =>  0,
                'error'     =>  $upload->getError(),
            );
        }else{
            // 上传成功
            if($thumb){
                //如果$thumb参数不为空，表示要生成缩略图
                //创建图片的实例化对象
                $image = new \Think\Image(); 
                //根据传递的thumb的参数，循环创建缩略图
                foreach($thumb as $key => $value){
                    //拼接图片的路径，并且打开图片
                    $i = $key+1;
                    $image->open(UPLOAD_PATH.$imagePath);
                    $ret['image'][$i] = $thumbSavePath.'thumb-'.$i.'-'.$imageSaveName;
                    $info = $image->thumb($value[0] , $value[1] , $conf['style'])->save(UPLOAD_PATH.$ret['image'][$i]);
                    if(!$info){
                        //如果生成失败，跳出函数，并返回错误信息
                        return array(
                            'status'    =>  0,
                            'error'     =>  '缩略图创建失败',
                        );
                    }
                    $ret['status'] = 1;
                } 
            }  
            return $ret;
        }
    }
    //如果没有上传图片,返回状态码为-1
    return array(
        'status'    =>  -1,
    );
}

/**
 * 生成下拉菜单单选框的方法，只能针对单表操作，无法完成联表操作
 * @param $modelName string 用来生成下拉菜单的模型名
 * @param $selectName string 用来给下拉菜单取名
 * @param $valueField string value属性的显示值
 * @param $textField string text文本中的显示值
 * @param $checked string 用来指定默认选中的项目
 * 
 */
function buildSelect($modelName,$selectName,$valueField,$textField,$checked = "",$firstOption = "--请选择--",$sid = "",$sclass=""){
    //生成要创建的表的模型类对象
    $model = D($modelName);
    //取出所有的数据
    $order = strtolower($modelName).'_id asc';
    $list = $model->field("$valueField,$textField")->order($order)->select();
    //如果是分类的下拉列表
    if(strtolower($modelName) === "cat" || strtolower($modelName) === "auth"){
        $list = $model->getTree();
    }
    $option = "<select id='".$sid."' class='".$sclass."' name='$selectName'><option value=''>$firstOption</option>";
    //循环列表中所有选项，并展示出来
    foreach ($list as $v) {
        if($checked && ($v[$valueField] === $checked)){
            $selected = "selected = 'selected'";
        }else{
            $selected = "";
        }
        $option .= "<option value='".$v[$valueField]."'".$selected .">".str_repeat('&nbsp;',2*$v['level']).$v[$textField]."</option>";
    }   
    $option .= "</select>";
    echo $option;
}

/**
 * 封装ajax无刷新分页函数，可以直接调用
 * 
 */
function ajaxPage($total, $listRows = 10, $pa = ""){
    $page = new \Admin\Common\Util\Page($total,$listRows,$pa);
    return $page->fpage();
}







