<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminPremiController extends BaseController
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
            $url = BASE_API.'premi/list';
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
                $data['premi'] = $res['data'];
                $data['kategori'] = [
                '1' => '18-25',
                '2' => '26-30',
                '3' => '31-35',
                '4' => '36-40',
                '5' => '41-45',
                '6' => '46-50',
                '7' => '51-55' ];

                return view('admin/premi/tampil', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    
    public function create()
    {
        $url = BASE_API.'produk/list';
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
            $lsProduk = [];
            $lsProduk[''] = 'Pilih Produk';
            foreach($res['data'] as $data){
                $lsProduk[$data['id']] = $data['nama_produk'];
            }
            $data['produk'] = $lsProduk;
            $data['kategori'] = [
                '1' => '18-25',
                '2' => '26-30',
                '3' => '31-35',
                '4' => '36-40',
                '5' => '41-45',
                '6' => '46-50',
                '7' => '51-55'
            ];
            return view('admin/premi/tambah', $data);
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    public function add()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'premi/create';
            $data = array(
                'nominal'     => $this->request->getPost('nominal'),
                'satuan'   => $this->request->getPost('satuan'),
                'id_produk'   => $this->request->getPost('id_produk'),
                'kategori'   => $this->request->getPost('kategori'),
                'up'   => $this->request->getPost('up'),
                'token' => session()->get('token'),
            );
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
           
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', array($res['message']));
                session()->setFlashdata('errors', $res['data']);
                return view('admin/premi/tambah');
            } else { 
                return redirect()->to(base_url('admin/premi'));
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
            $url = BASE_API.'produk/list';
            $lsProduk = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $lsProduk);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if(sessionCheck() == true) 
        {
            $lsProduk = [];
            $lsProduk[0] = 'Pilih Produk';
            foreach($res['data'] as $ls){
                $lsProduk[$ls['id']] = $ls['nama_produk'];
            }
            $data['produk'] = $lsProduk;
            $data['kategori'] = [
                '1' => '18-25',
                '2' => '26-30',
                '3' => '31-35',
                '4' => '36-40',
                '5' => '41-45',
                '6' => '46-50',
                '7' => '51-55'
            ];
            $validation =  \Config\Services::validation();
            $url = BASE_API.'premi/id';
            $premi = array(
                'id'     => $id,
                'token' => session()->get('token'),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $premi);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'])
            {
                session()->setFlashdata('errors', $res['data']);
                return view('admin/premi/edit', $data);
            } else { 
                $data['premi'] = $res['data'];
                
                return view('admin/premi/edit', $data);
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
            $url = BASE_API.'premi/update';
            $data = array(
                'id'          => $this->request->getPost('id'),
                'nominal'     => $this->request->getPost('nominal'),
                'satuan'      => $this->request->getPost('satuan'),
                'id_produk'   => $this->request->getPost('id_produk'),
                'kategori'    => $this->request->getPost('kategori'),
                'up'          => $this->request->getPost('up'),
                'token'       => session()->get('token'),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            $data['premi'] = $data;
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', $validation->getErrors());
                return view('admin/premi/edit', $data);
            } else { 
                return redirect()->to(base_url('admin/premi'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function delete($id)
    {  
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'premi/delete';
            $data = array(
                'token' => session()->get('token'),
                'id'    => $id
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            
            if($res['error'])
            {
                // show error after delete
                // session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', $validation->getErrors());
                return $this->index();
            } else {
                return redirect()->to(base_url('admin/premi'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
	//--------------------------------------------------------------------
}
