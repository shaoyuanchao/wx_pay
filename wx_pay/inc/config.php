<?php

require_once('mysql.php');
require_once('db_connect.php');

// 配置信息类
class Config
{
  // 系统ID
  const SYSTEM_ID = '2';
  // 系统代号
  const SYSTEM_CD = 'user';
  // 确认URL
  //图片链接前缀


  // 使用测试数据调试区分（false 使用正式数据 true 使用测试数据）

  const AUTO_TEST_FLAG = false;

  // 跟踪日志等级
  const DEBUG_LEVEL = 1;
  // 信息日志等级
  const INFO_LEVEL = 2;
  // 警告日志等级
  const WARN_LEVEL = 4;
  // 异常日志等级
  const ERROR_LEVEL = 8;
  // PHP日志等级(0关闭，15全部, 14关闭跟踪日志)
  const PHP_LOG_LEVEL = 14;
  // PHP日志文件目录加前缀
  const PHP_LOG_FILE_PREFIX = 'logs';



    // 一次读取记录条数
  const REC_LIMIT = 10;
  // 一次最多读取记录条数
  const REC_LIMIT_MAX = 100;
  // 每页显示记录条数
  const PAGESIZE = 10;
  // API调用超时
  const API_TIMEOUT = 30;

  const project_dictory = __DIR__;

  const BTC_CONG = 100000000;

}
?>