<?php
return array(
	//'配置项'=>'配置值'
//设置文件上传的参数：upload
    'UPLOAD_IMG'  =>  array(
        'maxSize'       =>    2*1024*1024,                             //设置最大上传尺寸为：2M 
        'rootPath'      =>    UPLOAD_PATH,                             // 设置附件上传根目录     
        // 'savePath'   =>    'Images/Goods/',                         //设置上传文件的存放路径
        // 'saveName'      =>    array('time','uniqid'),                  //采用时间戳命名
        'saveName'      =>    'uniqid',                                //采用唯一id命名
        'exts'          =>    array('jpg', 'gif', 'png', 'jpeg'),      //设置允许上传的后缀名,
                                                                       //无法上传.bmp类型图片
        'autoSub'       =>    true,                                    //是否自动创建子目录
        'subName'       =>    array('date','Y-m-d'),                   //子目录的名字
    ),
//设置商品缩略图的参数
    'GOODS_THUMB' =>  array(
        'style'         =>      3 ,                     //设置商品缩略图的裁剪样式：1-7
        'imgSavePath'   =>      'Images/Goods/',        //设置上传原始文件的存放路径
        'thumbSavePath' =>      'Thumb/Goods/'          //设置上传缩略图文件的存放路径
    ),
//设置商品品牌缩略图的参数
    'BRAND_THUMB' =>  array(
        'style'         =>      3 ,                     //设置商品品牌缩略图的剪切样式：1-7
        'imgSavePath'   =>      'Images/Brand/',        //设置上传原始文件的存放路径
        'thumbSavePath' =>      'Thumb/Brand/'          //设置上传缩略图文件的存放路径
    ),
//设置商品分类缩略图的参数
    'CAT_THUMB'   =>  array(
        'style'         =>      3 ,                     //设置商品分类缩略图的剪切样式：1-7
        'imgSavePath'   =>      'Images/Cat/',          //设置上传原始文件的存放路径
        'thumbSavePath' =>      'Thumb/Cat/'          //设置上传缩略图文件的存放路径
    ),
//设置商品类型缩略图的参数
    'TYPE_THUMB'  =>  array(
        'style'         =>      3 ,                     //设置商品类型缩略图的剪切样式：1-7
        'imgSavePath'   =>      'Images/Type/',         //设置上传原始文件的存放路径
        'thumbSavePath' =>      'Thumb/Type/'          //设置上传缩略图文件的存放路径
    ),
//设置商品属性缩略图的参数
    'ATTR_THUMB'  =>  array(
        'style'         =>      3 ,                     //设置商品属性缩略图的剪切样式：1-7
        'imgSavePath'   =>      'Images/Attr/',         //设置上传原始文件的存放路径
        'thumbSavePath' =>      'Thumb/Attr/'          //设置上传缩略图文件的存放路径
    ),
);