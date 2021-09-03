<?php namespace App\Controllers\Order;
use App\Controllers\BaseController;

use App\Models\KesehatanNasabahModel;
use App\Models\SpajModel;
use App\Models\ProdukModel;
use App\Models\PremiModel;
use App\Models\PaymentModel;
use App\Models\PertanyaanModel;
use App\Models\ExtensionPabxModel;
use App\Models\DataNasabahModel;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->spaj = new SpajModel();
        $this->kesehatan = new KesehatanNasabahModel();
        $this->produk = new ProdukModel();
        $this->premi = new PremiModel();
        $this->payment = new PaymentModel();
        $this->pertanyaan = new PertanyaanModel();
        $this->extension = new ExtensionPabxModel();
        $this->d_nasabah = new DataNasabahModel();
    }

    public function createOrder(){
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
                        $pertanyaan = $req['pertanyaan'];
                        $req['created_by'] = $val['id'];
                        unset($req['pertanyaan']);
                        $create = $this->spaj->addNew($req);
                        if(!$create)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->spaj->errorMessage(),
                                'message' => 'Something when wrong!'
                            ];
                        } else {
                            $lsAsk = [];
                            $spaj_id = $create['id'];
                            $create_jawaban = false;
                            foreach($pertanyaan as $ask){
                                $lsAsk['id_spaj'] = $spaj_id;
                                $lsAsk['id_pertanyaan'] = $ask['id_pertanyaan'];
                                $lsAsk['jawaban'] = $ask['jawaban'];
                                $lsAsk['created_by'] = $val['id'];
                                $create_jawaban = $this->kesehatan->addNew($lsAsk);
                            }
                            if(!$create_jawaban){
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => $this->kesehatan->errorMessage(),
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => '',
                                    'message' => 'Simpan Data Order nasabah '.$req['nama'].' Berhasil'
                                ];
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
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                   
                    if($val['role'] == 1){
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $checked = ['0', '1', '2', '3'];
                        $listOrder = $this->spaj->getAll(array('role'=>$val['role'], 'id_login' => $val['id'], 'checked' => $checked))->getResultArray();
                        if(count($listOrder) == 0)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Order Kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => true,
                                'data' => $listOrder,
                                'message' => 'Order untuk '.$val['nama'].' '.count($listOrder)
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
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                   
                    if($val['role'] == 1){
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
                            $kesehatan_nasabah = $this->kesehatan->getAll(array('id_spaj'=>$order['id']))->getResultArray();
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
                                $ls['destination'] = $data_nasabah['telp1'];
                                $ls['extension'] = $extension['extension'];
                                $order['extension'] = $ls;
                                $res = [
                                    'status' => 200,
                                    'error' => true,
                                    'data' => $order,
                                    'message' => 'Order untuk TSR'.$val['nama'].' Nasabah '.$order['nama']
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
                        if($val['role'] != 3)
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
                            $req['checked'] = 3;
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
                        'status' => 400,
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
        return $this->response->setJSON($res);
    }

    public function productList(){
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
                    if($val['role'] != 3)
                    {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $produk = $this->produk->getAll()->getResultArray();
                        foreach($produk as $item){
                            unset($item['keterangan']);
                            unset($item['created_at']);
                            unset($item['updated_at']);
                            unset($item['created_by']);
                            unset($item['updated_by']);
                        }
                        
                        if(!$produk)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Produk Kosong'
                            ];
                        } else {
                            $premi = $this->premi->getAll()->getResultArray();
                            foreach($premi as $item){
                                unset($item['created_by']);
                                unset($item['created_at']);
                            }

                            if(!$premi)
                            {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Premi Kosong'
                                ];
                            } else {

                                $payment = $this->payment->getAll()->getResultArray();
                                foreach($payment as $item){
                                    unset($item['created_by']);
                                    unset($item['created_at']);
                                }

                                if(!$payment)
                                {
                                    $res = [
                                        'status' => 404,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Data Payment Kosong'
                                    ];
                                } else {
                                    $ls = array('products' => $produk, 'premi'=>$premi, 'payment'=>$payment);
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $ls,
                                        'message' => 'Daftar Produk, Premi dan Payment'
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

    public function pertanyaanList(){
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
                    if($val['role'] != 3)
                    {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $pertanyaan = $this->pertanyaan->getAll()->getResultArray();
                        foreach($pertanyaan as $item){
                            unset($item['created_at']);
                            unset($item['updated_at']);
                            unset($item['created_by']);
                            unset($item['updated_by']);
                        }
                        
                        if(!$pertanyaan)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Pertanyaan Kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $pertanyaan,
                                'message' => 'Daftar Pertanyaan Kesehatan'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
}