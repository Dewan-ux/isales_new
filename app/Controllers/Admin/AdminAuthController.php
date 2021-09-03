<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;

class AdminAuthController extends BaseController
{
    public function __construct()
    {
        helper(['form']);
        $this->auth = new LoginModel();
        $this->user = new UserModel();
        $this->auth_log = new LogLoginModel();
    }


    public function login() {
        $url = BASE_API.'auth/login';
        $ch = curl_init($url);
        $validation =  \Config\Services::validation();

        $data = array(
            'username'  => $this->request->getPost('username'),
            'password'  => $this->request->getPost('password'),
            'ip'        => $this->get_client_ip()
        );

        if($validation->run($data, 'login') == FALSE){
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to(base_url('admin/login'));
        }else{
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl = curl_exec($ch);
            if($curl)
            {
                $res = json_decode($curl, true);
                if(!empty($res['code'])) {
                    session()->setFlashdata('errors', ["SERVER ERROR, THIS WILL FIXED LATER!", "ERR: ".$curl]);
                    session()->setFlashdata('inputs', $this->request->getPost());
                    return redirect()->to(base_url('admin/login'));
                }
                if($res['status'] != '200'){					
                    $messageError = array($res['message'], curl_error($ch));
                    session()->setFlashdata('inputs', $this->request->getPost());
                    session()->setFlashdata('errors', $messageError);
                    return redirect()->to(base_url('admin/login'));
                }else{
                    session()->set('token', $res['data']['token']);
                    session()->set('nama', $res['data']['nama']);
                    session()->set('role', $res['data']['role']);
                    session()->set('id', $res['data']['id']);
                    return redirect()->to(base_url('admin'));
                }
            } else {
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', [curl_error($ch)]);
                return redirect()->to(base_url('admin/login'));

            }
            curl_close($ch);
        }
    }

    public function logout() {
        $url = BASE_API.'auth/logout';
        $ch = curl_init($url);
        $validation =  \Config\Services::validation();
        $data = array(
            'token'  => session()->get('token'),
            'status' => 0
        );
        if($validation->run($data, 'logout') == FALSE){
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to(base_url('admin'));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if($res['status'] != 200){
                $messageError = array($res['message']);
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', $messageError);
                return redirect()->to(base_url('admin'));
            }else{
                // session()->remove('token');
                session()->destroy();
                return redirect()->to(base_url('admin/login'));
            }
        }

    }

    private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
