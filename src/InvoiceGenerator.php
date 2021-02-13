<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer;
use Toggle\ToggleHTTPClient;
use Toggle\InvoiceClient;

class InvoiceGenerator
{

    /**
     * @var InvoiceClient
     */
    public $invoiceClient;

    /**
     * Path to the template to use
     * @var string
     */
    public $templatePath;

    /**
     * Rate
     * @var int
     */
    public $rate;


    /**
     * Path to the template to use
     * @var Worksheet
     */
    private $worksheet;

    /**
     *
     * @var Writer\IWriter
     */
    private $writer;

    public function __construct(InvoiceClient $client = NULL,  $template = '/invoice_templates/template.xlsx', $rate = 75)
    {
        $this->invoiceClient = $client;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__ . $template);
        $this->worksheet = $spreadsheet->getActiveSheet();
        $this->writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $this->rate = $rate;
    }

    public function genInvoice($info, $outPutFileName = 'Invoice Service Invoice Hourly Rate.xlsx')
    {

        $data = $this->invoiceClient->getData($info);


        $template_row = 15;
        $worksheet = $this->worksheet;
        $worksheet->insertNewRowBefore(16, count($data) - 1);

        if (!empty($data)) {
            $total = 0;
            $total_hours = 0;
            foreach ($data as $row_index => $row_value) {
                $decimalHrs =  $this->toDecimal($this->formatMilliseconds($row_value[2]));
                $worksheet->setCellValue('B' . $template_row, $row_value[0]);
                $worksheet->setCellValue('C' . $template_row,  date('Y-m-d', strtotime($row_value[1])));
                $worksheet->setCellValue('D' . $template_row, $decimalHrs);
                $total_hours = $total_hours + $decimalHrs;
                $worksheet->setCellValue('E' . $template_row, $this->rate);
                $row_total = $this->rate * $decimalHrs;
                $worksheet->setCellValue('F' . $template_row, $row_total);
                $total = $total + $row_total;
                $template_row++;
            }

            $worksheet->setCellValue('F' . $template_row, $total);
            $worksheet->setCellValue('D' . $template_row, 'HOURS: ' . $total_hours);
        }
        $this->writer->save($outPutFileName);
    }

    function formatMilliseconds($milliseconds) {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $milliseconds = $milliseconds % 1000;
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;
        $format = '%u:%02u:%02u.%03u';
        $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
        return rtrim($time, '0');
    }

    /**
     * Convert time into decimal time.
     *
     * @param string $time The time to convert
     *
     * @return integer The time as a decimal value.
     */
    function toDecimal($time)
    {
        $timeArr = explode(':', $time);
        $decTime = ($timeArr[0] * 60) + ($timeArr[1]) + ($timeArr[2] / 60);
        return round($decTime / 60,3);
    }
}
 $myClient = new ToggleHTTPClient('XXXXX', '00000');
 $invoiceGenerator = new InvoiceGenerator($myClient);
 $invoiceGenerator->genInvoice(['since'=> '2020-11-30', 'until' => '2020-12-13', 'user' => '4431821']);
