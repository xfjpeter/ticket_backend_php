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

use think\Controller;

class Api extends Controller
{
    protected $page;
    protected $limit;
    protected $domain;

    protected function initialize()
    {
        $this->page  = $this->request->param('page', 1);
        $this->limit = $this->request->param('limit', 10);
        $this->domain = $this->request->domain();
    }

    protected function jsonEncode(array $data = []): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES);
    }

    protected function jsonDecode(string $data): array
    {
        return json_decode($data, true);
    }

    public function aesEncode(string $data)
    {
        return base64_encode(
            openssl_encrypt($data, 'aes-128-ecb', config('ticket.aesKey'), OPENSSL_RAW_DATA)
        );
    }

    public function aesDecode(string $data)
    {
        return openssl_decrypt(base64_decode($data), 'aes-128-ecb', config('ticket.aesKey'), OPENSSL_RAW_DATA);
    }
}