<?php namespace App\Controllers\Qa;
use App\Controllers\BaseController;

use App\Models\KesehatanNasabahModel;
use App\Models\SpajModel;
use App\Models\ProdukModel;
use App\Models\PremiModel;
use App\Models\PaymentModel;
use App\Models\LoginModel;
use App\Models\PertanyaanModel;
use App\Models\LogFupSpajModel;
use App\Models\ExtensionPabxModel;
use App\Models\DataNasabahModel;

class QaController extends BaseController
{
    public function __construct()
    {
        $this->spaj = new SpajModel();
        $this->kesehatan = new KesehatanNasabahModel();
        $this->produk = new ProdukModel();
        $this->premi = new PremiModel();
        $this->payment = new PaymentModel();
        $this->auth = new LoginModel();
        $this->pertanyaan = new PertanyaanModel();
        $this->log_fup = new LogFupSpajModel();
        $this->extension = new ExtensionPabxModel();
        $this->d_nasabah = new DataNasabahModel();
    }

    public function checkOrder(){
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
                    if($this->validate->run($req, 'checkSpaj') === FALSE)
                    {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed'
                        ];
                    } else {
                        if($val['role'] != 4)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Access Denied'
                            ];
                        } else {
                            $checked_data = [
                                'checked' => $req['checked'],
                                'checked_by' => $val['id']
                            ];
                            $checkedUpdate = $this->spaj->editAble($req['id'], $checked_data);
                            if(!$checkedUpdate)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                $log_fup_checked = [
                                    'id_spaj' => $req['id'],
                                    'status' => $req['checked'],
                                    'remark' => isset($req['remark']) ? $req['remark'] : NULL,
                                    'created_by' => $val['id']  
                                ];
                                $logFup = $this->log_fup->addNew($log_fup_checked);
                                if(!$logFup)
                                {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something when wrong!'
                                    ];
                                    return $this->response->setJSON($res);
                                } else {
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => '',
                                        'message' => CHECKED[$req['checked']] . ' SUCCESS!'
                                    ];
                                    return $this->response->setJSON($res);
                                }
                            }
                        }

                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function listOrder(){
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
                   
                    if($val['role'] == '4' || $val['role'] == '2'){
                        if($this->validate->run($req, 'listOrders') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed'
                            ];

                            return $this->response->setJSON($res);
                        } else {
     
                            $checked = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10','11','12'];
                            $listOrder = $this->spaj->getAll(array('role'=>$val['role'], 'id_login' => $req['tsr_id'], 'checked' => $checked))->getResultArray();
                            
                            if(count($listOrder) == 0)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Order Kosong'
                                ];
                            } else {
                                $nama_tsr = $listOrder[0]['nama_tsr'];
                                $orderList = [];
                                foreach($listOrder as $lsOrder)
                                {
                                    unset($lsOrder['nama_tsr']);
                                    $orderList[] = $lsOrder;
                                }
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $orderList,
                                    'message' => 'Order untuk '.$nama_tsr.' '.count($orderList)
                                ];
                            }
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
                   
                    if($val['role'] == '3' || $val['role'] == 1){
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $tsr_list = $this->auth->getAll(array('list' => '4', 'group' => $val['group']))->getResultArray();
                        if(count($tsr_list) == 0)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Tsr Kosong'
                            ];
                        } else {
                            $tsrList = [];
                            foreach($tsr_list as $ls)
                            {
                                unset($ls['token']);
                                unset($ls['password']);
                                $tsrList[] = $ls;
                            }
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $tsrList,
                                'message' => 'TSR List '.count($tsrList)
                            ];
                        }
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                   
                     if($val['role'] == '4' || $val['role'] == '2'){
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
                    if($val['role'] != '5')
                    {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        $spaj = $this->d_nasabah->getAll(array('spaj' => '1'))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $spaj,
                            'message' => 'List SPAJ Total '.count($spaj)
                        ];
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
                        if($val['role'] != '4')
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
                                    $lsAsk['jawaban']       = $ask['jawaban'];
                                    $lsAsk['remark'] = isset($ask['remark']) ? $ask['remark'] : "";
                                    $lsAsk['updated_by']    = $val['id'];
                                    $update_jawaban = $this->kesehatan->editAble($ask['id'], $lsAsk);

                                    if(!$update_jawaban){
                                        $res = [
                                            'status'  => 500,
                                            'error'   => true,
                                            'data'    => '',
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
}