<?php namespace App\Controllers\Pekerjaan;
use App\Controllers\BaseController;

use App\Models\PekerjaanModel;

class PekerjaanController extends BaseController
{
    public function __construct()
    {
        $this->pekerjaan = new PekerjaanModel();
    }
    public function pekerjaanList()
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
                    if($val['role'] == '3')
                    {
                        $pekerjaan = $this->pekerjaan->getAll()->getResultArray();
                        if(count($pekerjaan) == 0)
                        {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Pekerjaan Kosong (404)'
                            ];
                        } else {
                            
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $pekerjaan,
                                'message' => 'Pekerjaan Data List'
                            ];
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

    

}
