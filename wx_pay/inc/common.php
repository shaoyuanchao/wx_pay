<?php
ini_set('date.timezone','Asia/Shanghai');
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');


require_once('config.php');
require_once('mysql.php');
require_once('db_connect.php');

//======================================
// 函数: 获取GET/POST的参数，添加反斜杠处理
// 参数: $type          GET或POST
// 参数: $arg           参数
// 参数: $max_len       最大长度（默认50）
// 返回: 处理后的参数
// 说明:
//======================================
function get_arg_str($type, $arg, $max_len = 100)
{
  if (Config::AUTO_TEST_FLAG)
    return get_test_arg($arg);

  $arg_str = '';
  if ($type == 'GET' && isset($_GET[$arg]))
    $arg_str = substr(trim($_GET[$arg]), 0, $max_len);

  if ($type == 'POST' && isset($_POST[$arg]))
    $arg_str = substr(trim($_POST[$arg]), 0, $max_len);

  // PHP已开启自转义
  if (get_magic_quotes_gpc())
    return $arg_str;

  return addslashes($arg_str);
}


//======================================
// 函数: 取得分页相关参数(limit, offset)
// 参数: $type          GET或POST
// 返回: array($limit, $offset)
// 说明: 分页相关参数(limit, offset)处理
//======================================
function get_paging_arg($type)
{
  $limit = Config::REC_LIMIT;
  $offset = 0;
  if ($type == 'GET' && isset($_GET['limit']))
    $limit = intval($_GET['limit']);
  if ($type == 'POST' && isset($_POST['limit']))
    $limit = intval($_POST['limit']);
  if ($type == 'GET' && isset($_GET['offset']))
    $offset = intval($_GET['offset']);
  if ($type == 'POST' && isset($_POST['offset']))
    $offset = intval($_POST['offset']);

  $limit = min($limit,Config::REC_LIMIT_MAX);

  return array($limit, $offset);
}

//======================================
// 函数: 取得下拉列表选项
// 参数: $list          全体列表数组
// 参数: $select        默认选择项目
// 返回: 下拉列表选项
// 说明:
//======================================
function get_select_option($list, $select)
{
  $option = '';
  foreach ($list as $key=>$val) {
    if ($key == $select) {
      $option .= '<option value="' . $key . '" selected="selected">' . $val . '</option>';
    } else {
      $option .= '<option value="' . $key . '">' . $val . '</option>';
    }
  }
  return $option;
}

//======================================
// 函数: 取得单选框列表选项
// 参数: $name          控件名
// 参数: $list          全体列表数组
// 参数: $checked       默认选择项目
// 返回: 下拉列表选项
// 说明:
//======================================
function get_radio_input($name, $list, $checked)
{
  $input = '';
  foreach ($list as $key=>$val) {
    if ($key == $checked) {
      $input .= '<input type="radio" name="' . $name . '" value="' . $key . '" title="' . $val . '" checked>';
    } else {
      $input .= '<input type="radio" name="' . $name . '" value="' . $key . '" title="' . $val . '">';
    }
  }
  return $input;
}

//======================================
// 函数: 将用户输入内容转型放入数据库(HTML代码无效)
// 参数: $value         处理字符集
// 返回: 转型后字符集
//======================================
function str_to_html($value) {

  $rtn_str = '';

 if (isset($value)) {
    $rtn_str = str_replace("<", "&lt;", $value);
    $rtn_str = str_replace(">", "&gt;", $rtn_str);
    $rtn_str = str_replace(chr(34), "&quot;", $rtn_str);
    $rtn_str = str_replace(chr(13), "<br>", $rtn_str);
    $rtn_str = str_replace("\n", "<br>", $rtn_str);
    $rtn_str = str_replace(chr(9), "　　　　", $rtn_str);
  }

  return $rtn_str;
}

//======================================
// 函数: 将用户输入内容转型放入数据库(HTML代码有效)
// 参数: $value         处理字符集
// 返回: 转型后字符集
//======================================
function str_to_html_able($value) {

  $rtn_str = '';

 if (isset($value)) {
    $rtn_str = str_replace(chr(34), "&quot;", $value);
    $rtn_str = str_replace(chr(13), "<br>", $rtn_str);
    $rtn_str = str_replace("\n", "<br>", $rtn_str);
    $rtn_str = str_replace(chr(9), "　　　　", $rtn_str);
  }

  return $rtn_str;
}

//======================================
// 函数: 将数据库存放的用户输入内容转换回再修改内容
// 参数: $value         处理字符集
// 返回: 转型后字符集
//======================================
function html_to_str($value) {

  $rtn_str = '';

 if (isset($value)) {
    $rtn_str = str_replace("&nbsp;", " ", $value);
    $rtn_str = str_replace("&lt;", "<", $rtn_str);
    $rtn_str = str_replace("&gt;", ">", $rtn_str);
    $rtn_str = str_replace("&quot;", chr(34), $rtn_str);
    $rtn_str = str_replace("<br>", chr(13), $rtn_str);
    $rtn_str = str_replace("<br />", chr(13), $rtn_str);
    $rtn_str = str_replace("<br/>", chr(13), $rtn_str);
    $rtn_str = str_replace("&#32;", chr(9), $rtn_str);
  }

  return $rtn_str;
}

