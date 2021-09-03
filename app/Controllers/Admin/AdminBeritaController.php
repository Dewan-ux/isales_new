<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminBeritaController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }

	public function index()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'berita/list';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                $data['berita'] = $res['data'];
                $data['kategori'] = ['1' => '25-30',
                '2' => '30-45',
                '3' => '>45'   ];
                return view('admin/berita/tampil', $data);
            }
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

 public function create()
    {
        $url = BASE_API.'tags/list';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);

        if(sessionCheck() == true) 
        {
            $lsTags = [];
            $lsTags[0] = 'Pilih Tags';
            foreach($res['data'] as $ls){
                $lsTags[$ls['id']] = $ls['tags'];
            }

            $data['tags'] = $lsTags;
            

            return view('admin/berita/tambah',$data);
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function add()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'berita/create';
            $image = $this->request->getFile('foto');
            if(isset($image)){
                $data = array(
                    'judul'     => $this->request->getPost('judul'),
                    'isi'   => $this->request->getPost('isi'),
                    'kategori'   => $this->request->getPost('kategori'),
                    'id_tags'   => $this->request->getPost('id_tags'),
                    'foto'   => imageToBase64($image),
                    'token' => session()->get('token'),
                );
            } else {
                $data = array(
                    'judul'     => $this->request->getPost('judul'),
                    'isi'   => $this->request->getPost('isi'),
                    'kategori'   => $this->request->getPost('kategori'),
                    'id_tags'   => $this->request->getPost('id_tags'),
                    'token' => session()->get('token'),
                );
            }
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // print_r($res);
            // die();
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', array($res['message']));
                session()->setFlashdata('errors', $res['data']);
                return view('admin/berita/tambah');
            } else { 
                return redirect()->to(base_url('admin/berita'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    public function edit($id)
    {  
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'tags/list';
            $lsTags = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $lsTags);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if(sessionCheck() == true) 
        {
            $lsTags = [];
            $lsTags[0] = 'Pilih Tags';
            foreach($res['data'] as $data){
                $lsTags[$data['id']] = $data['tags'];
            }
            $data['tags'] = $lsTags;
            
            $validation =  \Config\Services::validation();
            $url = BASE_API.'berita/id';
            $berita = array(
                'id'     => $id,
                'token' => session()->get('token'),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $berita);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if($res['error'])
            {
                session()->setFlashdata('errors', $res['data']);
                return view('admin/berita/edit', $data);
            } else { 
                $data['berita'] = $res['data'];
                return view('admin/berita/edit', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}
    public function update()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'berita/update';

            $foto = $this->request->getFile('foto');
            if(!empty($foto->getName()))
            {
                $data = array(
                    'id'     => $this->request->getPost('id'),
                    'judul'     => $this->request->getPost('judul'),
                    'isi'     => $this->request->getPost('isi'),
                    'tags'      => $this->request->getPost('tags'),
                    'id_tags'   => $this->request->getPost('id_tags'),
                    'kategori'    => $this->request->getPost('kategori'),
                    'foto'   => imageToBase64($foto),
                    'token'       => session()->get('token'),
                );
            } else {
                $data = array(
                    'id'     => $this->request->getPost('id'),
                    'judul'     => $this->request->getPost('judul'),
                    'isi'     => $this->request->getPost('isi'),
                    'tags'      => $this->request->getPost('tags'),
                    'id_tags'   => $this->request->getPost('id_tags'),
                    'kategori'    => $this->request->getPost('kategori'),
                    'token'       => session()->get('token'),
                );
            }
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            $data['berita'] = $data;
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', $res['data']);
                return view('admin/berita/edit', $data);
            } else { 
                return redirect()->to(base_url('admin/berita'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}
