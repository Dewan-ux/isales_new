<?php
namespace App\Models;

use CodeIgniter\Model;

class SubDistrictModel extends Model
{
    protected $table = 'subdistrict';
	protected $primaryKey = 'subdis_id';

	protected $allowedFields = ['subdis_name', 'dis_id'];

	public function getAll($param = array())
	{
		if (isset($param['subdis_id'])) { $this->where('subdis_id', $param['subdis_id']); }
		if (isset($param['subdis_name'])) { $this->where('subdis_name', $param['subdis_name']); }
		if (isset($param['dis_id'])) { $this->where('dis_id', $param['dis_id']); }
		
		$this->select('*');
		$this->orderBy('subdis_name', 'ASC');
		$query = $this->get();
		return $query;
	}
}
