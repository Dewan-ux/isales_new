<?php

namespace App\Controllers\Telesales;

use App\Controllers\BaseController;
//LEADBYID → COMMENTED,UNCOMMENT IF READY TO USE
use App\Models\LoginModel;
use App\Models\DataNasabahModel;
use App\Models\LogCallModel;
use App\Models\LogConfirmInterestModel;
use App\Models\KesehatanNasabahModel;
use App\Models\SpajModel;
use App\Models\ProdukModel;
use App\Models\PremiModel;
use App\Models\PertanyaanModel;
use App\Models\PaymentModel;
use App\Models\SalesModel;
use App\Models\LogInterfrensiModel;
use App\Models\ReasonRejectionModel;
use App\Models\VirtualAccountModel;
use App\Models\UserModel;
use Pusher\Pusher;

class TelesalesController extends BaseController
{
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->d_nasabah = new DataNasabahModel();
        $this->log_call = new LogCallModel();
        $this->log_ci = new LogConfirmInterestModel();
        $this->spaj = new SpajModel();
        $this->pertanyaan = new PertanyaanModel();
        $this->d_sales = new SalesModel();
        $this->produk = new ProdukModel();
        $this->payment = new PaymentModel();
        $this->premi = new PremiModel();
        $this->kesehatan = new KesehatanNasabahModel();
        $this->log_interfrensi = new LogInterfrensiModel();
        $this->rejection = new ReasonRejectionModel();
        $this->va = new VirtualAccountModel();
        $this->user = new UserModel();
        $this->push = new Pusher('1a5783b0a2e4c736875e', 'd67079df2d2a95b1f25d', '1190341', ['cluster' => 'ap1']);
    }

    public function createOrder()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($this->validate->run($req, 'createSpaj') === FALSE) {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed'
                        ];
                    } else {
                        $pertanyaan = $req['pertanyaan'];
                        $req['jns_asuransi'] = 0;
                        $req['created_by'] = $val['id'];
                        $maxNoPorposal = $this->spaj->getAll(['max' => 'no_proposal'])->getRowArray();
                        $req['no_proposal'] = codeAlphaNumeric('ARW143', $maxNoPorposal['no_proposal'], 6, 10, 7);
                        $randVa = $this->va->getAll(['unused' => 1])->getRowArray();
                        $req['id_virtual_account'] = $randVa['id'];
                        // $req['no_proposal'] = 'ARWICS' . $randVa['no_spaj'];

                        unset($req['pertanyaan']);
                        $create = $this->spaj->addNew($req);
                        if (!$create) {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->spaj->errorMessage(),
                                'message' => 'Something when wrong!'
                            ];
                        } else {
                            $lsAsk = [];
                            $spaj_id = $create['id'];
                            // print_r($spaj_id); die();
                            $update_data_nas_spaj = [
                                'id_spaj' => $spaj_id,
                            ];
                            $update_nasabah_spaj = $this->d_nasabah->editAble((int)$req['id_data_nasabah'], $update_data_nas_spaj);
                            $create_jawaban = false;
                            foreach ($pertanyaan as $ask) {
                                $lsAsk['id_spaj'] = $spaj_id;
                                $lsAsk['id_pertanyaan'] = $ask['id_pertanyaan'];
                                $lsAsk['jawaban'] = $ask['jawaban'];
                                $lsAsk['remark'] = isset($ask['remark']) ? $ask['remark'] : "";
                                $lsAsk['created_by'] = $val['id'];
                                $create_jawaban = $this->kesehatan->addNew($lsAsk);
                            }

                            if (!$create_jawaban) {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => $this->kesehatan->errorMessage(),
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                // $update = $this->va->
                                $update_data_va = [
                                    'used_by' => $spaj_id,
                                    'updated_by' => $val['id']
                                ];
                                $update_va = $this->va->editAble($randVa['id'], $update_data_va);
                                if (!$update_va) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => $this->va->errorMessage(),
                                        'message' => 'Something when wrong!'
                                    ];
                                } else {
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => ['success' => '1'],
                                        'message' => 'Simpan Data Order nasabah ' . $req['nama'] . ' Berhasil'
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

    public function leads()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        // $status = ['0','1','2','4','5','6','8','11'];
                        $status = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'];
                        $leads = $this->d_nasabah->getAll(array('list' => '1', 'assigned_to' => $val['id'], 'status' => $status))->getResultArray();
                        // $leads = $this->d_nasabah->getAll(array('list'=>'1', 'assigned_to' => $val['id']))->getResultArray();
                        $ls = [];
                        foreach ($leads as $lead) {
                            unset($lead['sent_to']);
                            $ls[] = $lead;
                        }
                        if (count($leads) <= 0) {
                            $res = [
                                'status' => 201,
                                'error' => false,
                                'data' => '',
                                'message' => 'Daftar Leads untuk TSR ' . $val['nama'] . ' kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $ls,
                                'message' => 'Daftar Leads untuk TSR ' . $val['nama'] . ' ' . count($leads)
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function leadById()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'leads') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $lead = $this->d_nasabah->getAll(array('id' => $req['nasabah_id']))->getRowArray();

                            $call_log_duration = $this->log_call->getAll(array('duration' => 3,  'id_nasabah' => $req['nasabah_id']))->getRowArray();

                            $duration = empty($call_log_duration) ? 0 : $call_log_duration['duration'];
                            // if(empty($call_log_duration)){
                            //     $duration = 0;
                            // } else {
                            //     $duration = $call_log_duration['duration'];
                            // }
                            // $jam = floor($duration / 3600);
                            // $jam = floor($duration / 3600);
                            $menit = ($duration / 60) % 60;
                            $detik = $duration % 60;
                            $lead['duration'] = $menit . ' menit ' . $detik . ' detik';

                            // Untuk LOG Call → Disable jika belum ready
                            // $lgcall = $this->log_call->getAll(array('lastlog' => 5, 'id_nasabah' => $req['nasabah_id']))->getResultArray();
                            // $a=0;
                            // if(!empty($lgcall)){
                            //     foreach ($lgcall as $key) {
                            //         $log[$a]['assigned_to'] = $key['tsr'];
                            //         $log[$a]['assigned_by'] = $this->user->getAll(array('id_login' => 
                            //             $this->d_nasabah->getAll(array('id' => $req['nasabah_id']))->getRowArray()['assigned_by']))
                            //             ->getRowArray()['nama'];
                            //         $log[$a]['last_status'] = $key['status'];
                            //         $log[$a]['last_call'] = $key['call_start_at'];
                            //         $duration = $this->log_call->getAll(array('id' => $key['id'], 'duration' => 3))->getRowArray()['duration'];
                            //         $menit = ($duration / 60) % 60;
                            //         $detik = $duration % 60;
                            //         $log[$a]['duration'] = $menit.' menit '.$detik.' detik';
                            //         $a++;
                            //     }
                            //     $lead['last_assign'] = $log;
                            // }else{
                            //     $lead['last_assign'] = [];
                            // }

                            unset($lead['updated_at']);
                            unset($lead['updated_by']);
                            unset($lead['assigned_at']);
                            unset($lead['assigned_by']);
                            unset($lead['created_at']);
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $lead,
                                'message' => 'Detail Lead'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function historyCall()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'leads') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $lgcall = $this->log_call->getAll(array('lastlog' => TRUE, 'id_nasabah' => $req['nasabah_id']))->getResultArray();
                            $a = 0;
                            if (!empty($lgcall)) {
                                foreach ($lgcall as $key) {
                                    $log[$a]['assigned_to'] = $key['tsr'];
                                    $log[$a]['assigned_by'] = $this->user->getAll(array('id_login' =>
                                    $this->d_nasabah->getAll(array('id' => $req['nasabah_id']))->getRowArray()['assigned_by']))
                                        ->getRowArray()['nama'];
                                    $log[$a]['last_status'] = $key['status'];
                                    $log[$a]['last_call'] = $key['call_start_at'];
                                    $duration = $this->log_call->getAll(array('id' => $key['id'], 'duration' => 3))->getRowArray()['duration'];
                                    $menit = ($duration / 60) % 60;
                                    $detik = $duration % 60;
                                    $log[$a]['duration'] = $menit . ' menit ' . $detik . ' detik';
                                    $a++;
                                }
                                $lead['last_assign'] = $log;
                            } else {
                                $lead['last_assign'] = [];
                            }
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $lead,
                                'message' => 'Detail History Call'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function reqInterfrensi()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'reqInterfrensi') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            if (!$log_cek = tokenCallCheck($req)) {
                                $res = [
                                    'status' => 400,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Token Call Invalid'
                                ];
                            } else {
                                $addLog = [
                                    'created_by'       => $val['id'],
                                    'id_log_call'    => $log_cek['id'],
                                    'status'       => '1',
                                ];
                                $insert_log = $this->log_interfrensi->addnew($addLog);
                                if (!$insert_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $updateLogCall = [
                                        'interfrensi' => '1'
                                    ];
                                    $edit_log = $this->log_call->editAble($log_cek['id'], $updateLogCall);
                                    if (!$edit_log) {
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
                                            'message' => 'Request Interfrensi Berhasil!'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function startCall()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'calling') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $clause = array('id' => $req['nasabah_id'], 'assigned_to' => $val['id'], 'telepon' => $req['telepon'], 'telepon2' => $req['telepon']);
                            $nasabah = $this->d_nasabah->getAll($clause)->getResultArray();
                            if (count($nasabah) <= 0) {
                                $res = [
                                    'status' => 403,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Access Denied! This Number does not belong to him'
                                ];
                            } else {
                                $token_call = uniqid($val['id'], false);
                                $addLog = [
                                    'token_call'    => $token_call,
                                    'call_by'       => $val['id'],
                                    'id_nasabah'    => $req['nasabah_id'],
                                    'call_to'       => $req['telepon'],
                                ];
                                $insert_log = $this->log_call->addnew($addLog);
                                if (!$insert_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $id = $insert_log['id'];
                                    $tsr = $this->auth->getAll(array('id' => $val['id']))->getRowArray();
                                    $log_call = $this->log_call->getAll(array('token_call' => $token_call))->getRowArray();
                                    $nasabah = $this->d_nasabah->getAll($clause)->getRowArray();
                                    $nasabah['log_call'] = $log_call;
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $nasabah,
                                        'message' => 'Call (' . $req['telepon'] . ') ' . $nasabah['nama'] . ' by ' . $tsr['nama'] . ', Success!'
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

    public function startConfirmInterestCall()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'startConfirmInterest') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $clause = array('id' => $req['nasabah_id'], 'assigned_to' => $val['id'], 'telepon' => $req['telepon'], 'telepon2' => $req['telepon']);
                            $nasabah = $this->d_nasabah->getAll($clause)->getResultArray();
                            if (count($nasabah) <= 0) {
                                $res = [
                                    'status' => 403,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Access Denied! This Number does not belong to him'
                                ];
                            } else {
                                $token_ci = uniqid($req['id_spaj'], false);
                                $addLog = [
                                    'id_spaj'           => $req['id_spaj'],
                                    'token_ci'          => $token_ci,
                                    'call_by'           => $val['id'],
                                    'id_data_nasabah'   => $req['nasabah_id'],
                                    'call_to'           => $req['telepon'],
                                ];
                                $insert_log = $this->log_ci->addNew($addLog);
                                if (!$insert_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $id = $insert_log['id'];
                                    $log_ci = $this->log_ci->getAll(array('token_ci' => $token_ci))->getRowArray();
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $log_ci,
                                        'message' => 'Call (' . $req['telepon'] . ') ' . $log_ci['nasabah'] . ' by ' . $log_ci['tsr'] . ', Success!'
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

    public function calling()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Validation Failed'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if (!$log_cek = tokenCallCheck($req)) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => 'Token is not Available, Call Already Ended'
                            ];
                        } else {
                            if ($this->validate->run($req, 'startCall') === FALSE) {
                                $res = [
                                    'status' => 400,
                                    'error' => true,
                                    'data' => $this->validate->getErrors(),
                                    'message' => 'Validation Failed!'
                                ];
                            } else {
                                $editLog = [
                                    'init_call_at'   => date('Y-m-d H:i:s'),
                                    'status'        => $req['status']
                                ];
                                $edit_log = $this->log_call->editAble($log_cek['id'], $editLog);
                                if (!$edit_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {

                                    $updateStatusNasabah = array('status' => $req['status']);
                                    $edit_nasabah = $this->d_nasabah->editAble($log_cek['id_nasabah'], $updateStatusNasabah);
                                    if (!$edit_nasabah) {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something went wrong!'
                                        ];
                                    } else {
                                        $tsr = $this->auth->getAll(array('id' => $log_cek['call_by']))->getRowArray();
                                        $nasabah = $this->d_nasabah->getAll(array('id' => $log_cek['id_nasabah'], 'telepon' => $log_cek['call_to'], 'telepon2' => $log_cek['call_to']))->getRowArray();
                                        $data_call = [
                                            'id_nasabah' => $nasabah['id'],
                                            'nama' => $nasabah['nama'],
                                            'called_by' => $log_cek['call_by'],
                                            'start_call' => $log_cek['call_start_at'],
                                            'end_call' => $editLog['call_end_at'],
                                            'status' => $nasabah['status']
                                        ];

                                        if (isset($req['reason']) && $req['status'] == '9') {
                                            $data_call['reason'] = $req['reason'];
                                        }

                                        $res = [
                                            'status' => 200,
                                            'error' => false,
                                            'data' => $data_call,
                                            'message' => 'End Call (' . $log_cek['call_by'] . ') to ' . $log_cek['call_to'] . ' by ' . $tsr['nama'] . ', Success!'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function endCall()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Validation Failed'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if (!$log_cek = tokenCallCheck($req)) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => 'Token is not Available, Call Already Ended'
                            ];
                        } else {
                            if ($this->validate->run($req, 'endCall') === FALSE) {
                                $res = [
                                    'status' => 400,
                                    'error' => true,
                                    'data' => $this->validate->getErrors(),
                                    'message' => 'Validation Failed!'
                                ];
                            } else {
                                if ($req['status'] == '9') {

                                    if (!isset($req['reason'])) {
                                        $res = [
                                            'status' => 400,
                                            'error' => true,
                                            'data' => ['The reason field must be defined'],
                                            'message' => 'Validation Failed!'
                                        ];
                                        return $this->response->setJSON($res);
                                    }
                                    $insert_data = [
                                        'id_log_call' => $log_cek['id'],
                                        'id_data_nasabah' => $log_cek['id_nasabah'],
                                        'reason' => $req['reason'],
                                        'created_by' => $val['id']
                                    ];
                                    $insert_rejection = $this->rejection->addNew($insert_data);
                                    if (!$insert_rejection) {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something went wrong in insert rejection!'
                                        ];
                                        return $this->response->setJSON($res);
                                    }
                                }
                                $editLog = [
                                    'call_end_at'   => date('Y-m-d H:i:s'),
                                    'token_call'    => '',
                                    'status'        => $req['status'],
                                    'interfrensi'   => '0'
                                ];
                                $edit_log = $this->log_call->editAble($log_cek['id'], $editLog);
                                if (!$edit_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {

                                    $updateStatusNasabah = array('status' => $req['status']);
                                    $edit_nasabah = $this->d_nasabah->editAble($log_cek['id_nasabah'], $updateStatusNasabah);
                                    if (!$edit_nasabah) {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something went wrong!'
                                        ];
                                    } else {
                                        $tsr = $this->auth->getAll(array('id' => $log_cek['call_by']))->getRowArray();
                                        $nasabah = $this->d_nasabah->getAll(array('id' => $log_cek['id_nasabah'], 'telepon' => $log_cek['call_to'], 'telepon2' => $log_cek['call_to']))->getRowArray();
                                        $data_call = [
                                            'id_nasabah' => $nasabah['id'],
                                            'nama' => $nasabah['nama'],
                                            'called_by' => $log_cek['call_by'],
                                            'start_call' => $log_cek['call_start_at'],
                                            'end_call' => $editLog['call_end_at'],
                                            'status' => $nasabah['status']
                                        ];

                                        if (isset($req['reason']) && $req['status'] == '9') {
                                            $data_call['reason'] = $req['reason'];
                                        }

                                        $check_interfrensi = $this->log_interfrensi->getAll(array('id_log_call' => $log_cek['id']))->getRowArray();
                                        if (!empty($check_interfrensi) || $check_interfrensi != "") {
                                            $update_interfrensi = $this->log_interfrensi->editAble($check_interfrensi['id'], array('status' => '1'));
                                            if (!$update_interfrensi) {
                                                $res = [
                                                    'status' => 500,
                                                    'error' => true,
                                                    'data' => $data_call,
                                                    'message' => 'Something went wrong!'
                                                ];
                                                return $this->response->setJSON($res);
                                            }
                                        }
                                        $datapush = ['extension' => $pabx['extension'], 'destination' => $log_cek['call_to'], 'method' => "CALLEND" , 'sip_code' => 200, 'progress_time' => time()];
                                        $this->push->trigger('sipcode', $pabx['extension'], $datapush);
                                        //disini letakkan sinkronisasi ke pabx ttg list recording+berikan respon ke mobile list recording no tsb
                                        $date = strtotime("+1 day");

                                        $this->conser->getRecordingPabx(['extension' => $pabx['extension'], 'startdate' => date('Y-m-d',$date)]);
                                        $res = [
                                            'status' => 200,
                                            'error' => false,
                                            'data' => $data_call,
                                            'message' => 'End Call (' . $log_cek['call_by'] . ') to ' . $log_cek['call_to'] . ' by ' . $tsr['nama'] . ', Success!'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function endConfirmInterestCall()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Validation Failed'
                ];

                return $this->response->setJSON($res);
            } else {

                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if (!$log_cek = tokenCallCi($req)) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => '',
                                'message' => 'Token is not Available, Call Already Ended'
                            ];
                        } else {
                            if ($this->validate->run($req, 'endConfirmInterest') === FALSE) {
                                $res = [
                                    'status' => 400,
                                    'error' => true,
                                    'data' => $this->validate->getErrors(),
                                    'message' => 'Validation Failed!'
                                ];
                            } else {
                                if ($val['id'] !== $log_cek['call_by']) {
                                    $res = [
                                        'status' => 401,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'This SPAJ not below to you!'
                                    ];

                                    return $this->response->setJSON($res);
                                }
                                $editLog = [
                                    'call_end_at'   => date('Y-m-d H:i:s'),
                                    'token_ci'    => NULL,
                                    'remark' => $req['remark']
                                ];
                                $edit_log = $this->log_ci->editAble($log_cek['id'], $editLog);
                                if (!$edit_log) {
                                    $res = [
                                        'status' => 500,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Something went wrong!'
                                    ];
                                } else {
                                    $update_checked = $this->spaj->editAble($log_cek['id_spaj'], ['checked' => '12']);
                                    if (!$update_checked) {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something went wrong!'
                                        ];
                                    } else {
                                        $tsr = $this->auth->getAll(array('id' => $log_cek['call_by']))->getRowArray();
                                        $nasabah = $this->d_nasabah->getAll(array('id' => $log_cek['id_data_nasabah'], 'telepon' => $log_cek['call_to'], 'telepon2' => $log_cek['call_to']))->getRowArray();
                                        $data_call = [
                                            'id_spaj' => $log_cek['id_spaj'],
                                            'id_nasabah' => $nasabah['id'],
                                            'nama' => $nasabah['nama'],
                                            'called_by' => $log_cek['call_by'],
                                            'start_call' => $log_cek['call_start_at'],
                                            'end_call' => $editLog['call_end_at'],
                                            'remark' => $req['remark']
                                        ];

                                        $res = [
                                            'status' => 200,
                                            'error' => false,
                                            'data' => $data_call,
                                            'message' => 'End Call (' . $log_cek['call_by'] . ') to ' . $log_cek['call_to'] . ' by ' . $tsr['nama'] . ', Success!'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function listOrder()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {

                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $checked = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                        $listOrder = $this->spaj->getAll(array('role' => $val['role'], 'id_login' => $val['id'], 'checked' => $checked))->getResultArray();

                        if (count($listOrder) == 0) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Order Kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $listOrder,
                                'message' => 'Order untuk ' . $val['nama'] . ' ' . count($listOrder)
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function detailOrder()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {

                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $order = $this->spaj->getAll(array('id' => $req['id'], 'detail' => 3))->getRowArray();
                        if (empty($order)) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Order Tidak Ada'
                            ];
                        } else {
                            if ($req['jns_asuransi'] == 0) {
                                $kesehatan_nasabah = $this->kesehatan->getAll(array('id_spaj' => $order['id'], 'detail' => '1'))->getResultArray();
                                if (count($kesehatan_nasabah) == 0) {
                                    $res = [
                                        'status' => 404,
                                        'error' => true,
                                        'data' => '',
                                        'message' => 'Data Kesehatan Kosong'
                                    ];
                                } else {
                                    $order['kesehatan_nasabah'] = $kesehatan_nasabah;
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => $order,
                                        'message' => 'Order untuk TSR' . $val['nama'] . ' Nasabah ' . $order['nama']
                                    ];
                                }
                            } else {
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $order,
                                    'message' => 'Order untuk TSR' . $val['nama'] . ' Nasabah ' . $order['nama']
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function updateOrder()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($this->validate->run($req, 'createSpaj') === FALSE) {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed'
                        ];
                    } else {
                        if ($val['role'] != '3') {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Access Denied'
                            ];
                        } else {
                            $pertanyaan = $req['pertanyaan'];
                            $id = $req['id'];

                            unset($req['id']);
                            unset($req['pertanyaan']);
                            $req['checked'] = '3';
                            $req['updated_by'] = $val['id'];
                            $edit = $this->spaj->editAble($id, $req);
                            if (!$edit) {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                $lsAsk = [];
                                $update_jawaban = false;
                                foreach ($pertanyaan as $ask) {
                                    $lsAsk['id_pertanyaan'] = $ask['id_pertanyaan'];
                                    $lsAsk['jawaban'] = $ask['jawaban'];
                                    $lsAsk['remark'] = $ask['remark'];
                                    $lsAsk['updated_by'] = $val['id'];
                                    $update_jawaban = $this->kesehatan->editAble($ask['id'], $lsAsk);
                                    if (!$update_jawaban) {
                                        $res = [
                                            'status' => 500,
                                            'error' => true,
                                            'data' => '',
                                            'message' => 'Something when wrong!'
                                        ];
                                        break;
                                    }
                                }
                                if ($update_jawaban) {
                                    $res = [
                                        'status' => 200,
                                        'error' => false,
                                        'data' => '',
                                        'message' => 'Update Data Order nasabah ' . $req['nama'] . ' Berhasil'
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

    public function performance()
    {
        $req = $this->request->getPost();

        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => $req
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    $req['tsr_id'] = $val['id'];
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        if ($this->validate->run($req, 'performance') === FALSE) {
                            $res = [
                                'status' => 400,
                                'error' => true,
                                'data' => $this->validate->getErrors(),
                                'message' => 'Validation Failed!'
                            ];
                        } else {
                            $performance = $this->auth->getAll(array(
                                'dashboard' => 'performance',
                                'filter' => isset($req['filter']) ? filterByDateSub($req['filter']) : filterByDateSub(1),
                                'tsr_id' => $req['tsr_id']
                            ))->getRowArray();
                            if (count($performance) == 0) {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Performance Kosong (404)'
                                ];
                            } else {
                                if ($performance['jumlah_case'] > 0 && $performance['jumlah_leads'] > 0) {
                                    $count = ($performance['jumlah_case'] / $performance['jumlah_leads']) * 100;
                                } else {
                                    $count = 0;
                                }
                                $performance['rate'] = round($count, 2);
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $performance,
                                    'message' => 'Performance ' . $performance['nama']
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $this->response->setJSON($res);
    }

    public function scriptSales()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 145,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 403,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied!'
                        ];
                    } else {
                        $sales = $this->d_sales->getAll(array('newest' => '1'))->getRowArray();
                        if (!$sales) {
                            $res = [
                                'status' => 404,
                                'error' => false,
                                'data' => '',
                                'message' => 'Pdf Sales Kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $sales,
                                'message' => 'Pdf Sales Terbaru'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }


    public function productList()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // if($val['role'] == 4 || $val['role'] == 1)
                    if ($val['role'] == 1) {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        if (isset($req['tgl_lahir'])) {
                            $usia = date("Y") - date("Y", strtotime($req['tgl_lahir']));
                            if ($usia >= 18 && $usia <= 25) {
                                $kategori = 1;
                            } else if ($usia >= 26 && $usia <= 30) {
                                $kategori = 2;
                            } else if ($usia >= 31 && $usia <= 35) {
                                $kategori = 3;
                            } else if ($usia >= 36 && $usia <= 40) {
                                $kategori = 4;
                            } else if ($usia >= 41 && $usia <= 45) {
                                $kategori = 5;
                            } else if ($usia >= 46 && $usia <= 50) {
                                $kategori = 6;
                            } else if ($usia >= 50 && $usia <= 55) {
                                $kategori = 7;
                            } else {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Umur tidak sesuai dengan Produk yang tersedia'
                                ];

                                return $this->response->setJSON($res);
                            }
                        } else {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Umur tidak ada'
                            ];

                            return $this->response->setJSON($res);
                        }
                        $premi = $this->premi->getAll(['list' => 1, 'kategori' => $kategori])->getResultArray();
                        if (!$premi) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Premi Tidak Ada Karena Umur Tidak Sesuai'
                            ];
                        } else {

                            $payment = $this->payment->getAll()->getResultArray();
                            foreach ($payment as $item) {
                                unset($item['created_by']);
                                unset($item['created_at']);
                            }

                            if (!$payment) {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Payment Kosong'
                                ];
                            } else {
                                $ls = array('produk' => $premi, 'payment' => $payment);
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $ls,
                                    'message' => 'Daftar Produk, Premi dan Payment'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function premiByProduk()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied'
                        ];
                    } else {
                        if ($this->validate->run($req, 'premis') === FALSE) {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Validation Invalid'
                            ];
                        } else {

                            $premi = $this->premi->getAll(array('id' => $req['id_premi']))->getRowArray();

                            if (!$premi) {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Pertanyaan Kosong'
                                ];
                            } else {
                                $ls = [
                                    'id_premi' => $premi['id'],
                                    'nominal' => $premi['nominal'],
                                    'satuan' => $premi['satuan']
                                ];

                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $ls,
                                    'message' => 'Daftar Pertanyaan Kesehatan'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    public function pertanyaanList()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // if($val['role'] == 4 || $val['role'] == 1)
                    if ($val['role'] == 1) {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $pertanyaan = $this->pertanyaan->getAll()->getResultArray();
                        foreach ($pertanyaan as $item) {
                            unset($item['created_at']);
                            unset($item['updated_at']);
                            unset($item['created_by']);
                            unset($item['updated_by']);
                        }

                        if (!$pertanyaan) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Pertanyaan Kosong'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $pertanyaan,
                                'message' => 'Daftar Pertanyaan Kesehatan'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    //Create Order CAR Personal Acciedent
    //Create by Iqbal Ms Arwics
    //27 Mei 2021
    public function createOrderPersonalAccient()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // var_dump(intval($req['jns_asuransi'])); die();
                    if ($this->validate->run($req, 'createSpaj_Pa') === FALSE) {
                        $res = [
                            'status' => 404,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Validation Failed'
                        ];
                    } else {
                        $req['created_by'] = $val['id'];
                        $req['jns_asuransi'] = 1;
                        $maxNoPorposal = $this->spaj->getAll(['max' => 'no_proposal'])->getRowArray();
                        $req['no_proposal'] = codeAlphaNumeric('ARW143', $maxNoPorposal['no_proposal'], 6, 10, 7);
                        $randVa = $this->va->getAll(['unused' => 1])->getRowArray();
                        $req['id_virtual_account'] = $randVa['id'];
                        // $req['no_proposal'] = 'ARWICS' . $randVa['no_spaj'];

                        $create = $this->spaj->addNew($req);
                        if (!$create) {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => $this->spaj->errorMessage(),
                                'message' => 'Something when wrong!'
                            ];
                        } else {
                            $spaj_id = $create['id'];
                            // print_r($spaj_id); die();
                            $update_data_nas_spaj = [
                                'id_spaj' => $spaj_id,
                            ];
                            $update_nasabah_spaj = $this->d_nasabah->editAble((int)$req['id_data_nasabah'], $update_data_nas_spaj);

                            // $update = $this->va->
                            $update_data_va = [
                                'used_by' => $spaj_id,
                                'updated_by' => $val['id']
                            ];
                            $update_va = $this->va->editAble($randVa['id'], $update_data_va);
                            if (!$update_va) {
                                $res = [
                                    'status' => 500,
                                    'error' => true,
                                    'data' => $this->va->errorMessage(),
                                    'message' => 'Something when wrong!'
                                ];
                            } else {
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => ['success' => '1'],
                                    'message' => 'Simpan Data Order nasabah ' . $req['nama'] . ' Berhasil'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }

    //Create Order CAR Personal Acciedent
    //Create by Iqbal Ms Arwics
    //8 June 2021
    public function productListPa()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // if($val['role'] == 4 || $val['role'] == 1)
                    if ($val['role'] == 1) {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        // ,'satuan' => $req['satuan']
                        $data = [];
                        $premi = $this->premiPa->getAll(['list' => 1])->getResultArray();
                        foreach ($premi as $row) {
                            $data[] = [
                                'id_produk' => $row['id_produk'],
                                'nama_produk' => $row['nama_produk'],
                                'id' => $row['id'],
                                'nominal' => $row['nominal'],
                                'satuan' => $row['satuan'],
                                'up' => 0
                            ];
                        }
                        if (!$premi) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data Premi Tidak Ada Karena Umur Tidak Sesuai'
                            ];
                        } else {

                            $payment = $this->payment->getAll()->getResultArray();
                            foreach ($payment as $item) {
                                unset($item['created_by']);
                                unset($item['created_at']);
                            }

                            if (!$payment) {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Payment Kosong'
                                ];
                            } else {
                                $ls = array('produk' => $data, 'payment' => $payment);
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $ls,
                                    'message' => 'Daftar Produk, Premi dan Payment'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    //Create Order CAR Personal Acciedent
    //Create by Iqbal Ms Arwics
    //8 June 2021
    public function premiByProdukPa()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    if ($val['role'] != '3') {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => '',
                            'message' => 'Access Denied'
                        ];
                    } else {
                        if ($this->validate->run($req, 'premis') === FALSE) {
                            $res = [
                                'status' => 500,
                                'error' => true,
                                'data' => '',
                                'message' => 'Validation Invalid'
                            ];
                        } else {

                            $premi = $this->premiPa->getAll(array('id' => $req['id_premi']))->getRowArray();

                            if (!$premi) {
                                $res = [
                                    'status' => 404,
                                    'error' => true,
                                    'data' => '',
                                    'message' => 'Data Premi Kosong'
                                ];
                            } else {
                                $ls = [
                                    'id_premi' => $premi['id'],
                                    'nominal' => $premi['nominal'],
                                    'satuan' => $premi['satuan']
                                ];

                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => $ls,
                                    'message' => 'Daftar Premi'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
    public function manfaatListPa()
    {
        $req = $this->request->getPost();
        if (!$req) {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            if ($this->validate->run($req, 'authenticate') === FALSE) {
                $res = [
                    'status' => 400,
                    'error' => true,
                    'data' => '',
                    'message' => 'Token Invalid!'
                ];

                return $this->response->setJSON($res);
            } else {
                if (!$val = tokenCheck($req)) {
                    $res = [
                        'status' => 400,
                        'error' => true,
                        'data' => '',
                        'message' => 'Authentication Failed!'
                    ];
                } else {
                    // if($val['role'] == 4 || $val['role'] == 1)
                    if ($val['role'] == 1) {
                        $res = [
                            'status' => 500,
                            'error' => true,
                            'data' => $this->validate->getErrors(),
                            'message' => 'Access Denied'
                        ];
                    } else {
                        $manfaat = $this->manfaat->getAll($req)->getResultArray();
                        foreach ($manfaat as $row) {
                            $data[] = [
                                'id' => $row['id'],
                                'manfaat' => $row['manfaat'],
                                'up' => $row['up']
                            ];
                        }
                        if (!$manfaat) {
                            $res = [
                                'status' => 404,
                                'error' => true,
                                'data' => '',
                                'message' => 'Data manfaat Tidak Ada Karena Id Produk Personal Accdent Tidak Ditemukan'
                            ];
                        } else {
                            $res = [
                                'status' => 200,
                                'error' => false,
                                'data' => $data,
                                'message' => 'Daftar Manfaat Peronal Accident'
                            ];
                        }
                    }
                }
            }
        }
        return $this->response->setJSON($res);
    }
}
