<?php namespace App\Controllers\Leader;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;
use App\Models\SharedModel;
use App\Models\KesehatanNasabahModel;
use App\Models\SpajModel;
use App\Models\DataNasabahModel;
use App\Models\LogResetNasabah;
use App\Models\LogCallModel;
use App\Models\LogInterfrensiModel;
use App\Models\ReportingModel;
use App\Models\ExtensionPabxModel;

class LeaderController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->spaj = new SpajModel();
        $this->user = new UserModel();
        $this->log = new LogLoginModel();
        $this->d_nasabah = new DataNasabahModel();
        $this->kesehatan = new KesehatanNasabahModel();
        $this->shared = new SharedModel();
        $this->log_reset = new LogResetNasabah();
        $this->log_call = new LogCallModel();
        $this->log_interfrensi = new LogInterfrensiModel();
        $this->reporting = new ReportingModel();
        $this->extension = new ExtensionPabxModel();

    }

    public function shareLeads()
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'authenticate') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $leads_available = $this->d_nasabah->getAll(array('sent_to' => $val['id'], 'list' => 'unsigned'))->getRowArray();
                            $tsr_list = $this->auth->getAll(array('group'=> $val['id'], 'list' => '3'))->getResultArray();
                            $tList = [];
                            
                            foreach($tsr_list as $list)
                            {
                                unset($list['jk']);
                                unset($list['foto']);
                                unset($list['group']);

                                $tList[] = $list;
                            }
                            $data = [
                                'leads_available' => (int)$leads_available['leads_available'],
                                'tsr_list' => $tList
                            ];
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $data,
                                'message' => 'Share Leads Available & TSR List'
                            ];
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function doShare()
    {
        $req = $this->request->getPost();

        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'doshareleads') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $tsr = $this->auth->getAll(array('id' => $req['tsr_id']))->getRowArray();

                            $updataDataNasabah = [
                                'assigned_by'   => "'".$val['id']."'",
                                'assigned_to'   => "'".$req['tsr_id']."'",
                                'assigned_at'   => "'".date('Y-m-d H:i:s')."'",
                                'updated_by'    => "'".$val['id']."'"
                            ];
                            $updateNasabah = $this->d_nasabah->updateShare($updataDataNasabah, $req['share'], $val['id']);
                            if($updateNasabah == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Nasabah Available Kosong'
                                ];
                            } else {
                                $dataShared = [
                                    'share' => $updateNasabah,
                                    'shared_by'   => $val['id'],
                                    'shared_to'   => $req['tsr_id'],
                                    'created_by'   => $val['id'],
                                ];
                                $shared = $this->shared->addNew($dataShared);
                                if(!$shared) 
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
                                        'data' => $updataDataNasabah,
                                        'message' => 'Share Leads ('.$updateNasabah.') to '.$tsr['nama'].', Success!'
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

    

    public function tsrLog()
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        $tsr_list = $this->auth->getAll(array('group' => $val['id'], 'role' => '3'))->getResultArray();
                        
                        $tList = [];
                        foreach($tsr_list as $list )
                        {
                            $log = $this->log->getAll(array('id_login' => $list['id'], 'list' => 1))->getRowArray();
                            $call_log = $this->log_interfrensi->getAll(array('created_by' => $list['id'], 'status' => '1'))->getRowArray();
                            $list['status_logout'] = $log;
                            $list['interfrensi'] = !empty($call_log['status']) ? $call_log['status'] : NULL;
                            unset($list['password']);
                            unset($list['token']);
                            unset($list['group']);

                            $extension = $this->extension->getAll(array('id_login'=>$list['id']))->getRowArray();
                            if(!empty($extension))
                            {
                                $list['extension'] = $extension['extension'];
                            } else {
                                $list['extension'] = NULL;
                            }
                            $tList[] = $list;
                        }
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $tList,
                            'message' => 'Get TSR Log!'
                        ];
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function tsrLogID()
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'tsrId') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            
                            $tsr = $this->auth->getAll(array('id' => $req['tsr_id'], 'log' => '1'))->getRowArray();
                            unset($tsr['password']);
                            unset($tsr['email']);
                            unset($tsr['foto']);
                            $token_call = $this->log_call->getAll(array('call_by' => $req['tsr_id'], 'help'=> 1))->getRowArray();
                            $tsr['token_call'] = !empty($token_call['token_call']) ? $token_call['token_call'] : NULL;
                            $log = $this->log->getAll(array('tsr_id'=> $req['tsr_id'], 'list' => 'logoutminutes'))->getRowArray();
                            $sec = isset($log['toilet']) ? $log['toilet'] : 0;
                            $menit = ($sec / 60) % 60;
                            $detik = $sec % 60;
                            $jam = floor($sec / 3600);
                            $log['toilet'] =$jam.' Jam '. $menit.' menit '.$detik.' detik';

                            $sec = isset($log['istirahat']) ? $log['istirahat'] : 0;
                            $menit = ($sec / 60) % 60;
                            $detik = $sec % 60;
                            $jam = floor($sec / 3600);
                            $log['istirahat'] =$jam.' Jam '. $menit.' menit '.$detik.' detik';
                            
                            $sec = isset($log['shalat']) ? $log['shalat'] : 0;
                            $menit = ($sec / 60) % 60;
                            $detik = $sec % 60;
                            $jam = floor($sec / 3600);
                            $log['shalat'] =$jam.' Jam '. $menit.' menit '.$detik.' detik';

                            $tsr['logs'] = $log;
                            
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $tsr,
                                'message' => 'Get TSR Log!'
                            ];
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function listTsr(){
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
                   
                    if($val['role'] == '2'){
                        $tsr_lists = $this->auth->getAll(array('list' => '5', 'group' => $val['id']))->getResultArray();
                        if(count($tsr_lists) == 0)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Tsr Kosong'
                            ];
                        } else {
                            $tsrList = [];
                            foreach($tsr_lists as $ls)
                            {
                                unset($ls['token']);
                                unset($ls['password']);
                                // unset($ls['foto']);
                                $ls['foto'] = (!empty($ls['foto']))  ? $ls['foto'] : NIMG;
                                $ls['extension'] = $this->extension->getAll(array('id_login' => $ls['id']))->getRowArray()['extension'];
                                $call = $this->log_call->getAll(array('help' => 2, 'call_by' => $ls['id']))->getRowArray()['token_call'];
                                // var_dump($call);die();
                                // $call = $this->log_call->getAll(array('help' => 2, 'call_by' => $ls['id']))['call_end_at'];
                                // $ls['tsrstat'] = (!empty($call)) ? 2 : (($ls['logged_in'] == '1') ? 1 : 0);
                                $ls['NumberTocalleR'] = $this->log_call->getAll(array('help' => 2, 'call_by' => $ls['id'], 'call_end_at' => NULL))->getRowArray()['call_to'];
                                $tsrList[] = $ls;
                            }
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $tsrList,
                                'message' => 'TSR List '.count($tsrList)
                            ];
                        }
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

    public function detailOrder(){
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
                   
                    if($val['role'] != '2'){
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
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
                            $kesehatan_nasabah = $this->kesehatan->getAll(array('id_spaj'=>$order['id'], 'detail' => '1'))->getResultArray();
                            if(count($kesehatan_nasabah) == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Kesehatan Kosong'
                                ];
                            } else {
                                $order['kesehatan_nasabah'] = $kesehatan_nasabah;
                                $extension = $this->extension->getAll(array('id_login'=>$order['created_by']))->getRowArray();
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
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updateOrder(){
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
                    if($this->validate->run($req, 'createSpaj') === FALSE)
                    {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed'
                        ];
                    } else {
                        if($val['role'] != '2')
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Access Denied'
                            ];
                        } else {
                            $pertanyaan = $req['pertanyaan'];
                            $id = $req['id'];
                            
                            unset($req['id']);
                            unset($req['pertanyaan']);
                            $req['checked'] = '3';
                            $req['updated_by'] = $val['id'];
                            $edit = $this->spaj->editAble($id, $req);
                            if(!$edit)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                $lsAsk = [];
                                $update_jawaban = false;
                                foreach($pertanyaan as $ask){
                                    $lsAsk['id_pertanyaan'] = $ask['id_pertanyaan'];
                                    $lsAsk['jawaban'] = $ask['jawaban'];
                                    $lsAsk['updated_by'] = $val['id'];
                                    $update_jawaban = $this->kesehatan->editAble($ask['id'], $lsAsk);
                                    if(!$update_jawaban){
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something when wrong!'
                                        ];
                                        break;
                                    }
                                }
                                if($update_jawaban)
                                {
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => '',
                                        'message' => 'Update Data Order nasabah '.$req['nama'].' Berhasil'
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

    public function dashboardList()
    {
        $req = $this->request->getPost();

        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'dashboard') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $dashboard = $this->auth->getAll(array('group'=>$val['id'],'dashboard' => 'list', 
                                'filter' => isset($req['filter']) ? filterByDateSub($req['filter']) : filterByDateSub(1)))->getResultArray();
                            if(count($dashboard) == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Dashboard Kosong (404)'
                                ];
                            } else {
                                $ls = [];
                                foreach($dashboard as $ds)
                                {
                                    if($ds['jumlah_case'] > 0 && $ds['jumlah_leads'] > 0){
                                        $count = ($ds['jumlah_case'] / $ds['jumlah_leads'])*100;
                                        $ds['takeup_rate'] = $count;
                                    } else {
                                        $ds['takeup_rate'] = 0;
                                    }
                                    $ls[] = $ds;
                                }
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $ls,
                                    'message' => 'Dashboard List'
                                ];
                            }
                            
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function performance()
    {
        $req = $this->request->getPost();

        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'performance') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $performance = $this->auth->getAll(array('group' => $val['id'],'dashboard' => 'performance', 
                                'filter' => isset($req['filter']) ? filterByDateSub($req['filter']) : filterByDateSub(1)))->getResultArray();
                            if(count($performance) == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Performance Kosong (404)'
                                ];
                            } else {
                                $performances = [];
                                foreach($performance as $pr)
                                {
                                    if($pr['jumlah_case'] > 0 && $pr['jumlah_leads'] > 0)
                                    {
                                        $count = ($pr['jumlah_case']/$pr['jumlah_leads'])*100;
                                    } else {
                                        $count = 0;
                                    }
                                    $pr['rate'] = round($count, 2);
                                    $performances[] = $pr;
                                }
                                // $call_act = $this->log_call->getAll(array('log' => 'activity'))->getResultArray();

                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $performances,
                                    'message' => 'Performance List'
                                ];
                            }
                            
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function performanceById()
    {
        $req = $this->request->getPost();

        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'performanceById') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $performance = $this->auth->getAll(array('dashboard' => 'performance', 
                                'filter' => isset($req['filter']) ? filterByDateSub($req['filter']) : filterByDateSub(1), 
                                'tsr_id' => $req['tsr_id']))->getRowArray();
                            if(count($performance) == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Performance Kosong (404)'
                                ];
                            } else {
                                if($performance['jumlah_case'] > 0 && $performance['jumlah_leads'] > 0){
                                    $count = ($performance['jumlah_case']/$performance['jumlah_leads'])*100;
                                } else {
                                    $count = 0;
                                }
                                $performance['rate'] = $count;
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $performance,
                                    'message' => 'Performance '.$performance['nama']
                                ];
                            }
                            
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function reset()
    {
        $req = $this->request->getPost();

        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
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
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run(['json_status'=>$req['status']], 'json_status') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $req['status'],
                                'message' => $this->validate->getErrors()
                            ];

                            return $this->response->setJSON($res);
                        } else {
                            $data = array(
                                // 'status' => '0',
                                'assigned_to' => NULL,
                                'assigned_by' => NULL,
                                'assigned_at' => NULL,
                            );
                            $status = [];
                            $arr_req_status = json_decode($req['status']);
                            foreach($arr_req_status as $stat)
                            {
                                if($this->validate->run(['status'=>$stat], 'reset_share') === FALSE)
                                {
                                    $res = [
                                        'status' => 400,
                                        'error' => true,
                                        'data' => $this->validate->getErrors(),
                                        'message' => 'Validation Failed!'
                                    ];
                                    return $this->response->setJSON($res);
                                } else {
                                    $status[] = ''.$stat;
                                } 
                            }
                            $reset = 0;
                            if(in_array('11', $status) == '11'){
                                $thinking = ['11'];
                                $reset += $this->d_nasabah->resetThinking($data, $thinking, $val['id']);
                                $status = array_diff($status, ['11']);
                            }
                            $reset += $this->d_nasabah->resetShare($data, $status, $val['id']);
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
                                    'reset_by' => $val['id']
                                ];
                                $log_reset = $this->log_reset->addNew($data);
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => '',
                                    'message' => 'Reset Data Nasabah '.$reset.' Berhasil'
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function startInterfrensi()
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'interfrensi') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        }else{
                            if(!$log_cek = tokenCallCheck($req))
                            {
                                $res = [
                                    'status' => 400,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Token is not Available!'
                                ];
                            } else {

                                $addLog = [
                                    'created_by'       => $val['id'],
                                    'id_log_call'    => $log_cek['id'],
                                    'status'       => $req['status'],
                                ];
                                $insert_log = $this->log_interfrensi->addnew($addLog);
                                if(!$insert_log){
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $updateLogCall = [
                                        'interfrensi' => $req['status'],
                                        'interfrensi_by' => $val['id']
                                    ];
                                    $edit_log = $this->log_call->editAble($log_cek['id'], $updateLogCall);
                                    if(!$edit_log)
                                    {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something went updatecall!'
                                        ];
                                    } else {
                                        $response = [
                                            'id' => $log_cek['id'],
                                            'interfrensi' => $req['status'],
                                            'interfrensi_by' => $val['id'],
                                            'token_call' => $log_cek['token_call'],
                                            'call_to' => $log_cek['call_to'],
                                            'call_by' => $log_cek['call_by'],
                                            'call_start_at' => $log_cek['call_start_at']
                                        ];
                                        $res = [
                                            'status' => 200,
                                            'error' => false,
                                            'data' => $response,
                                            'message' => 'Mulai Interfrensi!'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function endInterfrensi()
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'endInterfrensi') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        }else{
                            if(!$log_cek = tokenCallCheck($req))
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => $log_cek,
                                    'message' => 'Invalid Token Call'
                                ];
                            } else {
                                $addLog = [
                                    'created_by'       => $val['id'],
                                    'id_log_call'    => $log_cek['id'],
                                    'status'       => '0',
                                ];
                                $insert_log = $this->log_interfrensi->addnew($addLog);
                                if(!$insert_log){
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $updateLogCall = [
                                        'interfrensi' => '0'
                                    ];
                                    $edit_log = $this->log_call->editAble($log_cek['id'], $updateLogCall);
                                    if(!$edit_log)
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
                                            'message' => 'Seleseai Interfrensi dengan nomor '.$log_cek['call_to'].'!'
                                        ];
                                    }
                                    
                                }
                            }
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function exportDpr()
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] != '2')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if($this->validate->run($req, 'export') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        }else{
                            $category = '0';
                            $addExport = [
                                'reporting'       => $category,
                                'start_date'    => $req['start_date'],
                                'end_date'       => $req['end_date'],
                                'export_by'       => $val['id'],
                            ];
                            $insert_export = $this->reporting->addnew($addExport);
                            if(!$insert_export){
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong when export reporting!'
                                ];
                            } else {
                                $id = $insert_export['id'];
                                $reporting = $this->reporting->getAll(['reporting'=> $category, 'leader_id' => $val['id'], 'id' => $id])->getResultArray();
                                if(!isset($reporting))
                                {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $summary = [];
                                    foreach ( $reporting as $report){
                                        foreach( $report as $key => $val){
                                            if($key != 'leader_id' && $key != 'role' && $key != 'datelist')
                                            {
                                                $$key[] = intval($val);
                                                $summary[$key] = array_sum($$key);
                                            }
                                        }
                                    }
                                    $ls = [];
                                    $ls['reporting'] = $reporting;
                                    $ls['period'] = $req['start_date'] . ' - ' . $req['end_date'];
                                    $ls['summary'] = $summary;
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $ls,
                                        'message' => 'Daily Production Report '
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
}