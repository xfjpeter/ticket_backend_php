<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// 生成激活码
if (!function_exists('generateCode')) {
    function generateCode(int $length = 16): string
    {
        $str    = '0123456789';
        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $code = mt_rand(0, strlen($str) - 1);
            if (!in_array($code, $result)) {
                array_push($result, $code);
            } else {
                $i--;
            }
        }

        return implode('', $result);
    }
}

if (!function_exists('download_xml')) {
    /**
     * 下载xml
     *
     * @param array  $header 表头 array('A1' => '姓名', 'B1' => '年龄', 'C1' => '性别')
     * @param array  $data   数据
     * @param string $title  表名
     *
     * @return void
     */
    function download_xml(array $header, array $data, $title = null)
    {
        $head        = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
        ); // 表头
        $title       = $title ?? bin2hex(random_bytes(4));
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');

        // 设置表头
        foreach ($header as $key => $value) {
            $column = $head[$key].'1';
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($column, $value);
            // 设置列宽为自动
            $spreadsheet->getActiveSheet()->getColumnDimension($head[$key])->setAutoSize(true);
            // 设置字体和加粗
            $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->setBold(true)->setSize(14);
        }

        // 设置数据
        $i = 2;
        foreach ($data as $index => $item) {
            $index = 0;
            foreach ($item as $key => $value) {
                $column = $head[$index].$i;
                $spreadsheet->setActiveSheetIndex(0)->setCellValueExplicit($column, $value,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $spreadsheet->getActiveSheet()->getStyle($column)->getFont()->setSize(13);
                $index++;
            }
            $i++;
        }

        $spreadsheet->getActiveSheet()->setTitle($title);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}