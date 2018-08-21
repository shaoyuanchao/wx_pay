<?php
session_start();
require_once  'db_function.php';
require_once  'inc/common.php';
include  'Base.php';

/**
 *  获取用户openid
 *  构建原始数据
 *  加入签名
 *  调用统一下单API
 *  获取到prepay_id
 */

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");
/*
========================== 获取商品信息 ==========================
GET参数
    info               商品描述
    total_fee          商品价格
    code               商品编号
返回
    中间接口，获取信息使用,并把信息传递
说明

*/
//
//$info = get_arg_str('GET', 'info');
//$total_fee = get_arg_str('GET', 'total_fee');
//$code = get_arg_str('GET', 'code');

$info = '商品描述';
$total_fee = '200';
$code = '10927';


//获取商品信息
//$good = get_good_detail_info($code);
$good['price'] =100;
$good['rema_count'] =10;
$good['code'] = 10927;
if($good['code'] != $code){
    exit('商品不编码错误');
}
if(!$good){
    exit('商品不存在');
}
if(!is_int($total_fee/$good['price'])){
    exit('商品价格与数据库不匹配');
}
if($total_fee/$good['price'] > $good['rema_count']){
    exit('商品余量不足，请重新下单');
}
//生成订单号，商户内部唯一
$soid = get_guid();
//根据信息获取微信内部的预付款id
$base = new Base();
$prepay_id = $base->getPrepayId($soid,$total_fee);
//根据预付款id生成json串   调用微信支付
$json = $base->getJsParams($prepay_id);
$data = array();
$data['info'] = $info;
$data['soid'] = $soid;
$data['total_fee'] = $total_fee;
$data['code'] = $code;
//把订单信息写入数据库
//$ret = ins_order_info($data);
?>

<script>
    function onBridgeReady(){
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', <?php echo $json;?>,
            function(res){
                if(res.err_msg == "get_brand_wcpay_request:ok" ) {}     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
            }
        );
    }
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>

