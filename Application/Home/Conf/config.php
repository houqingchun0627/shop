<?php
return array(
	//'配置项'=>'配置值'
    /************** 静态缓存的配置 ***************/
    // 'HTML_CACHE_ON'     =>    true, // 开启静态缓存
    // 'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
    // 'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
    // 'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则     
    //     // 定义格式1 数组方式     
    //     //'静态地址'    =>     array('静态规则', '有效期', '附加规则'),
    //     'Goods:index' => array('index', 86400), // 首页生成index.html一天
    //     'Goods:goods' => array('goods-{id}', 86400), // 首页生成index.html一天
    //     // 定义格式2 字符串方式     
    //     //'静态地址'    =>     '静态规则', 
    // ),
    
    /************** 发邮件的配置 ***************/
    // 163邮箱发送
    'MAIL_ADDRESS'      => 'phper_lyt@163.com',      // 发件人的email地址
    'MAIL_FROM'         => 'LYTshop商城',            // 发件人姓名
    'MAIL_SMTP'         => 'smtp.163.com',           // 邮件服务器的地址
    'MAIL_LOGINNAME'    => 'phper_lyt',              // 发件人的email帐号
    'MAIL_PASSWORD'     => 'leiyongtao853362',       // 发件人的email密码
    
    //qq邮箱发送
    // 'MAIL_ADDRESS'      => '939769710@qq.com',       // 发件人的email地址
    // 'MAIL_FROM'         => 'lytshop商城',            // 发件人姓名
    // 'MAIL_SMTP'         => 'smtp.qq.com',            // 邮件服务器的地址
    // 'MAIL_LOGINNAME'    => '939769710',              // 发件人的email帐号
    // 'MAIL_PASSWORD'     => 'uszqdqtwbssmbeaa',       // 发件人的email密码

);