<?php
require_once  'inc/common.php';
require_once  'Base.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 获取发送信息列表 ==========================
GET参数
    token             用户token
返回
    errcode = 0       成功
    rows            信息数组
    code                       产品编码
    order_num                 订单号(内部唯一)
    price                     产品价格
    detail                    产品描述
    count                     产品数量
    address                   收件人地址
    phone                     收件人手机号
    name                      收件人姓名
    qa_flag                   订单状态(1已支付，2未支付，3取消)
    ctime                      创建时间
说明
*/

//获取发送信息列表
$row = get_send_good_list();
if(!$row){
    exit();
}

//成功后返回数据
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['row'] = $row;
$rtn_str = json_encode($rtn_ary);
