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

use johnxu\tool\Hash;
use johnxu\tool\Str;
use think\Db;

class User extends Api
{
    /**
     * @var \app\api\model\User
     */
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = model('user');
    }

    // 用户列表
    public function index()
    {
        $users = $this->model->page($this->page, $this->limit)->select();
        $total = $this->model->count('*');
        foreach ($users as $key => $user) {
            $users[$key]['role_id'] = Db::name('user_role')->where('user_id', $user['id'])->value('role_id');
            unset($users[$key]['password'], $users[$key]['id']);
        }

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => [
                'list'  => $users,
                'total' => $total,
            ],
        ]);
    }

    // 获取指定用户
    public function get(string $uid = '')
    {
        $user = $this->model->get(['uid' => $uid]);

        if (!$user) {
            return json([
                'err_code' => 1,
                'message'  => 'Not found user',
            ]);
        }

        // 查询用户的角色
        $user['role_id'] = Db::name('user_role')->where('user_id', $user->id)->value('role_id');
        unset($user['id'], $user['password']);

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => $user,
        ]);
    }

    // 编辑用户信息
    public function save(string $uid = '')
    {
        $data = $this->request->post();
        $user = $this->model->get(['uid' => $uid]);
        if (!$user) {
            return json([
                'err_code' => 1,
                'message'  => 'Not found user',
            ]);
        }

        // 判断有没有密码
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $res = $user->save($data);
        if ($res !== false) {
            $this->updateUserRole($data, $user->id);

            return json([
                'err_code' => 0,
                'message'  => 'success',
                'data'     => $data,
            ]);
        } else {
            return json([
                'err_code' => 1,
                'message'  => 'Update user fail',
            ]);
        }
    }

    // 添加用户
    public function add()
    {
        $data        = $this->request->post();
        $data['uid'] = Str::getInstance()->generateUid();
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data['password'] = Hash::make('123456');
        }
        $user = $this->model::create($data);
        if ($user) {
            $this->updateUserRole($data, $user->id);
            unset($data['password']);

            return json([
                'err_code' => 0,
                'message'  => 'Add user success',
                'data'     => $data,
            ]);
        } else {
            return json([
                'err_code' => 1,
                'message'  => 'Add user fail',
            ]);
        }
    }

    // 删除用户
    public function delete(string $uid = '')
    {
        $is = $this->model->where(['uid' => $uid, 'system' => 0])->delete();
        if ($is) {
            return json([
                'err_code' => 0,
                'message'  => 'Delete user success',
            ]);
        } else {
            return json([
                'err_code' => 1,
                'message'  => 'Delete user fail',
            ]);
        }
    }

    // 登录
    public function login()
    {
        $data = $this->request->post();
        $user = $this->model->get(['username' => $data['username']]);
        if (!$user) {
            return json([
                'err_code' => 1,
                'message'  => 'username or password error',
            ]);
        } elseif (!Hash::check($data['password'], $user['password'])) {
            return json([
                'err_code' => 1,
                'message'  => 'username or password error',
            ]);
        } elseif ($user['status'] !== 1) {
            return json([
                'err_code' => 1,
                'message'  => '账号被禁用',
            ]);
        } else {
            unset($user['password'], $user['id']);
            $user['authorization'] = $this->jwt->getToken($this->getJwtPayload(['uid' => $user['uid']]));

            return json([
                'err_code' => 0,
                'message'  => 'success',
                'data'     => $user,
            ]);
        }
    }

    // 检测登录状态
    public function check()
    {
        $authorization = $this->request->header('Authorization');
        $result        = $this->jwt->verify($authorization);
        if (!$result) {
            return json(['err_code' => 401, 'message' => '登录过期']);
        } else {
            return json(['err_code' => 0, 'message' => '登录正常']);
        }
    }

    // 更新用户角色
    protected function updateUserRole(array $data, $userId)
    {
        if (isset($data['role_id']) && !empty($data['role_id'])) {
            $userRole = Db::name('user_role')->where('user_id', $userId)->find();
            if ($userRole) {
                Db::name('user_role')->where('user_id', $userId)->update(['role_id' => $data['role_id']]);
            } else {
                Db::name('user_role')->insert(['role_id' => $data['role_id'], 'user_id' => $userId]);
            }
        }
    }
}