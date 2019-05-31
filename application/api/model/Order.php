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

namespace app\api\model;

use think\Model;

class Order extends Model
{
    protected $name      = 'order';
    protected $json      = ['details'];
    protected $jsonAssoc = true;

    protected function getCreateTimeAttr($value, $data)
    {
        return date('Y-m-d H:i', $data['create_time']);
    }

    protected function getUpdateTimeAttr($value, $data)
    {
        return date('Y-m-d H:i', $data['update_time']);
    }

    protected function getFinishedTimeAttr($value, $data)
    {
        return $data['finished_time'] ? date('Y-m-d H:i:s', $data['finished_time']) : '';
    }
}