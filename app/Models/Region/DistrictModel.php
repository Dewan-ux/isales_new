<?php
namespace App\Models;

use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'district';
	protected $primaryKey = 'dis_id';

	protected $allowedFields = ['dis_name', 'city_id'];

	public function getAll($param = array())
	{
		if (isset($param['dis_id'])) { $this->where('dis_id', $param['dis_id']); }
		if (isset($param['dis_name'])) { $this->where('dis_name', $param['dis_name']); }
		if (isset($param['city_id'])) { $this->where('city_id', $param['city_id']); }
		
		$this->select('*');
		$this->orderBy('dis_name', 'ASC');
		$query = $this->get();
		return $query;
	}
}
