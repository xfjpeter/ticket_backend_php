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

class Role extends Api
{
    /**
     * @var \app\api\model\Role
     */
    protected $model;

    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = model('role');
    }

    // 获取列表
    public function index()
    {
        $list  = $this->model->page($this->page, $this->limit)->select();
        $total = $this->model->count('*');

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => [
                'list'  => $list,
                'total' => $total,
            ],
        ]);
    }

    // 获取某个角色
    public function get(int $id = 0)
    {
        $role = $this->model->get($id);
        if (!$role) {
            return json([
                'err_code' => 1,
                'message'  => 'Not found role',
            ]);
        }

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => $role,
        ]);
    }

    // 更新角色
    public function save(int $id = 0)
    {
        $data = $this->request->post();
        $role = $this->model->get($id);
        if (!$role) {
            return json([
                'err_code' => 1,
                'message'  => 'Not found role',
            ]);
        }

        // 保存角色信息，保存对应的权限
        $res = $role->save($data);

        return $res
            ? json([
                'err_code' => 0,
                'message'  => 'success',
                'data'     => $data,
            ])
            : json([
                'err_code' => 1,
                'message'  => 'Update role fail',
            ]);
    }

    // 添加
    public function add()
    {
        $data = $this->request->post();
        $role = $this->model::create($data);

        return $role
            ? json([
                'err_code' => 0,
                'message'  => 'success',
                'data'     => $role,
            ])
            : json([
                'err_code' => 1,
                'message'  => 'Add role fail',
            ]);
    }

    // 删除角色
    public function delete(int $id = 0)
    {
        return $this->model->where('id', $id)->delete()
            ? json([
                'err_code' => 0,
                'message'  => 'success',
            ])
            : json([
                'err_code' => 1,
                'message'  => 'Delete role fail',
            ]);
    }
}