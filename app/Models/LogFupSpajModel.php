<?php
namespace App\Models;

use CodeIgniter\Model;

class LogFupSpajModel extends Model
{
    protected $table = 't_log_fup_spaj';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_spaj', 'status', 'remark','created_at', 'created_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_fup_spaj.id', $param['id']); }
        if (isset($param['id_spaj'])) { $this->where('t_log_fup_spaj.id_spaj', $param['id_spaj']); }
		if (isset($param['status'])) { $this->where('t_log_fup_spaj.status', $param['status']); }
		if (isset($param['remark'])) { $this->where('t_log_fup_spaj.remark', $param['remark']); }
		if (isset($param['created_at'])) { $this->where('t_log_fup_spaj.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_log_fup_spaj.created_by', $param['created_by']); }
		
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
