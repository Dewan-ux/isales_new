<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminPremiPaCarController extends BaseController
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
            $url = BASE_API.'premismile/list';
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

                return view('admin/premi/index', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    
    public function create()
    {
        $url = BASE_API.'produksmile/list';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // var_dump($res); die();
        if(sessionCheck() == true) 
        {
            $lsProduk = [];
            $lsProduk[''] = 'Pilih Produk';
            foreach($res['data'] as $data){
                $lsProduk[$data['id']] = $data['nama_produk'];
            }
            $data['produk'] = $lsProduk;
          
            return view('admin/premi/more', $data);
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    public function add()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'premismile/create';
            $data = array(
                'nominal'     => $this->request->getPost('nominal'),
                'satuan'   => $this->request->getPost('satuan'),
                'id_produk_pa_car'   => $this->request->getPost('id_produk_pa_car'),
                'up'   => $this->request->getPost('up'),
                'manfaat'   => $this->request->getPost('manfaat'),
                'token' => session()->get('token'),
            );
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // print_r($res); die();
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', array($res['message']));
                session()->setFlashdata('errors', $res['data']);
                return view('admin/premi/more');
            } else { 
                return redirect()->to(base_url('admin/premi/index'));
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
            $url = BASE_API.'produksmile/list';
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
            
            $validation =  \Config\Services::validation();
            $url = BASE_API.'premismile/id';
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
                return view('admin/premi/ubah', $data);
            } else { 
                $data['premi'] = $res['data'];
                
                return view('admin/premi/ubah', $data);
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
            $url = BASE_API.'premismile/update';
            $data = array(
                'id'                 => $this->request->getPost('id'),
                'nominal'            => $this->request->getPost('nominal'),
                'satuan'             => $this->request->getPost('satuan'),
                'id_produk_pa_car'   => $this->request->getPost('id_produk_pa_car'),
                'up'                 => $this->request->getPost('up'),
                'manfaat'                 => $this->request->getPost('manfaat'),
                'token'              => session()->get('token'),
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
                return redirect()->to(base_url('admin/premi/index'));
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
            $url = BASE_API.'premismile/delete';
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
                return redirect()->to(base_url('admin/premi/index'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
	//--------------------------------------------------------------------
}
