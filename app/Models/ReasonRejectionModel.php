<?php
namespace App\Models;

use CodeIgniter\Model;

class ReasonRejectionModel extends Model
{
    protected $table = 't_reason_rejection';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_log_call', 'id_data_nasabah', 'reason', 'created_by', 'created_at'];
	

	public function getAll($param = array())
	{
		if (isset($param['id_log_call'])) { $this->where('id_log_call', $param['id_log_call']); }
        if (isset($param['id_data_nasabah'])) { $this->where('id_data_nasabah', $param['id_data_nasabah']); }
		if (isset($param['reason'])) { $this->whereIn('reason', $param['reason']); }
		if (isset($param['created_by'])) { $this->whereIn('created_by', $param['created_by']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
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
