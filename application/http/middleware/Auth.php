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

namespace app\http\middleware;

use Closure;
use johnxu\tool\Jwt;
use think\Db;
use think\Request;

class Auth
{
    // 权限管理
    public function handle(Request $request, Closure $next)
    {
        // 出去登录界面不需要授权，其他均需要授权
        $moduleControllerAction = $request->module().'/'.$request->controller().'/'.$request->action();

        if ($moduleControllerAction == 'api/User/login') {
            return $next($request);
        }

        $result = Jwt::getInstance()->verify($request->header('authorization', ''));
        if (!$result) {
            return json([
                'err_code' => -1,
                'message'  => '请先登录后操作',
            ]);
        }
        $request->param('uid', $result['uid']);

        // 规则权限判断
        $ruleIds = Db::view('user_role ur', 'role_id')->view('role', 'rule_id', 'ur.role_id=role.id')->value('rule_id');
        $rules   = Db::name('rule')->whereIn('id', $ruleIds)->select();
        $arr     = [];
        foreach ($rules as $rule) {
            array_push($arr, "{$rule['module']}/{$rule['controller']}/{$rule['action']}");
        }
        if (!in_array($moduleControllerAction, $arr)) {
            return json([
                'err_code' => -1,
                'message'  => '没有权限访问',
            ]);
        }

        // 进行权限判断
        return $next($request);
    }
}