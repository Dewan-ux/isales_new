<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class AdminController extends BaseController
{
	public function index()
    {
        if(sessionCheck() == true)
        {
            // $req = [
            //     'token'       => session()->get('token'),
            // ];
            // $url = BASE_API.'admin/dashboard';
            // $ch = curl_init($url);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $res = json_decode(curl_exec($ch), true);
            // curl_close($ch);
            // $data['chart'] = $res['data'];
            // return view('admin/home', $data);
            return view('admin/home');
        } else {
            // return view('admin/home');
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function visitors()
    {
        if(sessionCheck() == true)
        {
            $req = [
                'token'       => session()->get('token'),
                'start_date'  => $this->request->getPost('start_date'),
                'end_date'  => $this->request->getPost('end_date')
            ];
            $url = BASE_API.'admin/dashboard';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            return $this->response->setJSON($res);
        } else {
            // return view('admin/home');
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function login()
    {
        if(sessionCheck() == true)
        {
            $req = [
                'token'       => session()->get('token'),
            ];
            $url = BASE_API.'admin/dashboard';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            $data['chart'] = $res['data'];
            return view('admin/home', $data);
        } else {
            return view('admin/auth/login');
        }
    }

	//--------------------------------------------------------------------

}
