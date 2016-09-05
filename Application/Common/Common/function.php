<?php
/**
 * 发送邮件函数
 */
function sendMail($to, $title, $content)
{
    require_once('./Public/Plugins/PHPMailer_v5.1/class.phpmailer.php');
    $mail = new PHPMailer();
    // 设置为要发邮件
    $mail->IsSMTP();
    // 是否允许发送HTML代码做为邮件的内容
    $mail->IsHTML(TRUE);
    // 是否需要身份验证
    $mail->SMTPAuth=TRUE;
    $mail->CharSet='UTF-8';
    /*  邮件服务器上的账号是什么 */
    $mail->From=C('MAIL_ADDRESS');
    $mail->FromName=C('MAIL_FROM');
    $mail->Host=C('MAIL_SMTP');
    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');
    // 发邮件端口号：默认为25，腾讯加密状态下为：465或587
    $mail->Port = 25;
    // 收件人
    $mail->AddAddress($to);
    // 邮件标题
    $mail->Subject=$title;
    // 邮件内容
    $mail->Body=$content;
    return($mail->Send());
}

/**
 * 展示用户地址四级联动菜单信息
 */
function addressSelect($level="",$parentId="",$checked = "",$firstOption = "--请选择--"){
    //取出满足要求的所有数据
    switch ($level) {
        case '1':
                $name = "consignee_address_province";
                break;
        case '2':
                $name = "consignee_address_city";
                break;
        case '3':
                $name = "consignee_address_country";
                break;
        case '4':
                $name = "consignee_address_town";
                break;                        
    }
    $list = D("Admin/ChinaRegion")->where(array('level' => array('eq',$level),'parent_id' => array('eq',$parentId)))->order("id asc")->select();
    //拼接满足要求的html代码
        $option = '<select id="'.$name.'" class="di-bl fl seauii" onchange="get_address(this,'.$level.');" name="'.$name.'"><option value="0">'.$firstOption.'</option>';
    //循环列表中所有选项，并展示出来
    foreach ($list as $v) {
        if($checked && ($v["id"] == $checked)){
            $selected = 'selected = "selected"';
        }else{
            $selected = '';
        }
        $option .= '<option value="'.$v["id"].'" '.$selected.'>'.$v["name"].'</option>';
    }
    $option .= '</select>';
    // echo $option;
    return $option;
}

/**
 * 获取当前的地址中的详细信息
 */
function getAddress($id){
    return D("Admin/ChinaRegion")->where(array('id' => array('eq',$id)))->getField("name");
}