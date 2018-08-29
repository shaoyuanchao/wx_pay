<?php
//======================================
// 函数: 查询商品详细信息
// 参数: code                商品编码
// 返回: row                 商品信息数组
//======================================
function get_good_detail_info($code){
    $db = new DB_COM();
    $sql = "SELECT * FROM good_list where code = '{$code}' limit 1";
    $db->query($sql);
    $row = $db->fetchRow();
    return $row;
}

//======================================
// 函数: 订单信息入库
// 参数: data                订单信息数组
// 返回: count                影响的行数
//======================================
function ins_order_info($data){
    $db = new DB_COM();
    $sql = $db->sqlInsert("order_list", $data);
     $db->query($sql);
    $row = $db->affectedRows();
    return $row;
}
//======================================
// 函数: 获取订单支付金额
// 参数: order               支付订单
// 返回: row                 信息数组
//======================================
function get_order_fee($order){
    $db = new DB_COM();
    $sql = "SELECT * FROM order_list where order = '{$order}'";
    $db->query($sql);
    $row = $db->fetchRow();
    return $row;
}
//======================================
// 函数: 获取产品列表
// 参数:
// 返回: rows               信息数组
//======================================
function get_good_list(){
    $db = new DB_COM();
    $sql = "SELECT * FROM good_list";
    $db->query($sql);
    $rows = $db->fetchAll();
    return $rows;
}
//======================================
// 函数: 获取需发送的商品列表
// 参数:
// 返回: rows               信息数组
//======================================
function get_send_good_list(){
    $db = new DB_COM();
    $sql = "SELECT * FROM order_list";
    $db->query($sql);
    $rows = $db->fetchAll();
    return $rows;
}
