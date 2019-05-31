<?php
/**
 * | ---------------------------------------------------------------------------------------------------
 * | ProjectName: ai
 * | ---------------------------------------------------------------------------------------------------
 * | Author：johnxu <fsyzxz@163.com>
 * | ---------------------------------------------------------------------------------------------------
 * | Home: https://www.xfjpeter.cn
 * | ---------------------------------------------------------------------------------------------------
 * | Data: 201905222019-05-22
 * | ---------------------------------------------------------------------------------------------------
 * | Desc:
 * | ---------------------------------------------------------------------------------------------------
 */

namespace app\api\libs;

class Tree
{
    /**
     * 无限级树状结构(菜单)
     *
     * @param array $data
     * @param int   $pid
     * @return array
     */
    public static function getTree(array $data, $pid = 0)
    {
        $result = [];
        if ($data && is_array($data)) {
            foreach ($data as $item) {
                if ($pid == $item['pid']) {
                    $result[] = [
                        'id'          => $item['id'],
                        'label'       => $item['name'],
                        'description' => $item['description'],
                        'children'    => self::getTree($data, $item['id']),
                    ];
                }
            }
        }

        foreach ($result as $key => $item) {
            if (!$item['children']) {
                unset($result[$key]['children']);
            }
        }

        return $result;
    }

    /**
     * 无限级树状结构(规则)
     *
     * @param array $data
     * @param int   $pid
     * @return array
     */
    public static function getTreeRule(array $data, $pid = 0)
    {
        $result = [];
        if ($data && is_array($data)) {
            foreach ($data as $item) {
                if ($pid == $item['pid']) {
                    $result[] = [
                        'id'          => $item['id'],
                        'name'        => $item['name'],
                        'label'       => $item['name'],
                        'description' => $item['description'],
                        'module'      => $item['module'],
                        'controller'  => $item['controller'],
                        'action'      => $item['action'],
                        'method'      => $item['method'],
                        'router'      => $item['router'],
                        'ismenu'      => $item['ismenu'],
                        'value'       => $item['id'],
                        'children'    => self::getTreeRule($data, $item['id']),
                    ];
                }
            }
        }
        // 去除为空的children项
        foreach ($result as $key => $item) {
            if (!$item['children']) {
                unset($result[$key]['children']);
            }
        }

        return $result;
    }
}