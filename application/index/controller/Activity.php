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

use johnxu\tool\Str;

class Activity extends Api
{
    // 活动列表
    public function index()
    {
        $field = 'no,logo,name,description,price,content,unit,start_time,end_time,address';
        $list  = model('api/activity')->field($field)->order('start_time')->where('status', 1)->page($this->page,
            $this->limit)->select();
        $total = model('api/activity')->where('status', 1)->count('*');

        foreach ($list as $key => $item) {
            $list[$key]['logo'] = $this->domain.$item['logo'];
        }

        return [
            'err_code' => 0,
            'message'  => 'success',
            'data'     => $this->aesEncode($this->jsonEncode([
                'list'  => $list,
                'total' => $total,
            ])),
        ];
    }

    // 获取指定活动
    public function get(string $no = '')
    {
        $activity = model('api/activity')->field('id,hall_id', true)->get(['no' => $no]);

        if ($activity) {
            if ($activity->status != 1) {
                return [
                    'err_code' => 1,
                    'message'  => '活动已经下线了',
                ];
            }

            // 添加表单项
            $formContent      = $this->jsonDecode(model('api/form')->where(['id' => $activity['form_id']])->value('content'));
            $activity['form'] = array_map(function ($item) {
                unset($item['id']);

                return $item;
            }, $formContent);

            return [
                'err_code' => 0,
                'message'  => 'success',
                'data'     => $this->aesEncode($this->jsonEncode($activity->toArray())),
            ];
        } else {
            return [
                'err_code' => 1,
                'message'  => '活动不存在',
            ];
        }
    }
}