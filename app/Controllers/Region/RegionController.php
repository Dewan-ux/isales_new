<?php namespace App\Controllers\Region;
use App\Controllers\BaseController;

use App\Models\Region\ProvinceModel;
use App\Models\Region\CityModel;
use App\Models\Region\DistrictModel;
use App\Models\Region\PostalCodeModel;

class RegionController extends BaseController
{
    public function __construct()
    {
        $region_db = db_connect("region");
        $this->province = new ProvinceModel($region_db);
        $this->cities = new CityModel($region_db);
        $this->postal_code = new PostalCodeModel($region_db);
    }

    public function provinces()
    {
        $province = $this->province->getAll()->getResultArray();
        if(!$province)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Tidak ada data provinisi tersedia'
            ];
        } else {
            $data = [];
            foreach($province as $prov)
            {
                $data[] = [
                    'province_id' => $prov['prov_id'],
                    'province_name' => $prov['prov_name']
                ];
            }
            
            $res = [
                'status' => 200,
                'error' => false,
                'data' => $data,
                'message' => 'Data Provinsi Indonesia'
            ];
        }
       return $this->response->setJSON($res);
    }

    public function cities()
    {
        $req = $this->request->getPost();
        
        $cities = $this->cities->getAll($req)->getResultArray();
        if(!$cities)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Tidak ada data kota tersedia'
            ];
        } else {
            $data = [];
            foreach($cities as $city)
            {
                $data[] = [
                    'city_id' => $city['city_id'],
                    'city_name' => $city['city_name']
                ];
            }
            
            $res = [
                'status' => 200,
                'error' => false,
                'data' => $data,
                'message' => 'Data Kota Indonesia'
            ];
        }
       return $this->response->setJSON($res);
    }

    public function postal_code()
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
        
        $postal_code = $this->postal_code->getAll($req)->getResultArray();
        if(!$postal_code)
        {
            $res = [
                'status' => 404,
                'error' => true,
                'data' => '',
                'message' => 'Tidak ada data kota tersedia'
            ];
        } else {
            $data = [];
            foreach($postal_code as $ps)
            {
                $data[] = [
                    'postal_id' => $ps['postal_id'],
                    'postal_code' => $ps['postal_code']
                ];
            }
            
            $res = [
                'status' => 200,
                'error' => false,
                'data' => $data,
                'message' => 'Data Kode Pos Indonesia'
            ];
        }
       return $this->response->setJSON($res);
    }
}