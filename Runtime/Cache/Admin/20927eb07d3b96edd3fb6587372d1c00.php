<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 角色列表 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/shop/Public/Admin/Style/general.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('roleMessage',array('id' => 1));?>">添加角色</a></span>
    <span class="action-span1"><a href="<?php echo U('Admin/index');?>" target="_top">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 角色列表 </span>
    <div style="clear:both"></div>
</h1>
<!--商品品牌搜索-->
<div class="form-div">
    <form action="" name="searchForm">
    <img src="/shop/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
    <input type="text" name="brand_name" size="15" />
    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<!--商品品牌展示-->
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>角色id</th>
                <th>角色名称</th>
                <!-- 角色所拥有的权限暂不在列表页面展示 -->
                <th>角色描述</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($roleList)): $i = 0; $__LIST__ = $roleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                    <td align="center"><?php echo ($vo["role_id"]); ?></td>
                    <td align="center"><?php echo ($vo["role_name"]); ?></td>
                    <td align="center"><?php echo ($vo["role_desc"]); ?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$vo['addtime']);?></td>
                    <td align="center">
                    <a href="" target="_blank" title="查看"><img src="/shop/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('roleMessage',array('id' => 2,'role_id' => $vo['role_id']));?>" title="编辑"><img src="/shop/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('roleMessage',array('id' => 3,'role_id' => $vo['role_id']));?>" title="删除" onclick="return confirm('你确定要删除吗？');"><img src="/shop/Public/Admin/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('roleMessage',array('id' => 4,'role_id' => $vo['role_id']));?>" onclick="" title="回收站"><img src="/shop/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
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