<?php
return [
    'wxpay'  => array(
        'dev'        => true,
        'app_id'     => 'wx30a1e46d72833c45',
        'mch_id'     => '1537267991',
        'key'        => '40ace1babb133e1ea17f09932dd2e508',
        'sign_type'  => 'HMAC-SHA256', // 签名类型，默认为MD5，支持HMAC-SHA256和MD5。
        'secret'     => 'a8b71dd1418a85c6697e5b2e6fb3f200',
        'notify_url' => 'https://ticket.johnxu.net/api/wxpay/notify',
        'return_url' => 'https://ticket.johnxu.net/api/wxpay/return',
    ),
    'alipay' => array(
        'debug'               => true,
        'dev'                 => true,
        'charset'             => 'utf-8',
        'sign_type'           => 'RSA2',
        'app_id'              => '2016090900471394',
        'platform_public_key' => __DIR__.'/pem/platform_key.pem', // 可以填写文件路径，也可以填写字符串
        'user_private_key'    => __DIR__.'/pem/private_key.pem', // 同上
        'user_public_key'     => __DIR__.'/pem/public_key.pem', // 同上
        'return_url'          => 'http://pay.johnxu.net/demo/return_url.php', // 同步通知地址
        'notify_url'          => 'http://pay.johnxu.net/demo/notify_url.php' // 异步通知地址
    ),
];