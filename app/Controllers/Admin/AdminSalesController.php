<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminSalesController extends BaseController
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
            $url = BASE_API.'sales/list';
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
                $data['sales'] = $res['data'];
                return view('admin/helper/tampil', $data);
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
            return view('admin/helper/tambah');
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function add()
    {
        if(sessionCheck() == true)
        {
            $url = BASE_API.'sales/create';
            $data = array(
                'pdf'     => imageToBase64($this->request->getFile('pdf')),
                'pdf_ho'  => imageToBase64($this->request->getFile('pdf_ho')),
                'pdf_faq'  => imageToBase64($this->request->getFile('pdf_faq')),
                'pdf_plan'  => imageToBase64($this->request->getFile('pdf_plan')),
                'pdf_dumb'  => imageToBase64($this->request->getFile('pdf_dumb')),
                'pdf_kantor'  => imageToBase64($this->request->getFile('pdf_kantor')),

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
                return view('admin/helper/tambah');
            } else {
            return redirect()->to(base_url('admin/helper'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function preview($id)
    {
        if(sessionCheck() == true)
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'sales/id';
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
                return view('admin/helper/preview', $data);
            } else {
                $data['sales'] = $res['data'];
                return view('admin/helper/preview', $data);
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
            $url = BASE_API.'sales/delete';
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
            return redirect()->to(base_url('admin/helper'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
	//--------------------------------------------------------------------
}
