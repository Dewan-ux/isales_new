<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminProductPaCarController extends BaseController
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
            $url = BASE_API.'produksmile/list';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // print_r($res);
            // die();
            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                $data['produk'] = $res['data'];
                return view('admin/produk/index', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function create()
    {
        if(sessionCheck() == true) 
        {
            return view('admin/produk/more');
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function add()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'produksmile/create';
            $data = array(
                'nama_produk'     => $this->request->getPost('nama_produk'),
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
                return view('admin/produk/more');
            } else { 
                return redirect()->to(base_url('admin/produk/index'));
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
            $validation =  \Config\Services::validation();
            $url = BASE_API.'produksmile/id';
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
                session()->setFlashdata('errors', $res['data']);
                return view('admin/produk/ubah', $data);
            } else { 
                $data['produk'] = $res['data'];
                return view('admin/produk/ubah', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
    public function update()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'produksmile/update';
            $data = array(
                'id'              => $this->request->getPost('id'),
                'nama_produk'     => $this->request->getPost('nama_produk'),
                'token' => session()->get('token'),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            
            curl_close($ch);
            $data['produk'] = $data;
            if($res['error'])
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', $validation->getErrors());
                return view('admin/produk/ubah', $data);
            } else { 
                return redirect()->to(base_url('admin/produk/index'));
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
            $url = BASE_API.'produksmile/delete';
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
                return redirect()->to(base_url('admin/produk/index'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
	//--------------------------------------------------------------------

}
