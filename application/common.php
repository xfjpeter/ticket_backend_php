<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 生成激活码
if (!function_exists('generateCode')) {
    function generateCode(int $length = 16): string
    {
        $str    = '0123456789';
        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $code = mt_rand(0, strlen($str) - 1);
            if (!in_array($code, $result)) {
                array_push($result, $code);
            } else {
                $i--;
            }
        }

        return implode('', $result);
    }
}