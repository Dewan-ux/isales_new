<?php namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Config\Constant;

class UserController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
        $this->recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY, new \ReCaptcha\RequestMethod\CurlPost());
    }

    public function index()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'auth/list';
            $data = array(
                'token' => session()->get('token')
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // print_r($res);
            // die();
            if($res['error'])
            {
                session()->setFlashdata('errors', array($res['message']));
                return redirect()->to(base_url('admin/login'));
            } else { 
                // unset($res['data'][0]);
                // $data['users'] = $res['data'];
                foreach($res['data'] as $temp){
                    $tmp = $temp;
                    $tmp['foto'] = (!empty($temp['foto']))  ? $temp['foto'] : NIMG;
                    $data['users'][] = $tmp;
                }
                return view('admin/users/tampil', $data);
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
            return view('admin/users/tambah');
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function add()
    {
        if(sessionCheck() == true) 
        {
            $url = BASE_API.'auth/create';
            $image = $this->request->getFile('foto');
            $data = array(
                'nama'     => $this->request->getPost('nama'),
                'email'   => $this->request->getPost('email'),
                'username'   => $this->request->getPost('username'),
                'jk'   => $this->request->getPost('jk'),
                'password'   => $this->request->getPost('password'),
                'role'   => $this->request->getPost('role'),
                'token' => session()->get('token')
            );
            if(isset($image))
            {
                if(!empty($image->getName())){
                    $data['foto'] = imageToBase64($image);
                }
            }
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'] == 1)
            {
                // show error after update
                session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', array($res['message']));
                session()->setFlashdata('errors', $res['data']);
                return view('admin/users/tambah');
            } else { 
                return redirect()->to(base_url('admin/user'));
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
            $url = BASE_API.'auth/user/id';
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
                return view('admin/users/edit', $data);
            } else { 
                $data['user'] = $res['data'];
                return view('admin/users/edit', $data);
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
            $url = BASE_API.'auth/update';
            $foto = $this->request->getFile('foto');
            $data = array(
                'id'     => $this->request->getPost('id'),
                'id_user'     => $this->request->getPost('id_user'),
                'nama'     => $this->request->getPost('nama'),
                'email'   => $this->request->getPost('email'),
                'username'   => $this->request->getPost('username'),
                'jk'   => $this->request->getPost('jk'),
                'password'   => $this->request->getPost('password'),
                'role'   => $this->request->getPost('role'),
                'token' => session()->get('token'),
            );
            if(isset($foto)){
                if(!empty($foto->getName()))
                {
                    $data['foto'] = imageToBase64($foto);
                }
            }

            
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if($res['error'] == 1)
            {
                $data['user'] = $data;
                // show error after update
                session()->setFlashdata('inputs', $data);
                session()->setFlashdata('errors', $res['data']);
                return view('admin/users/edit', $data);
            } else { 
                if($this->request->getPost('id') == session()->get('id')){
                    session()->set('nama', $data['nama']);
                }
                return redirect()->to(base_url('admin/user'));
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
            $url = BASE_API.'auth/delete';
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
                return redirect()->to(base_url('admin/user'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function forceLogout()
    {  
        if(sessionCheck() == true) 
        {
            // $captcha = $this->recaptcha();
            
            // if($captcha['error'] == FALSE)
            // {
                $url = BASE_API.'auth/forcelogout';
                $data = array(
                    'token' => session()->get('token'),
                    'password' => $this->request->getPost('confirm_password'),
                    'id'    => $this->request->getPost('id')
                );
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = json_decode(curl_exec($ch), true);
                curl_close($ch);
                if($res['error'])
                {
                    // show error after delete
                    session()->setFlashdata('inputs', $this->request->getPost());
                    // session()->setFlashdata('errors', $validation->getErrors());
                    
                    return $this->index();
                } else {
                    session()->setFlashdata('success', "Logout User Done!");
                    return redirect()->to(base_url('admin/user'));
                }
            // } else {

                // $messageError = array($captcha['message']);
                
                // session()->setFlashdata('inputs', $this->request->getPost());
                // session()->setFlashdata('errors', $messageError);
                // return redirect()->to(base_url('admin/user'));
            // }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    function recaptcha()
    {
        try {
            if ($this->request->getPost('g-recaptcha-response') == NULL) {
                $responseArray = array('code' => '501', 'error' => TRUE, 'type' => 'danger', 'message' => 'ReCaptcha is not set.');
            }
            $response = $this->recaptcha->verify($this->request->getPost('g-recaptcha-response'), $this->request->getIPAddress());
            if (!$response->isSuccess()) {
                $responseArray = array('code' => '500', 'error' => TRUE, 'type' => 'danger', 'message' => 'ReCaptcha was not validated.');
            }

            $responseArray = array('code' => '200', 'error' => FALSE, 'type' => 'success', 'message' => 'Success');


        } catch (\Exception $e){
            $responseArray = array('code' => '403', 'error' => TRUE, 'type' => 'danger', 'message' => $e->getMessage());
        }

        return $responseArray;
    }
    //--------------------------------------------------------------------

}
