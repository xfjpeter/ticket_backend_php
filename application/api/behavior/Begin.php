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

namespace app\api\behavior;

use think\Db;
use think\facade\Route;

class Begin
{
    /**
     * @param $param
     */
    public function run($param)
    {
        $this->loadRouter();
    }

    // 加载后台动态路由
    protected function loadRouter()
    {
        Route::group('v1', function () {
            $rules = Db::name('rule')->where('router != ""')->select();
            foreach ($rules as $rule) {
                Route::{$rule['method']}($rule['router'].'$', "{$rule['module']}/{$rule['controller']}/{$rule['action']}");
            }
        })->middleware(['Auth'])->allowCrossDomain();
    }
}