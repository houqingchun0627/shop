<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: category_list.htm 17019 2010-01-29 10:10:34Z liuhui $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品分类 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/shop/Public/Admin/Style/general.css" rel="stylesheet" type="text/css" />
<link href="/shop/Public/Admin/Style/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo U('goodsCat',array('id' => 1));?>">添加分类</a></span>
    <span class="action-span1"><a href="<?php echo U('Admin/Admin/index');?>" target="_top">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 商品分类 </span>
    <div style="clear:both"></div>
</h1>
<form method="" action="/shop/index.php/Admin/Goods/goodsCat.html" name="listForm">
    <div class="list-div" id="listDiv">
        <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
            <tr>
                <th>分类id</th>
                <th>分类名称</th>
                <th>分类级别</th>
                <!-- <th>上级分类</th> -->
                <th>是否显示</th>
                <th>是否推荐</th>
                <th>导航显示</th>
                <th>排序</th>
                <th>分类logo</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            <?php if(is_array($catList)): $i = 0; $__LIST__ = $catList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo["cat_level"]); ?>_<?php echo ($vo["cat_id"]); ?>" align="center" class="0" style="display:table-row;">
                    <td align="center"><img width="9" height="9" border="0" onclick="rowClicked(this)" style="margin-left:0em" src="/shop/Public/Admin/Images/menu_minus.gif"><?php echo ($vo["cat_id"]); ?></td>
                    <td align="left"><?php echo str_repeat('&nbsp;',8*$vo['level']); echo ($vo["cat_name"]); ?></td>
                    <td align="center"><?php echo ($vo["cat_level"]); ?></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo["is_show"] == 是 ): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo["is_recommend"] == 是 ): ?>yes.gif<?php else: ?>no.gif<?php endif; ?>"/></td>
                    <td align="center"><img src="/shop/Public/Admin/Images/<?php if($vo["is_navbar"] == 是): ?>yes.gif <?php else: ?>no.gif<?php endif; ?>" /></td>
                    <td align="center"><?php echo ($vo["cat_sort"]); ?></td>
                    <td class="first-cell" align="center">
                    <span><a href="<?php echo ($vo["cat_image"]); ?>" target="_brank"><img src="<?php echo ($vo["cat_logo"]); ?>" width="30" height="30" border="0" alt="分类图片" /></a></span>
                    </td>
                    <td align="center"><span><?php echo date('Y-m-d H:i:s',$vo['addtime']);?></span></td>
                    <td align="center">
                        <a href="" target="_blank" title="查看"><img src="/shop/Public/Admin/Images/icon_view.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('Goods/goodsCat',array('id' => 2,'cat_id' => $vo['cat_id']));?>" title="编辑"><img src="/shop/Public/Admin/Images/icon_edit.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('Goods/goodsCat',array('id' => 3,'cat_id' => $vo['cat_id']));?>" title="删除" onclick="return confirm('你确定要删除吗？');"><img src="/shop/Public/Admin/Images/icon_drop.gif" width="16" height="16" border="0" /></a>
                        <a href="<?php echo U('Goods/goodsCat',array('id' => 4,'cat_id' => $vo['cat_id']));?>" onclick="" title="回收站"><img src="/shop/Public/Admin/Images/icon_trash.gif" width="16" height="16" border="0" /></a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
</form>
<div id="footer">
共执行 1 个查询，用时 0.055904 秒，Gzip 已禁用，内存占用 2.202 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
<script>
// 展开收缩
var imgPlus = new Image();
imgPlus.src = "/shop/Public/Admin/Images/menu_plus.gif";
/**
 * 折叠分类列表
 */
function rowClicked(obj){
  // 当前图像
  img = obj;
  // 取得上二级tr>td>img对象
  obj = obj.parentNode.parentNode;
  // 整个分类列表表格
  var tbl = document.getElementById("list-table");
  // 当前分类级别
  var lvl = parseInt(obj.className);
  // 是否找到元素
  var fnd = false;
  var sub_display = img.src.indexOf('menu_minus.gif') > 0 ? 'none' : (Browser.isIE) ? 'block' : 'table-row' ;
  // 遍历所有的分类
  for (i = 0; i < tbl.rows.length; i++){
      var row = tbl.rows[i];
      if (row == obj){
          // 找到当前行
          fnd = true;
          //document.getElementById('result').innerHTML += 'Find row at ' + i +"<br/>";
      }else{
          if (fnd == true){
              var cur = parseInt(row.className);
              var icon = 'icon_' + row.id;
              if (cur > lvl){
                  row.style.display = sub_display;
                  if (sub_display != 'none'){
                      var iconimg = document.getElementById(icon);
                      iconimg.src = iconimg.src.replace('plus.gif', 'minus.gif');
                  }
              }
              else{
                  fnd = false;
                  break;
              }
          }
      }
  }

    for (i = 0; i < obj.cells[0].childNodes.length; i++){
        var imgObj = obj.cells[0].childNodes[i];
        if (imgObj.tagName == "IMG" && imgObj.src != '/shop/Public/Admin/Images/menu_arrow.gif'){
          imgObj.src = (imgObj.src == imgPlus.src) ? '/shop/Public/Admin/Images/menu_minus.gif' : imgPlus.src;
        }
    }
}
//-->
</script>
</body>
</html>