<?php
/**
 * | ---------------------------------------------------------------------------------------------------
 * | ProjectName: ticket
 * | ---------------------------------------------------------------------------------------------------
 * | Author：johnxu <fsyzxz@163.com>
 * | ---------------------------------------------------------------------------------------------------
 * | Home: https://www.xfjpeter.cn
 * | ---------------------------------------------------------------------------------------------------
 * | Data: 201905272019-05-27
 * | ---------------------------------------------------------------------------------------------------
 * | Desc:
 * | ---------------------------------------------------------------------------------------------------
 */

namespace app\api\controller;

class Form extends Api
{
    /**
     * @var \app\api\model\Form
     */
    protected $model;

    protected function initialize()
    {
        parent::initialize();
        $this->model = model('form');
    }


    // 列表
    public function index()
    {
        $type = $this->request->param('type', '');
        if ($type) {
            $list = $this->model->where(['status' => 1])->select();
        } else {
            $list = $this->model->select();
        }
        $total = $this->model->count('*');

        return json([
            'err_code' => 0,
            'message'  => 'success',
            'data'     => [
                'list'  => $list,
                'totla' => 0,
            ],
        ]);
    }

    // 添加
    public function add()
    {
        $data = $this->request->post();

        if ($this->model->where(['name' => $data['name']])->find()) {
            return json([
                'err_code' => 1,
                'message'  => '该表单名称已经存在',
            ]);
        }

        $form = $this->model->create($data);

        return $form
            ? json(['err_code' => 0, 'message' => '添加成功', 'data' => $form])
            : json(['err_code' => 0, 'message' => '添加失败']);
    }

    // 保存
    public function save(int $id = 0)
    {
        $data = $this->request->post();
        $form = $this->model->get($id);
        if (!$form) {
            return json(['err_code' => 1, 'message' => '找不到该表单项']);
        }

        return $form->save($data)
            ? json(['err_code' => 0, 'message' => '保存成功', 'data' => $form])
            : json(['err_code' => 1, 'message' => '保存失败']);

    }

    // 删除
    public function delete(int $id = 0)
    {
        return $this->model->where(['id' => $id])->delete()
            ? json(['err_code' => 0, 'message' => '删除成功'])
            : json(['err_code' => 1, 'message' => '删除失败']);
    }
}