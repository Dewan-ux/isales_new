<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\CampaignModel;
use App\Models\DataNasabahModel;
use App\Models\VisitorModel;

class AdminDashboardController extends BaseController
{
    public function __construct()
    {
        $db2 = db_connect('secondary');
        $this->auth = new LoginModel();
        $this->d_dashboard = new DataNasabahModel();
        $this->d_visitor = new VisitorModel($db2);
        $this->campaign = new CampaignModel($db2);
    }

    public function listDashboard(){
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
                        if($this->validate->run($req, 'listcampaign') === FALSE)
                        {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            unset($req['token']);
                            $req['dashboard'] = 'admin';
                            $visitors = $this->d_visitor->getAll($req)->getResultArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $visitors,
                                'message' => 'Dashboard Admin'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    
}