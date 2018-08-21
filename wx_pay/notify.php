<?php
require_once  'inc/common.php';
require_once  'Base.php';
/*
 *  1.获取通知数据 ->转换为数组
 *  2.验证签名
 *  3.验证业务结果
 *  4.验证订单号和金额 (out_trade_no total_fee)
 *  5.记录日志 修改订单状态 给用户发货
 */
class Notify extends Base
{
    public function __construct() {
        $xmlData = $this->getPost();
        $arr = $this->XmlToArr($xmlData);
        if($this->chekSign($arr)){
            if($arr['return_code'] == 'SUCCESS' && $arr['result_code'] == 'SUCCESS'){
                //从数据库读取订单信息，通过订单号
//                $ret = get_order_fee($arr['out_trade_no']);
                if($arr['total_fee'] == 200){
                    $this->logs('stat.txt', '交易成功!'); //更改订单状态
                    //订单状态进行更改
//                    $rse = upd_order_flag();
                    $returnParams = [
                        'return_code' => 'SUCCESS',
                        'return_msg'  => 'OK'
                    ];
                    echo $this->ArrToXml($returnParams);
                }else{
                    $this->logs('stat.txt', '金额有误!');
                }
            }else{
                $this->logs('stat.txt', '业务结果不正确!');
            }
        }else{
            $this->logs('stat.txt', '签名失败!');
        }
    }
}
$obj = new Notify();