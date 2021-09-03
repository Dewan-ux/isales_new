<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AdminPerformanceController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }

	public function index()
    {
        
        if(sessionCheck() == true)
        {
            // $validation =  \Config\Services::validation();

            # CARI CAMPAIGN
            $url = BASE_API.'campaign';
            $send = [
                'token' => session()->get('token')
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // dd($res); die();
            if($res['error'])                    
            {
                session()->setFlashdata('errors', $res['data']);
                return view('admin/performance/index', $res);
            }else{
                $campaign = $res['data']['campaign']; 
                $ls = [];
                $ls[''] = 'Pilih Semua Campaign';
                foreach($campaign as $c) {
                    $ls[$c['id']] = $c['campaign'];
                }
                $data['campaign'] = $ls;
            }
            # Cari Produk Here
            $url = BASE_API.'produk/list'; //Ganti api ke produk
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // dd($res); die();
            if($res['error'])                    
            {
                session()->setFlashdata('errors', $res['data']);
                return view('admin/performance/index', $res);
            }else{
                $produk = $res['data']; 
                $ls = [];
                $ls[''] = 'Pilih Semua Produk';
                foreach($produk as $c) {
                    $ls[$c['id']] = $c['nama_produk'];
                }
                $data['produk'] = $ls;
            }

            # Performa
            $url = BASE_API.'performance';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);       
            if($res['error'])
            {
                session()->setFlashdata('errors', $res['data']);
                return view('admin/performance/index', $res);
            } else { 
                $data['dashboard']  = $res['data'];
                return view('admin/performance/index', $data);
            }

        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

}
