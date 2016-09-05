<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

// 定义运行时目录
define('RUNTIME_PATH','./Runtime/');

//定义程序插件的目录
define('PLUGIN_PATH','./Public/Plugins/');

//定义第三方插件的目录
define('ADDON_PATH','./Public/Addon/');

//定义文件的上传目录
define('UPLOAD_PATH','./Public/Uploads/');

//定义图片的路径
define('IMG_PATH' , '/Public/Uploads/');

//定义网站的域名
define('DOMAIN_PATH','http://shop.php.com');

//定义HTML静态缓存文件的存放目录
define('HTML_PATH','./Runtime/Html/');

// 引入ThinkPHP入口文件
require APP_PATH.'ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单