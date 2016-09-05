<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 管理员列表 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/shop/Public/Admin/Style/general.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/shop/Public/Plugins/UmEditor/third-party/jquery.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('adminMessage',array('id' => 1));?>">添加管理员</a></span>
    <span class="action-span1"><a href="<?php echo U('Admin/index');?>" target="_top">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 管理员列表 </span>
    <div style="clear:both"></div>
</h1>
<!--管理员搜索-->
<div class="form-div">
    <form action="" name="searchForm">
    <img src="/shop/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
    <input type="text" name="brand_name" size="15" />
    <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<!--商品品牌展示-->
<form method="post" action="/shop/index.php/Admin/Admin/adminMessage.html" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>管理员id</th>
                <th>用户名</th>
                <th>所属权限组</th>
                <th>Email</th>
                <th>QQ</th>
                <th>Tel</th>
                <th>上次登录IP</th>
                <th>最后登录时间</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($adminList)): $k = 0; $__LIST__ = $adminList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr class="tr">
                    <td align="center"><input type="hidden" value="<?php echo ($vo["admin_id"]); ?>" /><?php echo ($vo["admin_id"]); ?></td>
                    <td align="center"><?php echo ($vo["admin_username"]); ?></td>
                    <td align="center">
                        <?php if($vo['role_name'] == null): ?>管理员
                        <?php else: ?>
                            <?php echo ($vo["role_name"]); endif; ?>
                    </td>
                    <td align="center"><?php echo ($vo["admin_mail"]); ?></td>
                    <td align="center"><?php echo ($vo["admin_qq"]); ?></td>
                    <td align="center"><?php echo ($vo["admin_tel"]); ?></td>
                    <td align="center"><?php echo ($vo["last_login_ip"]); ?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$vo['last_login_time']);?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$vo['addtime']);?></td>
                    <td align="center">
                    <a href="" target="_blank" title="查看"><img src="/shop/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('adminMessage',array('id' => 2,'admin_id' => $vo['admin_id']));?>" title="编辑"><img src="/shop/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                    <a href="javascript:void(0);" title="删除" onclick="ajaxDelete(this);"><img src="/shop/Public/Admin/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                    <a href="<?php echo U('adminMessage',array('id' => 4,'admin_id' => $vo['admin_id']));?>" onclick="" title="回收站"><img src="/shop/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a></td>
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
<script type="text/javascript">
    function ajaxDelete(a){
        if(confirm("是否确定删除？")){
            //如果用户确定删除
            //判断管理员id是否为1，如果为1，表示是超级管理员，无法进行删除
            var adminId = $(a).parent('td').siblings('td').children('input:hidden').val();
            var tr = $(a).parent('td').parent('tr');
            //判断是否是超级管理员
            if(adminId === "1"){
                alert("超级管理员无法删除！");
                return false;
            }
            //如果不是超级管理员，执行ajax进行删除
            $.ajax({
                type : "GET" ,
                url : "<?php echo U("ajaxDelete","",FALSE); ?>/admin_id/"+adminId ,
                dataType : "json" ,
                success : function(data){
                    if(data === "0"){
                        alert("超级管理员无法删除！");
                        return false;
                    }
                    if(data === "1"){
                        tr.remove();
                        alert("删除成功！");
                        //移除该管理员显示
                        return false;
                    }
                    if(data === "2"){
                        alert("删除失败！");
                        return false;
                    }
                }
            });
        }
    }
</script>
</body>
</html>