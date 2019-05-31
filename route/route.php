<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::group('api', function () {
    // 用户登录
    Route::post('/login$', 'user/login');

    // 获取活动列表
    Route::get('/activity$', 'activity/index');
    // 获取指定活动详情
    Route::get('/activity/:no$', 'activity/get');

    // 下单购买
    Route::post('/activity/order/:no$', 'order/place');
    // 获取订单列表
    Route::get('/orders/:uid', 'order/index');
    // 获取指定订单详情
    Route::get('/order/:order_no', 'order/get');
    // 取消订单
    Route::post('/order/cancel', 'order/cancelOrder');

    // 支付
    Route::post('pay', 'order/payment');

    // 微信回调
    Route::post('/wxpay/notify$', 'Order/notify');
    Route::post('/wxpay/return$', 'Order/return');
})->prefix('index/')->allowCrossDomain();