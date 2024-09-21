<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\service\excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Excel工具类
 */
class ExcelDriver
{
    /**
     * 导出
     *
     * @param array $fields
     * @param array $lists
     * @param array $options
     * @return string
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @author zero
     */
    public static function export(array $fields, array $lists, array $options = []): string
    {
        $exportName = trim($options['exportName'] ?? 'excel');
        $exportUuid = md5(uniqid(strval(time()),true).mt_rand().$exportName);
        $exportPath = root_path().'runtime/export/'.date('Ymd').'/'.$exportUuid.'/';
        $option = [
            // 导出名称
            'exportName'      => trim($options['exportName'] ?? 'excel'),
            // 导出方式: [flow=流式, path=路径]
            'exportMethod'    => trim($options['exportMethod'] ?? 'flow'),
            // 导出路径: [系统路径: path模式下才生效]
            'exportPath'      => trim($options['exportPath'] ?? $exportPath),
            // 行头高度
            'headerRowHeight' => intval($options['cellRowHeight'] ?? 30),
            // 每行高度
            'cellOtRowHeight' => intval($options['cellRowHeight'] ?? 22),
            // 字体样式
            'fontStyle' => [
                'name'      => $options['fontStyle']['name']??'',
                'size'      => $options['fontStyle']['size']??'',
                'bold'      => $options['fontStyle']['bold']??'',
                'color'     => $options['fontStyle']['color']??'',
                'italic'    => $options['fontStyle']['italic']??'',
                'underline' => $options['fontStyle']['underline']??'',
            ],
            // 行头样式
            'headerStyle' => [
                'font' => [
                    'bold'  => $options['headerStyle']['font']['bold']  ?? true,
                    'color' => $options['headerStyle']['font']['color'] ?? ['argb' => '000000'],
                    'size'  => $options['headerStyle']['font']['size']  ?? 12
                ],
                'fill' => [
                    'fillType'   => $options['headerStyle']['fill']['fillType'] ?? Fill::FILL_SOLID,
                    'startColor' => $options['headerStyle']['fill']['fillType'] ?? ['argb' => '999999']
                ]
            ],
            // 基础样式
            'basisStyle' => [
                'borders' => [
                    'allBorders' => $options['basisStyle']['borders'] ?? [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => '000000'],
                    ],
                ],
                'alignment' => $options['basisStyle']['alignment'] ?? [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ]
            ]
        ];

        // 实例化表
        $spreadsheet = new Spreadsheet();
        $worksheet   = $spreadsheet->getActiveSheet();

        // 表格数据
        $listsData = [];
        foreach ($lists as $row) {
            $temp = [];
            foreach ($fields as $key => $val) {
                $fieldData = $row[$key];
                if (is_numeric($fieldData) && strlen($fieldData) >= 12) {
                    $fieldData .= "\t";
                }
                $temp[$key] = $fieldData;
            }
            $listsData[] = $temp;
        }

        // 表头字段
        $titleWidth = [];
        $titleArray = array_values($fields);
        foreach ($titleArray as $key => $item) {
            if (is_array($item)) {
                $titleWidth[] = intval($item[1]);
                $item = $item[0];
            } else {
                $titleWidth[] = 30;
            }
            $worksheet->setCellValue([$key+1, 1], $item);
        }

        // 写入数据
        $row = 2;
        foreach ($listsData as $item) {
            $column = 1;
            foreach ($item as $value) {
                $worksheet->setCellValue([$column, $row], $value);
                if (!empty($option['fontStyle']['name'])) {
                    $sty = explode('@', $option['fontStyle']['name']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? $sty[1] : $sty;
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setName($val);
                }
                if (!empty($option['fontStyle']['size'])) {
                    $sty = explode('@', $option['fontStyle']['size']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? $sty[1] : $sty;
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setSize($val);
                }
                if (!empty($option['fontStyle']['bold'])) {
                    $sty = explode('@', $option['fontStyle']['bold']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? $sty[1] : $sty;
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setBold($val);
                }
                if (!empty($option['fontStyle']['color'])) {
                    $sty = explode('@', $option['fontStyle']['color']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? [0=>$sty[1]] : [0=>$sty];
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setColor($val[0]);
                }
                if (!empty($option['fontStyle']['italic'])) {
                    $sty = explode('@', $option['fontStyle']['italic']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? [0=>$sty[1]] : [0=>$sty];
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setItalic($val[0]);
                }
                if (!empty($option['fontStyle']['underline'])) {
                    $sty = explode('@', $option['fontStyle']['underline']);
                    $col = count($sty) >= 2 ? intval($sty[0]) : $column;
                    $val = count($sty) >= 2 ? [0=>$sty[1]] : [0=>$sty];
                    $worksheet->getCell([$col, $row])->getStyle()->getFont()->setItalic($val[0]);
                }
                $column++;
            }
            $worksheet->getRowDimension($row)->setRowHeight($option['cellOtRowHeight']);
            $row++;
        }

        // 表格宽度
        foreach ($titleWidth as $k => $w) {
            $worksheet->getColumnDimensionByColumn($k+1)->setWidth($w);
        }

        // 表头样式
        $worksheet->getRowDimension(1)->setRowHeight($option['cellOtRowHeight']);
        $worksheet->getStyle([1, 1, count($titleArray), 1])->applyFromArray($option['headerStyle']);

        // 基础样式
        $highestColumn = $worksheet->getHighestColumn();
        $highestRow = $worksheet->getHighestRow();
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 'A'; $col <= $highestColumn; ++$col) {
                $cell = $worksheet->getCell($col . $row);
                if (!empty($cell->getValue())) {
                    $worksheet->getStyle($col . $row)->applyFromArray($option['basisStyle']);
                }
            }
        }

        // 导出文件
        if ($option['exportMethod'] === 'path') {
            if (!file_exists($exportPath)) {
                mkdir($exportPath, 0775, true);
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($exportPath . $exportName . '.xlsx');
            return $exportPath . $exportName . '.xlsx';
        } else {
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment;filename="'.$option['exportName'].'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
        return '';
    }

    /**
     * 导入
     *
     * @param string $path
     * @param array $fields
     * @param array $options
     * @return array
     */
    public static function import(string $path, array $fields, array $options = []): array
    {
        $option = [
            // 跳过第几行
            'skip'  => intval($options['skip']??1),
            // 写入数据行
            'write' => $options['write'] ?? false,
            // 操作的模型
            'model' => $options['model'] ?? null
        ];

        // 读取表格
        $spreadsheet = IOFactory::load($path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(true, true, true, true, true);

        // 循环数据
        $i = 1;
        $lists = [];
        foreach ($sheetData as $rows) {
            if ($i == $option['skip']) {
                $i++;
                continue;
            }

            $data = [];
            foreach ($fields as $key => $field) {
                $data[$field] = $rows[$key];
            }

            $lists[] = $data;
        }

        if ($option['write']) {
            app($option['model'])->saveAll($lists);
        }

        return $lists;
    }
}