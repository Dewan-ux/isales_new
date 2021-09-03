<?php namespace App\Controllers\Premi;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;
use App\Models\ProdukModel;
use App\Models\PremiModel;

class PremiController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->d_premi = new PremiModel();
        $this->d_produk = new ProdukModel();
    }
    public function mastercreate()
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

        if($this->validate->run($req, 'createPremi') === FALSE)
        {
            $res = [
                'status' => 400,
                'error' => true,
                'data' => $this->validate->getErrors(),
                'message' => 'Validation Failed!'
            ];

            return $this->response->setJSON($res);
        }
    }

    public function create()
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
                if($val['role'] == '1' || $val['role'] == '5')
                {
                       if($this->validate->run($req, 'createPremi') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        // Declare Success Message And Default Password Based on Role Request
                        // create t_premi
                        $t_premi_data = [
                            'nominal'    => $req['nominal'],
                            'satuan'     => $req['satuan'],
                            'id_produk'  => $req['id_produk'],
                            'kategori'   => $req['kategori'],
                            'up'         => $req['up'],
                            'created_by' => $val['id']
                        ];
                        $create = $this->d_premi->addNew($t_premi_data);
                        if(!$create)
                        {
                            $res = [
                                'status'   => 500,
                                'error'    => true,
                                'data'     => '',
                                'message'  => 'Something went wrong!'
                            ];
                        } else {
                            $res = [
                                'status' => 201,
                                'error' => false,
                                'data' => '',
                                'message' => 'Simpan premi method sukses'
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
        return $this->response->setJSON($res);
    }

    
    public function updatePremi()
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
                    'message' => 'Authentication salah!'
                ];
            } else {
                if($val['role'] == '1' || $val['role'] == '5')
                {
                     if($this->validate->run($req, 'updatePremi') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                            $t_premi_update = [
                                'nominal'    => $req['nominal'],
                                'satuan'     => $req['satuan'],
                                'kategori'   => $req['kategori'],
                                'id_produk'  => $req['id_produk'],
                                'up'         => $req['up'],
                                'created_by' => $val['id']
                            ];
                        $update = $this->d_premi->editAble($req['id'], $t_premi_update);
                        if(!$update)
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
                                'message' => 'Premi Updated'
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
        return $this->response->setJSON($res);
                        
    }

    public function listPremi(){
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
                        
                        $premi = $this->d_premi->getAll(array('list' => 1))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $premi,
                            'message' => 'List Premi Total'.count($premi)
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
    public function deletePremi()
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
                if($val['role'] == '1' || $val['role'] == '5')
                {
                    if($this->validate->run($req, 'deletePremi') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                            $t_premi_update = [
                                'aktif' => "0",
                                'updated_by' => $val['id']
                            ];
                        $update = $this->d_premi->editAble($req['id'], $t_premi_update);
                        if(!$update)
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
                                'message' => 'Premi Updated'
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
        return $this->response->setJSON($res);
                        
    }

    public function premi(){
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
            if($this->validate->run($req, 'premi') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed!'
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
                            $premi = $this->d_premi->getAll(array('id' => $req['id']))->getRowArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $premi,
                                'message' => 'Premi '. $premi['nominal']
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
        }
        return $this->response->setJSON($res);
    }
}



