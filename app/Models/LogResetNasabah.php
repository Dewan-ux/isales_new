<?php
namespace App\Models;

use CodeIgniter\Model;

class LogResetNasabah extends Model
{
    protected $table = 't_log_reset';
	protected $primaryKey = 'id';

	protected $allowedFields = ['reset_by', 'total_reset', 'reset_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_call.id', $param['id']); }
        if (isset($param['reset_by'])) { $this->where('t_log_call.reset_by', $param['reset_by']); }
		if (isset($param['total_reset'])) { $this->where('t_log_call.total_reset', $param['total_reset']); }
		if (isset($param['reset_at'])) { $this->where('t_log_call.reset_at', $param['reset_at']); }
		
        $this->select('*');        
		$query = $this->get();
		return $query;
	}

	public function addNew($data)
	{
		$data['query'] = $this->insert($data);
		$data['id'] = $this->insertID();

		return $data;
	}

	public function editAble($id, $data)
	{
	    return $this->update($id, $data);
	}
}
