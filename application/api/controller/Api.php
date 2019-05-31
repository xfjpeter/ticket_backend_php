<?php
/**
 * | ---------------------------------------------------------------------------------------------------
 * | ProjectName: ticket
 * | ---------------------------------------------------------------------------------------------------
 * | Author：johnxu <fsyzxz@163.com>
 * | ---------------------------------------------------------------------------------------------------
 * | Home: https://www.xfjpeter.cn
 * | ---------------------------------------------------------------------------------------------------
 * | Data: 201905242019-05-24
 * | ---------------------------------------------------------------------------------------------------
 * | Desc:
 * | ---------------------------------------------------------------------------------------------------
 */

namespace app\api\controller;

use johnxu\tool\Jwt;
use think\Controller;

class Api extends Controller
{
    protected $model;
    protected $page  = 1;
    protected $limit = 10;
    protected $uid;
    /**
     * @var Jwt
     */
    protected $jwt;

    protected function initialize()
    {
        $this->page  = $this->request->get('page', 1);
        $this->limit = $this->request->get('limit', 10);
        $this->jwt   = Jwt::getInstance();
        $this->verify();
    }

    protected function getJwtPayload(array $extra = [])
    {
        $payload = [
            'iss' => 'johnxu', // 该jwt的签发者
            'iat' => time(), // 签发时间
            'exp' => time() + 5 * 60 * 60, // 过期时间
            'nbf' => time(), // 该时间之前不接收处理该Token
            'sub' => 'https://www.xfjpeter.cn', // 面向的用户
            'jti' => md5(uniqid('jwt').time()) // 该token的唯一值
        ];

        return array_merge($payload, $extra);
    }

    protected function verify()
    {
        $payload = $this->jwt->verify($this->request->header('authorization', ''));
        if ($payload) {
            $this->uid = $payload['uid'] ?? '';
        }
    }
}