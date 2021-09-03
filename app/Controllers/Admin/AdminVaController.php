<?php namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Config\Constant;

class AdminVaController extends BaseController
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
            $url = BASE_API.'virtual_account';
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
                $data['virtual_account'] = $res['data'];
                return view('admin/virtual_account/index.php', $data);
            }
        } else {
            
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function readXls($batch)
    {
        if(isset($batch) || empty($batch)){
            
        }
    }

    public function uploadVa()
    {
        if(sessionCheck() == true) 
        {
            $validation =  \Config\Services::validation();
 
            $va_file = $this->request->getFile('va_file');
            $file_data = [
                'va_file' => $va_file
            ];
            if($validation->run($file_data, 'vaFileUpload') == FALSE)
            {
                session()->setFlashdata('errors', $validation->getErrors());
                return redirect()->to(base_url('admin/uploadva'));
            } else {
                $url = BASE_API.'virtual_account/uploadva';

                $extension = $va_file->getClientExtension();
                if($extension == 'xls')
                {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $batch_va = [];
                $spreadsheet = $reader->load($va_file);
                $import_data = $spreadsheet->getActiveSheet()->toArray();
 
                foreach ($import_data as $idx => $val){
                    if($idx == 0){
                        continue;
                    }

                    $batch_va[] = [
                        'batch_number' => $val[0],
                        'jenis_spaj' => $val[1],
                        'type_spaj' => $val[2],
                        'no_spaj' => $val[3],
                        'virtual_account' => $val[4],
                        'generated_at' => date('Y-m-d', strtotime(str_replace('/', '-', $val[5]))),
                    ];
                }

                
                $data = array(
                    'batch_va'     => json_encode($batch_va),
                    'token' => session()->get('token')
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
                    return view('admin/virtual_account');
                } else { 
                    return redirect()->to(base_url('admin/virtual_account'));
                }
            }
            
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}
