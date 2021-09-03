<?php
namespace App\Models;

use CodeIgniter\Model;

class CallbackStatusModel extends Model
{
    protected $table = 't_callback_status';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id', 'extension', 'destination', 'method', 'progress_time', 'created_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_callback_status.id', $param['id']); }
        if (isset($param['extension'])) { $this->where('t_callback_status.extension', $param['extension']); }
		if (isset($param['destination'])) { $this->where('t_callback_destination.destination', $param['destination']); }
		if (isset($param['method'])) { $this->where('t_callback_status.method', $param['method']); }
		if (isset($param['progress_time'])) { $this->where('t_callback_status.progress_time', $param['progress_time']); }
        if (isset($param['created_at'])) { $this->where('t_callback_status.created_at', $param['created_at']); }		
			
		$this->select('t_callback_status.*');
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
