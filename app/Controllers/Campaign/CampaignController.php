<?php namespace App\Controllers\Campaign;
use App\Controllers\BaseController;

use App\Models\DataNasabahModel;
use App\Models\LogShareCampaignModel;
use App\Models\LogUploadCampaignModel;
use App\Models\VisitorModel;
use App\Models\LoginModel;
use App\Models\CampaignModel;

class CampaignController extends BaseController {
    public function __construct()
    {
        $db2 = db_connect("secondary");
        $this->d_visitor = new VisitorModel($db2);
        $this->log_share_campaign = new LogShareCampaignModel($db2);
        $this->log_upload_campaign = new LogUploadCampaignModel($db2);
        $this->campaign = new CampaignModel($db2);
        $this->d_nasabah = new DataNasabahModel();
        $this->auth = new LoginModel();
    }

    public function listCampaign()
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
                        $campaigns = $this->campaign->getAll(array('landingpage'=>'Landing Page'))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $campaigns,
                            'message' => 'Log Campaign'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function logShare()
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
                        $data = [];
                        $leaders = $this->auth->getAll(array('role' => '2'))->getResultArray();
                        $available = $this->d_visitor->getAll(array('count'=>'available','sent' => ['0']))->getRowArray();
                        $log_share_campaign = $this->log_share_campaign->getAll()->getResultArray();
                        $data['log_share_campaign'] = $log_share_campaign;
                        $data['campaign'] = $this->campaign->getAll()->getResultArray();
                        $data['leaders'] = $leaders;
                        $data['available'] = $available;
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $data,
                            'message' => 'Log Campaign'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function logUpload()
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
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        $data = [];
                        $log_upload_campaign = $this->log_upload_campaign->getAll()->getResultArray();
                        $data['log_upload_campaign'] = $log_upload_campaign;
                        $campaign = $this->campaign->getAll()->getResultArray();
                        $data['campaign'] = $campaign;

                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $data,
                            'message' => 'Log Campaign'
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

    public function shareCampaign()
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

            return $this->response->setJSON($res);
        }

        if($this->validate->run($req, 'authenticate') === FALSE)
        {
            $res = [
                'status' => 400,
                'error' => true,
                'data' => '',
                'message' => 'Token Invalid!'
            ];

            return $this->response->setJSON($res);
        } 

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
                if($this->validate->run($req, 'share_campaign') == FALSE)
                {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => $this->validate->getErrors(),
                        'message' => 'Validation Failed!'
                    ];

                    return $this->response->setJSON($res);
                }
               $visitors = $this->d_visitor->getAll(array('limit' => $req['limit'], 'sent' => '0', 'id_campaign'=>$req['id_campaign']))->getResultArray();

               if(!$visitors)
                {
                    $res = [
                        'status' => 404,
                        'error' => true,
                        'data' => '',
                        'message' => 'Data campaign sudah semua terkirim!'
                    ];
                } else {
                    $data = [];
                    foreach($visitors as $visitor)
                    {
                        $check = $this->d_nasabah->getAll(array('telepon' => $visitor['telepon']))->getResult();

                        if(!$check)
                        {
                            
                            $data[] = [
                                'sent_to' => $req['id_login'],
                                'nama' => $visitor['nama'],
                                'telepon' => $visitor['telepon'],
                            ];

                            $data_update_visitor = [
                                'sent' => '1',
                            ];
                            $update_visitor = $this->d_visitor->editAble($visitor['id'], $data_update_visitor);
                            $tlp['log_kirim'][] = $visitor['telepon'];
                            if(!$update_visitor)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong when update visitor'
                                ];
                            }
                        } else {
                            $tlp['log_gagal'][] = $visitor['telepon'];
                            
                        }
                    }
                    if(empty($data)){
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => 'No Data Avaiable!'
                         ];
                         return $this->response->setJSON($res);
                    }
                    
                    $inserts = $this->d_nasabah->addNewBatch($data);
                    // var_dump($inserts); die();

                    if(!$inserts)
                    {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => 'Something went wrong!'
                        ];
                    } else {
                    
                        $total = count($data);
                        $data_log = [
                            'total' => $total,
                            'created_by' => $val['id'],
                            'id_login' => $req['id_login'],
                            'id_campaign' => $req['id_campaign'],
                            'log_kirim' => json_encode((isset($tlp['log_kirim']) ) ? $tlp['log_kirim'] : [] ),
                            'log_gagal' => json_encode((isset($tlp['log_gagal']) ) ? $tlp['log_gagal'] : ['semua data berhasil dikirim'] ),

                        ];
                        $insert_log = $this->log_share_campaign->addNew($data_log);
                        $available = $this->d_visitor->getAll(array('count'=>'available','sent' => ['0']))->getRowArray();
                        if(!$insert_log)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong when insert log share campaign!'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $available,
                                'message' => 'Share Campaign Berhasil. Total '. $total
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updloadCampaign()
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
                    if(isset($req['campaign']))
                    {
                        $data = [
                            'campaign' => $req['campaign'],
                            'created_by' =>$val['id']
                        ];
                        $insert_new_campaign = $this->campaign->addNew($data);
                        if(!$insert_new_campaign)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                            return $this->response->setJSON($res);
                        }

                        $req['id_campaign'] = $insert_new_campaign['id'];
                    }


                    if($this->validate->run($req, 'upload_campaign') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        
                        $ls = [];
                        $batch_campaign = json_decode($req['batch_campaign'], true);
                        foreach($batch_campaign as $campaign) {
                            if(isset($campaign['telepon']))
                            {
                                if($campaign != '')
                                {
                                    $campaign['telepon'] = formatPhoneInd($campaign['telepon']);
                                    if($this->validate->run(['telepon' => $campaign['telepon']], 'phone_format') === FALSE)
                                    {
                                        $res = [
                                            'status' => 400,
                                            'error' => true,
                                            'data' => ['telepon' => $campaign['telepon']],
                                            'message' => $this->validate->getErrors()
                                        ];
                                        return $this->response->setJSON($res);
                                    }
                                    $campaign['created_by'] = $val['id'];
                                    $campaign['id_campaign'] = $req['id_campaign'];
                                    $campaign['ip'] = $this->request->getIPAddress();
                                    $ls[] = $campaign;
                                }
                            }
                        }
                        $create = $this->d_visitor->addNewBatch($ls);
                        if(!$create)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            $log_data = [
                                'id_campaign' => $req['id_campaign'],
                                'total' => count($ls),
                                'created_by' => $val['id']
                            ];
                            $insert_log = $this->log_upload_campaign->addNew($log_data);
                            if(!$insert_log)
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
                                    'data' => '',
                                    'message' => 'Simpan Batch Campaign SUKSES !'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    
    public function detailCampaign()
    {
        $req = $this->request->getPost();
        // var_dump($req); die();
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
                        $campaigns['camp'] = $this->log_share_campaign->getAll(array('id'=> $req['id'], 'cardetail' => true))->getRowArray();
                        $campaigns['sisa'] = $this->d_visitor->getAll(array('count'=>'available','sent' => ['0'], 'id_campaign'=>$campaigns['camp']['id_campaign']))->getRowArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $campaigns,
                            'message' => 'Log Campaign'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
}
