<?php namespace App\Controllers\VirtualAccount;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\VirtualAccountModel;

class VirtualAccountController extends BaseController
{
    public function __construct()
    {
        $db2 = db_connect('secondary');
        $this->auth = new LoginModel();
        $this->va = new VirtualAccountModel();
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

        if($this->validate->run($req, 'createTags') === FALSE)
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

    public function listVa(){
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
                        $tags = $this->va->getAll()->getResultArray();
                        
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $tags,
                            'message' => 'List Virtual Account Total '.count($tags)
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
    

    public function uploadVa()
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
if($this->validate->run($req, 'uploadVa') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        
                        // Declare Success Message And Default Password Based on Role Request
                        // create t_tags
                        $ls = [];
                        $batch_va = json_decode($req['batch_va'], true);
                        foreach($batch_va as $va) {
                            $va['created_by'] = $val['id'];
                            $ls[] = $va;
                        }
                        $create = $this->va->addNewBatch($ls);
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
                                'status' => 200,
                                'error' => false,
                                'data' => '',
                                'message' => 'Simpan Batch Virtual Account SUKSES !'
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

   
}


