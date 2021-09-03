<?php namespace App\Controllers\ServicesAdmin;
use App\Controllers\BaseController;

use App\Models\LogCallModel;
use App\Models\DataNasabahModel;
use App\Models\LogResetNasabah;
use App\Models\RecordingModel;

class ServicesAdminController extends BaseController
{
    public function __construct()
    {
        $this->d_nasabah = new DataNasabahModel();
        $this->log_reset = new LogResetNasabah();
        $this->recording = new RecordingModel();
        $this->log_call = new LogCallModel();
        
        $this->client = \Config\Services::curlrequest();

    }

    public function reset()
    {
        $data = array(
            'status' => '0',
            'assigned_to' => NULL,
            'assigned_by' => NULL,
            'assigned_at' => NULL
        );
        $reset = $this->d_nasabah->resetShare($data);
        if($reset == 0)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Tidak ada yang direset'
            ];
        } else {
            $data = [
                'total_reset' => $reset,
                'reset_by' => 0
            ];
            $log_reset = $this->log_reset->addNew($data);
            $res = [
                'status' => 200,
                'error' => false,
                'data' => '',
                'message' => 'Reset Data Nasabah '.$reset.' Berhasil'
            ];
        }
       return $this->response->setJSON($res);
    }

    function getRecordingPabx($send = []){
        // $url = "https://sip-1.c-icare.cc/apipbx/recording/search";
        $url = PABXURL."recording/search/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: '.PABX_APIB_KEY
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), false);
        curl_close($ch);
        // d($res);die();
        if(isset($res['total_page'])) if($res['total_page'] > 1){
            for($x = 2; $x <= $res['total_page']; $x++){
                // $url = "https://sip-1.c-icare.cc/apipbx/recording/search";
                $url = PABXURL."recording/search/";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                    'Authorization: '.PABX_APIB_KEY
                ));
                $send['page'] = $x;
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $resi = json_decode(curl_exec($ch), true);
                curl_close($ch);
                foreach($resi['data'] as $dat){
                    $res['data'][] = $dat;
                }
            }

        }
        return $res;
    }
    
    public function getRecording()
    {
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
                    if($val['role'] != '1')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                            $result = $this->getRecordingPabx();
                        if(empty($result))
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => ['time' => date('Y-m-d H:i:s'), 'result' => $result],
                                'message' => 'Empty Recording List'
                            ];
                            return $this->response->setJSON($res);
                        }
                        $total_get = count((array)$result->data);
                        if($result->result)
                        {
                            $ls = [];
                            $sync = 0;
                            $dupl = 0;
                            foreach($result['data'] as $res){
                                $listing[] = base64_encode($res['recordingfile']);
                            }
                            $hash = $this->recording->getAll(['download_hash' => $res['recordingfile']])->getResultArray()['download_hash'];

                            // foreach($result['data'] as $index => $res){
                            //     $recording_date = date('Y-m-d H:i:s',strtotime($res['calldate']));
                            //     //MAYBE HERE MEMORY LEAK
                            //     $recordingExist = $this->recording->getAll(array('recording_file' => $res['recordingfile'], 'destination' => $res['destination'], 'recording_date' => $recording_date))->getResult();
                            //     if(!$recordingExist)
                            //     {
                            //         $data = [
                            //             'download_hash' => base64_encode($res['recordingfile']),
                            //             'recording_date' => $recording_date,
                            //             'recording_file' => $res['recordingfile'],
                            //             'extension' => $res['extension'],
                            //             'did' => "TSR:".$res['extension'],
                            //             'destination' => $res['destination'],
                            //             'duration' => $res['billsec'], //"duration": "83"
                            //             'direction' => "OUT",
                            //         ];
                            //         $ls[] = $data;
                            //         $sync++;
                            //     } else {
                            //         unset($result['data'][$index]);
                            //     }
                            // }
                            foreach ($result['data'] as $key => $res) {
                                $recording_date = date('Y-m-d H:i:s',strtotime($res['calldate']));

                                // in_array("Unix", $os)
                                if(in_array(base64_encode($res['recordingfile']), $hash)){
                                    $dupl++;
                                }else{
                                    $data = [
                                        'download_hash' => $res->download_hash,
                                        'recording_date' => $recording_date,
                                        'recording_file' => $res['recordingfile'],
                                        'extension' => $res['extension'],
                                        'did' => "TSR:".$res['extension'],
                                        'destination' => $res['destination'],
                                        'duration' => $res['billsec'], //"duration": "83"
                                        'direction' => "OUT",
                                    ];
                                    $ls[] = $data;
                                    $sync++;
                                }
                            }
                            $recording = true;
                            if($sync > 0)
                            {
                                $recording = $this->recording->addNewBatch($ls);
                            }
                            if(!$recording)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong!'
                                ];
                            } else {
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => ['total_data' => $total_get, 'synced' => "$sync of ".$sync+$dupl, 'last' => $dupl, 'time' => date('Y-m-d H:i:s')],
                                    'message' => 'Get Recording List PABX'
                                ];
                            }
                        } else {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => ['time' => date('Y-m-d H:i:s')],
                                'message' => 'Recording List Empty'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    // public function listRecording(){
    //     $req = $this->request->getPost();
    //     if(!$req)
    //     {
    //         $res = [
    //             'status' => 404,
    //             'error' => true,
    //             'data' => '',
    //             'message' => 'Endpoint Not Found'
    //         ];

    //         return $this->response->setJSON($res);  
    //     } else {
    //         if($this->validate->run($req, 'authenticate') === FALSE)
    //         {
    //             $res = [
    //                 'status' => 400,
    //                 'error' => true,
    //                 'data' => '',
    //                 'message' => 'Token Invalid!'
    //             ];
    //             return $this->response->setJSON($res);
    //         } else {
    //             if(!$val = tokenCheck($req))
    //             {
    //                 $res = [
    //                     'status' => 145,
    //                     'error' => true,
    //                     'data' => '',
    //                     'message' => 'Authentication Failed!'
    //                 ];
    //             } else {
    //                 if($val['role'] == '1' || $val['role'] == '5')
    //                 {
    //                     if($this->validate->run($req, 'downloadziprecording') === FALSE)
    //                     {
    //                         $res = [
    //                             'status' => 400,
    //                             'error' => true,
    //                             'data' => '',
    //                             'message' => 'Invalid!'
    //                         ];           
    //                         return $this->response->setJSON($res);
    //                     } else {
    //                         $date = date('Ymd', strtotime($req['date']));
    //                         $path = WRITEPATH.'download/'.$date;
    //                         $zipf = $path."-RECORDING.zip";
    //                         $rootPath = realpath($path);

    //                         if(file_exists($path)){
    //                             $this->zip = new ZipArchive();

    //                             $this->zip->open($zipf, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    //                             $files = new RecursiveIteratorIterator(
    //                                 new RecursiveDirectoryIterator($path),
    //                                 RecursiveIteratorIterator::LEAVES_ONLY
    //                             );

    //                             foreach ($files as $name => $file){
    //                                 // Skip directories (they would be added automatically)
    //                                 if (!$file->isDir())
    //                                 {
    //                                     // Get real and relative path for current file
    //                                     $filePath = $file->getRealPath();
    //                                     $relativePath = substr($filePath, strlen($rootPath) + 1);

    //                                     // Add current file to archive
    //                                     if(filesize($filePath) > 63){
    //                                         $this->zip->addFile($filePath, $relativePath);
    //                                     }
    //                                 }
    //                             }
    //                             $this->zip->addFile($filePath, $relativePath);
    //                             $this->zip->close();

                               
    //                             header('Content-Description: File Transfer');
    //                             header('Content-Type: application/octet-stream');
    //                             header('Content-Disposition: attachment; filename='.basename($zipf));
    //                             header('Content-Transfer-Encoding: binary');
    //                             header('Expires: 0');
    //                             header('Cache-Control: must-revalidate');
    //                             header('Pragma: public');
    //                             header('Content-Length: ' . filesize($zipf));
    //                             readfile($zipf);
    //                             exit();
    //                         }else{
    //                             $res = [
    //                                 'status' => 404,
    //                                 'error' => true,
    //                                 'data' => '',
    //                                 'message' => 'Not Found!'
    //                             ];
    //                         }
    //                     }
    //                 } else {
    //                     $res = [
    //                         'status' => 403,
    //                         'error' => true,
    //                         'data' => '',
    //                         'message' => 'Access Denied!'
    //                     ];
    //                 }
    //             }
    //         }
    //     }
    //     return $this->response->setJSON($res);
    // }

   public function allRecording(){
        ini_set('memory_limit', '-1');
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
                    if($val['role'] == '1' || $val['role'] == '5' || $val['role'] == '2')
                    {

                        $recording = $this->recording->getAll(array('list' => 1, 'start_date' =>  $req['start_date'], 'end_date' => $req['end_date']))->getResultArray();

                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $recording,
                            'message' => 'List Recording Total'.count($recording)
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
}
