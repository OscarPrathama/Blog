<?php
namespace App\Helper;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HelperClass{
    static function excelExport($filename = 'Data', $data = array()){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Author');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Slug');
        $sheet->setCellValue('D1', 'Type');
        $sheet->setCellValue('E1', 'Content');
        $sheet->setCellValue('F1', 'Status');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        if (!empty($data)) {
            $i = 2;
            foreach ($data as $key => $value) {
                $sheet->setCellValue('A'.$i, $value->user_id);
                $sheet->setCellValue('B'.$i, $value->post_title);
                $sheet->setCellValue('C'.$i, $value->post_slug);
                $sheet->setCellValue('D'.$i, $value->post_type);
                $sheet->setCellValue('E'.$i, json_encode($value->post_content));
                $sheet->setCellValue('F'.++$i, $value->post_status);
                $i++;
            }
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        $writer->save('php://output');
        exit;
    }
}