//======================================
// 函数: 取得唯一标示符GUID
// 参数: 无
// 返回: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX（8-4-4-4-12，共36位）
// 说明: 随机数前缀+根据当前时间生成的唯一ID转成大写的MD5码
// 说明: 32位重排+分隔符4位生成36位GUID
//======================================
function get_guid()
{
  $charid = strtoupper(md5(uniqid(mt_rand(), true)));
  $hyphen = chr(45);  // "-"
  $uuid = substr($charid, 6, 2).substr($charid, 4, 2).substr($charid, 2, 2).substr($charid, 0, 2).$hyphen;
  $uuid .= substr($charid, 10, 2).substr($charid, 8, 2).$hyphen;
  $uuid .= substr($charid,14, 2).substr($charid,12, 2).$hyphen;
  $uuid .= substr($charid,16, 4).$hyphen;
  $uuid .= substr($charid,20,12);
  return $uuid;
}

//======================================
// 函数: 返回当前URL
// 参数: 无
// 返回:
//======================================
function get_url()
{
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

//======================================
// 函数: 取得用户访问IP
// 参数: 无
// 返回: XXX.XXX.XXX.XXX
// 返回:
//======================================
function get_ip()
{
  $ip=false;
  if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  }
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
    if($ip) {
      array_unshift($ips, $ip);
      $ip = FALSE;
    }
    for($i = 0; $i < count($ips); $i++) {
      if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i])) {
        $ip = $ips[$i];
        break;
      }
    }
  }
  return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

//======================================
// 函数: 取得用户访问IP的长整数
// 参数: 无
// 返回: IP地址对应的长整数
// 返回: 若无法取得返回 0
//======================================
function get_int_ip()
{
  $ip = ip2long(get_ip());
  if ($ip)
    return $ip;
  return 0;
}

//======================================
// 函数: 将日志里面的数组或JSON数据转成可识别的字符串形式
// 功能: 如果是JSON数据先转成数组形式
// 功能: 如果是数组则直接转成字符串形式
// 功能: 否则直接返回
// 参数: $rtn_data      需转换的数据
// 返回: 数组和JSON转成{"key1":"value1","key2":"value2"} 形式的字符串
//======================================
function get_log_msg_str($rtn_data)
{
  $rtn_arry = $rtn_data;
  // 非数组则尝试转成数组格式
  if (!is_array($rtn_arry)) {
    $rtn_arry = json_decode($rtn_data, true);
    // 转换结果为空数组或非数组则直接返回原有结果
    if (empty($rtn_arry) || !is_array($rtn_arry))
      return $rtn_data;
  }

  $buff = "";
  foreach ($rtn_arry as $k => $v) {
    if (!is_array($v)) {
      $buff .= '"' . $k . '":"' . $v . '",';
    } else {
      $buff .= '"' . $k . '":' . get_log_msg_str($v) . ',';
    }
  }

  $buff = trim($buff, ",");
  return '{' . $buff . '}';
}

//======================================
// 函数: 禁止游客访问
// 参数: 无
// 返回: 无
// 说明: 若session中没有设置staff_id，直接退出
//======================================
function exit_guest()
{
  if (!session_id())
    session_start();

  if (!isset($_SESSION['staff_id']))
    exit('Login failure, please login again.');
}




function getMillisecond(){
    list($s1,$s2)=explode(' ',microtime());
    return (float)sprintf('%.0f',(floatval($s1)+floatval($s2))*1000);
}

function check_mobile($mobile)
{
    if (!empty($mobile) && !preg_match("/^(1[0-9]{10})?$/", $mobile)) {
        return false;
    } else
        return $mobile;
}


//======================================
// 函数: 验证token信息
// 参数: token,type:如果为空,只返回id,则返回所以
// 返回: id
// 说明: 若验证错误,直接返回错误信息
//======================================

function check_token($token,$type=''){
    $key = Config::TOKEN_KEY;
    // 获取token并解密
    $des = new Des();
    $decryption_code = $des -> decrypt($token, $key);
    $now_time = time();
    $code_conf =  explode(',',$decryption_code);
    // 获取token中的需求信息
    $us_id = $code_conf[0];
    $timestamp = $code_conf[1];
    if($timestamp < $now_time){
        exit_error('114','Token timeout please retrieve!');
    }
    if ($type)
        return $code_conf;
    else
        return $us_id;
}
function getIpInfo($us_ip){
    if(empty($us_ip));

    $url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$us_ip."&co=&resource_id=6006&t=1534396669452&ie=utf8&oe=gbk&cb=op_aladdin_callback&format=json&tn=baidu&cb=jQuery110207126151679006834_1534396644758&_=1534396644768";
//    $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$us_ip;
    $result = file_get_contents($url);
    $result = json_decode($result,true);
    if($result['status']!==0 || !is_array($result['data'])){
        return false;
    }else{
        return $result['data']['location'];
    }
}
?>