<?php
function ins_data_info($params){
    $db = new DB_COM();
    $sql = $db->sqlInsert("us_log_login_fail", $params);
    $q_id = $db->query($sql);
    if ($q_id == 0)
        return false;
    return true;

}

function get_good_detail_info($code){
    $db = new DB_COM();
    $sql = "SELECT * FROM good_list where code = '{$code}' limit 1";
    $db->query($sql);
    $row = $db->fetchRow();
    return $row;
}


function ins_order_info($data){
    $db = new DB_COM();
    $sql = $db->sqlInsert("order_list", $data);
    $row = $db->query($sql);
    return $row;
}

function get_order_fee($order){
    $db = new DB_COM();
    $sql = "SELECT * FROM order_list where order = '{$order}'";
    $db->query($sql);
    $row = $db->fetchRow();
    return $row;
}
?>