<?php namespace App\Controllers\Reporting;
use App\Controllers\BaseController;
use App\Config\Constant;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\PremiModel;
use App\Models\LogUploadCampaignModel;
class ReportingController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
        $db2 = db_connect("secondary");
        $this->log_upload_campaign = new LogUploadCampaignModel($db2);
        $this->d_premi = new PremiModel();
    }

    //Export excel DPR
    public function exportDpr()
    {

        $url = BASE_API.'performance/export/dpr';

        $data = [];
        if(sessionCheck()){
            $data = array(
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'id_campaign' => $this->request->getPost('id_campaign'),
                'id_produk' => $this->request->getPost('id_produk'),
                'token' => session()->get('token')
            );
        } else if(!empty($this->request->getGet('secret'))){
            $data = array(
                'start_date' => $this->request->getGet('s'),
                'end_date' => $this->request->getGet('e'),
                'id_campaign' => $this->request->getPost('i'),
                'id_produk' => $this->request->getPost('n'),
                'token' => encryptor('decrypt',$this->request->getGet('secret'))
            );
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        // dd($res);
        if(!empty($this->request->getPost('id_campaign'))){
            $a = $this->log_upload_campaign->getAll(['id_campaign' => $this->request->getPost('id_campaign'), 'carnam'=>TRUE,])->getRowArray()['campaign'];
            $nampaign = $a->campaign;
        } else {
            $a = 'All Campaign';
        }
        if(!empty($this->request->getPost('id_produk'))){
            $b = $this->d_premi->getAll(['id_produk' => $this->request->getPost('id_produk'), 'carnam'=>TRUE,])->getRowArray()['produk'];
        dd($a);
            $produk = $b->produk;
        } else {
            $b = 'All Produk';
        }
        // var_dump($produks); die();
        
        if($res['error'] == FALSE)
        {
            $spreadsheet = new Spreadsheet();
                #Table Head Style
                $tableHead = [
                    'borders' => [
                    'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'wrapText' => TRUE
                    ],
            ];
            $color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00FFFF',
                    ]
                ],
            ];
        // #End
            // var_dump($res['data']);die();
        $spreadsheet->getDefaultstyle()
        ->getfont()
        ->setName('Arial');
        #STYLE COLOMN
        // $validation->setFormula1('A6:A10');
        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(15);
                $spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(20);
                $spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("H")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("I")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("J")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("K")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("L")->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension("M")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("N")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("O")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("P")->setWidth(7);
                $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setWidth(7);
                $spreadsheet->getActiveSheet()->getColumnDimension("R")->setWidth(9);
                $spreadsheet->getActiveSheet()->getColumnDimension("T")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("U")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("V")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("W")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("X")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("Y")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("Z")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AA")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AB")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AC")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AD")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AE")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AF")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AH")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AI")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AJ")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AK")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AL")->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension("AM")->setWidth(10);
                $spreadsheet->getActiveSheet()->getColumnDimension("AN")->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension("AO")->setWidth(12);
                $spreadsheet->getActiveSheet()->getColumnDimension("AP")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AQ")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AR")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AS")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AT")->setWidth(8);
                $spreadsheet->getActiveSheet()->getColumnDimension("AU")->setWidth(8);
