<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Frame;

class AdminSpajController extends BaseController
{


    public function index()
    {
        if(sessionCheck() == true) 
        {
            return view('admin/spaj/tampil');
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

// DataTables Server Side
    public function getData()
    {

        $validation =  \Config\Services::validation();
        $url = BASE_API.'spaj/list';
        // $search = $this->request->getPost('search')['value'];      
        $data = array(
            'token' => session()->get('token'),
            // 'search' =>  $search,
        );

        // print_r($data);
        // die();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        // var_dump($res); die();
        if($res['error'])
        {
            session()->setFlashdata('errors', array($res['message']));
            return redirect()->to(base_url('admin/login'));
        } else { 

            $datas = [];
            foreach($res['data']  as $key => $data)
            {

                $ls = [];
                $ls[] = $key + 1;
                $ls[] = $data['no_proposal'];
                $ls[] = 
                $data['jns_asuransi'] = ($data['jns_asuransi']=="0") ? 'Life Protection 20' : 'Perlindungan Kecelakaan';
                    // '0' => 'Life Protection 20' &
                    // '1' => ;
                $ls[] = $data['nama'];
                $ls[] = $this->sensor($data['telp1']);
                $ls[] = $data['nama_produk'];
                $ls[] = $data['nominal'];
                $ls[] = $data['tsr_nama'];
                $ls[] = $data['tanggal'];

                $ls[] = '<a href="spaj/preview/'.$data['id'].'"
                 <i class="fas fa-eye fa-lg"></i></a>&nbsp;<a href="spaj/print/'.$data['id'].'"
                 <i class="fas fa-print fa-lg"></i></a>';
                
            $datas[] = $ls;
            }
            $json_data = array(
                "draw"            => intval( $this->request->getPost('draw') ),  
                "recordsTotal"    => intval( 13 ), 
                "recordsFiltered" => intval( 13 ),
                "data"            => $datas );

            return $this->response->setJSON($json_data);
        }
    }
//End

    public function preview($id)
    {
        if(sessionCheck() == true)
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'spaj/id';
            $data = array(
                'token' => session()->get('token'),
                'id'    => $id
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
        //   print_r($res); die();
            if($res['error'])
            {
                session()->setFlashdata('warning', $res);
                return redirect()->to(base_url('admin/spaj'));
            } else {
                $data['spaj'] = $res['data'];
                $data['gender'] = [
                'L' => 'Laki-Laki',
                'P' => 'Perempuan'];
                $data['jns_asuransi'] = [
                    '0' => 'Life Protection 20',
                    '1' => 'Perlindungan Kecelakaan'];
                $data['wali_hubungan'] = [
                '1' => 'Suami/Istri',
                '2' => 'Anak Kandung',  
                '3' => 'Orang Tua Kandung'];
                $data['wali_status'] = [
                '0' => 'Belum Menikah',
                '1' => 'Sudah Menikah',
                '2' => 'Janda/Duda'];

                //sensor data
                $data['spaj']['NIK'] = $this->sensor($res['data']['NIK']);
                $data['spaj']['NPWP'] = $this->sensor($res['data']['NPWP']);
                $data['spaj']['card_number'] = $this->sensor($res['data']['card_number']);
                $data['spaj']['wali_NIK'] = $this->sensor($res['data']['wali_NIK']);
                $data['spaj']['wali_NIK2'] = $this->sensor($res['data']['wali_NIK2']);
                $data['spaj']['wali_NIK3'] = $this->sensor($res['data']['wali_NIK3']);
                $data['spaj']['telp1'] = $this->sensor($res['data']['telp1']);
                $data['spaj']['telp2'] = $this->sensor($res['data']['telp2']);
                $data['spaj']['wali_telp1'] = $this->sensor($res['data']['wali_telp1']);
                $data['spaj']['wali_telp2'] = $this->sensor($res['data']['wali_telp2']);
                $data['spaj']['wali2_telp1'] = $this->sensor($res['data']['wali2_telp1']);
                $data['spaj']['wali2_telp2'] = $this->sensor($res['data']['wali2_telp2']);
                $data['spaj']['wali3_telp1'] = $this->sensor($res['data']['wali3_telp1']);
                $data['spaj']['wali3_telp2'] = $this->sensor($res['data']['wali3_telp2']);
                //end
                return view('admin/spaj/preview', $data);
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

 
  public function printPdf($id)
   {
        if(sessionCheck() == true)
        {
            $validation =  \Config\Services::validation();
            $url = BASE_API.'spaj/id';
            $data = array(
                'token' => session()->get('token'),
                'id'    => $id
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = json_decode(curl_exec($ch), true);
            curl_close($ch);
            // var_dump($res); die();
            if($res['error'])
            {
                session()->setFlashdata('warning', $res);
                return redirect()->to(base_url('admin/spaj'));
            } else {

                $data['spaj'] = $res['data'];
                $data['jns_asuransi'] = [
                    '0' => 'Life Protection 20',
                    '1' => 'Perlindungan Kecelakaan'];
                $data['wali_status'] = [
                '0' => 'Belum Menikah',
                '1' => 'Sudah Menikah',
                '2' => 'Janda/Duda'];
                $data['gender'] = [
                'L' => 'Laki-Laki',
                'P' => 'Perempuan'];
                $data['wali_hubungan'] = [
                '1' => 'Suami/Istri',
                '2' => 'Anak Kandung',  
                '3' => 'Orang Tua Kandung'];
                //fungsi sensor data
                $data['spaj']['NIK'] = $this->sensor($res['data']['NIK']);
                $data['spaj']['NPWP'] = $this->sensor($res['data']['NPWP']);
                $data['spaj']['card_number'] = $this->sensor($res['data']['card_number']);
                $data['spaj']['wali_NIK'] = $this->sensor($res['data']['wali_NIK']);
                $data['spaj']['wali_NIK2'] = $this->sensor($res['data']['wali_NIK2']);
                $data['spaj']['wali_NIK3'] = $this->sensor($res['data']['wali_NIK3']);
                $data['spaj']['telp1'] = $this->sensor($res['data']['telp1']);
                $data['spaj']['telp2'] = $this->sensor($res['data']['telp2']);
                $data['spaj']['wali_telp1'] = $this->sensor($res['data']['wali_telp1']);
                $data['spaj']['wali_telp2'] = $this->sensor($res['data']['wali_telp2']);
                $data['spaj']['wali2_telp1'] = $this->sensor($res['data']['wali2_telp1']);
                $data['spaj']['wali2_telp2'] = $this->sensor($res['data']['wali2_telp2']);
                $data['spaj']['wali3_telp1'] = $this->sensor($res['data']['wali3_telp1']);
                $data['spaj']['wali3_telp2'] = $this->sensor($res['data']['wali3_telp2']);
                //end
                $fileName = str_replace(" ", "_", strtolower($res['data']['nama']));
                $options = new Options();
                $options->set('isRemoteEnabled',true);
                $options->set(['defaultFont' => 'sans-serif']);
                $dompdf = new Dompdf($options); 
                $dompdf->loadHtml(view('admin/spaj/print',
                    [
                    "spaj" => $data['spaj'],
                    "gender" => $data['gender'],
                    "wali_status" => $data['wali_status'],
                    "wali_hubungan" => $data['wali_hubungan'],
                    "jns_asuransi" => $data['jns_asuransi']
                ]));
                $dompdf->setPaper('A3', 'potrait');
                $dompdf->render();
                $dompdf->stream('SPAJ_'.$fileName.'_'.date('Ymd').'.pdf'); 
               
                return redirect()->to(base_url('admin/spaj/tampil'));
            }
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    private function sensor($data='')
    {
        if ($data == '') {
            return "-";
        } else {
            $sensor = substr($data,0,3);
            $censored = 'X';
            for ($i=0; $i < strlen($data)-4; $i++) { 
                $censored .= "X";
            }
            return $sensor.$censored;
        }
    }

    private function status($data='')
    {
      
    }

    //--------------------------------------------------------------------
}
