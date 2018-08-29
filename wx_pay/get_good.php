<?php
require_once  'inc/common.php';
require_once  'Base.php';

header("cache-control:no-cache,must-revalidate");
header("Content-Type:application/json;charset=utf-8");

/*
========================== 获取商品列表 ==========================
GET参数
    token             用户token

返回
    errcode = 0       成功
    rows              信息数组
    code                         产品编码
    price                        产品价格
    detail                       产品描述
    count                        产品总数
    sell_count                   已卖出数量
    rema                         剩余数量
    utime                        更新时间
    ctime                        创建时间
说明
*/


//获取商品列表
$row = get_good_list();
if(!$row){
    exit();
}

//成功后返回数据
$rtn_ary = array();
$rtn_ary['errcode'] = '0';
$rtn_ary['errmsg'] = '';
$rtn_ary['row'] = $row;
$rtn_str = json_encode($rtn_ary);
