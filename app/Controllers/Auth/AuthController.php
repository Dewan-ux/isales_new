<?php namespace App\Controllers\Auth;
use App\Controllers\BaseController;

use App\Models\LoginModel;
use App\Models\UserModel;
use App\Models\LogLoginModel;
use App\Models\ExtensionPabxModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->user = new UserModel();
        $this->auth_log = new LogLoginModel();
        $this->extension = new ExtensionPabxModel();
    }

    public function demo()
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
                    'status' => 145,
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
                    if($this->validate->run($req, 'demoUser') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                        $last_id = $this->auth->getLastId(array('role' => $req['role']))->getRowArray();
                        $logins = create_demo_login($req['role'], $req['length'], $val['id'], isset($req['group']) ? $req['group'] : null, ($last_id['id']+1));
                        // create t_login
                        
                        $create = $this->auth->addNewBatch($logins);
                        
                        if(!$create)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            $new_id_login = $create['id'];

                            // create t_user
                            $users = create_demo_user($req['role'], $req['length'], $val['id'], $new_id_login, ($last_id['id']+1));
                            
                            $create_user = $this->user->addNewBatch($users);

                            if(!$create_user)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong ! '
                                ];
                            } else {
                                foreach($logins as $index => $r)
                                {
                                    $data = [
                                        'id_login' => $index,
                                        'username' => $r['username'],
                                        'role'     => $r['role'],
                                        'user_id'  => $val['id']
                                    ];
                                    if($r['role'] == '2' || $r['role'] == '3'){
                                        if(!$ext = createExtension($data)){
                                            $res = [
                                                'status' => 500,
                                                'error' => true,
                                                'data' => $ext,
                                                'message' => 'Something went wrong at Extension!'
                                            ]; 
                                            return $this->response->setJSON($res);
                                        }
                                    }
                                }
                                
                                
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => '',
                                    'message' => 'Create Batch User Demo Success with Role '.$req['role']
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
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

        if($this->validate->run($req, 'createUserM') === FALSE)
        {
            $res = [
                'status' => 400,
                'error' => true,
                'data' => $this->validate->getErrors(),
                'message' => 'Validation Failed!'
            ];

            return $this->response->setJSON($res);
        } else {
            $req['password'] = do_hash($req['password']);
            $req = array_merge($req, array('role' => '1', 'active' => '1'));
            $create = $this->auth->addNew($req);

            $res = [
                'status' => 201,
                'error' => false,
                'data' => '',
                'message' => 'User Created!'
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
                    'status' => 145,
                    'error' => true,
                    'data' => '',
                    'message' => 'Authentication Failed!'
                ];
            } else {
                if($val['role'] == '5' || $val['role'] == '1')
                {
                    if($this->validate->run($req, 'createUser') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];

                    } else {
                        // Declare Success Message And Default Password Based on Role Request
                        $msg = createMessage($req['role']);
                        // $pss = createPassword($req['role']);
                        
                        // create t_login
                        $t_login_data = [
                            'username' => $req['username'],
                            'password' => do_hash($req['password']),
                            'role' => $req['role'],
                            'created_by' => $val['id']
                        ];
                        $create = $this->auth->addNew($t_login_data);

                        if(!$create)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            $new_id_login = $create['id'];
                            
                            if(isset($req['foto']))
                            {
                                if(!imageCheck($req['foto']))
                                {
                                    $this->auth->deleteAble($new_id_login);

                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong in FOTO!'
                                    ];
                                    
                                    return $this->response->setJSON($res);
                                } else {
                                    $insert_data = [
                                        'id_login' => $new_id_login,
                                        'nama' => $req['nama'],
                                        'jk' => $req['jk'],
                                        'created_by' => $val['id'],
                                        'foto' => $req['foto']
                                    ];
                                }
                            } else {
                                $insert_data = [
                                    'id_login' => $new_id_login,
                                    'nama' => $req['nama'],
                                    'jk' => $req['jk'],
                                    'created_by' => $val['id']
                                ];
                            }

                            // create t_user
                            $create_user = $this->user->addNew($insert_data);

                            if(!$create_user)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong ! '
                                ];
                            } else {
                                $data = [
                                    'id_login' => $new_id_login,
                                    'username' => $req['username'],
                                    'role'     => $req['role'],
                                    'user_id'  => $val['id']
                                ];
                                if($req['role'] == '2' || $req['role'] == '3'){
                                    if(!$ext = createExtension($data)){
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => $ext,
                                            'message' => 'Something went wrong at Extension!'
                                        ]; 
                                        return $this->response->setJSON($res);
                                    }
                                }
                                
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $data,
                                    'message' => $msg
                                ];
                            }
                        }
                    }
                } else {
                    $res = [
                        'status' => 403,
                        'error' => true,
                        'data' => '',
                        'message' => 'Access Denied!'
                    ];
                }
            }
        }

        return $this->response->setJSON($res);
    }

    

    public function updateAuth()
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
                if($val['role'] == '1' || $val['role'] == '5')
                {
                if($this->validate->run($req, 'updateUser') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        if(!isset($req['password']) || empty($req['password']) || $req['password'] == ""){
                            $t_login_update = [
                                'username' => $req['username'],
                                'role' => $req['role'],
                                'updated_by' => $val['id']
                            ];
                        } else {
                            $t_login_update = [
                                'username' => $req['username'],
                                'role' => $req['role'],
                                'password' => do_hash($req['password']),
                                'updated_by' => $val['id']
                            ];
                        }
                        $update = $this->auth->editAble($req['id'], $t_login_update);

                        if(!$update)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {

                            if(isset($req['foto']))
                            {
                                if(!imageCheck($req['foto']))
                                {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                    
                                    return $this->response->setJSON($res);
                                } else {
                                    $update_data = [
                                        'nama' => $req['nama'],
                                        'jk' => $req['jk'],
                                        'updated_by' => $val['id'],
                                        'foto' => $req['foto']
                                    ];
                                }
                            } else {
                                $update_data = [
                                    'nama' => $req['nama'],
                                    'jk' => $req['jk'],
                                    'updated_by' => $val['id']
                                ];
                            }

                            // update t_user
                            $update_user = $this->user->editAble($req['id_user'], $update_data);
                            if(!$update_user)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong!'
                                ];
                            } else {
                                $checkExistExtension = $this->extension->getAll(['id_login' => $req['id']])->getRowArray();
                                if(!isset($checkExistExtension))
                                {
                                    $data = [
                                        'id_login' => $req['id'],
                                        'username' => $req['username'],
                                        'role'     => $req['role'],
                                        'user_id'  => $val['id']
                                    ];
                                    if($ext = createExtension($data))
                                    {
                                        $res = [
                                            'status' => 404,
                                            'error' => false,
                                            'data' => $ext,
                                            'message' => 'User Updated'
                                        ];
                                        return $this->response->setJSON($res);                                        
                                    }
                                }
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => '',
                                    'message' => 'User Updated'
                                ];
                            }
                        }
                    }
                } else { 
                     $res = [
                        'status' => 403,
                        'error' => true,
                        'data' => '',
                        'message' => 'Access Denied!'
                    ];
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function deleteAuth()
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
                    'status' => 145,
                    'error' => true,
                    'data' => '',
                    'message' => 'Authentication Failed!'
                ];
            } else {
                if($val['role'] == '1' || $val['role'] == '5')
                {                 

                    if($this->validate->run($req, 'deleteAuth') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                        $update_data = [
                            'active' => '0',
                            'logged_in' => '0',
                            'updated_by' => $val['id']
                        ];
                        $update = $this->auth->editAble($req['id'], $update_data);
                        if(!$update)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            unset($update_data['logged_in']);
                            $update = $this->extension->editByLogin($req['id'], $update_data);
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
                                    'message' => 'User Updated'
                                ];
                            }
                        }
                    }
                } else { 
                     $res = [
                        'status' => 403,
                        'error' => true,
                        'data' => '',
                        'message' => 'Access Denied!'
                    ];
                }
            }
        }
        return $this->response->setJSON($res);    
    }
    
    public function listAuthGroup(){
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        

                        $users = $this->auth->getAll(array('auth_group' => 'group', 'roles' => ['2' , '3']))->getResultArray();
                        $lsLeader = [];
                        $lsTsr = [];
                        foreach($users as $user){
                            unset($user['password']);
                            unset($user['token']);
                            unset($user['foto']);
                            if($user['role'] == '2')
                            {
                                $lsLeader[] = $user;
                            } else {
                                $lsTsr[] = $user;
                            }
                        }
                        $ls = ['leader' => $lsLeader, 'tsr' => $lsTsr];
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' =>  $ls,
                            'message' => 'List TSR Total '.count($lsTsr).' and Leader Total '.count($lsLeader)
                        ];
                    } else {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updateAuthGroup()
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
                    'status' => 145,
                    'error' => true,
                    'data' => '',
                    'message' => 'Authentication Failed!'
                ];
            } else {
                if($val['role'] == '5' || $val['role'] == '1')
                {
                    if($this->validate->run($req, 'updateAuthGroup') === FALSE)
                    {
                        $res = [
                            'status' => 400,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed!'
                        ];
                    } else {
                            if($req['leader_id'] == '0' || $req['leader_id'] == 0)
                            {
                                $req['leader_id'] = NULL;
                            }
                            $update_data = [
                                'group' => $req['leader_id'],
                                'updated_by' => $val['id']
                            ];

                        $update = $this->auth->editAble($req['tsr_id'], $update_data);
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
                                'data' => $update_data,
                                'message' => 'Leader Group Updated'
                            ];
                            
                        }
                    }
                } else { 
                    $res = [
                        'status' => 403,
                        'error' => true,
                        'data' => '',
                        'message' => 'Access Denied!'
                    ];
                }
            }
        }
        return $this->response->setJSON($res);    
    }

    public function listAuth(){
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // if($val['role'] != '1')
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                     $users = $this->auth->getAll(array('active' => '1', 'serole' => ['2' , '3', '4']))->getResultArray();
                        $lsUsers = [];
                        foreach($users as $user){
                            unset($user['password']);
                            unset($user['token']);
                        }
                        $res = [
                            'status' => 200,
                            'error' => false,
                            'data' => $users,
                            'message' => 'List Users Total'.count($users)
                        ];   
                    } else {
                            $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function user(){
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
            if($this->validate->run($req, 'user') === FALSE)
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
                            'status' => 145,
                            'error' => true,
                            'data' => '',
                            'message' => 'Authentication Failed!'
                        ];
                    } else {
                        if($val['role'] == '1' || $val['role'] == '5')
                        {
                            $users = $this->auth->getAll(array('id' => $req['id']))->getRowArray();
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $users,
                                'message' => 'User '. $users['username']
                            ];
                        } else {
                                $res = [
                                'status' => 403,
                                'error' => true,
                                'data' => '',
                                'message' => 'Access Denied!'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    

    public function doLogin()
    {
        $req = $this->request->getPost();
        $req['ip'] = empty($req['ip']) ? $this->get_client_ip() : $req['ip'];
//         var_dump($req);die();
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if($this->validate->run($req, 'login') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed!'
                ];
            } else {
                // check username
                $param_cek = array('username' => $req['username']);
                $cek = $this->auth->getAll($param_cek);
                if(!$cek->getResult())
                {
                    $res = [
                        'status' => 404,
                        'error' => true,
                        'data' => '',
                        'message' => 'Check Your Username or Password!'
                    ];
                } else {
                    // check password
                    $req['password'] = do_hash($req['password']);
                    $cekPass = $this->auth->getAll($req);
                    
                    if(!$cekPass->getResult())
                    {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => '',
                            'message' => 'Check Your Password!'
                        ];
                    } else {
                        $curr_user = $cekPass->getRowArray();
                        // if($curr_user['role'] != '1'){

                        if($curr_user['logged_in'] == 1 && $curr_user['role'] != '1'){
                            $ingpoh = $this->auth_log->getAll(['list' => 2, 'id_login' => $curr_user['id']])->getRowArray();
                            $ip = empty($ingpoh['ipaddr']) ? '::1' : $ingpoh['ipaddr'];
                            $url = "https://geolocation-db.com/json/$ip";
                            $crl = curl_init($url);
                            curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                            $loc = json_decode(curl_exec($crl), true);
                            curl_close($crl);
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => "User Already Login! \nLast login: $ingpoh[created_at] at: $ip($loc[country_name], $loc[state], $loc[city])"
                            ];
                                               
                        }else{
                            $token = 'A'.$curr_user['id'].time();
                            // update status
                            $updateData = [
                                'token' => $token,
                                'last_login' => date('Y-m-d H:i:s'),
                                'logged_in' => '1',
                                'updated_by' => $curr_user['id']
                            ];

                            $curr_user_update = $this->auth->editAble($curr_user['id'], $updateData);

                            if(!$curr_user_update)
                            {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something went wrong!'
                                ];
                            } else {
                                $addLog = [
                                    'id_login'  => $curr_user['id'],
                                    'status'    => '1',
                                    'ipaddr'    => $req['ip']
                                ];
                                $insert_log = $this->auth_log->addNew($addLog);
                                if(!$insert_log)
                                {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $curr_user_updated = $this->auth->getAll(array('id' => $curr_user['id']))->getRowArray();
                                    // remove password colummn
                                    unset($curr_user_updated['password']);
                                    unset($curr_user_updated['active']);
                                    unset($curr_user_updated['logged_in']);
                                    if($curr_user_updated['role'] == '2' || $curr_user_updated['role'] == '3')
                                    {
                                        $extension = $this->extension->getAll(array('id_login' => $curr_user['id']))->getRowArray();
                                        if(!$extension){
                                            $res = [
                                                'status' => 404,
                                                'error' => true,
                                                'data' => '',
                                                'message' => 'Extension was not registered. Please Contact Administrator!'
                                            ];
                                            return $this->response->setJSON($res);

                                        } else {
                                            $curr_user_updated['extension'] = $extension;
                                        }
                                    }
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $curr_user_updated,
                                        'message' => 'Login Success!'
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function force_logout()
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
            if($this->validate->run($req, 'forceLogout') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed!'
                ];
            } else {
                if(!$val = tokenCheck($req))
                {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed! User Already Logged Out!'
                    ];
                } else {
                    if($val['role'] == '1' || $val['role'] == '5')
                    {
                        $hash = do_hash($req['password']);
                        $param_cek = array('username' => $val['username']);
                        $cek = $this->auth->getAll($param_cek);
                        if(!$cek)
                        {
                            $res = [
                                'status' => 403,
                                'error' => true,
                                'data' => '',
                                'message' => 'Access Denied!'
                            ];
                        }
                        // update status
                        $updateData = [
                            'logged_in' => '0',
                            'updated_by' => $val['id'],
                            'token' => ''
                        ];

                        $curr_user_update = $this->auth->editAble($req['id'], $updateData);
                        if(!$curr_user_update)
                        {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Something went wrong!'
                            ];
                        } else {
                            $addLog = [
                                'id_login'  => $req['id'],
                                'status'    => '0'
                            ];
                            $insert_log = $this->auth_log->addNew($addLog);
                            if(!$insert_log)
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
                                    'message' => 'Log Out Success!'
                                ];
                            }
                        }
                    } else {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function doLogout()
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
            if($this->validate->run($req, 'logout') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed!'
                ];
            } else {
                if(!$val = tokenCheck($req))
                {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed! User Already Logged Out!'
                    ];
                } else {
                    // update status
                    $updateData = [
                        'logged_in' => '0',
                        'updated_by' => $val['id'],
                        'token' => ''
                    ];

                    $curr_user_update = $this->auth->editAble($val['id'], $updateData);
                    if(!$curr_user_update)
                    {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => 'Something went wrong!'
                        ];
                    } else {
                        $addLog = [
                            'id_login'  => $val['id'],
                            'status'    => $req['status']
                        ];
                        $insert_log = $this->auth_log->addNew($addLog);
                        if(!$insert_log)
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
                                'message' => 'Log Out Success!'
                            ];
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }
    public function changePassword()
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
            if($this->validate->run($req, 'changePassword') === FALSE)
            {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => $this->validate->getErrors(),
                    'message' => 'Validation Failed!'
                ];
            } else {
                if(!$val = tokenCheck(array('token'=>$req['token'])))
                {
                        $res = [
                            'status' => 145,
                            'error' => true,
                            'data' => '',
                            'message' => 'Authentication Failed!'
                        ];
                } else {
                    // check password
                    $password = do_hash($req['curr_password']);
                    $cekPass = $this->auth->getAll(array('id' => $val['id'], 'password'=>$password));
                    if(!$cekPass->getResult())
                    {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => '',
                            'message' => 'Check Your Current Password!'
                        ];
                    } else {
                        $updateData = [
                            'password' => do_hash($req['new_password']),
                            'updated_by' => $val['id']
                        ];

                        $curr_user_update = $this->auth->editAble($val['id'], $updateData);

                        if(!$curr_user_update)
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
                                'message' => 'Change Password Success!'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function profile(){
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
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    $user = $this->auth->getAll(array('id' => $val['id']))->getRowArray();
                    unset($user['password']);
                    unset($user['active']);
                    unset($user['token']);
                    unset($user['last_login']);
                    unset($user['created_by']);
                    unset($user['created_at']);
                    unset($user['updated_at']);
                    unset($user['updated_by']);
                    unset($user['id_user']);

                    $res = [
                        'status' => 200,
                        'error' => false,
                        'data' => $user,
                        'message' => 'Profile '. $user['nama']
                    ];
                }
            }
        }
        return $this->response->setJSON($res);
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
