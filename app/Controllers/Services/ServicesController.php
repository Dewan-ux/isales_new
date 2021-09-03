<?php
namespace App\Controllers\Services;
use App\Controllers\BaseController;

use App\Models\DataNasabahModel;
use App\Models\LogResetNasabah;
use App\Models\RecordingModel;
use App\Models\LogCallModel;
use App\Models\LoginModel;
use App\Models\LogLoginModel;
use ZipArchive;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

class ServicesController extends BaseController
{

    public function __construct()
    {
        $this->d_nasabah = new DataNasabahModel();
        $this->log_reset = new LogResetNasabah();
        $this->recording = new RecordingModel();
        $this->loginmodel = new LoginModel();
        $this->auth_log = new LogLoginModel();
        $this->logcall = new LogCallModel();

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
    
    public function downloadRecording()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '300');
        $req = $this->request->getPost();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];

            return $this->response->setJSON($res);  
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
                        'status' => 145,
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
                        if($this->validate->run($req, 'download_recording') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => 'Token Invalid!'
                            ];
            
                            return $this->response->setJSON($res);
                        } else {
                            $recording = $this->recording->getAll(['start_date' => $req['start_date'], 'end_date' => $req['end_date']])->getResultArray();
                            // var_dump($recording);
                            // die();
                            
                            if(empty($recording))
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Recording is empty!'
                                ];
                            } else {
                                $counter = 0;
                                foreach($recording as $record)
                                {
                                    
                                    $path = WRITEPATH.'download/'.date('Ymd', strtotime($record['recording_date']))."/";
                                                            
                                    if(!file_exists($path)){
                                        mkdir($path, 0777, true);
                                    } 

                                    // $url = "https://sip-1.c-icare.cc/apipbx/recording/file?key=".PABX_API_KEY."&filename=".base64_decode($record['download_hash']);
                                    $url = PABXURL."recording/file?key=".PABX_API_KEY."&filename=".base64_decode($record['download_hash']);
                                    
                                    $opt = [
                                        CURLOPT_URL => $url,
                                        CURLOPT_RETURNTRANSFER => true,
                                    ];

                                    $ch = curl_init();
                                    curl_setopt_array($ch, $opt);

                                    if(!$res = curl_exec($ch)){
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => curl_error($ch)
                                        ];
                                        curl_close($ch);
                                        return $this->response->setJSON($res);
                                    } else {
                                        $ct = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                                        curl_close($ch);
                                        if(strpos($ct, 'application') !== FALSE){
                                            $filename = $path.$record['recording_file'];
                                            if(!file_exists($filename)){
                                                $file = fopen($filename, "w+");
                                                fputs($file, $res);
                                                fclose($file);
                                                // if (filesize($filename) < 64)) {
                                                //     unlink($filename)
                                                // }else{
                                                    $counter++;
                                                // }
                                            }
                                        } else {
                                            $result = [
                                                'status' => 500,
                                                'error' => true,
                                                'data' => '',
                                                'message' => 'Unknown Error'
                                            ];
                                            return $this->response->setJSON($result);
                                        } 
                                    }
                                }
                                
                                if($counter == 0)
                                {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => "Tidak ada yang download"
                                    ];
                                } else {
                                    $res = [
                                        'status' => 200,
                                        'error' => true,
                                        'data' => [
                                            'start_date' => $req['start_date'],
                                            'end_date' => $req['end_date'],
                                            'total_download' => $counter
                                        ],
                                        'message' => "Download record berhasil"
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    function donloadZipRecording(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '300');
        $req = $this->request->getPost();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];

            return $this->response->setJSON($res);  
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        if($this->validate->run($req, 'downloadziprecording') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => 'Invalid!'
                            ];
            
                            return $this->response->setJSON($res);
                        } else {
                            $date = date('Ymd', strtotime($req['date']));
                            $path = WRITEPATH.'download/'.$date;
                            $zipf = $path."-RECORDING.zip";
                            $rootPath = realpath($path);

                            if(file_exists($path)){
                                $this->zip = new ZipArchive();

                                $this->zip->open($zipf, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                                $files = new RecursiveIteratorIterator(
                                    new RecursiveDirectoryIterator($path),
                                    RecursiveIteratorIterator::LEAVES_ONLY
                                );

                                foreach ($files as $name => $file){
                                    // Skip directories (they would be added automatically)
                                    if (!$file->isDir())
                                    {
                                        // Get real and relative path for current file
                                        $filePath = $file->getRealPath();
                                        $relativePath = substr($filePath, strlen($rootPath) + 1);

                                        // Add current file to archive
                                        if(filesize($filePath) > 63){
                                            $this->zip->addFile($filePath, $relativePath);
                                        }
                                    }
                                }
                                $this->zip->addFile($filePath, $relativePath);
                                $this->zip->close();

                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename='.basename($zipf));
                                header('Content-Transfer-Encoding: binary');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: '.filesize($zipf));
                                readfile($zipf);
                                exit();
                            }else{
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Not Found!'
                                ];
                            }
                        }
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

    function getRecordingPabx($send){
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
        $res = json_decode(curl_exec($ch), true);
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
    
    public function getRecording($send = [])
    {
        $result = $this->getRecordingPabx($send);
        // dd($result);
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
        if(isset($result['status'])) if($result['status'] == FALSE){
            $res = [
                'status' => 404,
                'error' => true,
                'data' => ['time' => date('Y-m-d H:i:s'), 'result' => $result],
                'message' => $result['message']
            ];
            return $this->response->setJSON($res);
        }
        $total_get = count((array)$result['data']);
        // d($result);die();
        if($result['total'])
        {
            $ls = [];
            $sync = 0;
            $dupl = 0;
            foreach($result['data'] as $res){
                $listing[] = base64_encode($res['recordingfile']);
            }

            $hash = $this->recording->getAll(['d_hash' => $listing]);
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
                        'download_hash' => base64_encode($res['recordingfile']),
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
            if($sync > 0){
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
                    'data' => ['total_data' => $total_get, 'added' => "$sync", 'duplicate' => $dupl, 'time' => date('Y-m-d H:i:s')],
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
        return $this->response->setJSON($res);
    }

    public function forceLout()
    {
        $data = $this->loginmodel->getAll(array('logged_in' => '1'))->getResultArray();
        $res = [];
        foreach ($data as $key => $value) {
            $logt = $this->loginmodel->editAble($value['id'], array('logged_in' => '0','token' => ''));
            $inlg = $this->auth_log->addNew(array('id_login' => $value['id'], 'status' => '0'));
            $res[] = [
                'Name' => base64_encode($value['username']),
                'isLogout' => $logt,
                'logQuery' => base64_encode($inlg['query'])
            ];
        }
        return $this->response->setJSON(array('status' => 200, 'error' => false, 'data' => $res, 'message' => 'Logging Out All User'));
    }

    public function cjDownRecording(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        $req = $this->request->getPost();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];

            return $this->response->setJSON($res);  
        } else {
            $date['a'] = $req['date']; $date['b'] = date('Y-m-d', strtotime(base64_decode($req['token'])));

            if ($date['a'] != $date['b']) {
                $res = [
                    'status' => 145,
                    'error' => true,
                    'data' => '',
                    'message' => 'Authentication Failed!'
                ];
            }else{
                $recording = $this->recording->getAll(['list' => '1', 'start_date' => $date['a'], 'end_date' => $date['b']])->getResultArray();
                // d($recording);die();         
                if(empty($recording)){
                    $res = [
                        'status' => 404,
                        'error' => true,
                        'data' => '',
                        'message' => 'Recording is empty!'
                    ];
                } else {
                    $counter = 0;
                    foreach($recording as $record){
                        $path = WRITEPATH.'download/'.date('Ymd', strtotime($record['recording_date']))."/";
                                                
                        if(!file_exists($path)){
                            mkdir($path, 0777, true);
                        } 

                        // $url = "https://sip-1.c-icare.cc/apipbx/recording/file?key=".PABX_API_KEY."&filename=".base64_decode($record['download_hash']);
                        $url = PABXURL."recording/file?key=".PABX_API_KEY."&filename=".base64_decode($record['download_hash']);
                        
                        $opt = [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                        ];

                        $ch = curl_init();
                        curl_setopt_array($ch, $opt);

                        if(!$res = curl_exec($ch)){
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => curl_error($ch)
                            ];
                            curl_close($ch);
                            return $this->response->setJSON($res);
                        } else {
                            $ct = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                            curl_close($ch);
                            if(strpos($ct, 'application') !== FALSE){
                                $filename = $path.$record['recording_file'];
                                if(!file_exists($filename)){
                                    $file = fopen($filename, "w+");
                                    fputs($file, $res);
                                    fclose($file);
                                    // if (filesize($filename) < 64)) {
                                    //     unlink($filename)
                                    // }else{
                                        $counter++;
                                    // }
                                }
                            } else {
                                $result = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Unknown Error'
                                ];
                                return $this->response->setJSON($result);
                            } 
                        }
                    }
                    if($counter == 0)
                    {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => "Tidak ada yang download"
                        ];
                    } else {
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => [
                                'start_date' => $date['a'],
                                'end_date' => $date['a'],
                                'total_download' => $counter
                            ],
                            'message' => "Download recording file berhasil"
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function streaming(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '600');
        $dat = $this->request->getPost();
        $req = $this->request->getGet();
        $agent = $this->request->getUserAgent()->isBrowser();
        $tokens['token'] = (!empty($dat['token'])) ? $dat['token'] : session()->get('token') ;        
        if(!isset($req['hash'])){
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Not Found'
            ];
            return ($agent)? view('errors/html/error_404', $res) : $this->response->setJSON($res) ;
        }else{
            if($this->validate->run($tokens, 'authenticate') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return ($agent)? redirect()->to(base_url()) : $this->response->setJSON($res) ;
            }else{
                if(!$val = tokenCheck($tokens)){
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];

                    return ($agent)? redirect()->to(base_url()) : $this->response->setJSON($res) ;
                } else {
                    if($val['role'] == '4'){
                        $res = [
                            'status' => 145,
                            'error' => true,
                            'data' => '',
                            'message' => 'Authentication Failed!'
                        ];

                        return ($agent)? redirect()->to(base_url()) : $this->response->setJSON($res) ;
                    }else{
                        $data = $this->recording->getAll(['download_hash' => $req['hash']])->getRowArray();
                        if (empty($data)) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Not Found!'
                            ];
                            return ($agent)? view('errors/html/error_404', $res) : $this->response->setJSON($res) ;
                        }
                        $gUser['username'] = '×××';
                        $gUser = $this->logcall->getAll(['cariuser' => true, 'cariutang' => $data['extension'], 'call_to' => $data['destination']]);
                        $nama = (!empty($gUser['nama']))? $gUser['nama'] : $gUser['username'];
                        $path = WRITEPATH.'download/'.date('Ymd', strtotime($data['recording_date']));
                        $filename = $data['recording_file'];
                        $replace = str_replace('_', '', $data['recording_file']);
                        $downname = $nama."_".$data['destination']."[".date('Y-m-d_H-i-s', strtotime($data['recording_date']))."](".$replace.").wav";
                        $file = $path."/".$filename;
                        $files = $path."/".$filename.".gsm";
                        $mime_type = "audio/vnd.wave, audio/wav, audio/wave, audio/x-wav, audio/gsm, audio/x-gsm";

                        if(file_exists($file)||file_exists($files)){
                            if(file_exists($files)) $file = $files;
                            if(isset($req['download']))return $this->response->download($file, null)->setFileName($downname);
                            elseif(isset($req['streaming'])){
                                header('Content-type: {$mime_type}');
                                header('Content-length: '.filesize($file));
                                header('Content-Disposition: filename="'.$filename);
                                header('X-Pad: avoid browser bug');
                                header("Content-Transfer-Encoding: binary");
                                header('Cache-Control: no-cache');
                                readfile($file);
                            }elseif(isset($req['play'])){
                                if(filesize($path) < 16000000){
                                    $audio = 'data:audio/wav;base64,'.base64_encode(file_get_contents($file));
                                    echo "<html><head><title>▶ Play-iD™ — $filename [$data[recording_date]]</title></head>
                                            <body>
                                            <audio controls autoplay>
                                              <source src='$audio' type='audio/wav'>
                                              Your browser does not support the audio tag.
                                            </audio></body></html>";
                                }else{
                                    $res = [
                                        'status' => 413,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'File Too Big! Can\'t Load. Please Download Instead.'
                                    ];
                                    return ($agent)? view('errors/html/error_404', $res) : $this->response->setJSON($res) ;
                                }
                            }else{
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Not found Page'
                                ];
                                return ($agent)? view('errors/html/error_404', $res) : $this->response->setJSON($res) ;
                            }
                        }else{
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'File Not Found or Too Small'
                            ];
                            return ($agent)? view('errors/html/error_404', $res) : $this->response->setJSON($res) ;
                        }
                    }
                }
            }
        }
    }
}