#END STYLE COLOMN
                //Header
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet->setCellValue('A1',  'Total Campaign Tracking');
                $sheet->setCellValue('A2',  'Period');
                $sheet->setCellValue('D2',  $res['data']['reporting'][0]['start_date'].' - '.$res['data']['reporting'][0]['end_date']);
                // $sheet->setCellValue('E2',  endDate.format('YYYY-MM-DD'));
                $sheet->setCellValue('D3',  date('d-m-Y H:i:s'));
                $sheet->setCellValue('A3',  'Date Request');
                $sheet->setCellValue('A4',  'User Request');
                $sheet->setCellValue('A5',  'Data Source');
                $sheet->setCellValue('F2',  'Produk');
                $sheet->setCellValue('D4',  $res['data']['user_request']);
                if(!empty($a))$sheet->setCellValue('D5',  $a);
                if(!empty($produk))$sheet->setCellValue('H2',  $produk);
                $spreadsheet->getActiveSheet()->mergeCells("A1:C1");
                $spreadsheet->getActiveSheet()->mergeCells("A2:C2");
                $spreadsheet->getActiveSheet()->mergeCells("A3:C3");
                $spreadsheet->getActiveSheet()->mergeCells("A4:C4");
                $spreadsheet->getActiveSheet()->mergeCells("A5:C5");
                $spreadsheet->getActiveSheet()->mergeCells("F2:G2");
                $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getFont()->setSize(9)->setBold(true);
                //END
                $sheet->setCellValue('A6',  'Campaign ID');
                $sheet->setCellValue('B6',  'Campaign Name');
                $sheet->setCellValue('C6',  'Start Date (Upload Date)');
                $sheet->setCellValue('D6',  'End Date');
                $sheet->setCellValue('E6',  'Database');
                $sheet->setCellValue('E10', '#');
                $sheet->setCellValue('F6',  'Solicited');
                $sheet->setCellValue('F10',  '#');
                $sheet->setCellValue('G10',  '%');
                $sheet->setCellValue('H6',  'Terminated');
                $sheet->setCellValue('H10',  '#');
                $sheet->setCellValue('I10',  '%');
                $sheet->setCellValue('J6',  'Call Attempted');
                $sheet->setCellValue('J10',  '#');
                $sheet->setCellValue('K10',  'Avg');
                $sheet->setCellValue('L6',  'Solicited');
                $sheet->setCellValue('L7',  'Not Connected');
                $sheet->setCellValue('L8',  'Invalid');
                $sheet->setCellValue('L10', '#');
                $sheet->setCellValue('M7',  'TOTAL (CONNECTED)');
                $sheet->setCellValue('M10',  '#');
                $sheet->setCellValue('N10',  '%');
                $sheet->setCellValue('O6',  'CONTACTED');
                $sheet->setCellValue('O7',  'NOT CONTACTED');
                $sheet->setCellValue('O8',  'Busy');
                $sheet->setCellValue('O10',  '#');
                $sheet->setCellValue('P8',  'NPU');
                $sheet->setCellValue('P10', '#');
                $sheet->setCellValue('Q8',  'Miss Customer');
                $sheet->setCellValue('Q10', '#');
                $sheet->setCellValue('R8',  'Others');
                $sheet->setCellValue('R10',  '#');
                $sheet->setCellValue('S7',  'TOTAL ( CONTACTED )');
                $sheet->setCellValue('S10',  '#');
                $sheet->setCellValue('T10',  '%');
                $sheet->setCellValue('U6',  'CONTACTED');
                $sheet->setCellValue('U7',  'NOT PRESENTATION');
                $sheet->setCellValue('U8',  'Callback');
                $sheet->setCellValue('U10',  '#');
                $sheet->setCellValue('V8',  'RUF');
                $sheet->setCellValue('V10',  '#');
                $sheet->setCellValue('W8',  'Not Qualified');
                $sheet->setCellValue('W10',  '#');
                // $sheet->setCellValue('Y8',  'Change Number');
                // $sheet->setCellValue('Y10',  '#');
                $sheet->setCellValue('X8', 'NOT PRESENTATION');
                $sheet->setCellValue('X9',  '(Total)');
                $sheet->setCellValue('X10',  '#');
                $sheet->setCellValue('Y10',  '%');
                $sheet->setCellValue('Z7', 'TOTAL PRESENTATION');
                $sheet->setCellValue('Z10', '#');
                $sheet->setCellValue('AA10', '%');
                $sheet->setCellValue('AB6', 'PRESENTATION');
                $sheet->setCellValue('AB7', 'THINKING');
                $sheet->setCellValue('AB8', 'Thinking');
                $sheet->setCellValue('AB10', '#');
                $sheet->setCellValue('AC7', 'INTEREST');
                $sheet->setCellValue('AC8', 'Interest');
                $sheet->setCellValue('AC9', 'PIF');
                $sheet->setCellValue('AC10', '#');
                $sheet->setCellValue('AD9', 'Sales');
                $sheet->setCellValue('AD10', '#');
                $sheet->setCellValue('AE7', 'NOT INTEREST');
                $sheet->setCellValue('AE8', 'NOT INTEREST');
                $sheet->setCellValue('AE9', '(Total)');
                $sheet->setCellValue('AE10', '#');
                $sheet->setCellValue('AD10', '#');
                $sheet->setCellValue('AF8', 'Already Insured');
                $sheet->setCellValue('AF10', '#');
                $sheet->setCellValue('AG8', 'High Premium');
                $sheet->setCellValue('AG10', '#');
                $sheet->setCellValue('AH8', 'Need High Benefit');
                $sheet->setCellValue('AH10', '#');
                $sheet->setCellValue('AI8', 'Asking Investment Product');
                $sheet->setCellValue('AI10', '#');
                $sheet->setCellValue('AJ8', 'No Need Insurance');
                $sheet->setCellValue('AJ10', '#');
                $sheet->setCellValue('AK8', 'No interest Orders');
                $sheet->setCellValue('AK10', '#');
                $sheet->setCellValue('AL8', 'No Have CC / payment Mechanism');
                $sheet->setCellValue('AL10', '#');
                $sheet->setCellValue('AM8', 'Interest - no CC / payment Mechanism');
                $sheet->setCellValue('AM10', '#');
                $sheet->setCellValue('AN6', 'SCR');
                $sheet->setCellValue('AN10', '#');
                $sheet->setCellValue('AO6', 'RR');
                $sheet->setCellValue('AO10', '%');
                $sheet->setCellValue('AP6', 'ANP');
                $sheet->setCellValue('AP10', '#');
                $sheet->setCellValue('AQ6', 'AVARAGE PREMIUM');
                $sheet->setCellValue('AQ10', '#');
                $sheet->setCellValue('AR6', 'ACTUAL PREMI MONTHLY');
                $sheet->setCellValue('AR10', '#');
                // $sheet->setCellValue('AT6', 'PREMI');
                // $sheet->setCellValue('AT10', '#');
                //Merge Column
                $spreadsheet->getActiveSheet()->mergeCells("A6:A10");
                $spreadsheet->getActiveSheet()->mergeCells("B6:B10");
                $spreadsheet->getActiveSheet()->mergeCells("C6:C10");
                $spreadsheet->getActiveSheet()->mergeCells("D6:D10");
                $spreadsheet->getActiveSheet()->mergeCells("E6:E9");
                $spreadsheet->getActiveSheet()->mergeCells("F6:G9");
                $spreadsheet->getActiveSheet()->mergeCells("H6:I9");
                $spreadsheet->getActiveSheet()->mergeCells("J6:K9");
                $spreadsheet->getActiveSheet()->mergeCells("L6:N6");
                $spreadsheet->getActiveSheet()->mergeCells("L8:L9");
                // $spreadsheet->getActiveSheet()->mergeCells("L10:M10");
                $spreadsheet->getActiveSheet()->mergeCells("M7:N9");
                $spreadsheet->getActiveSheet()->mergeCells("O6:T6");
                $spreadsheet->getActiveSheet()->mergeCells("O7:R7");
                $spreadsheet->getActiveSheet()->mergeCells("O8:O9");
                $spreadsheet->getActiveSheet()->mergeCells("P8:P9");
                $spreadsheet->getActiveSheet()->mergeCells("Q8:Q9");
                $spreadsheet->getActiveSheet()->mergeCells("R8:R9");
                $spreadsheet->getActiveSheet()->mergeCells("S7:T9");
                $spreadsheet->getActiveSheet()->mergeCells("U6:AA6");
                $spreadsheet->getActiveSheet()->mergeCells("U7:Y7");
                $spreadsheet->getActiveSheet()->mergeCells("U8:U9");
                $spreadsheet->getActiveSheet()->mergeCells("V8:V9");
                $spreadsheet->getActiveSheet()->mergeCells("W8:W9");
                $spreadsheet->getActiveSheet()->mergeCells("X8:Y8");
                $spreadsheet->getActiveSheet()->mergeCells("X9:Y9");
                $spreadsheet->getActiveSheet()->mergeCells("Z7:AA9");
                $spreadsheet->getActiveSheet()->mergeCells("AB6:AM6");
                $spreadsheet->getActiveSheet()->mergeCells("AB8:AB9");
                $spreadsheet->getActiveSheet()->mergeCells("AC7:AD7");
                $spreadsheet->getActiveSheet()->mergeCells("AE7:AM7");
                $spreadsheet->getActiveSheet()->mergeCells("AC8:AD8");
                // $spreadsheet->getActiveSheet()->mergeCells("AE8:AF8");
                $spreadsheet->getActiveSheet()->mergeCells("AF8:AF9");
                $spreadsheet->getActiveSheet()->mergeCells("AG8:AG9");
                $spreadsheet->getActiveSheet()->mergeCells("AH8:AH9");
                $spreadsheet->getActiveSheet()->mergeCells("AI8:AI9");
                $spreadsheet->getActiveSheet()->mergeCells("AJ8:AJ9");
                $spreadsheet->getActiveSheet()->mergeCells("AK8:AK9");
                $spreadsheet->getActiveSheet()->mergeCells("AL8:AL9");
                $spreadsheet->getActiveSheet()->mergeCells("AM8:AM9");
                $spreadsheet->getActiveSheet()->mergeCells("AN6:AN9");
                $spreadsheet->getActiveSheet()->mergeCells("AO6:AO9");
                $spreadsheet->getActiveSheet()->mergeCells("AP6:AP9");
                $spreadsheet->getActiveSheet()->mergeCells("AQ6:AQ9");
                $spreadsheet->getActiveSheet()->mergeCells("AR6:AR9");
                $spreadsheet->getActiveSheet()->mergeCells("AS6:AS9");

                $spreadsheet->getActiveSheet()->getStyle('E6:E10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $col = 11;
                foreach($res['data']['reporting'] as $data)
                {
                    $sheet->setCellValue('A'.$col, 'ARWICS_'.date('MY'));
                    $sheet->setCellValue('B'.$col, 'ARWICS_'.date('MY'));
                    $sheet->setCellValue('C'.$col, $data['start_date']);
                    $sheet->setCellValue('D'.$col, $data['end_date']);
                    $sheet->setCellValue('E'.$col, $data['db']);
                    $sheet->setCellValue('F'.$col, $data['solicited']);
                    $sheet->setCellValue('G'.$col, $data['p_solicited']);
                    $sheet->setCellValue('H'.$col, $data['terminate']);
                    $sheet->setCellValue('I'.$col, $data['p_terminate']);
                    $sheet->setCellValue('J'.$col, $data['call_attempted']);
                    $sheet->setCellValue('K'.$col, $data['p_attempted']);
                    $sheet->setCellValue('L'.$col, $data['n_connected']);
                    $sheet->setCellValue('M'.$col, $data['y_connected']);
                    $sheet->setCellValue('N'.$col, $data['connected_rate']);
                    $sheet->setCellValue('O'.$col, $data['busy_line']);
                    $sheet->setCellValue('P'.$col, $data['npu']);
                    $sheet->setCellValue('Q'.$col, $data['miss_customer']);
                    $sheet->setCellValue('R'.$col, $data['others']);
                    $sheet->setCellValue('S'.$col, $data['total_contacted']);
                    $sheet->setCellValue('T'.$col, $data['p_total_contacted']);
                    $sheet->setCellValue('U'.$col, $data['call_back']);
                    $sheet->setCellValue('V'.$col, $data['ruf']);
                    $sheet->setCellValue('W'.$col, $data['not_qualified']);
                    $sheet->setCellValue('X'.$col, $data['total_not_presentation']);
                    $sheet->setCellValue('Y'.$col, $data['p_total_not_presentation']);
                    $sheet->setCellValue('Z'.$col, $data['total_presentation']);
                    $sheet->setCellValue('AA'.$col, $data['p_total_presentation']);
                    $sheet->setCellValue('AB'.$col, $data['thinking']);
                    $sheet->setCellValue('AC'.$col, $data['pif']);
                    $sheet->setCellValue('AD'.$col, $data['sales']);
                    $sheet->setCellValue('AE'.$col, $data['rejection']);
                    $sheet->setCellValue('AF'.$col, $data['already_insured']);
                    $sheet->setCellValue('AG'.$col, $data['high_premi']);
                    $sheet->setCellValue('AH'.$col, $data['need_high_benefit']);
                    $sheet->setCellValue('AI'.$col, $data['asking_investment']);
                    $sheet->setCellValue('AJ'.$col, $data['no_need_asurance']);
                    $sheet->setCellValue('AK'.$col, $data['not_interest_others']);
                    $sheet->setCellValue('AL'.$col, $data['no_have_cc']);
                    $sheet->setCellValue('AM' .$col, $data['interest_no_have_cc']);
                    $sheet->setCellValue('AN'.$col, $data['scr']);
                    $sheet->setCellValue('AO'.$col, $data['rr']);
                    $sheet->setCellValue('AP'.$col, $data['anp']);
                    $sheet->setCellValue('AQ'.$col, $data['average_premium']);
                    $sheet->setCellValue('AR'.$col, $data['actual_premi_monthly']);

                    $col++;
                }

                $spreadsheet->getActiveSheet()->getStyle('A6:AR'.($col-1))->applyFromArray($tableHead);
                $spreadsheet->getActiveSheet()->getStyle('A6:AR10')->applyFromArray($color);
                $spreadsheet->getActiveSheet()->getStyle('A1:AR10')->getFont()->setSize(9)->setBold(true);
                $writer = new Xlsx($spreadsheet);
                $fileName = 'DPR_CALL_TRACKING_'.date('Ymd');

                if(sessionCheck())
                {
                    ob_start();
                    $writer->save('php://output');
                    $file = ob_get_contents();
                    $res = array(
                        'filename' => $fileName,
                        'type' => 'xlsx',
                        'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
                    );
                    ob_end_clean();

                    die( json_encode($res, true));
                    // return $this->response->setJSON($res);
                } else {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename='.$fileName.'.xls');
                    $writer->save('php://output');
                    exit();
                }
                // Redirect hasil generate xlsx ke web client

        } else {
            $res = [
                'status' => 403,
                'error' => true,
                'data' => $res['data'],
                'message' => 'Validation Failed!'
            ];
            return $this->response->setJSON($res);
        }
    }
