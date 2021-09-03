<?php
namespace App\Models;

use CodeIgniter\Model;

class LogInterfrensiModel extends Model
{
    protected $table = 't_log_interfrensi';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_log_call', 'status', 'created_at' , 'created_by', 'updated_at', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_interfrensi.id', $param['id']); }
        if (isset($param['id_log_call'])) { $this->where('t_log_interfrensi.id_log_call', $param['id_log_call']); }
		if (isset($param['status'])) { $this->where('t_log_interfrensi.status', $param['status']); }
		if (isset($param['created_by'])) { $this->where('t_log_interfrensi.created_by', $param['created_by']); }
        if (isset($param['created_at'])) { $this->where('t_log_interfrensi.created_at', $param['created_at']); }
		if (isset($param['updated_at'])) { $this->where('t_log_interfrensi.updated_at', $param['updated_at']); }
		if (isset($param['updated_by'])) { $this->orWhere('t_log_interfrensi.updated_by', $param['updated_by']); }
		
			
		$this->select('t_log_interfrensi.*');
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
