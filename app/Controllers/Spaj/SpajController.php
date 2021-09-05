<?php namespace App\Controllers\Spaj;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Config\Constant;

use App\Models\SpajModel;
use App\Models\ProdukModel;
use App\Models\PremiModel;
use App\Models\PaymentModel;
use App\Models\LoginModel;
use App\Models\PertanyaanModel;
use App\Models\LogFupSpajModel;
use App\Models\ExtensionPabxModel;
use App\Models\KesehatanNasabahModel;
use App\Models\DataNasabahModel;
use App\Models\UserModel;

class SpajController extends BaseController
{
    public function __construct()
    {
        $this->spaj 		= new SpajModel();
        $this->kesehatan 	= new KesehatanNasabahModel();
        $this->produk 		= new ProdukModel();
        $this->premi 		= new PremiModel();
        $this->payment 		= new PaymentModel();
        $this->auth 		= new LoginModel();
        $this->pertanyaan 	= new PertanyaanModel();
        $this->log_fup 		= new LogFupSpajModel();
        $this->extension 	= new ExtensionPabxModel();
        $this->d_nasabah 	= new DataNasabahModel();
		$this->user 		= new UserModel();
    }

     public function listSpaj(){
        $req = $this->request->getPost();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if($this->validate->run($req, 'authenticate') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if(!$val = tokenCheck($req))
                {
                    $res = [
                        'status' => 404,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        $spaj = $this->d_nasabah->getAll(array('spaj' => '1'))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $spaj,
                            'message' => 'List SPAJ Total '.count($spaj)
                        ];

                    } else {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

        public function detailSpaj(){
        $req = $this->request->getPost();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if($this->validate->run($req, 'authenticate') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if(!$val = tokenCheck($req))
                {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                   
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                       $order = $this->spaj->getAll(array('id' => $req['id'], 'detail' => 3))->getRowArray();
                        if(empty($order))
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Order Tidak Ada'
                            ];
                        } else {
                                // var_dump($order); die(); 

                            if ($order['jns_asuransi'] == 0) {
                                $kesehatan_nasabah = $this->kesehatan->getAll(array('id_spaj'=>$order['id'], 'detail' => '1'))->getResultArray();
                                if(count($kesehatan_nasabah) == 0){
                                    $res = [
                                        'status' => 404,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Data Kesehatan Kosong'
                                    ];
                                }
                                $order['kesehatan_nasabah'] = $kesehatan_nasabah;
                            } else {
                                $order['kesehatan_nasabah'] = array();
                            }
                             
                                
                                $extension = $this->extension->getAll(array('id_login' => $order['created_by']))->getRowArray();
                                // var_dump($order); die();
                                $data_nasabah = $this->d_nasabah->getAll(array('id' => $order['id_data_nasabah']))->getRowArray();
                                $ls = [];
                                $ls['destination'] = $data_nasabah['telepon'];
                                $ls['extension'] = $extension['extension'];
                                $order['extension'] = $ls;
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $order,
                                    'message' => 'Order untuk '.$val['nama'].' Nasabah '.$order['nama']
                                ];
                               
                        }
                        // dd($res);
                    } else {
                         $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
	
	public function eksport(){
        $spaj 			= $this->d_nasabah->ExportData1();
        $spreadsheet	= new Spreadsheet();
		
        $spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'No')
			->setCellValue('B1', 'No Proposal')
			->setCellValue('C1', 'Jenis Asuransi')
            ->setCellValue('D1', 'Nama')
            ->setCellValue('E1', 'telepon')
			->setCellValue('F1', 'Nama Produk')
			->setCellValue('G1', 'Premi')
			->setCellValue('H1', 'Telesales')
            ->setCellValue('I1', 'Tanggal');
			
        $column 		= 2;
		$asuransi		= '';
		$sensorNoHp		= '';
        foreach($spaj as $dataspaj)
		{
			if ($dataspaj['jns_asuransi'] == 0){
				$asuransi = 'Life Protection 20';
			}
			else{
				$asuransi = 'Perlindungan Kecelakaan';
			}
			
			$sensorNoHp = substr($dataspaj['telp1'], 0, 3).' XXX XXX XXX';
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $column, $dataspaj['id'])
				->setCellValue('B' . $column, $dataspaj['no_proposal'])
				->setCellValue('C' . $column, $asuransi)
				->setCellValue('D' . $column, $dataspaj['nama'])
				->setCellValue('E' . $column, $sensorNoHp)
				->setCellValue('F' . $column, $dataspaj['nama_produk'])
				->setCellValue('G' . $column, $dataspaj['nominal'])
				->setCellValue('H' . $column, $dataspaj['nama_sales'])
				->setCellValue('I' . $column, $dataspaj['created_at']);
			$column++;
        }
		
        $writer = new Xlsx($spreadsheet);
        $filename = date('Y-m-d-His'). '-Data-spaj';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
	}
}