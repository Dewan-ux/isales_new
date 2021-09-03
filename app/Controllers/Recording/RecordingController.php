<?php namespace App\Controllers\Recording;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\RecordingModel;
use App\Models\LogCallModel;
use App\Models\ExtensionPabxModel;

class RecordingController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->recording = new RecordingModel();
        $this->logcall = new LogCallModel();
        $this->ext = new ExtensionPabxModel();
    }
   
    public function recordingByExtension(){
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    //1,2,4,5
                    if($val['role'] == '3')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'recordingExt') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $lenght = strlen($req['extension']);
                            $req['extension'] = substr($req['extension'], 3, $lenght);
                            $recording = $this->recording->getAll(array('extension' => $req['extension'], 'destination' => $req['destination']))->getResultArray();
                            if (!isset($recording)) {
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => [],
                                    'message' => 'Kosong!?'
                                ];
                            }else{
                                $rec = [];
                                $a = 1;
                                $nama = "";
                                $inv = 0;

                                foreach ($recording as $key => $val) {
                                    //search user with tanggal call mulai + destination number
                                    // $path = WRITEPATH.'download/'.date('Ymd', strtotime($val['recording_date']))."/".$val['recording_file'].'.gsm';
                                    // if(!file_exists($path)){
                                        $gUser = $this->logcall->getAll(['cardetpang' => true, 'cariutang' => $val['recording_date'], 'call_to' => $val['destination'], 'extension' => $val['extension']]);
                                        $eUser = $this->auth->getAll(['id' => $this->ext->getAll(['extension' => $val['extension']])->getRowArray()['id_login']])->getRowArray()['nama'];
                                        if($gUser['nama'] == $nama){
                                            $a++;
                                        }else{
                                            $a = 1;
                                        }
                                        if(empty($val['duration'])){
                                            if(!isset($gUser['call_start_at'])) $gUser['call_start_at'] = 0;
                                            if(!isset($gUser['call_end_at'])) $gUser['call_end_at'] = $gUser['call_start_at'];
                                            $to_time = strtotime($gUser['call_start_at']);
                                            $from_time = strtotime($gUser['call_end_at']);
                                            $duration = round(abs($to_time - $from_time),0).'dtk';
                                        }else{
                                            $duration = $val['duration'].'dtk';
                                        }
                                        $nama = !empty($gUser['nama']) ? $gUser['nama'] : $eUser;
                                        // seq-TSR NM-tlp nb(dest)-YmdHis
                                        $rec[] = [
                                        'id' => $val['id'],
                                        'download_hash' => $val['download_hash'],
                                        'extension' => $val['extension'],
                                        'did' => $val['did'],
                                        'destination' => $val['destination'],
                                        'direction' => $val['direction'],
                                        'recording_date' => $val['recording_date'],
                                        'created_at' => $val['created_at'],
                                        'recording_file' => str_pad($a, 3, "0", STR_PAD_LEFT)."_".$nama."_".$val['destination']."_".date('Y-m-d_H-i-s',strtotime($val['recording_date']))."_".$duration
                                        ];
                                    // }else{
                                    //     $inv++;
                                    // }
                                }
                                
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $rec,
                                    'message' => 'List Recording Total '.count($rec).'. Inv'.$inv
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

}