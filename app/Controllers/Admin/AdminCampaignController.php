<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminCampaignController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'campaign';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                
                $data['log_upload_campaign'] = $res['data']['log_upload_campaign'];
                $campaign = $res['data']['campaign'];
                $ls = [];
                $ls[''] = 'Pilih Campaign';
                foreach($campaign as $c) {
                    $ls[$c['id']] = $c['campaign'];
                }
                $data['campaign'] = $ls;

                return view('admin/campaign/index', $data);
            }
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function shareCampaign()
    {           
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'campaign';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // var_dump($res); die();
        if(sessionCheck() == true) 
        {
            $tmp = array();
            $campaign['log_upload_campaign'] = $res['data']['log_upload_campaign'];
            foreach ($res['data']['log_upload_campaign'] as $key) {
                $tmp[$key['id_campaign']] = $key['total'];
            }
            // d($campaign); die();

            $validation =  \Config\Services::validation();
            $url = BASE_API.'campaign/log_share';
            $campaign = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $campaign);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                $data['log_share_campaign'] = $res['data']['log_share_campaign'];
                $data['leaders'] = $res['data']['leaders'];
                $campaign = $res['data']['campaign'];
                $ls = [];
                $ls[''] = 'Pilih Campaign';
                foreach($campaign as $c) {
                    $ls[$c['id']] = $c['campaign'];
                }
                $data['campaign'] = $ls;
                $data['available'] = $res['data']['available']['available'];
                $data['camplst'] = $tmp;
                $data['camps'] = $res['data'];

                return view('admin/campaign/log_share', $data);
            }
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}

public function previewCampaign($id)
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'campaign/detail';
            $data = array(
                'token' => session()->get('token'),
                'id' => $id
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // var_dump($res); die();

            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                
                $data['campaign'] = $res['data']['camp'];
                $data['sisa'] = $res['data']['sisa']['available'];
           
                return view('admin/campaign/preview', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function doShareCampaign()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'campaign/share_campaign';
            $data = array(
                'token' => session()->get('token'),
                'limit' => $this->request->getPost('limit'),
                'id_login' => $this->request->getPost('id_login'),
                'id_campaign' => $this->request->getPost('id_campaign'),
                'log_gagal' => $this->request->getPost('log_gagal')
                
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // var_dump($res); die();
            if($res['error'])
            {
                return json_encode($res);
            } else { 
                return json_encode($res);
            }
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }


    public function uploadCampaign()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
 
            $campaign_file = $this->request->getFile('campaign_file');
            $file_data = [
                'campaign_file' => $campaign_file,
            ];

            

            if($validation->run($file_data, 'campaignFileUpload') == FALSE)
            {
                session()->setFlashdata('errors', $validation->getErrors());
                return redirect()->to(base_url('admin/campaign'));
            } else {
                $url = BASE_API.'campaign/upload';

                $extension = $campaign_file->getClientExtension();
                if($extension == 'xls')
                {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $batch_campaign = [];
                $spreadsheet = $reader->load($campaign_file);
                $import_data = $spreadsheet->getActiveSheet()->toArray();
 
                foreach ($import_data as $idx => $val){
                    if($idx == 0){
                        continue;
                    }

                    $batch_campaign[] = [
                        'nama' => $val[0],
                        'telepon' => $val[1]
                    ];
                }

                
                $data = array(
                    'batch_campaign'     => json_encode($batch_campaign),
                    'token' => session()->get('token')
                );

                if($this->request->getPost('id_campaign') != NULL)
                {
                    $data['id_campaign'] = $this->request->getPost('id_campaign');
                } 
                if($this->request->getPost('campaign') != NULL)
                {
                    $data['campaign'] = $this->request->getPost('campaign');
                }

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = json_decode(curl_exec($ch), true);
                curl_close($ch);
            
                if($res['error'])
                {
                    // show error after update
                    session()->setFlashdata('inputs', $this->request->getPost());
                    // session()->setFlashdata('errors', array($res['message']));
                    session()->setFlashdata('errors', $res['data']);
                    return view('admin/campaign/index');
                } else { 
                    return redirect()->to(base_url('admin/campaign/upload'));
                }
            }
            
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}
