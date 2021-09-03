<?php namespace App\Controllers\Berita;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;
use App\Models\BeritaModel;
use App\Models\TagsModel;

class BeritaController extends BaseController
{
    public function __construct()
    {
        $db2 = db_connect("secondary");
        $this->auth = new LoginModel();
        $this->d_berita = new BeritaModel($db2);
        $this->d_tags = new TagsModel($db2);
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

        if($this->validate->run($req, 'createBerita') === FALSE)
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
                if($val['role'] != '1')
                {
                    $res = [
                        'status' => 403,
                        'error' => true,
                        'data' => '',
                        'message' => 'Access Denied!'
                    ];
                } else { 
                 
                    if($this->validate->run($req, 'createBerita') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        if(isset($req['foto']))
                        {
                        // Declare Success Message And Default Password Based on Role Request
                        // create t_berita
                        $t_berita_data = [
                            'judul'      => $req['judul'],
                            'isi'        => $req['isi'],
                            'kategori'   => $req['kategori'],
                            'id_tags'    => $req['id_tags'],
                            'foto'       => $req['foto'],
                            'created_by' => $val['id']
                        ];
                    } else {
                        $t_berita_data = [
                            'judul'      => $req['judul'],
                            'isi'        => $req['isi'],
                            'kategori'   => $req['kategori'],
                            'id_tags'    => $req['id_tags'],
                            'created_by' => $val['id']
                        ];
                    }
                        $create = $this->d_berita->addNew($t_berita_data);
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
                                'message' => 'Simpan Berita method sukses'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updateBerita()
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
                    if($this->validate->run($req, 'updateBerita') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                        if(isset($req['foto']))
                        {
                            $t_berita_update = [
                                'judul'      => $req['judul'],
                                'kategori'   => $req['kategori'],
                                'isi'        => $req['isi'],
                                'id_tags'    => $req['id_tags'],
                                'foto'       => $req['foto'],
                                'updated_by' => $val['id']
                            ];
                        } else {
                            $t_berita_update = [
                                'judul'      => $req['judul'],
                                'kategori'   => $req['kategori'],
                                'isi'        => $req['isi'],
                                'id_tags'    => $req['id_tags'],
                                'updated_by' => $val['id']
                            ];
                        }
                        $update = $this->d_berita->editAble($req['id'], $t_berita_update);
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
                                'message' => 'Berita Updated'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
                        
    }
    public function listBerita(){
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
                        $berita = $this->d_berita->getAll(array('list' => 2))->getResultArray();
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $berita,
                            'message' => 'List berita Total'.count($berita)
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    

    public function deleteBerita()
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
                    if($this->validate->run($req, 'deleteBerita') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                            $t_berita_update = [
                                'aktif' => "0",
                                'updated_by' => $val['id']
                            ];
                        $update = $this->d_berita->editAble($req['id'], $t_berita_update);
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
                                'message' => 'Berita dihapus'
                            ];
                            
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
                        
    }

    public function berita(){
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
            if($this->validate->run($req, 'berita') === FALSE)
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
                            $berita = $this->d_berita->getAll(array('id' => $req['id']))->getRowArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $berita,
                                'message' => 'Berita '. $berita['judul']
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
}


