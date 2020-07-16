<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Get the timesheet data
$data = getDataFromTimeSheet($argv[1]);
print_r($data);
// Get the template:
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('invoice_template.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');

$data = getDataFromTimeSheet($argv[1]);
$template_row = 15;
$spreadsheet->getActiveSheet()->insertNewRowBefore(16, count($data) - 1);

if (!empty($data)) {
    $total = 0; $total_hours = 0;
    foreach($data as $row_index => $row_value) {
        $worksheet->setCellValue('B' . $template_row, $row_value[0]);
        $worksheet->setCellValue('C' . $template_row, $row_value[1]);
        $worksheet->setCellValue('D' . $template_row, $row_value[2]);
        $total_hours = $total_hours + $row_value[2];
        $worksheet->setCellValue('E' . $template_row, 75);
        $row_total = 75 * $row_value[2];
        $worksheet->setCellValue('F' . $template_row, $row_total);
        $total = $total + $row_total;
        $template_row++;
    }

    $worksheet->setCellValue('F' . $template_row, $total);
    $worksheet->setCellValue('D' . $template_row, 'HOURS: ' . $total_hours);
}
$outPutFileName = ($argv[2]) ? $argv[2] : 'Invoice Service Invoice Hourly Rate.xlsx';
$writer->save($outPutFileName);

// Function to read downloaded timesheet and extract relevant data
// to build my templated invoice
function getDataFromTimeSheet($name) {
    $outputData = [];
    $row = 0;
    if (false !== ($ih = fopen($name, 'r'))) {
        while (false !== ($data = fgetcsv($ih))) {
            if ($row == 0) {
                $row++;
                continue;
            }
            // this is where you build your new row
            $t = gettype($data[11]);
            $d = timeToDecimal($data[11]);
            $outputData[] = array($data[5], $data[7], $d);
        }
    }
    fclose($ih);
    return $outputData;
}

/**
 * Convert time into decimal time.
 *
 * @param string $time The time to convert
 *
 * @return integer The time as a decimal value.
 */
function timeToDecimal($time) {
    $timeArr = explode(':', $time);
    $decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
    return $decTime/60;
}