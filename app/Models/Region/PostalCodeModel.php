<?php
namespace App\Models\Region;

use CodeIgniter\Model;

class PostalCodeModel extends Model
{
    protected $table = 'postal_code';
	protected $primaryKey = 'postal_id';

	protected $allowedFields = ['subdis_name', 'dis_id'];

	public function getAll($param = array())
	{
		if (isset($param['postal_id'])) { $this->where('postal_id', $param['postal_id']); }
		if (isset($param['subdis_id'])) { $this->where('subdis_id', $param['subdis_id']); }
		if (isset($param['dis_id'])) { $this->where('dis_id', $param['dis_id']); }
		if (isset($param['city_id'])) { $this->where('city_id', $param['city_id']); }
		if (isset($param['prov_id'])) { $this->where('prov_id', $param['prov_id']); }
		if (isset($param['postal_code'])) { $this->where('postal_code', $param['postal_code']); }
		
		$this->select('*');
		$this->orderBy('postal_code', 'ASC');
		$query = $this->get();
		return $query;
	}
}
