<?php

namespace app\common\service\excel;

use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $exportUuid = md5(uniqid(time(),true).mt_rand().$exportName);
        $exportPath = root_path().'runtime/export/'.date('Ymd').'/'.$exportUuid.'/';
        $option = [
            // 导出名称
            'exportName'      => trim($options['exportName'] ?? 'excel'),
            // 导出方式: [flow=流式, path=路径]
            'exportMethod'    => trim($options['exportMethod'] ?? 'flow'),
            // 导出路径: [系统路径: 紧path模式下需要,且必须]
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
     */
    public static function import()
    {

    }
}