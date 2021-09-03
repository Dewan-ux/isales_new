<?php namespace App\Controllers\LeaderGroup;
use App\Controllers\BaseController;
use App\Config\Constant;

class LeaderGroupController extends BaseController
{
    public function __construct()
    {
        $this->leader = [];
        $this->tsr = [];
        helper(['form']);
    }

    private function getDataUser(){
        $url = BASE_API.'auth/group';
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
            $lsLeader = [];
            $lsLeader[0] = 'Pilih Leader';
            foreach($res['data']['leader'] as $leader){
                $lsLeader[$leader['id']] = $leader['nama'];
            }
            $this->leader = $lsLeader;
            $lsTsr = [];
            // foreach($res['data']['tsr'] as $tsr){
            //     $lsTsr[$tsr['id']] = $tsr['nama'];
            // }
            $no = 0;
            foreach($res['data']['tsr'] as $tsr)
            {
                if($tsr['role'] == '3')
                {
                    $no++;
                    $group = !isset($tsr['group']) ? "" : $tsr['group'];
                    $arr = [];
                    $leader_id = session()->get('leader_id') != NULL ? session()->get('leader_id') : "0";
                    $checked = $leader_id == $group ? "checked" : "";
                    // print_r('leader_id = '.$this->getLeaderId().' group = '.$group);
                    // die();
                    $arr[] = '<input type="checkbox" class="singlechkbox" '.$checked.' name="id'.$no.'" value="'.$tsr['id'].'"/>';
                    $arr[] = $no;
                    $arr[] = $tsr['nama'];
                    $arr[] = isset($tsr['leader_nama']) ? $tsr['leader_nama'] : "Belum ada Leader";
                    $lsTsr[] = $arr;
                }

            }
            $this->tsr = $lsTsr;
        }
    }

    public function update()
    {
        $url = BASE_API.'auth/group/update';
        $leader_id = $this->request->getPost('leader_id');
        $data = [
            'tsr_id' => $this->request->getPost('tsr_id'),
            'leader_id' => $leader_id,
            'token' => session()->get('token')
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $this->response->setJSON($res);

    }

    public function getTsr()
    {
        $this->getDataUser();
        $draw = $this->request->getPost('draw');
        $row = $this->request->getPost('start');
        $count = count($this->tsr);
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => intval($count),
            "recordsFiltered" => intval($count),
            "data" => $this->tsr
        );
        return $this->response->setJSON($response);
    }

    public function getLeaderId(){
        session()->set('leader_id', $this->request->getPost('leader_id'));
        return $this->request->getPost('leader_id');
    }

    public function index()
    {
        if(sessionCheck() == true)
        {
            $validation =  \Config\Services::validation();
            $this->getDataUser();
            $data['leader'] = $this->leader;
            $data['tsr'] = $this->tsr;
            session()->remove('leader_id');
            return view('admin/leadergroup/index',$data);
        } else {
            session()->setFlashdata('errors', array('You Must Login First'));
            return redirect()->to(base_url('admin/login'));
        }
    }
}
