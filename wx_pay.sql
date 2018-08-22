CREATE TABLE `good_list` (
`code` varchar(255) NOT NULL COMMENT '商品编号',
`count` int(11) DEFAULT NULL COMMENT '商品总数量',
`rema_count` int(11) DEFAULT NULL COMMENT '剩余数量',
`sold_coount` int(11) DEFAULT NULL COMMENT '已售数量',
`info` varchar(255) DEFAULT NULL COMMENT '商品描述',
`price` int(10) DEFAULT NULL COMMENT '商品价格',
`utime` int(11) DEFAULT NULL COMMENT '更新时间',
`ctime` datetime DEFAULT NULL COMMENT '创建时间',
PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '商品信息表';

CREATE TABLE `order_list` (
  `oid` varchar(255) NOT NULL COMMENT '商品订单号',
  `code` varchar(255) DEFAULT NULL COMMENT '商品编号',
  `count` int(11) DEFAULT NULL COMMENT '购买数量',
  `total_fee` int(11) DEFAULT NULL COMMENT '支付金额',
  `flag` tinyint(1) DEFAULT NULL COMMENT '订单状态',
  `utime` int(11) DEFAULT NULL COMMENT '更新时间戳',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `name` varchar(255) NOT NULL COMMENT '用户购买时姓名',
  `address` varchar(255) NOT NULL COMMENT '用户地址信息',
  `phone` text NOT NULL COMMENT '用户手机号',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单信息表';
