<?php
namespace App\Models\Region;

use CodeIgniter\Model;
class CityModel extends Model
{
    protected $table = 'city';
	protected $primaryKey = 'city_id';

	protected $allowedFields = ['city_name', 'prov_id'];

	public function getAll($param = array())
	{
		if (isset($param['city_id'])) { $this->where('city_id', $param['city_id']); }
		if (isset($param['city_name'])) { $this->where('city_name', $param['city_name']); }
		if (isset($param['prov_id'])) { $this->where('prov_id', $param['prov_id']); }
		
		$this->select('*');
		$this->orderBy('city_name', 'ASC');
		$query = $this->get();
		return $query;
	}
}
