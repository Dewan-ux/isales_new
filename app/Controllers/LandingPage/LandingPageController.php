<?php namespace App\Controllers\LandingPage;
use App\Controllers\BaseController;
use ReCaptcha\ReCaptcha;

use App\Models\DataNasabahModel;
use App\Models\VisitorModel;

class LandingPageController extends BaseController {
    public function __construct()
    {
        helper(['form']);
        $this->recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY, new \ReCaptcha\RequestMethod\CurlPost());
    }

    public function index($page = ""){

        // $title['page_title'] = 'LandingPage | Warkop';

        // $res = $this->getFoto(['foto'=>'foto_brosur']);
        // if(!$res['error']){
        //     $data['foto_brosur'] = $res['data'];
        //     return view('landingpage/home', $data, $title);
        // }else{
        //     $messageError = array($res['message']);
        //     session()->setFlashdata('inputs', $this->request->getPost());
        //     session()->setFlashdata('errors', $messageError);
        //     return redirect()->to(base_url('landingpage'));
        // }
        $data['page'] = $page;

        if($page == 'cangkirkopi')
        {
            $data['title'] = 'Landing Page | Cangkir Kopi';
            $data['foto_brosur'] = 'banner.jpg';
        } else {
            $data['title'] = 'Landing Page | Belanja Online';
            $data['foto_brosur'] = 'belanja.jpg';
        }
        return view('landingpage/home', $data);

    }

    public function create($page){
        $data['page'] = $page;
        if($page == 'cangkirkopi')
        {
            $data['tag_line'] = 'Tagline-kopi.png';
            $data['voucher'] = 'voucer-tag.png';
            $data['foto'] = 'Banner-Kopi.png';
            $data['dir'] = 'CangkirKopi';
            $data['title'] = 'Landing Page | Cangkir Kopi';
            $data['vocher'] = 'Voucher-Gopay-2.png';
            $pertanyaan = [
                '*Berapa banyak Anda meminum Kopi dalam sehari?',
                '*Berapa budget yang anda habiskan sehari untuk Kopi?',
                '*Apakah anda tahu anggaran kopi Anda dalam sehari dapat memberikan
                perlindungan maksimal "Sampai dengan 500 juta rupiah" bagi keluarga Anda tercinta?'
            ];
            $data['pertanyaan'] = $pertanyaan;
            $option = [
                ['1-3 Cangkir','3-5 Cangkir','Lebih dari 5 Cangkir'],
                ['15.000-30.000','30.000-50.000','>50.000']
            ];
            $data['option'] = $option;
            $data['bordercol'] = "gray";

        } else {
            $data['tag_line'] = 'Tagline-Online-Shop.png';
            $data['dir'] = 'BelanjaOnline';
            $data['foto'] = 'Banner-Online-Shop.png';
            $data['voucher'] = 'voucer-tag.png';
            $data['title'] = 'Landing Page | Belanja Online';
            $data['vocher'] = 'Voucher-Gopay-Online-Shop.png';
            $pertanyaan = [
                '*Berapa sering Anda Belanja Online dalam sehari?',
                '*Berapa banyak anggaran yang Anda sisihkan untuk Belanja Online?',
                'Apakah anda tahu anggaran Belanja Online Anda dalam sehari dapat
                memberikan perlindungan maksimal "Sampai dengan 500 juta rupiah" bagi keluarga
                Anda tercinta?'
            ];
            $data['pertanyaan'] = $pertanyaan;
            $option = [
                ['1-3 Kali','3-5 kali','Lebih dari 5 kali'],
                ['10.000-50.000','50.000-200.000','200.000 <']
            ];
            $data['option'] = $option;
            $data['bordercol'] = "orange";
        }
        return view('landingpage/create', $data);

    }

    public function getFoto($req){
        $url = BASE_API.'landingpage/foto';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $res;
    }

    public function add($page){
        $url = BASE_API.'landingpage/submit';
        $validation =  \Config\Services::validation();

        $data = array(
            'nama'          => $this->request->getPost('nama'),
            'telepon'       => $this->request->getPost('telepon'),
            'id_campaign'          => '1',
            'segment'          => $page,
            'jumlah_kopi'          => $this->request->getPost('jumlah_kopi'),
            'budget_kopi'          => $this->request->getPost('budget_kopi'),
            'asuransi'          => $this->request->getPost('asuransi'),
            'ip'            => $this->request->getIPAddress(),
        );
        if($validation->run($data, 'landingPage') == FALSE){
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            return redirect()->to(base_url('landingpage/'.$page.'/create'));
        }else{
            $captcha = $this->recaptcha();
            if($captcha['error'] == FALSE)
            {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = json_decode(curl_exec($ch), true);
                curl_close($ch);
                if($res['status'] == '200'){
                    return redirect()->to(base_url('landingpage/'.$page.'/success'));
                }else{
                    print_r($res);
                    die();
                    $messageError = array($res['message']);
                    session()->setFlashdata('inputs', $this->request->getPost());
                    session()->setFlashdata('errors', $messageError);
                    return redirect()->to(base_url('landingpage/'.$page.'/create'));
                }
            } else {
                $messageError = array($captcha['message']);
                session()->setFlashdata('inputs', $this->request->getPost());
                session()->setFlashdata('errors', $messageError);
                return redirect()->to(base_url('landingpage/'.$page.'/create'));
            }

        }
    }

    public function success()
    {
        return view('landingpage/success');
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
}