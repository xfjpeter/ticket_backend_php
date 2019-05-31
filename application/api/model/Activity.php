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

namespace app\api\model;

use think\Model;

class Activity extends Model
{
    protected $name = 'activity';

    protected function getStartTimeAttr($value, $data)
    {
        return $data['start_time'] ? date('Y-m-d H:i', $data['start_time']) : '';
    }

    protected function getEndTimeAttr($value, $data)
    {
        return $data['end_time'] ? date('Y-m-d H:i', $data['end_time']) : '';
    }

    protected function getMapAttr($value, $data)
    {
        return $data['map'] ? json_decode($data['map']) : [];
    }

    protected function setMapAttr($value)
    {
        return is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $value;
    }
}