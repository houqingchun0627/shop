// goods列表页获取商品的可选属性
/** 把服务器返回的属性循环拼成一个LI字符串，并显示在页面中 **/
// var content = '';
// // 循环属性列表中的每个属性值
// $(data).each(function(k,v){
//     content += '<tr><td class="attr">';
//     //判断属性是唯一属性，还是可选属性
//     if(v.attr_unique == '可选属性'){
//         //如果为可选属性
//         // 属性名称
//         content += v.attr_name + '：</td><td><ul class="ulstyle"><li>';
//         //可选属性的属性值一定为多选值
//         // 把可选值,转化成数组，遍历依次取出
//         var _attr = v.optional_list.split(',');
//         var _attr_id = (v.id != null) ? v.id.split(',') : [""];
//         content += '<input type="hidden" name="attr_number[]" value="'+_attr_id[0]+'" />';
//         content += '<a onclick="addDelete(this);" href="javascript:void(0)" style="color:red;">[+]</a>';
//         // 判断attr_value是否为空
//         if(v.attr_value == null ){
//             //如果商品属性值为空
//             content += '<select name="attr_value['+v.attr_id+'][]"><option value="">--请选择--</option>';
//             for(var i=0; i<_attr.length; i++){
//                 content += '<option value="'+_attr[i]+'">';
//                 content += _attr[i];
//                 content += '</option>';
//             }
//             content += '</select></li>';
//         }else{
//             var attr_value = v.attr_value.split(',');
//             //如果商品属性值不为空
//             for(var j=0; j<attr_value.length; j++){
//                 //判断是否是第一个属性值
//                 if(j > 0){
//                     content += '<li><input type="hidden" name="attr_number[]" value="'+_attr_id[j]+'" />';
//                     content += '<a onclick="addDelete(this);" href="javascript:void(0)" style="color:red;">[-]</a>';
//                 }
//                 content += '<select name="attr_value['+v.attr_id+'][]"><option value="">--请选择--</option>';
//                 for(var i=0; i<_attr.length; i++){
//                     content += '<option value="'+_attr[i]+'" ';
//                     if(attr_value[j] == _attr[i]){
//                         content += ' selected="selected"';
//                     }
//                     content += '>'+_attr[i];
//                     content += '</option>';
//                 }
//                 content += '</select></li>';
//             }
//         }
//     }else{
//         //如果是唯一属性
//         content += v.attr_name + '：</td><td><ul class="ulstyle"><li>';
//         var vid = (v.id !== null ) ? v.id : "";
//         //判断属性值是否唯一
//         if(v.optional_list == ""){
//             //如果属性值是唯一值
//             //判断属性值是否是null
//             if(v.attr_value == null){
//                 var attrvalue = "";
//             }else{
//                 var attrvalue = v.attr_value;
//             }
//             content += '<input type="hidden" name="attr_number[]" value="'+vid+'" />';
//             content += '<input type="text" name="attr_value['+v.attr_id+'][]" value="'+attrvalue+'" />';  
//         }else{
//             //如果属性值可选
//             content += '<input type="hidden" name="attr_number[]" value="'+vid+'" />';
//             content += '<select name="attr_value['+v.attr_id+'][]"><option value="">--请选择--</option>';
//             // 把可选值,转化成数组，遍历依次取出
//             var _attr = v.optional_list.split(',');
//             for(var i=0; i<_attr.length; i++){
//                 content += '<option value="'+_attr[i]+'"';
//                 if(v.attr_value == _attr[i]){
//                         content += ' selected="selected"';
//                     }
//                 content += '>'+_attr[i];
//                 content += '</option>';
//             }
//             content += '</select></li>';
//         }
//     }
//     content += '</ul></td></tr>';    
// });

// $("#"+tableId+" tr:gt(1)").remove();
// $("#"+tableId).append(content);


//设置单击后，不可用
function handlePromote(checked)
{
    document.forms['theForm'].elements['promote_price'].disabled = !checked;
    document.forms['theForm'].elements['selbtn1'].disabled = !checked;
    document.forms['theForm'].elements['selbtn2'].disabled = !checked;
}

//按本店价计算
function priceSetted(){
    computePrice('market_price', marketPriceRate);
    computePrice('integral', integralPercent / 100);

    set_price_note(1);//注册会员价格
    set_price_note(3);//代销会员价格
    set_price_note(2);//vip会员价格
}

//按市场价计算
function marketPriceSetted(){
    computePrice('shop_price', 1/marketPriceRate, 'market_price');
    computePrice('integral', integralPercent / 100);

    set_price_note(1);
    set_price_note(3);
    set_price_note(2);
}
//计算商品价格
function computePrice(inputName, rate, priceName){
    var shopPrice = priceName == undefined ? document.forms['theForm'].elements['shop_price'].value : document.forms['theForm'].elements[priceName].value;
    
    shopPrice = Utils.trim(shopPrice) != '' ? parseFloat(shopPrice)* rate : 0;
    
    if(inputName == 'integral'){
        shopPrice = parseInt(shopPrice);
    }
    shopPrice += "";

    n = shopPrice.lastIndexOf(".");
    if(n > -1){
        shopPrice = shopPrice.substr(0, n + 3);
    }

    if(document.forms['theForm'].elements[inputName] != undefined){
        document.forms['theForm'].elements[inputName].value = shopPrice;
    }else{
        document.getElementById(inputName).value = shopPrice;
    }
}
//设置会员价格
function set_price_note(rank_id){
    var shop_price = parseFloat(document.forms['theForm'].elements['shop_price'].value);
    var rank = new Array();    
    rank[1] = 100;
    rank[3] = 90;
    rank[2] = 95;
    if (shop_price >0 && rank[rank_id] && document.getElementById('rank_' + rank_id) && 
        parseInt(document.getElementById('rank_' + rank_id).value) == -1){
        var price = parseInt(shop_price * rank[rank_id] + 0.5) / 100;
        if (document.getElementById('nrank_' + rank_id)){
            document.getElementById('nrank_' + rank_id).innerHTML = '(' + price + ')';
        }
    }else{
        if (document.getElementById('nrank_' + rank_id)){
            document.getElementById('nrank_' + rank_id).innerHTML = '';
        }
    }
}