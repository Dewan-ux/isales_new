<?php
namespace App\Models;

use CodeIgniter\Model;

class LogConfirmInterestModel extends Model
{
    protected $table = 't_log_confirm_interest';
	protected $primaryKey = 'id';

	protected $allowedFields = ['call_by', 'id_data_nasabah', 'id_spaj', 'token_ci', 'call_to' , 'call_start_at', 'call_end_at', 'remark'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_confirm_interest.id', $param['id']); }
        if (isset($param['id_spaj'])) { $this->where('t_log_confirm_interest.id_spaj', $param['id_spaj']); }
        if (isset($param['id_nasabah'])) { $this->where('t_log_confirm_interest.id_data_nasabah', $param['id_data_nasabah']); }
		if (isset($param['call_to'])) { $this->where('t_log_confirm_interest.call_to', $param['call_to']); }
        if (isset($param['call_by'])) { $this->where('t_log_confirm_interest.call_by', $param['call_by']); }
		if (isset($param['token_ci'])) { $this->where('t_log_confirm_interest.token_ci', $param['token_ci']); }
		if (isset($param['call_start_at'])) { $this->orWhere('t_log_confirm_interest.call_start_at', $param['call_start_at']); }
		if (isset($param['call_end_at'])) { $this->where('t_log_confirm_interest.call_end_at', $param['call_end_at']); }
		
		if(isset($param['duration'])){
			if($param['duration'] == 3){
				$this->select('t_log_confirm_interest.*, SUM(TIMESTAMPDIFF(SECOND, t_log_confirm_interest.call_start_at, t_log_call.call_end_at)) as duration');
				$query = $this->get();
				return $query;
			}
		} else if(isset($param['help']))
		{
			if($param['help'] == 1)
			{
				$this->select('t_log_confirm_interest.token_call');
				$this->orderBy('t_log_confirm_interest.call_start_at','DESC');
				$this->limit(1);
				$query = $this->get();
				return $query;
			}
		} else {
			$this->select('t_user.nama as tsr, t_data_nasabah.nama as nasabah, t_log_confirm_interest.*');
			$this->join('t_data_nasabah', 't_log_confirm_interest.id_data_nasabah = t_data_nasabah.id');
			$this->join('t_login', 't_log_confirm_interest.call_by = t_login.id');
			$this->join('t_user', 't_user.id_login = t_login.id');
			$query = $this->get();
			return $query;
		}
	}

	public function addNew($data)
	{
		$data['query'] = $this->insert($data);
		$data['id'] = $this->insertID;

		return $data;
	}

	public function editAble($id, $data)
	{
	    return $this->update($id, $data);
	}

	public function updateShare($data, $limit)
	{
		$this->set($data);
		$this->where('assigned_to', NULL);
		$this->orderBy('id', 'ASC');
		$this->limit($limit);
		return $this->update();
	}
}
