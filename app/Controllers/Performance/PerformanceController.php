<?php namespace App\Controllers\Performance;
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

class PerformanceController extends BaseController
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
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        if($this->validate->run($req, 'dashboard') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $dashboard = $this->auth->getAll(array('dashboard' => 'list',
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

    public function getExportApr()
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
                    if($val['role'] == '1' || $val['role'] == '2' || $val['role'] == '5')
                    {

                        if($this->validate->run($req, 'reporting_apr') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        }else{
                            $reporting = '1';
                            $addExport = [
                                'reporting'     => $reporting,
                                'start_date'    => $this->request->getPost('start_date'),
                                'end_date'      => $this->request->getPost('end_date'),
                                'export_by'     => $val['id'],
                                'id_campaign'   => $req['id_campaign'],
                                'id_produk'     => $req['id_produk']

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

                                if(!isset($req['tsr_ids']))
                                {
                                    $req['tsr_ids'] = [];
                                }
                                // if(isset($req['tsr_ids'])){
                                //     $ids = implode("','", $req['tsr_ids']);
                                // } else {
                                //     $ids = "";
                                // }
						        
                                $reporting = $this->reporting->getAll(['leader_id' => $val['role'] == '2' ? $val['id'] : NULL, 'reporting'=> $reporting, 'id' => $id, 'tsr_ids' => $req['tsr_ids'] ])->getResultArray();
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
                                        foreach( $report as $key => $data){
                                            if($key != 'start_date' || $key != 'end_date')
                                            {
                                                $$key[] = intval($data);
                                                $summary[$key] = strval(array_sum($$key));
                                            }
                                        }
                                    }
                                    $ls = [];
                                    $ls['reporting'] = $reporting;
                                    $ls['period'] = $req['start_date'] . ' - ' . $req['end_date'];
                                    $ls['summary'] = $summary;
                                    if($val['role'] == '1')
                                    {
                                        $ls['user_request'] = 'Manager';
                                    } else if($val['role'] == '2')
                                    {
                                        $ls['user_request'] = 'Leader';
                                    } else if($val['role'] == '5')
                                    {
                                        $ls['user_request'] = 'CAR Admin';
                                    } else {
                                        $ls['user_request'] = '';
                                    }
                                    
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $ls,
                                        'message' => 'Agent Production Report '
                                    ];
                                }
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


    public function getExportDpr()
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
                    if($val['role'] == '1' || $val['role'] == '2' || $val['role'] == '5')
                    {
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
                                'start_date'      => $req['start_date'],
                                'end_date'        => $req['end_date'],
                                'export_by'       => $val['id'],
                                'id_campaign'     => $req['id_campaign'],
                                'id_produk'       => $req['id_produk']
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
                                $reporting = $this->reporting->getAll(['reporting'=> $category, 'id' => $id])->getResultArray();
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
                                        foreach( $report as $key => $data){
                                            if($key != 'start_date' || $key != 'end_date')
                                            {
                                                $$key[] = intval($data);
                                                $summary[$key] = strval(array_sum($$key));
                                            } 
                                        }
                                    }
                                    $ls = [];
                                    $ls['reporting'] = $reporting;
                                    $ls['period'] = $req['start_date'] . ' - ' . $req['end_date'];
                                    $ls['summary'] = $summary;
                                    if($val['role'] == '1')
                                    {
                                        $ls['user_request'] = 'Manager';
                                    } else if($val['role'] == '2')
                                    {
                                        $ls['user_request'] = 'Leader';
                                    } else if($val['role'] == '5')
                                    {
                                        $ls['user_request'] = 'CAR Admin';
                                    } else {
                                        $ls['user_request'] = '';
                                    }
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $ls,
                                        'message' => 'Daily Production Report '
                                    ];
                                }
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

    public function reporting()
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
                    if($val['role'] == '1' || $val['role'] == '5' || $val['role'] == '4')
                    {
                        if($this->validate->run($req, 'reporting') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        }else{
                            $reporting = '2';
                            $addExport = [
                                'reporting'     => $reporting,
                                'start_date'    => $req['start_date'],
                                'end_date'      => $req['end_date'],
                                'export_by'     => $val['id'],
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
                                
                                $reporting = $this->reporting->getAll(['reporting'=> $reporting, 'id' => $id, 'start_date'=>$req['start_date'], 'end_date' => $req['end_date']])->getResultArray();
                                if(!isset($reporting))
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
                                        'data' => $reporting,
                                        'message' => $req['start_date'] . ' s/d ' .$req['end_date']
                                    ];
                                }
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

    public function reportingUrl()
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
                        } else {

                            $hash = encryptor('encrypt', $req['token']);
                            if($req['type'] == 'dpr')
                            {
                                $url = [
                                    'download_url' => base_url('/report/export/'.$req['type'].'?secret='.$hash.'&s='.$req['start_date'].'&e='.$req['end_date'])
                                    ];
                            } else {
                                if(empty($req['tsr_ids']) || !isset($req['tsr_ids']))
                                {
                                    $req['tsr_ids'] = [];
                                }
                                $enc_tsr_ids = encryptor('encrypt', json_encode($req['tsr_ids'], true));
                                $url = [
                                    'download_url' => base_url('/report/export/'.$req['type'].'?secret='.$hash.'&s='.$req['start_date'].'&e='.$req['end_date'].'&ids='.$enc_tsr_ids)
                                ];
                            }
                            
                            
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $url,
                                'message' => 'Download URL Daily Production Report '
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

}
