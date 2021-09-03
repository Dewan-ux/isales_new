<?php namespace App\Controllers\LandingPage;
use App\Controllers\BaseController;

use App\Models\DataNasabahModel;
use App\Models\LogShareCampaignModel;
use App\Models\CmsLandingPageModel;
use App\Models\VisitorModel;

class VisitorController extends BaseController {
    public function __construct()
    {
        $db2 = db_connect("secondary");
        $this->d_visitor = new VisitorModel($db2);
        $this->log_share_campaign = new LogShareCampaignModel($db2);
        $this->d_nasabah = new DataNasabahModel();
        $this->cms = new CmsLandingPageModel($db2);
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

            return $this->response->setJSON($res);
        }
        
        if($this->validate->run($req, 'landingPage') == FALSE)
        {
            $res = [
                'status' => 400,
                'error' => true,
                'data' => $this->validate->getErrors(),
                'message' => 'Validation Failed!'
            ];

            return $this->response->setJSON($res);
        }
        $create = $this->d_visitor->addNew($req);
        
        if(!$create)
        {
            $res = [
                'status' => 500,
                'error' => true,
                'data' => '',
                'message' => 'Something Went Wrong!'
            ];

            return $this->response->setJSON($res);
        } else {
            // $dataCalon = [
            //     'nama' => $req['nama'],
            //     'telepon' => $req['telepon']
            // ];
            // $addDataNasabah = $this->d_nasabah->addNew($dataCalon);
            // if(!$addDataNasabah)
            // {
            //     $res = [
            //         'status' => 500,
            //         'error' => true,
            //         'data' => '',
            //         'message' => 'Something Went Wrong!'
            //     ];
            // } else {
            $res = [
                'status' => 200,
                'error' => false,
                'data' => '',
                'message' => 'Data Added!'
            ];
            // }
            return $this->response->setJSON($res);
        }
    }

    public function createCms()
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
                 
                    if($this->validate->run($req, 'createCmsLandingPage') === FALSE)
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
                        $t_tags_data = [
                            'foto_banner' => $req['foto_banner'],
                            'foto_brosur' => $req['foto_brosur'],
                            'created_by' => $val['id']
                        ];
                        $create = $this->cms->addNew($t_tags_data);
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
                                'message' => 'Simpan CMS Landing Page method sukses'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updateCmsLandingPage()
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
                    if($this->validate->run($req, 'updateCmsLandingPage') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                            $t_tags_update = [
                                'foto_banner' => $req['foto_banner'],
                                'foto_brosur' => $req['foto_brosur'],
                                'updated_by' => $val['id']
                            ];
                           
                        $update = $this->cms->editAble($req['id'], $t_tags_update);
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
                                'message' => 'CmsLandingPage Updated'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
                        
    }
    public function listCmsLandingPage(){
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
                        $tags = $this->cms->getAll()->getResultArray();
                        
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $tags,
                            'message' => 'List CmsLandingPage Total'.count($tags)
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    

    public function deleteCmsLandingPage()
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
                    if($this->validate->run($req, 'deleteCmsLandingPage') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                            $t_cms_update = [
                                'aktif' => "0",
                                'updated_by' => $val['id']
                            ];
                        $update = $this->cms->editAble($req['id'], $t_cms_update);
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
                                'message' => 'CmsLandingPages dihapus'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
                        
    }

    public function cms(){
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
            if($this->validate->run($req, 'cms') === FALSE)
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
                        if($val['role'] != '1')
                        {
                            $res = [
                                'status' => 403,
                                'error' => true,
                                'data' => '',
                                'message' => 'Access Denied!'
                            ];
                        } else {
                            $cms = $this->cms->getAll(array('id' => $req['id']))->getRowArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $cms,
                                'message' => 'CmsLandingPage Detail'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function foto(){
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
            $cms = $this->cms->getAll($req)->getRowArray();
            $res = [
                'status' => 200,
                'error' => false,
                'data' => $cms,
                'message' => 'Foto ' .$req['foto']
            ];
        }
        return $this->response->setJSON($res);
    }
}