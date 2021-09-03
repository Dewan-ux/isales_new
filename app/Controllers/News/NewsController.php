<?php namespace App\Controllers\News;

use App\Controllers\BaseController;

use App\Models\BeritaModel;
use App\Models\TagsModel;
use App\Models\VisitorModel;
use CodeIgniter\Encryption\Encryption;

class NewsController extends BaseController
{
    public function __construct()
    {
        $db2 = db_connect("secondary");
        $this->berita = new BeritaModel($db2);
        $this->tags = new TagsModel($db2);
        $this->visitor = new VisitorModel($db2);

        $this->encrypter = \Config\Services::encrypter();
    }

    public function listTags()
    {
        $tags = $this->tags->getAll()->getResultArray();
        if(count($tags) <= 0){
            $res = [
                'status' => 404,
                'error' => false,
                'data' => '',
                'message' => 'Daftar Tags Kosong'
            ];
        }else{
            $ls = [];
            foreach($tags as $tag)
            {
                $tag['id'] = encryptor('encrypt',$tag['id']);
                $ls[] = $tag;
            }
            $res = [
                'status' => 200,
                'error' => false,
                'data' => $ls,
                'message' => 'Daftar Tags'.count($tags)
            ];
        }
        return $this->response->setJSON($res);
    }

    public function checkExistIp()
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
            if($this->validate->run($req, 'calon_nasabah') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'IP Invalid'
                ];

                return $this->response->setJSON($res);
            } else {
                $tags = $this->visitor->getAll(array('ip' => $req['ip']))->getRowArray();
                if(empty($tags)){
                    $res = [
                        'status' => 404,
                        'error' => true,
                        'data' => false,
                        'message' => 'IP Belum terdaftar'
                    ];
                }else{
                    $res = [
                        'status' => 200,
                        'error' => false,
                        'data' => true,
                        'message' => 'IP '.$req['ip'].' sudah terdaftar'
                    ];
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function pages(){
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
            if($this->validate->run($req, 'berita_id') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'IP Invalid'
                ];

                return $this->response->setJSON($res);
            } else {
                $id = encryptor('decrypt', $req['id']);
                $berita = $this->berita->getAll(array('id' => $id, 'ip' => $req['ip']))->getRowArray();
                if(empty($berita))
                {
                    $res = [
                        'status' => 404,
                        'error' => false,
                        'data' => '',
                        'message' => 'Berita Kosong'
                    ];
                } else {
                    $res = [
                        'status' => 200,
                        'error' => false,
                        'data' => $berita,
                        'message' => 'Berita dengan judul '.$berita['judul']
                    ];
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function latestBeritaLimit(){
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
            if($this->validate->run($req, 'limit_berita') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Limit Invalid'
                ];

                return $this->response->setJSON($res);
            } else {

                $berita = $this->berita->getAll(array('list' => '4', 
                    'ip' => $req['ip'], 
                    'limit' => $req['limit']))->getResultArray();

                $lsberita = [];
                foreach($berita as $val)
                {
                    unset($val['isi']);
                    $val['id'] = encryptor('encrypt',$val['id']);
                    $lsberita[] = $val;
                }

                if(count($lsberita) == 0)
                {
                    $res = [
                        'status' => 404,
                        'error' => false,
                        'data' => '',
                        'message' => 'Berita Terbaru Limit Kosong'
                    ];
                } else {
                    $res = [
                        'status' => 200,
                        'error' => false,
                        'data' => $lsberita,
                        'message' => 'Berita Terbaru Limit '.count($lsberita)
                    ];
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function beritaByCategory(){
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
            if($this->validate->run($req, 'berita_id') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed'
                ];

                return $this->response->setJSON($res);
            } else {
                $id = encryptor('decrypt', $req['id']);
                $berita = $this->tags->getAll(array('id' => $id,
                    'ip' => $req['ip']))->getResultArray();

                $lsberita = [];
                foreach($berita as $val)
                {
                    unset($val['isi']);
                    $val['id'] = encryptor('encrypt',$val['id']);
                    $lsberita[] = $val;
                }

                if(count($lsberita) == 0)
                {
                    $res = [
                        'status' => 404,
                        'error' => false,
                        'data' => '',
                        'message' => 'Berita Kategori Kosong'
                    ];
                } else {
                    $res = [
                        'status' => 200,
                        'error' => false,
                        'data' => $lsberita,
                        'message' => 'Berita Kategori '.count($lsberita)
                    ];
                }
            }
        }
        return $this->response->setJSON($res);
    }
}