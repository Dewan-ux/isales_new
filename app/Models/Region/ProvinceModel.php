<?php
namespace App\Models\Region;

use CodeIgniter\Model;
class ProvinceModel extends Model
{
    protected $table = 'province';
	protected $primaryKey = 'prov_id';

	protected $allowedFields = ['prov_name'];

	public function getAll($param = array())
	{
		if (isset($param['prov_id'])) { $this->where('prov_id', $param['prov_id']); }
		if (isset($param['prov_name'])) { $this->where('prov_name', $param['prov_name']); }
		
		$this->select('*');
		$this->orderBy('prov_name', 'ASC');
		$query = $this->get();
		return $query;
	}
}
