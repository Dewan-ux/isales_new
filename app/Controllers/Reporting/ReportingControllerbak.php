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
class ReportingController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
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
                $sheet->setCellValue('D4',  $res['data']['user_request']);
                $spreadsheet->getActiveSheet()->mergeCells("A1:C1");
                $spreadsheet->getActiveSheet()->mergeCells("A2:C2");
                $spreadsheet->getActiveSheet()->mergeCells("A3:C3");
                $spreadsheet->getActiveSheet()->mergeCells("A4:C4");
                $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getFont()->setSize(9)->setBold(true);
                //END
                $sheet->setCellValue('A6',  'Campaign ID');
                $sheet->setCellValue('B6',  'Campaign Name');
                $sheet->setCellValue('C6',  'Start Date (Upload Date)');
                $sheet->setCellValue('D6',  'End Date');
                $sheet->setCellValue('E6',  'Databaase');
                $sheet->setCellValue('E10', '#');
                $sheet->setCellValue('F6',  'Solicited');
                $sheet->setCellValue('F10',  '#');
                $sheet->setCellValue('G10',  '%');
                $sheet->setCellValue('H6',  'Terminated');
                $sheet->setCellValue('H10',  '#');
                $sheet->setCellValue('I10',  '%');
                $sheet->setCellValue('J6',  'Call Attempted');
                $sheet->setCellValue('J10',  '#');
                $sheet->setCellValue('K10',  '%');
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
                $sheet->setCellValue('S7',  'TOTAL ( CONNECTED )');
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
                // Redirect hasil generate xlsx ke web client

                // header('Content-Type: application/vnd.ms-excel');
                // header('Content-Disposition: attachment;filename='.$fileName.'.Xlsx');
                // header('Cache-Control: max-age=0');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');

                $writer->save('php://output');
                exit();
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
        if(sessionCheck()){
            $url = BASE_API.'performance/report';

            $data = array(
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'token' => session()->get('token'),
            );
            // $hash = $this->request->getGet('secret');
            // if($hash){
            //     $data['token'] = encryptor('decrypt', $hash);
            // } else if(sessionCheck()){
            //     $data['token'] = session()->get('token');
            // } else {
            //     // die('access denied');
            // }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($res['error'] == FALSE)
            {

            $spreadsheet = new Spreadsheet();
             #Table Head Style
             $tableHead = [
                'font' =>[
                    'color'=>[
                        'rgb'=>'FFFFFF'
                ]
                    ],
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
        // #End


            $spreadsheet->getDefaultstyle()
                ->getfont()
                ->setName('Calibri');
            #STYLE COLOMN
            $spreadsheet->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("R")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("S")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("T")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("U")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("V")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("W")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("X")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("Y")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("Z")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AA")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AD")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AE")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AF")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AG")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AH")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AI")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AJ")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AK")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AL")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AM")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AN")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AO")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AP")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AQ")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AR")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AS")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AT")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AU")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AV")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AW")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AX")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AY")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("AZ")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("BA")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("BB")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("BC")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("BD")->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension("BE")->setAutoSize(true);

            #END STYLE COLOMN
            $sheet = $spreadsheet->setActiveSheetIndex(0);
            #header date
            $sheet->setCellValue('A1',  'Tanggal :');
            $sheet->setCellValue('B1',  date('D M y, H:i'));
            $spreadsheet->getActiveSheet()->mergeCells("B1:C1");
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getFont()->setSize(20);
            $spreadsheet->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A3:BE3')->getFont()->setSize(13);
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
            $spreadsheet->getActiveSheet()->getStyle('A:BE')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A3:BE3')->applyFromArray($tableHead);

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
                $sheet->setCellValue('K'.$col, $data['jk']);
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
                $sheet->setCellValue('AA'.$col, $data['wali_jk']);
                $sheet->setCellValue('AB'.$col, HUBUNGAN[intval($data['wali_hubungan'])-1]);
                $sheet->setCellValue('AC'.$col, $data['wali_tgl_lahir']);
                $sheet->setCellValue('AD'.$col, $data['wali_nama2']);
                $sheet->setCellValue('AE'.$col, $data['wali_jk2']);
                $sheet->setCellValue('AB'.$col, !empty($data['wali_hubungan2']) ? HUBUNGAN[intval($data['wali_hubungan2'])-1] : "");
                $sheet->setCellValue('AG'.$col, $data['wali_tgl_lahir2']);
                $sheet->setCellValue('AH'.$col, $data['wali_nama3']);
                $sheet->setCellValue('AI'.$col, $data['wali_jk3']);
                $sheet->setCellValue('AB'.$col, !empty($data['wali_hubungan3']) ? HUBUNGAN[intval($data['wali_hubungan3'])-1] : "");
                $sheet->setCellValue('AK'.$col, $data['wali_tgl_lahir3']);
                $sheet->setCellValue('AL'.$col, $data['card_number']);
                $sheet->setCellValue('AM'.$col, '#');
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

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Daily Report SPAJ_'.date('Ymd');
            // Redirect hasil generate xlsx ke web client
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $writer->save('php://output');
            exit();

            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    public function exportApr(){

            $url = BASE_API.'performance/export/apr';

            if(sessionCheck()){
                $data = array(
                    'start_date' => $this->request->getPost('start_date'),
                    'end_date' => $this->request->getPost('end_date'),
                    'token' => session()->get('token'),
                    'tsr_ids' => $this->request->getPost('tsr_ids')
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
    $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension("B")->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension("C")->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension("D")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("E")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("F")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("G")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("H")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("I")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("J")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("K")->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension("L")->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension("M")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("N")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("O")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("P")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("Q")->setWidth(8);
    $spreadsheet->getActiveSheet()->getColumnDimension("R")->setWidth(6);
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
                $sheet->setCellValue('D4',  $res['data']['user_request']);
                $spreadsheet->getActiveSheet()->mergeCells("A1:C1");
                $spreadsheet->getActiveSheet()->mergeCells("A2:C2");
                $spreadsheet->getActiveSheet()->mergeCells("A3:C3");
                $spreadsheet->getActiveSheet()->mergeCells("A4:C4");
                $spreadsheet->getActiveSheet()->getStyle('A1:A4')->getFont()->setSize(9)->setBold(true);
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
                $sheet->setCellValue('J10',  '%');
                $sheet->setCellValue('K6',  'Talk Time');
                $sheet->setCellValue('K7',  'NOT CONNECTED');
                $sheet->setCellValue('L7',  'TOTAL CONNECTED');
                $sheet->setCellValue('K8',  'Invalid');
                $sheet->setCellValue('K10', '#');
                $sheet->setCellValue('L10', '#');
                $sheet->setCellValue('M10', '%');
                $sheet->setCellValue('N6',  'CONNECTED');
                $sheet->setCellValue('N7',  'NOT CONNECTED');
                $sheet->setCellValue('R7',  'TOTAL (CONNECTED)');
                $sheet->setCellValue('Q8',  'Others');
                $sheet->setCellValue('Q10',  '#');
                $sheet->setCellValue('R10',  '#');
                $sheet->setCellValue('S10',  '%');
                $sheet->setCellValue('N8',  'Busy');
                $sheet->setCellValue('N10',  '#');
                $sheet->setCellValue('O8',  'NPU');
                $sheet->setCellValue('O10', '#');
                $sheet->setCellValue('P8',  'Miss Customer');
                $sheet->setCellValue('P10', '#');
                $sheet->setCellValue('T6',  'CONTACTED');
                $sheet->setCellValue('T7',  'NOT PRESENTATION');
                $sheet->setCellValue('T8',  'Callback');
                $sheet->setCellValue('T10',  '#');
                $sheet->setCellValue('U8',  'RUF');
                $sheet->setCellValue('U10',  '#');
                $sheet->setCellValue('V8',  'Not Qualified');
                $sheet->setCellValue('V10',  '#');
                $sheet->setCellValue('W8',  'NOT PRESENTATION');
                $sheet->setCellValue('W9',  '(Total)');
                $sheet->setCellValue('Y7', 'TOTAL PRESENTATION');
                $sheet->setCellValue('V10',  '#');
                $sheet->setCellValue('W10',  '#');
                $sheet->setCellValue('X10',  '%');
                $sheet->setCellValue('Y10',  '#');
                $sheet->setCellValue('Z10',  '%');
                $sheet->setCellValue('AA10', '#');
                $sheet->setCellValue('AA6', 'PRESENTATION');
                $sheet->setCellValue('AK8', 'No Have CC / Payment Mechanism');
                $sheet->setCellValue('AL8', 'SCR');
                $sheet->setCellValue('AM8', 'RR');
                $sheet->setCellValue('AN8', 'ANP');
                $sheet->setCellValue('AO8', 'AVARAGE PREMIUM');
                $sheet->setCellValue('AP8', 'ACTUAL PREMI MONTHLY');
                $sheet->setCellValue('AQ8', 'PREMI');
                $sheet->setCellValue('AQ6', '(TOTAL)');
                $sheet->setCellValue('AA7', 'THINKING');
                $sheet->setCellValue('AB7', 'INTEREST');
                $sheet->setCellValue('AD7', 'NOT INTEREST');
                $sheet->setCellValue('AA8', 'Thinking');
                $sheet->setCellValue('AB8', 'Interest');
                $sheet->setCellValue('AD8', 'NOT INTEREST');
                $sheet->setCellValue('AE8', 'Already Insured');
                $sheet->setCellValue('AF8', 'High Premium');
                $sheet->setCellValue('AG8', 'Need High Benefit');
                $sheet->setCellValue('AH8', 'Asking Investment Product');
                $sheet->setCellValue('AI8', 'No Need Insurance');
                $sheet->setCellValue('AJ8', 'Not Interest Others');
                $sheet->setCellValue('AQ8', 'Login');
                $sheet->setCellValue('AR8', 'Talk');
                // $sheet->setCellValue('AT8', 'Wait');
                // $sheet->setCellValue('AU8', 'Wrap');
                $sheet->setCellValue('AB9', 'PIF');
                $sheet->setCellValue('AC9', 'SALES');
                $sheet->setCellValue('AD9',  '(Total)');
                $sheet->setCellValue('AQ9',  'Hours');
                $sheet->setCellValue('AR9',  'Time');
                // $sheet->setCellValue('AT9',  'Time');
                // $sheet->setCellValue('AU9',  'Time');
                $sheet->setCellValue('AB10',  '#');
                $sheet->setCellValue('AC10',  '#');
                $sheet->setCellValue('AD10',  '#');
                $sheet->setCellValue('AE10',  '#');
                $sheet->setCellValue('AF10',  '#');
                $sheet->setCellValue('AG10',  '#');
                $sheet->setCellValue('AH10',  '#');
                $sheet->setCellValue('AI10',  '#');
                $sheet->setCellValue('AJ10',  '#');
                $sheet->setCellValue('AK10',  '#');
                $sheet->setCellValue('AL10',  '%');
                $sheet->setCellValue('AM10',  '%');
                $sheet->setCellValue('AN10',  '#');
                $sheet->setCellValue('AO10',  '#');
                $sheet->setCellValue('AP10',  '#');
                // $sheet->setCellValue('AQ10',  '#');

                $sheet->setCellValue('AQ10',  'hours');
                $sheet->setCellValue('AR10',  'time');
                $spreadsheet->getActiveSheet()->mergeCells("A6:A9");
                $spreadsheet->getActiveSheet()->mergeCells("B6:B9");
                $spreadsheet->getActiveSheet()->mergeCells("C6:C9");
                $spreadsheet->getActiveSheet()->mergeCells("D6:D9");
                $spreadsheet->getActiveSheet()->mergeCells("E6:F9");
                $spreadsheet->getActiveSheet()->mergeCells("G6:H9");
                $spreadsheet->getActiveSheet()->mergeCells("I6:J9");
                $spreadsheet->getActiveSheet()->mergeCells("K6:M6");

                $spreadsheet->getActiveSheet()->mergeCells("L7:M7");
                $spreadsheet->getActiveSheet()->mergeCells("K8:M8");
                $spreadsheet->getActiveSheet()->mergeCells("N6:S6");
                $spreadsheet->getActiveSheet()->mergeCells("N7:Q7");
                $spreadsheet->getActiveSheet()->mergeCells("R7:S7");
                $spreadsheet->getActiveSheet()->mergeCells("Q8:S8");
                $spreadsheet->getActiveSheet()->mergeCells("T6:Z6");
                $spreadsheet->getActiveSheet()->mergeCells("T7:X7");
                $spreadsheet->getActiveSheet()->mergeCells("Y7:Z7");
                $spreadsheet->getActiveSheet()->mergeCells("W8:Z8");
                $spreadsheet->getActiveSheet()->mergeCells("W9:AA9");
                $spreadsheet->getActiveSheet()->mergeCells("AA6:AP6");
                $spreadsheet->getActiveSheet()->mergeCells("AB7:AC7");
                $spreadsheet->getActiveSheet()->mergeCells("AL7:AP7");
                $spreadsheet->getActiveSheet()->mergeCells("AD7:AK7");
                $spreadsheet->getActiveSheet()->mergeCells("AQ6:AR7");
                $spreadsheet->getActiveSheet()->mergeCells("AB8:AC8");
                //end
                $spreadsheet->getActiveSheet()->getStyle('E6:E10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $col = 11;
            if(count($res['data']['reporting']) <= 0){
                $res = [
                    'status' => 403,
                    'error' => true,
                    'data' => '',
                    'message' => 'Data Kosong'
                ];
                return $this->response->setJSON($res);
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
                $sheet->setCellValue('K'.$col, $data['n_connected']);
                $sheet->setCellValue('L'.$col, $data['y_connected']);
                $sheet->setCellValue('M'.$col, $data['connected_rate']);
                $sheet->setCellValue('N'.$col, $data['busy_line']);
                $sheet->setCellValue('O'.$col, $data['npu']);
                $sheet->setCellValue('P'.$col, $data['miss_customer']);
                $sheet->setCellValue('Q'.$col, $data['others']);
                $sheet->setCellValue('R'.$col, $data['y_connected']);
                $sheet->setCellValue('S'.$col, $data['connected_rate']);
                $sheet->setCellValue('T'.$col, $data['call_back']);
                $sheet->setCellValue('U'.$col, $data['ruf']);
                $sheet->setCellValue('V'.$col, $data['not_qualified']);
                $sheet->setCellValue('W'.$col, $data['total_not_presentation']);
                $sheet->setCellValue('X'.$col, $data['p_total_not_presentation']);
                $sheet->setCellValue('Y'.$col, $data['total_presentation']);
                $sheet->setCellValue('Z'.$col, $data['p_total_presentation']);
                $sheet->setCellValue('AA'.$col, $data['thinking']);
                $sheet->setCellValue('AB'.$col, $data['pif']);
                $sheet->setCellValue('AC'.$col, $data['sales']);
                $sheet->setCellValue('AD'.$col, $data['rejection']);
                $sheet->setCellValue('AE'.$col, $data['already_insured']);
                $sheet->setCellValue('AF'.$col, $data['high_premi']);
                $sheet->setCellValue('AG'.$col, $data['need_high_benefit']);
                $sheet->setCellValue('AH'.$col, $data['asking_investment']);
                $sheet->setCellValue('AI'.$col, $data['no_need_asurance']);
                $sheet->setCellValue('AJ'.$col, $data['not_interest_others']);
                $sheet->setCellValue('AK'.$col, $data['no_have_cc']);
                $sheet->setCellValue('AL'.$col, $data['scr']);
                $sheet->setCellValue('AM'.$col, $data['rr']);
                $sheet->setCellValue('AN'.$col, $data[ 'anp']);
                $sheet->setCellValue('AO'.$col, $data['average_premium']);
                $sheet->setCellValue('AP'.$col, $data['actual_premi_monthly']);
                $sheet->setCellValue('AQ'.$col, $data['login']);
                $sheet->setCellValue('AR'.$col, $data['talk']);
                // $sheet->setCellValue('AV'.$col, $data['avg_collected_premi']);

                $col++;
            }
            $sheet->setCellValue('A'.($col), 'SUMMARY');
            $spreadsheet->getActiveSheet()->mergeCells("A$col:C$col");
            $sheet->setCellValue('D'.$col, $res['data']['summary']['db']);
            $sheet->setCellValue('E'.$col, $res['data']['summary']['solicited']);
            $sheet->setCellValue('F'.$col, $res['data']['summary']['p_solicited']);
            $sheet->setCellValue('G'.$col, $res['data']['summary']['terminate']);
            $sheet->setCellValue('H'.$col, $res['data']['summary']['p_terminate']);
            $sheet->setCellValue('I'.$col, $res['data']['summary']['call_attempted']);
            $sheet->setCellValue('J'.$col, $res['data']['summary']['p_attempted']);
            $sheet->setCellValue('K'.$col, $res['data']['summary']['n_connected']);
            $sheet->setCellValue('L'.$col, $res['data']['summary']['y_connected']);
            $sheet->setCellValue('M'.$col, $res['data']['summary']['connected_rate']);
            $sheet->setCellValue('N'.$col, $res['data']['summary']['busy_line']);
            $sheet->setCellValue('O'.$col, $res['data']['summary']['npu']);
            $sheet->setCellValue('P'.$col, $res['data']['summary']['miss_customer']);
            $sheet->setCellValue('Q'.$col, $res['data']['summary']['others']);
            $sheet->setCellValue('R'.$col, $res['data']['summary']['y_connected']);
            $sheet->setCellValue('S'.$col, $res['data']['summary']['n_connected']);
            $sheet->setCellValue('T'.$col, $res['data']['summary']['call_back']);
            $sheet->setCellValue('U'.$col, $res['data']['summary']['ruf']);
            $sheet->setCellValue('V'.$col, $res['data']['summary']['not_qualified']);
            $sheet->setCellValue('W'.$col, $res['data']['summary']['total_not_presentation']);
            $sheet->setCellValue('X'.$col, $res['data']['summary']['p_total_not_presentation']);
            $sheet->setCellValue('Y'.$col, $res['data']['summary']['total_presentation']);
            $sheet->setCellValue('Z'.$col, $res['data']['summary']['p_total_presentation']);
            $sheet->setCellValue('AA'.$col, $res['data']['summary']['thinking']);
            $sheet->setCellValue('AB'.$col, $res['data']['summary']['pif']);
            $sheet->setCellValue('AC'.$col, $res['data']['summary']['sales']);
            $sheet->setCellValue('AD'.$col, $res['data']['summary']['already_insured']);
            $sheet->setCellValue('AE'.$col, $res['data']['summary']['rejection']);
            $sheet->setCellValue('AF'.$col, $res['data']['summary']['need_high_benefit']);
            $sheet->setCellValue('AG'.$col, $res['data']['summary']['high_premi']);
            $sheet->setCellValue('AH'.$col, $res['data']['summary']['need_high_benefit']);
            $sheet->setCellValue('AI'.$col, $res['data']['summary']['asking_investment']);
            $sheet->setCellValue('AJ'.$col, $res['data']['summary']['no_need_asurance']);
            $sheet->setCellValue('AK'.$col, $res['data']['summary']['not_interest_others']);
            $sheet->setCellValue('AL'.$col, $res['data']['summary']['scr']);
            $sheet->setCellValue('AM'.$col, $res['data']['summary']['rr']);
            $sheet->setCellValue('AN'.$col, $res['data']['summary'][ 'anp']);
            $sheet->setCellValue('AO'.$col, $res['data']['summary']['average_premium']);
            $sheet->setCellValue('AP'.$col, $res['data']['summary']['actual_premi_monthly']);
            $sheet->setCellValue('AQ'.$col, $res['data']['summary']['login']);
            $sheet->setCellValue('AR'.$col, $res['data']['summary']['talk']);
            $spreadsheet->getActiveSheet()->getStyle('A6:AR'.($col))->applyFromArray($tableHead);
            $spreadsheet->getActiveSheet()->getStyle('A6:AR10')->applyFromArray($color);
            $spreadsheet->getActiveSheet()->getStyle("A$col:AR$col")->applyFromArray($color);
            $spreadsheet->getActiveSheet()->getStyle('A1:AR10')->getFont()->setSize(9);
            $writer = new Xls($spreadsheet);
            $fileName = 'APR_Total_Agent_Tracking_'.date('Ymd');
            if(sessionCheck())
            {
                ob_start();
                $writer->save('php://output');
                $file = ob_get_contents();
                $res = array(
                    'filename' => $fileName,
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
