<?php namespace App\Controllers\Callback;
use App\Controllers\BaseController;

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
use App\Models\CallbackStatusModel;
use Pusher\Pusher;

class CallbackControllers extends BaseController
{  
    public function __construct()
    {
        $this->auth = new LoginModel();
        $this->d_nasabah = new DataNasabahModel();
        $this->log_call = new CallbackStatusModel();
        $this->call_back_status = new LogCallModel();
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
    }

    // {"extension":"081289614441","destination":"9999999999","method":"BYE","sip_code":200,"progress_time":1617014285}
    // SIP Code liat di: https://en.wikipedia.org/wiki/List_of_SIP_response_codes
    //buat database tstate_call ya? (id, extension, destination, method, progress_time) terus depend ke database call_log
    //terus ini forward balik ke PABX dan forward ke Frontend/Mobile melalui Pusher
    public function index(){
        $get = $this->request->getJSON();
        // $req = $this->request->getRawInput(); //Kalo gak bisa diambil pake RAW input
        // $req1 = $this->request->header(); //Kalo ada yang dikirim lewat head-mu
        // print_r($res);
        // $req = json_decode($req, true);
        $req = (array) $get;
        // print_r($req);
        if(!$req)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Endpoint Not Found'
            ];
        } else {
            // if($this->validate->run($req, 'pabxPusher') === FALSE) //?? Apa yang mau divalidasi? ke config/validation.php
            // {
            //     $res = [
            //         'status' => 400,
            //         'error' => true,
            //         'data' => '',
            //         'message' => 'Token Invalid!'
            //     ];

            //     return $this->response->setJSON($res);
            // } else {
                //Dibawah ini untuk ngecek user login pake token khusus internal isales bukan PABX
                // if(!$val = tokenCheck($req))
                // {
                //     $res = [
                //         'status' => 145,
                //         'error' => true,
                //         'data' => '',
                //         'message' => 'Authentication Failed!'
                //     ];
                // } else {
                //     if($val['role'] != '3')
                //     {
                //         $res = [
                //             'status' => 403,
                //             'error' => true,
                //             'data' => '',
                //             'message' => 'Access Denied!'
                //         ];
                //     } else {
                        // if($this->validate->run($req, 'pabxPusher') === FALSE)
                        // {
                        //     $res = [
                        //         'status' => 400,
                        //         'error' => true,
                        //         'data' => $this->validate->getErrors(),
                        //         'message' => 'Validation Failed!'
                        //     ];

                        // } else {                         
                            // $clause = array('id' => $req['nasabah_id'], 'assigned_to'=>$val['id'], 'telepon' => $req['telepon']);
                            // $nasabah = $this->d_nasabah->getAll($clause)->getResultArray();
                            // if(count($nasabah) <= 0){
                            //     $res = [
                            //         'status' => 404,
                            //         'error' => true,
                            //         'data' => '',
                            //         'message' => 'ERROR! This number is no registered!'
                            //     ];
                            // } else {

                                $pusher = new Pusher(
                                    '1a5783b0a2e4c736875e', //ganti dengan App_key pusher Anda
                                    'd67079df2d2a95b1f25d', //ganti dengan App_secret pusher Anda
                                    '1190341', //ganti dengan App_key pusher Anda
                                    array('cluster' => 'ap1') //Cluster Array
                                );

                                //pattern nomor telepon
                                preg_match('/\b\w/', $req['extension'], $reget);

                                // print_r($reget);
                                // die();

                                //if rexex match = 0 maka itu berasal dari user
                                if ( $reget[0] == 0 ) {
                                    $extension = $req['destination'];
                                    //BYE BY USER
                                    $code = ($req['method'] == "BYE") ? 8001 : $req['sip_code'] ;
                                }else{
                                    $extension = $req['extension'];
                                    //BYE BY TSR
                                    $code = ($req['method'] == "BYE") ? 8000 : $req['sip_code'] ;
                                }
                                $data['status'] = $code;

                                $msg = "Received!";
                                // $data['method'] = $get;
                                // Status Code: 100, 180, 181, 200, 202, 487, 800×
                                if(($code == 100)||($code == 180)||($code == 181)||($code == 200)||($code == 202)||($code == 487)||($code == 8000)||($code == 8001)){
                                    $pusher->trigger('sipcode', $extension, $data);
                                    $msg = "Sended!";
                                }
                                $res = [
                                    'status' => 200,
                                    'error' => false,
                                    'data' => ['extension' =>  $req['extension'], 'parameter' => $req['method'], 'code' => $data['status'],
                                               'time_req' => date("Y-m-d H:i:s", $req['progress_time']), 'time_rec' => date("Y-m-d H:i:s")],
                                    'message' => $msg
                                ];

                                // $pusher = json_decode($req['nasabah_id'], false); //? buat apa
                                // $addCallStatus = [
                                //     'destination'    => $val['destination'],
                                //     'extension'      => $req['extension'],
                                //     'method'         => $req['method'],
                                // ];

                                // $insert_callStatus = $this->call_back_status->addNew($addCallStatus);
                                // if(!$insert_callStatus){
                                //     $res = [
                                //         'status' => 500,
                                //         'error' => true,
                                //         'data' => '',
                                //         'message' => 'Something went wrong!'
                                //     ];
                                // } else {
                                //     $id = $insert_callStatus['id'];
                                //     $log_ci = $this->call_back_status->getAll(array('token_ci' => $token_ci))->getRowArray();
                                //     $res = [
                                //         'status' => 200,
                                //         'error' => false,
                                //         'data' => $log_ci,
                                //         'message' => 'Call ('.$req['telepon'].') '.$log_ci['nasabah'].' by '.$log_ci['tsr'].', Success!'
                                //     ];
                                // }
                            // }
                        // }
                    // }
                // }
            // }
        }
        return $this->response->setJSON($res);
    }
}