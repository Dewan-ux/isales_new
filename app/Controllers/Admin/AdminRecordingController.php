<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Config\Constant;
use App\Models\LogCallModel;

class AdminRecordingController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
        $this->logcall = new LogCallModel();
    }

    public function index()
    {
        if(sessionCheck() == true) 
        {
            return view('admin/recording/tampil');
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function playRecording()
    {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', '300');
        $validation =  \Config\Services::validation();
        $url = BASE_API.'servicesadmin/all';

        // $search = $this->request->getPost('search')['value'];
        // $option = $this->request->getPost('length')['start'];
        $start = $this->request->getPost('start_date');
        $end = $this->request->getPost('end_date');
        
        $data = array(
            'token' => session()->get('token'),
            // 'search' =>  $search,
            // 'length' => $$option,
            'start_date' => $start,
            'end_date' => $end,
        );
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        //print_r($res);
        //die();
        if($res['error'] == FALSE)
        {
            $datas = array();
            $x = 0;
            $y = 1;
            foreach($res['data'] as $key => $data){
                $path = WRITEPATH.'download/'.date('Ymd', strtotime($data['recording_date']))."/".$data['recording_file'];
                //Backward Compability
                $pathold = WRITEPATH.'download/'.date('Ymd', strtotime($data['recording_date']))."/".$data['recording_file'].".gsm";
                if(file_exists($path)){
                    $gUser = $this->logcall->getAll(['cariuser' => true, 'cariutang' => $data['extension'], 'call_to' => $data['destination']]);
                    // $nama = (!empty($gUser['nama']))? $gUser['nama'] : $gUser['username'];
                    $nama = !empty($gUser['nama']) || $gUser['nama'] != FALSE ? $gUser['nama'] : $data['extension']."(？)";
                    $ls = [];
                    $ls[] = $y;
                    $ls[] = $nama."→".$data['destination'];
                    $audio = base_url()."/services/media?hash=$data[download_hash]&";
                    $ls[] = "<a type='button' class='btn btn-success' onclick=\"window.open('".$audio."play=', 
                            'newwindow', 'width=400,height=300'); return false;\"><i class='fas fa-play' style='color:white'></i> </a>&nbsp;".
                            "<a type='button' class='btn btn-primary'  onclick=\"window.open('".$audio."download=', 
                            'newwindow', 'width=400,height=300'); return false;\"><i class='fas fa-download' style='color:white'></i> </a>";
                    $ls[] = filesize($path)." B (".$this->human_filesize(filesize($path)).")";
                    $ls[] = $data['recording_date'];
                    $datas[] = $ls;
                    $x++; $y++;
                }
                if(file_exists($pathold)){
                    if(filesize($pathold) > 64){
                        $gUser = $this->logcall->getAll(['cariuser' => true, 'cariutang' => $data['extension'], 'call_to' => $data['destination']]);
                        $nama = !empty($gUser['nama']) || $gUser['nama'] != FALSE ? $gUser['nama'] : $data['extension']."(？)";
                        $ls = [];
                        $ls[] = $y;
                        $ls[] = $nama." → ".$data['destination'];
                        
                        $audio = base_url()."/services/media?hash=$data[download_hash]&";
                        $ls[] = "<a type='button' class='btn btn-success' onclick=\"window.open('".$audio."play=', 
                                'newwindow', 'width=400,height=300'); return false;\"><i class='fas fa-play' style='color:white'></i> </a>&nbsp;".
                                "<a type='button' class='btn btn-primary'  onclick=\"window.open('".$audio."download=', 
                                'newwindow', 'width=400,height=300'); return false;\"><i class='fas fa-download' style='color:white'></i> </a>";
                        $ls[] = filesize($pathold)." B (".$this->human_filesize(filesize($pathold)).")";
                        $ls[] = $data['recording_date'];
                        $datas[] = $ls;
                        $x++; $y++;
                    }
                }
            }
            $json_data = array(
                "draw"            => intval( $this->request->getPost('draw') ),  
                "recordsTotal"    => intval( $x ), 
                "recordsFiltered" => intval( $x ),
                "data"            => $datas );

            return $this->response->setJSON($json_data);
        }
    }
    
    private function human_filesize($bytes, $dec = 0){
    $size   = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$dec}f", $bytes / pow(1024, $factor))." ".@$size[$factor];
    }
    
}