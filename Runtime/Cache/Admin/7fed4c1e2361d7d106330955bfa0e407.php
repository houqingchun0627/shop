<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 会员等级 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/shop/Public/Admin/Style/general.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('User/userLevel',array('id' => 1));?>">添加会员等级</a></span>
    <span class="action-span1"><a href="<?php echo U('Admin/index');?>" target="_top">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 会员等级 </span>
    <div style="clear:both"></div>
</h1>
<!--会员等级搜索-->
<div class="form-div">
    <form action="" name="searchForm">
    <img src="/shop/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
    <input type="text" name="user_level" size="15" />
    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<!--商品品牌展示-->
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>会员id</th>
                <th>会员名称</th>
                <th>积分下限</th>
                <th>积分上限</th>
                <th>会员折扣</th>
                <th>附加描述</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($levelList)): $i = 0; $__LIST__ = $levelList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><?php echo ($vo["level_id"]); ?></td>
                <td align="center"><?php echo ($vo["level_name"]); ?></td>
                <td align="center"><?php echo ($vo["jifen_bottom"]); ?></td>
                <td align="center"><?php echo ($vo["jifen_top"]); ?></td>
                <td align="center"><?php echo ($vo["level_discount"]); ?></td>
                <td align="center"><?php echo ($vo["level_comment"]); ?></td>
                <td align="center">
                <a href="" target="_blank" title="查看"><img src="/shop/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('User/userLevel',array('id' => 2,'level_id' => $vo['level_id']));?>" title="编辑"><img src="/shop/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('User/userLevel',array('id' => 3,'level_id' => $vo['level_id']));?>" title="删除" onclick="return confirm('你确定要删除吗？');"><img src="/shop/Public/Admin/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                <a href="<?php echo U('User/userLevel',array('id' => 4,'level_id' => $vo['level_id']));?>" onclick="" title="回收站"><img src="/shop/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr>
                <!-- 商品品牌展示分页 -->
                <!-- <?php echo ($page); ?> -->
            </tr>
        </table>
    </div>
</form>

<div id="footer">
共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>