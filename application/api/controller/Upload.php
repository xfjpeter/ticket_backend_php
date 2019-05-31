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

namespace app\api\controller;

use think\facade\Env;

class Upload extends Api
{
    protected $size = 1024 * 1024 * 2;
    protected $ext  = [
        'image' => 'jpg,png,jpeg,gif',
    ];

    // 上传图片
    public function image()
    {
        $file = $this->request->file('file');
        $info = $file->validate([
            'size' => $this->size,
            'ext'  => $this->ext['image'],
        ])->move(Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads');

        if ($info) {
            return json([
                'err_code' => 0,
                'message'  => 'success',
                'data'     => [
                    'path' => '/uploads/'.$info->getSaveName(),
                ],
            ]);
        } else {
            return json([
                'err_code' => 1,
                'message'  => $file->getError(),
            ]);
        }
    }
}