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

use app\api\libs\Tree as TreeLib;
use think\Db;

class Tree extends Api
{
    // 获取菜单列表
    public function menu()
    {
        $userId  = model('user')->where('uid', $this->uid)->value('id');
        $roleId  = Db::view('user', 'id')->where('user_id', $userId)->view('user_role', 'role_id',
            'user.id=user_role.user_id')->value('role_id');
        $ruleIds = Db::name('role')->where('id', $roleId)->value('rule_id');
        $rules   = model('rule')->whereIn('id', $ruleIds)->where('ismenu', 1)->select();
        $menu    = TreeLib::getTree($rules->toArray());

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => $menu,
        ]);
    }

    // 获取树状规则列表
    public function rule()
    {
        $rules = model('rule')->select();
        $list  = TreeLib::getTree($rules->toArray());

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => [
                'list' => $list,
            ],
        ]);
    }
}