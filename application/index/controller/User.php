<?php
/**
 * | ---------------------------------------------------------------------------------------------------
 * | ProjectName: ticket
 * | ---------------------------------------------------------------------------------------------------
 * | Author：johnxu <fsyzxz@163.com>
 * | ---------------------------------------------------------------------------------------------------
 * | Home: https://www.xfjpeter.cn
 * | ---------------------------------------------------------------------------------------------------
 * | Data: 201905252019-05-25
 * | ---------------------------------------------------------------------------------------------------
 * | Desc:
 * | ---------------------------------------------------------------------------------------------------
 */

namespace app\index\controller;

use Exception;
use johnxu\tool\Http;
use johnxu\tool\Str;

class User extends Api
{
    // 微信授权登录
    public function login()
    {
        $wechatConfig = config('payment.wxpay');
        $data         = $this->request->post();
        if (!isset($data['code']) || !$data['code']) {
            return json([
                'err_code' => 1,
                'message'  => '缺少必要参数code',
            ]);
        }
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
        try {
            $result = Http::getInstance()->request(sprintf($url, $wechatConfig['app_id'], $wechatConfig['secret'],
                $data['code']));
            $data   = $result->getContent();
            if (isset($data['errcode']) && $data['errcode'] !== 0) {
                return json([
                    'err_code' => 1,
                    'message'  => '登录授权失败',
                ]);
            } else {
                // 判断openid是否已经绑定用户了，如果绑定了就不用重新绑定
                $user = model('api/user')->where(['openid' => $data['openid']])->find();
                if (!$user) {
                    // 创建user
                    $uid  = Str::getInstance()->generateUid('wx_');
                    $user = model('api/user')::create([
                        'uid'      => $uid,
                        'openid'   => $data['openid'],
                        'status'   => 0,
                        'nickname' => substr_replace($uid, '********', 7, 8),
                        'system'   => 0,
                    ]);
                }

                return json([
                    'err_code' => 0,
                    'message'  => 'success',
                    'data'     => $this->aesEncode(
                        $this->jsonEncode(['uid' => $user['uid']])
                    ),
                ]);
            }
        } catch (Exception $e) {
            return json([
                'err_code' => 1,
                'message'  => '登录授权异常:'.$e->getMessage(),
            ]);
        }
    }
}