<?php
require_once "TP/class.TemplatePower.inc.php";
require_once "class/model/order/payment/allpay/atm.php"; 
require_once "class/mcrypt/aes.php"; 
require_once "conf/config.inc.php";
require_once "conf/vaccount.php";
require_once "conf/database.php";
include_once("libs/libs-mysql.php");
$db = new DB($cms_cfg['db_host'],$cms_cfg['db_user'],$cms_cfg['db_password'],$cms_cfg['db_name'],$cms_cfg['tb_prefix']);
/*初始化payment物件*/
$card = new Model_Order_Payment_Allpay_Atm($cms_cfg['vaccount']);
if($_GET['VAReturn']){
    $tpl = new TemplatePower("test3.html");
    $tpl->prepare();
    /*輸出回傳結果*/
    $tpl->gotoBlock("_ROOT");
//    $tpl->assign("UPDATE_ORDER_SQL",$sql);
    foreach($_GET as $key => $value){
        $tpl->assign("MSG_".strtoupper($key),$value);
        if($key=="RtnCode"){
            $tpl->assign("MSG_".strtoupper($key)."_STR",  Model_Order_Payment_Allpay_Returncode_Atm::$code[$value]);
        }
    }    
    $tpl->printToScreen();
}elseif($_POST['XMLData']){
    /*解析回傳結果*/
    $returnXML = $card->parse_xmldata($_POST['XMLData']);
    /*更新訂單*/
    $sql = $card->update_order($db,$returnXML);
    if(file_put_contents("tmp/".date("YmdHis").".txt", $sql)!==false){
        echo "1|OK";
    }
}
