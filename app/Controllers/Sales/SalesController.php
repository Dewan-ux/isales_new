<?php namespace App\Controllers\Sales;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;
use App\Models\SalesModel;

class SalesController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->d_sales = new SalesModel();
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

        if($this->validate->run($req, 'createSales') === FALSE)
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
                    if($this->validate->run($req, 'createSales') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        // Declare Success Message And Default Password Based on Role Request
                        // create t_produk
                        $t_sales_data = [
                            'pdf' => $req['pdf'],
                            'pdf_ho' => $req['pdf_ho'],
                            'pdf_faq' => $req['pdf_faq'],
                            'pdf_plan' => $req['pdf_plan'],
                            'pdf_dumb' => $req['pdf_dumb'],
                            'pdf_kantor' => $req['pdf_kantor'],
                            'pdf_pa' => $req['pdf_pa'],
                            'pdf_faq_script_rev' => $req['pdf_faq_script_rev'],
                            'pdf_produk_pa' => $req['pdf_produk_pa'],
                            'created_by' => $val['id']
                        ];
                        $create = $this->d_sales->addNew($t_sales_data);
                        if(!$create)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            $res = [
                                'status' => 201,
                                'error' => false,
                                'data' => '',
                                'message' => 'Simpan pdf method sukses'
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

     public function deleteSales()
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
                        if($this->validate->run($req, 'deleteSales') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                            $t_sales_update = [
                                'aktif' => "0",
                                'updated_by' => $val['id']
                            ];
                        $update = $this->d_sales->editAble($req['id'], $t_sales_update);
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
                                'message' => 'Pdf dihapus'
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

    public function listSales(){
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
                        $sales = $this->d_sales->getAll(array('list' => 1))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $sales,
                            'message' => 'List Sales Total'.count($sales)
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

    public function sales(){
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
            if($this->validate->run($req, 'sales') === FALSE)
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
                            $sales = $this->d_sales->getAll(array('id' => $req['id']))->getRowArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $sales,
                                'message' => 'Pdf '. $sales['pdf']
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