//END
    public function exportReporting(){
        $url = BASE_API.'performance/report';
        $data = [];
        if(sessionCheck()){
            $data = array(
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'token' => session()->get('token')
            );
        } else if(!empty($this->request->getGet('secret'))){
            $data = array(
                'start_date' => $this->request->getGet('s'),
                'end_date' => $this->request->getGet('e'),
                'token' => encryptor('decrypt',$this->request->getGet('secret'))
            );
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if($res['error'] == FALSE)
        {
            if(count($res['data']) <= 0){
                $res = [
                    'status' => 403,
                    'error' => true,
                    'data' => '',
                    'message' => 'Data Kosong'
                ];
                return $this->response->setJSON($res);
            }
            $spreadsheet = new Spreadsheet();
             #Table Head Style
             $tableHead = [
            'borders' => [
                'allBorders' => [
                  'borderStyle' => Border::BORDER_THIN
              ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFFFFF00',
                    ]
                ],
            ];
            $color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00FFFF',
                    ]
                ],
            ];
            // #End


            $spreadsheet->getDefaultstyle()
                ->getfont()
                ->setName('Calibri');
            #STYLE COLOMN
            $spreadsheet->getActiveSheet()->getColumnDimension("A")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("B")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("C")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("D")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("E")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("F")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("G")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("H")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("I")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("J")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("K")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("L")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("M")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("N")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("O")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("P")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("R")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("S")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("T")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("U")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("V")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("W")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("X")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("Y")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("Z")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AA")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AB")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AC")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AD")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AE")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AF")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AG")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AH")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AI")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AJ")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AK")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AL")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AM")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AN")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AO")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AP")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AR")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AS")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AT")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AU")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AV")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AW")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AX")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AY")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("AZ")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("BA")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("BB")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("BC")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("BD")->setAutoSize(10);
            $spreadsheet->getActiveSheet()->getColumnDimension("BE")->setAutoSize(10);


            #END STYLE COLOMN
            $sheet = $spreadsheet->setActiveSheetIndex(0);
            #header date
            $sheet->setCellValue('A1',  'Tanggal :');
            $sheet->setCellValue('B1',  $res['message']);
            $spreadsheet->getActiveSheet()->mergeCells("B1:C1");
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setSize(12);
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A3:BE3')->getFont()->setSize(11);
            
            $sheet->setCellValue('A3',  'POLICY_NUMBER');
            $sheet->setCellValue('B3',  'NO_PROPOSAL');
            $sheet->setCellValue('C3',  'NO_VA');
            $sheet->setCellValue('D3',  'NAMA_PEMEGANG_POLIS');
            $sheet->setCellValue('E3',  'ALAMAT_TINGGAL');
            $sheet->setCellValue('F3',  'KOTA');
            $sheet->setCellValue('G3',  'PROVINCE');
            $sheet->setCellValue('H3',  'TEMPAT_LAHIR');
            $sheet->setCellValue('I3',  'TANGGAL_LAHIR');
            $sheet->setCellValue('J3',  'NO_ID');
            $sheet->setCellValue('K3',  'GENDER');
            $sheet->setCellValue('L3',  'AGAMA');
            $sheet->setCellValue('M3',  'KODE_POS');
            $sheet->setCellValue('N3',  'NO_IDENTITAS');
            $sheet->setCellValue('O3',  'STATUS');
            $sheet->setCellValue('P3',  'FIRST STATUS QA');
            $sheet->setCellValue('Q3',  'NOMOR_RUMAH');
            $sheet->setCellValue('R3',  'NOMOR_RUMAH2');
            $sheet->setCellValue('S3',  'NOMOR_KANTOR');
            $sheet->setCellValue('T3',  'HANDPHONE_1');
            $sheet->setCellValue('U3',  'HANDPHONE_2');
            $sheet->setCellValue('V3',  'NOMOR_KANTOR2');
            $sheet->setCellValue('W3',  'EMAIL');
            $sheet->setCellValue('X3', 'CONTACT');
            $sheet->setCellValue('Y3', 'JENIS_PEKERJAAN');
            $sheet->setCellValue('Z3', 'BENEFICIARY1');
            $sheet->setCellValue('AA3', 'JENIS_KELAMIN1');
            $sheet->setCellValue('AB3', 'RELATION1');
            $sheet->setCellValue('AC3', 'TANGGAL_LAHIR1');
            $sheet->setCellValue('AD3', 'BENEFICIARY2');
            $sheet->setCellValue('AE3', 'JENIS_KELAMIN2');
            $sheet->setCellValue('AF3', 'RELATION2');
            $sheet->setCellValue('AG3', 'TANGGAL_LAHIR2');
            $sheet->setCellValue('AH3', 'BENEFICIARY3');
            $sheet->setCellValue('AI3', 'JENIS_KELAMIN3');
            $sheet->setCellValue('AJ3', 'RELATION3');
            $sheet->setCellValue('AK3', 'TANGGAL_LAHIR3');
            $sheet->setCellValue('AL3', 'CREDIT_CARD');
            $sheet->setCellValue('AM3', 'NO_CC');
            $sheet->setCellValue('AN3', 'BANK_PENERBIT_CC');
            $sheet->setCellValue('AO3', 'NAMA_PEMEGANG');
            $sheet->setCellValue('AP3', 'MASA_BERLAKU_CC');
            $sheet->setCellValue('AQ3', 'PRODUCT');
            $sheet->setCellValue('AR3', 'UMUR_TH');
            $sheet->setCellValue('AS3', 'UMUR_BL');
            $sheet->setCellValue('AT3', 'PLAN');
            $sheet->setCellValue('AU3', 'PEMBAYARAN');
            $sheet->setCellValue('AV3', 'PREMI');
            $sheet->setCellValue('AW3', 'SELLER_ID');
            $sheet->setCellValue('AX3', 'NAMA_AGENT');
            $sheet->setCellValue('AY3', 'DATE_SELLING');
            $sheet->setCellValue('AZ3', 'CAMPAIGN_NAME');
            $sheet->setCellValue('BA3', 'STATUS QA');
            $sheet->setCellValue('BB3', 'SPV');
            $sheet->setCellValue('BC3', 'QA ID');
            $sheet->setCellValue('BD3', 'CUSTOMER ID');
            $sheet->setCellValue('BE3', 'DATE QA');
            $spreadsheet->getActiveSheet()->getStyle('A3:BE3')->applyFromArray($tableHead);
            $spreadsheet->getActiveSheet()->getStyle('A3:BE3')->applyFromArray($color);

            $col = 4;

            foreach($res['data'] as $data)
            {
                $sheet->setCellValue('A'.$col, $data['no_proposal']);
                $sheet->setCellValue('B'.$col, $data['no_spaj']);
                $sheet->setCellValue('C'.$col, $data['virtual_account']);
                $sheet->setCellValue('D'.$col, $data['nama']);
                $sheet->setCellValue('E'.$col, $data['alamat']);
                $sheet->setCellValue('F'.$col, $data['kota']);
                $sheet->setCellValue('G'.$col, $data['provinsi']);
                $sheet->setCellValue('H'.$col, $data['tempat_lahir']);
                $sheet->setCellValue('I'.$col, $data['tgl_lahir']);
                $sheet->setCellValue('J'.$col, $data['id_data_nasabah']);
                $sheet->setCellValue('K'.$col, JK[$data['jk']]);
                $sheet->setCellValue('L'.$col, $data['agama']);
                $sheet->setCellValue('M'.$col, $data['pos']);
                $sheet->setCellValue('N'.$col, $data['NIK']);
                $sheet->setCellValue('O'.$col, STATUS_KAWIN[$data['status']]);
                $sheet->setCellValue('P'.$col, CHECKED[$data['first_status']]);
                $sheet->setCellValue('Q'.$col, $data['telp_rumah']);
                $sheet->setCellValue('R'.$col, '');
                $sheet->setCellValue('S'.$col, $data['telp_kantor']);
                $sheet->setCellValue('T'.$col, $data['telp1']);
                $sheet->setCellValue('U'.$col, $data['telp2']);
                $sheet->setCellValue('V'.$col, '');
                $sheet->setCellValue('W'.$col, '');
                $sheet->setCellValue('X'.$col, $data['telp_cp']);
                $sheet->setCellValue('Y'.$col, $data['pekerjaan']);
                $sheet->setCellValue('Z'.$col, $data['wali_nama']);
                $sheet->setCellValue('AA'.$col, JK[$data['wali_jk']]);
                $sheet->setCellValue('AB'.$col, HUBUNGAN[intval($data['wali_hubungan'])-1]);
                $sheet->setCellValue('AC'.$col, $data['wali_tgl_lahir']);
                $sheet->setCellValue('AD'.$col, $data['wali_nama2']);
                $sheet->setCellValue('AE'.$col, !empty($data['wali_jk2']) ? JK[$data['wali_jk2']] : "");
                $sheet->setCellValue('AF'.$col, !empty($data['wali_hubungan2']) ? HUBUNGAN[intval($data['wali_hubungan2'])-1] : "");
                $sheet->setCellValue('AG'.$col, $data['wali_tgl_lahir2']);
                $sheet->setCellValue('AH'.$col, $data['wali_nama3']);
                $sheet->setCellValue('AI'.$col, !empty($data['wali_jk3']) ? JK[$data['wali_jk3']] : "");
                $sheet->setCellValue('AJ'.$col, !empty($data['wali_hubungan3']) ? HUBUNGAN[intval($data['wali_hubungan3'])-1] : "");
                $sheet->setCellValue('AK'.$col, $data['wali_tgl_lahir3']);
                $sheet->setCellValue('AL'.$col, $data['jenis_cc']);
                $sheet->setCellValue('AM'.$col, $data['card_number']);
                $sheet->setCellValue('AN'.$col, $data['bank']);
                $sheet->setCellValue('AO'.$col, $data['nama']);
                $sheet->setCellValue('AP'.$col, $data['expired_date']);
                $sheet->setCellValue('AQ'.$col, 'LifeProtection20');
                $sheet->setCellValue('AR'.$col, hitung_umur($data['tgl_lahir'])[0]);
                $sheet->setCellValue('AS'.$col, hitung_umur($data['tgl_lahir'])[1]);
                $sheet->setCellValue('AT'.$col, strtoupper($data['nama_produk']));
                $sheet->setCellValue('AU'.$col, SATUAN[$data['satuan']]);
                $sheet->setCellValue('AV'.$col, number_format($data['nominal'],2,".",""));
                $sheet->setCellValue('AW'.$col, $data['seller_id']);
                $sheet->setCellValue('AX'.$col, $data['tsr_nama']);
                $sheet->setCellValue('AY'.$col, date('Y-m-d'));
                $sheet->setCellValue('AZ'.$col, 'ARWICS_'.date('MY'));
                $sheet->setCellValue('BA'.$col, CHECKED[$data['checked']]);
                $sheet->setCellValue('BB'.$col, $data['leader_nama']);
                $sheet->setCellValue('BC'.$col, $data['qa_id']);
                $sheet->setCellValue('BD'.$col, $data['id_data_nasabah']);
                $sheet->setCellValue('BE'.$col, $data['date_qa']);
                $col++;
            }
            $spreadsheet->getActiveSheet()->getStyle('C4:C'.($col-1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('N4:N'.($col-1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
            $spreadsheet->getActiveSheet()->getStyle('AV4:AV'.($col-1))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Submission Report_'.date('Ymd');
            // Redirect hasil generate xlsx ke web client
            // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
            // header('Cache-Control: max-age=0');
            // header('Cache-Control: max-age=1');
            // header('Cache-Control: cache, must-revalidate');
            // header('Pragma: public');
            // $writer->save('php://output');
            // exit();
            if(sessionCheck())
            {
                ob_start();
                $writer->save('php://output');
                $file = ob_get_contents();
                $res = array(
                    'filename' => $fileName,
                    'type' => 'xlsx',
                    'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
                );
                ob_end_clean();

                die( json_encode($res, true));
                // return $this->response->setJSON($res);
            } else {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$fileName.'.xls');
                $writer->save('php://output');
                exit();
            }

        }
    }
    public function exportApr(){

        $url = BASE_API.'performance/export/apr';

        if(sessionCheck()){
            $data = array(
                'start_date'   => $this->request->getPost('start_date'),
                'end_date'     => $this->request->getPost('end_date'),
                'token'        => session()->get('token'),
                'id_campaign'  => $this->request->getPost('id_campaign'),
                'id_produk'  => $this->request->getPost('id_produk'),
                'tsr_ids'      => $this->request->getPost('tsr_ids')
            );
        } else if(!empty($this->request->getGet('secret'))){
            $tsr_ids = json_decode(encryptor('decrypt',$this->request->getGet('ids')));
            $data = array(
                'start_date' => $this->request->getGet('s'),
                'end_date' => $this->request->getGet('e'),
                'token' => encryptor('decrypt',$this->request->getGet('secret'))
            );
            $data['tsr_ids'] = empty($tsr_ids) ? [] : $tsr_ids;
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
        $fields = http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);   

            if(!empty($this->request->getPost('id_campaign'))){
            $a = $this->log_upload_campaign->getAll(['id_campaign' => $this->request->getPost('id_campaign'), 'carnam'=>TRUE])->getRowArray()['campaign'];
            // $nampaign = $a->campaign; 
        } else {
            $a = 'All Campaign';
        }

        if($res['error'] == FALSE)
        {
            $spreadsheet = new Spreadsheet();
                #Table Head Style
                $spreadsheet = new Spreadsheet();
                #Table Head Style
                $tableHead = [
                    'borders' => [
                    'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'wrapText' => TRUE
                    ],
            ];
            $color = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '00FFFF',
                    ]
                ],
            ];
            // #End
            $spreadsheet->getDefaultstyle()
            ->getfont()
            ->setName('Arial');
            #STYLE COLOMN
            $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("H")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("I")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("J")->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension("K")->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension("L")->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension("M")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("N")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("O")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("P")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension("R")->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension("T")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("U")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("V")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("W")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("X")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("Y")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("Z")->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension("AA")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AB")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AC")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AD")->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension("AE")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AF")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AH")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AI")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AJ")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AK")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AL")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AM")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AN")->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension("AO")->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension("AP")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AQ")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AR")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AS")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AT")->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension("AU")->setWidth(8);
            #END STYLE COLOMN
            //Header
            $sheet = $spreadsheet->setActiveSheetIndex(0);
            $sheet->setCellValue('A1',  'Total Campaign Tracking');
            $sheet->setCellValue('A2',  'Period');
            $sheet->setCellValue('D2',  $res['data']['period']);
            $sheet->setCellValue('D3',  date('l jS \of F Y h:i:s A'));
            $sheet->setCellValue('A3',  'Date Request');
            $sheet->setCellValue('A4',  'User Request');
            $sheet->setCellValue('A5',  'Data Source:');
            $sheet->setCellValue('D4',  $res['data']['user_request']);
            if(!empty($a))$sheet->setCellValue('D5',  $a);
            $spreadsheet->getActiveSheet()->mergeCells("A1:C1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:C2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:C3");
            $spreadsheet->getActiveSheet()->mergeCells("A4:C4");
            $spreadsheet->getActiveSheet()->mergeCells("A5:C5");
            $spreadsheet->getActiveSheet()->getStyle('A1:A5')->getFont()->setSize(9)->setBold(true);
            //END
            $sheet->setCellValue('A6',  'TELEMARKETER ID');
            $sheet->setCellValue('B6',  'TELEMARKETER Name');
            $sheet->setCellValue('C6',  'TELEMARKETER SPV');
            $sheet->setCellValue('D6',  'Database');
            $sheet->setCellValue('D10', '#');
            $sheet->setCellValue('E6',  'Solicited');
            $sheet->setCellValue('E10',  '#');
            $sheet->setCellValue('F10',  '%');
            $sheet->setCellValue('G6',  'Terminated');
            $sheet->setCellValue('G10',  '#');
            $sheet->setCellValue('H10',  '%');
            $sheet->setCellValue('I6',  'Call Attempted');
            $sheet->setCellValue('I10',  '#');
            $sheet->setCellValue('J10',  'Avg');
            $sheet->setCellValue('K6',  'Talk Time');
            $sheet->setCellValue('K10', '#');
            $sheet->setCellValue('L6',  'CONNECTED');
            $sheet->setCellValue('L7',  'CONNECTED');
            $sheet->setCellValue('L10', '#');
            $sheet->setCellValue('M7',  'NOT CONNECTED');
            $sheet->setCellValue('M8',  'Busy');
            $sheet->setCellValue('M10',  '#');
            $sheet->setCellValue('N6',  'CONNECTED');
            $sheet->setCellValue('N7',  'NOT CONNECTED');
            $sheet->setCellValue('N8',  'NPU');
            $sheet->setCellValue('N10', '#');
            $sheet->setCellValue('O8',  'Miss Customer');
            $sheet->setCellValue('O10', '#');
            $sheet->setCellValue('P8',  'Invalid');
            $sheet->setCellValue('P10', '#');
            $sheet->setCellValue('Q7',  'TOTAL (CONNECTED)');
            $sheet->setCellValue('Q10',  '#');
            $sheet->setCellValue('R7',  'Connected Rate');
            $sheet->setCellValue('R10',  '%');
            $sheet->setCellValue('S6',  'CONTACTED');
            $sheet->setCellValue('S7',  'Contacted');
            $sheet->setCellValue('S10',  '#');
            $sheet->setCellValue('T7',  'NOT PRESENTATION');
            $sheet->setCellValue('T8',  'Callback');
            $sheet->setCellValue('T10',  '#');
            $sheet->setCellValue('U8',  'RUF');
            $sheet->setCellValue('U10',  '#');
            $sheet->setCellValue('V8',  'Not Qualified');
            $sheet->setCellValue('V10',  '#');
            $sheet->setCellValue('W7',  'PRESENTATION');
            $sheet->setCellValue('W8',  'THINKING');
            $sheet->setCellValue('W10',  '#');
            $sheet->setCellValue('X8', 'INTEREST');
            $sheet->setCellValue('X9',  'PIF');
            $sheet->setCellValue('X10',  '#');
            $sheet->setCellValue('Y9',  'SALES');
            $sheet->setCellValue('Y10',  '#');
            $sheet->setCellValue('Z7',  'Contact Rate');
            $sheet->setCellValue('Z10',  '%');
            $sheet->setCellValue('AA7', 'PRESENTATION');
            $sheet->setCellValue('AA8', 'Already Insured');
            $sheet->setCellValue('AA10', '#');
            $sheet->setCellValue('AB8', 'High Premium');
            $sheet->setCellValue('AB10', '#');
            $sheet->setCellValue('AC8', 'Need High Benefit');
            $sheet->setCellValue('AC10', '#');
            $sheet->setCellValue('AD8', 'Asking Investment Product');
            $sheet->setCellValue('AD10', '#');
            $sheet->setCellValue('AE8', 'No Need Insurance');
            $sheet->setCellValue('AE10', '#');
            $sheet->setCellValue('AF8', 'Not Interest Others');
            $sheet->setCellValue('AF10', '#');
            $sheet->setCellValue('AG8', 'No Have CC / Payment Mechanism');
            $sheet->setCellValue('AG10', '#');
            $sheet->setCellValue('AH8', 'Total Rejections');
            $sheet->setCellValue('AH10', '#');
            $sheet->setCellValue('AI8', 'SCR');
            $sheet->setCellValue('AI10', '%');
            $sheet->setCellValue('AJ8', 'RR');
            $sheet->setCellValue('AJ10', '%');
            $sheet->setCellValue('AK8', 'Premium');
            $sheet->setCellValue('AK10', '#');
            $sheet->setCellValue('AL8', 'ANP');
            $sheet->setCellValue('AL10', '#');
            $sheet->setCellValue('AM8', 'AVG Premium');
            $sheet->setCellValue('AM10', '#');

            $spreadsheet->getActiveSheet()->mergeCells("A6:A9");
            $spreadsheet->getActiveSheet()->mergeCells("B6:B9");
            $spreadsheet->getActiveSheet()->mergeCells("C6:C9");
            $spreadsheet->getActiveSheet()->mergeCells("D6:D9");
            $spreadsheet->getActiveSheet()->mergeCells("E6:F9");
            $spreadsheet->getActiveSheet()->mergeCells("G6:H9");
            $spreadsheet->getActiveSheet()->mergeCells("I6:J9");
            $spreadsheet->getActiveSheet()->mergeCells("K6:K9");
            $spreadsheet->getActiveSheet()->mergeCells("L6:R6");
            $spreadsheet->getActiveSheet()->mergeCells("L7:L9");
            $spreadsheet->getActiveSheet()->mergeCells("M7:P7");
            $spreadsheet->getActiveSheet()->mergeCells("M8:M9");
            $spreadsheet->getActiveSheet()->mergeCells("N8:N9");
            $spreadsheet->getActiveSheet()->mergeCells("O8:O9");
            $spreadsheet->getActiveSheet()->mergeCells("P8:P9");
            $spreadsheet->getActiveSheet()->mergeCells("Q7:Q9");
            $spreadsheet->getActiveSheet()->mergeCells("R7:R9");
            $spreadsheet->getActiveSheet()->mergeCells("S6:Z6");
            $spreadsheet->getActiveSheet()->mergeCells("S7:S9");
            $spreadsheet->getActiveSheet()->mergeCells("T7:V7");
            $spreadsheet->getActiveSheet()->mergeCells("W7:Y7");
            $spreadsheet->getActiveSheet()->mergeCells("X8:Y8");
            $spreadsheet->getActiveSheet()->mergeCells("Z7:Z9");
            $spreadsheet->getActiveSheet()->mergeCells("AA6:AM6");
            $spreadsheet->getActiveSheet()->mergeCells("AA7:AH7");
            $spreadsheet->getActiveSheet()->mergeCells("AA8:AA9");
            $spreadsheet->getActiveSheet()->mergeCells("AB8:AB9");
            $spreadsheet->getActiveSheet()->mergeCells("AC8:AC9");
            $spreadsheet->getActiveSheet()->mergeCells("AD8:AD9");
            $spreadsheet->getActiveSheet()->mergeCells("AE8:AE9");
            $spreadsheet->getActiveSheet()->mergeCells("AF8:AF9");
            $spreadsheet->getActiveSheet()->mergeCells("AG8:AG9");
            $spreadsheet->getActiveSheet()->mergeCells("AH8:AH9");
            $spreadsheet->getActiveSheet()->mergeCells("AI7:AM7");
            $spreadsheet->getActiveSheet()->mergeCells("AI8:AI9");
            $spreadsheet->getActiveSheet()->mergeCells("AJ8:AJ9");
            $spreadsheet->getActiveSheet()->mergeCells("AK8:AK9");
            $spreadsheet->getActiveSheet()->mergeCells("AL8:AL9");
            $spreadsheet->getActiveSheet()->mergeCells("AM8:AM9");
            //end
            $spreadsheet->getActiveSheet()->getStyle('E6:E10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col = 11;
            // update eka 20210317
            $rowx = count($res['data']['reporting']);
            // update eka 20210317
            if(count($res['data']['reporting']) <= 0){
                $r = [
                    'status' => 403,
                    'error' => true,
                    'data' => '',
                    'message' => 'Data Kosong'
                ];
                return $this->response->setJSON($r);
            }
            foreach($res['data']['reporting'] as $data)
            {
                $sheet->setCellValue('A'.$col, $data['id_login']);
                $sheet->setCellValue('B'.$col, $data['tsr_nama']);
                $sheet->setCellValue('C'.$col, $data['leader_nama']);
                $sheet->setCellValue('D'.$col, $data['db']);
                $sheet->setCellValue('E'.$col, $data['solicited']);
                $sheet->setCellValue('F'.$col, $data['p_solicited']);
                $sheet->setCellValue('G'.$col, $data['terminate']);
                $sheet->setCellValue('H'.$col, $data['p_terminate']);
                $sheet->setCellValue('I'.$col, $data['call_attempted']);
                $sheet->setCellValue('J'.$col, $data['p_attempted']);
                $sheet->setCellValue('K'.$col, $data['talk']);
                $sheet->setCellValue('L'.$col, $data['y_connected']);
                $sheet->setCellValue('M'.$col, $data['busy_line']);
                $sheet->setCellValue('N'.$col, $data['npu']);
                $sheet->setCellValue('O'.$col, $data['miss_customer']);
                $sheet->setCellValue('P'.$col, $data['n_connected']);
                $sheet->setCellValue('Q'.$col, $data['y_connected']);
                $sheet->setCellValue('R'.$col, $data['connected_rate']);
                $sheet->setCellValue('S'.$col, $data['total_contacted']);
                $sheet->setCellValue('T'.$col, $data['call_back']);
                $sheet->setCellValue('U'.$col, $data['ruf']);
                $sheet->setCellValue('V'.$col, $data['not_qualified']);
                $sheet->setCellValue('W'.$col, $data['thinking']);
                $sheet->setCellValue('X'.$col, $data['pif']);
                $sheet->setCellValue('Y'.$col, $data['sales']);
                $sheet->setCellValue('Z'.$col, $data['p_total_contacted']);
                $sheet->setCellValue('AA'.$col, $data['already_insured']);
                $sheet->setCellValue('AB'.$col, $data['high_premi']);
                $sheet->setCellValue('AC'.$col, $data['need_high_benefit']);
                $sheet->setCellValue('AD'.$col, $data['asking_investment']);
                $sheet->setCellValue('AE'.$col, $data['no_need_asurance']);
                $sheet->setCellValue('AF'.$col, $data['not_interest_others']);
                $sheet->setCellValue('AG'.$col, $data['no_have_cc']);
                $sheet->setCellValue('AH'.$col, $data['rejection']);
                $sheet->setCellValue('AI'.$col, $data['scr']);
                $sheet->setCellValue('AJ'.$col, $data['rr']);
                $sheet->setCellValue('AL'.$col, $data['anp']);
                $sheet->setCellValue('AK'.$col, $data['premium']);
                $sheet->setCellValue('AM'.$col, $data['average_premium']);

                $col++;
            }
            $sheet->setCellValue('A'.($col), 'SUMMARY');
            $spreadsheet->getActiveSheet()->mergeCells("A$col:C$col");
            // update eka 20210329
            //$sheet->setCellValue('D'.$col, $res['data']['summary']['db']);
            $ddata0 = $res['data']['summary']['db'];
            if($rowx != 0){
                $ddata = ($ddata0 / $rowx);
            }else{
                $ddata = 0;
            }
            $sheet->setCellValue('D'.$col, $ddata);
            // update eka 20210329
            $sheet->setCellValue('E'.$col, $res['data']['summary']['solicited']);
            // update eka 20210317
            //$sheet->setCellValue('F'.$col, $res['data']['summary']['p_solicited']);
            $fdata0 = $res['data']['summary']['solicited'];
            //$fdata1 = $res['data']['summary']['db'];
            //if($fdata1 != 0){
            //    $fdata = ($fdata0 / $fdata1) * 100;
            if($ddata != 0){
                $fdata = ($fdata0 / $ddata) * 100;
            }else{
                $fdata = 0;
            }
            $sheet->setCellValue('F'.$col, $fdata);
            // update eka 20210317
            $sheet->setCellValue('G'.$col, $res['data']['summary']['terminate']);
            // update eka 20210317
            //$sheet->setCellValue('H'.$col, $res['data']['summary']['p_terminate']);
            $hdata0 = $res['data']['summary']['terminate'];
            $hdata1 = $res['data']['summary']['solicited'];
            if($hdata1 != 0){
                $hdata = ($hdata0 / $hdata1) * 100;
            }else{
                $hdata = 0;
            }
            $sheet->setCellValue('H'.$col, $hdata);
            // update eka 20210317
            $sheet->setCellValue('I'.$col, $res['data']['summary']['call_attempted']);
            // update eka 20210317
            //$sheet->setCellValue('J'.$col, $res['data']['summary']['p_attempted']);
            $jdata0 = $res['data']['summary']['call_attempted'];
            $jdata1 = $res['data']['summary']['solicited'];
            if($jdata1 != 0){
                $jdata = floor($jdata0 / $jdata1);
            }else{
                $jdata = 0;
            }
            $sheet->setCellValue('J'.$col, $jdata);
            // update eka 20210317
            // update eka 20210317
            //$sheet->setCellValue('K'.$col, $res['data']['summary']['talk']);
            $init = $res['data']['summary']['talk1'];
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $init1 = $hours.":".$minutes.":".$seconds;
                        $sheet->setCellValue('K'.$col, $init1);
                        // update eka 20210317
            $sheet->setCellValue('L'.$col, $res['data']['summary']['y_connected']);
            $sheet->setCellValue('M'.$col, $res['data']['summary']['busy_line']);
            $sheet->setCellValue('N'.$col, $res['data']['summary']['npu']);
            $sheet->setCellValue('O'.$col, $res['data']['summary']['miss_customer']);
            $sheet->setCellValue('P'.$col, $res['data']['summary']['n_connected']);
            $sheet->setCellValue('Q'.$col, $res['data']['summary']['y_connected']);
            // update eka 20210317
            // $sheet->setCellValue('R'.$col, $res['data']['summary']['connected_rate']);
            $rdata0 = $res['data']['summary']['y_connected'];
            $rdata1 = $res['data']['summary']['solicited'];
            if($rdata1 != 0){
                $rdata = ($rdata0 / $rdata1) * 100;
            }else{
                $rdata = 0;
            }
            $sheet->setCellValue('R'.$col, $rdata);
            // update eka 20210317
            $sheet->setCellValue('S'.$col, $res['data']['summary']['total_contacted']);
            $sheet->setCellValue('T'.$col, $res['data']['summary']['call_back']);
            $sheet->setCellValue('U'.$col, $res['data']['summary']['ruf']);
            $sheet->setCellValue('V'.$col, $res['data']['summary']['not_qualified']);
            $sheet->setCellValue('W'.$col, $res['data']['summary']['thinking']);
            $sheet->setCellValue('X'.$col, $res['data']['summary']['pif']);
            $sheet->setCellValue('Y'.$col, $res['data']['summary']['sales']);
            // update eka 20210317
            // $sheet->setCellValue('Z'.$col, $res['data']['summary']['p_total_contacted']);
            $zdata0 = $res['data']['summary']['total_contacted'];
            $zdata1 = $res['data']['summary']['solicited'];
            if ($zdata1 != 0) {
                $zdata = ($zdata0 / $zdata1) * 100;
            }else{
                $zdata = 0;
            }
            $sheet->setCellValue('Z'.$col, $zdata);
            // update eka 20210317
            $sheet->setCellValue('AA'.$col, $res['data']['summary']['already_insured']);
            $sheet->setCellValue('AB'.$col, $res['data']['summary']['high_premi']);
            $sheet->setCellValue('AC'.$col, $res['data']['summary']['need_high_benefit']);
            $sheet->setCellValue('AD'.$col, $res['data']['summary']['asking_investment']);
            $sheet->setCellValue('AE'.$col, $res['data']['summary']['no_need_asurance']);
            $sheet->setCellValue('AF'.$col, $res['data']['summary']['not_interest_others']);
            $sheet->setCellValue('AG'.$col, $res['data']['summary']['no_have_cc']);
            $sheet->setCellValue('AH'.$col, $res['data']['summary']['rejection']);
            $sheet->setCellValue('AI'.$col, $res['data']['summary']['scr']);
            $sheet->setCellValue('AJ'.$col, $res['data']['summary']['rr']);
            $sheet->setCellValue('AL'.$col, $res['data']['summary'][ 'anp']);
            $sheet->setCellValue('AK'.$col, $res['data']['summary']['premium']);
            $sheet->setCellValue('AM'.$col, $res['data']['summary']['average_premium']);

            $spreadsheet->getActiveSheet()->getStyle('A6:AM'.($col))->applyFromArray($tableHead);
            $spreadsheet->getActiveSheet()->getStyle('A6:AM10')->applyFromArray($color);
            $spreadsheet->getActiveSheet()->getStyle("A$col:AM$col")->applyFromArray($color);
            $spreadsheet->getActiveSheet()->getStyle('A1:AM10')->getFont()->setSize(9);
            $writer = new Xlsx($spreadsheet);
            $fileName = 'APR_Total_Agent_Tracking_'.date('Ymd');
            if(sessionCheck())
            {
                ob_start();
                $writer->save('php://output');
                $file = ob_get_contents();
                $res = array(
                    'filename' => $fileName,
                    'type' => 'xlsx',
                    'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
                );
                ob_end_clean();

                die( json_encode($res, true));
                // return $this->response->setJSON($res);
            } else {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$fileName.'.xls');
                $writer->save('php://output');
                exit();
            }

        } else {
            $res = [
                'status' => 403,
                'error' => true,
                'data' => $res['data'],
                'message' => 'Validation Failed!'
            ];
            return $this->response->setJSON($res);
        }
    }
}
