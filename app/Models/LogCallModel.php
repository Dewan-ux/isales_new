<?php
namespace App\Models;

use CodeIgniter\Model;

class LogCallModel extends Model
{
    protected $table = 't_log_call';
	protected $primaryKey = 'id';

	protected $allowedFields = ['call_by', 'id_nasabah', 'status', 'interfrensi', 'interfrensi_by', 'token_call', 'call_to' , 'call_start_at', 'call_end_at', 'cariuser', 'cariutang', 'lastlog'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_call.id', $param['id']); }
        if (isset($param['id_nasabah'])) { $this->where('t_log_call.id_nasabah', $param['id_nasabah']); }
		if (isset($param['status'])) { $this->where('t_log_call.status', $param['status']); }
		if (isset($param['call_to'])) { $this->where('t_log_call.call_to', $param['call_to']); }
        if (isset($param['call_by'])) { $this->where('t_log_call.call_by', $param['call_by']); }
		if (isset($param['token_call'])) { $this->where('t_log_call.token_call', $param['token_call']); }
		if (isset($param['call_start_at'])) { $this->orWhere('t_log_call.call_start_at', $param['call_start_at']); }
		if (isset($param['call_end_at'])) { $this->where('t_log_call.call_end_at', $param['call_end_at']); }

		if(isset($param['cariuser'])){
			$db = \Config\Database::Connect();
			$builder = $db->table('t_extension_pabx');
			$q = $builder->select('t_user.nama,t_login.username')->join('t_login', 't_login.id = t_extension_pabx.id_login')->join('t_user', 't_user.id_login = t_login.id')->like('t_extension_pabx.extension', $param['cariutang'], 'before')->limit(1);

			$query = $q->get();
			$db->close();
			return $query->getRowArray();

		}

		if(isset($param['cardetpang'])){
			$tang[0] = date('Y-m-d H:i:s', strtotime($param['cariutang'])-10);
			$tang[1] = date('Y-m-d H:i:s', strtotime($param['cariutang'])+10);
			$this->where('t_log_call.call_start_at >=', $tang[0]);
			$this->where('t_log_call.call_start_at <=', $tang[1]);
			$this->join('t_login', 't_log_call.call_by = t_login.id');
			$this->join('t_user', 't_user.id_login = t_login.id');
			$query = $this->get();
			return $query->getRowArray();
		}

		if (isset($param['lastlog'])) {
			$this->orderBy('call_start_at', 'DESC');
			if($param['lastlog'] != TRUE )$this->limit($param['lastlog']);

			$this->select('t_user.nama as tsr, t_data_nasabah.nama as nasabah, t_log_call.*, t_login.username');
			$this->join('t_data_nasabah', 't_log_call.id_nasabah = t_data_nasabah.id');
			$this->join('t_login', 't_log_call.call_by = t_login.id');
			$this->join('t_user', 't_user.id_login = t_login.id');
			// $this->query('SUM(TIMESTAMPDIFF(SECOND, t_log_call.call_start_at, t_log_call.call_end_at)) as duration');
			
			$query = $this->get();

			return $query;
		}
		
		if(isset($param['duration'])){
			if($param['duration'] == 3){
				$this->select('t_log_call.*, SUM(TIMESTAMPDIFF(SECOND, t_log_call.call_start_at, t_log_call.call_end_at)) as duration');
				$query = $this->get();
				return $query;
			}
		} else if(isset($param['help']))
		{
			if($param['help'] == 1)
			{
				$this->select('t_log_call.token_call');
				$this->orderBy('t_log_call.call_start_at','DESC');
				$this->limit(1);
				$query = $this->get();
				return $query;
			}
			if($param['help'] == 2)
			{
				$this->orderBy('t_log_call.call_start_at','DESC');
				$this->limit(1);
				$query = $this->get();
				return $query;
			}
		} else if(isset($param['interfrensi'])){
			if($param['interfrensi'] == '1')
			{
				$this->select('t_log_call.*');
				$this->where('t_log_call.interfrensi',$param['interfrensi']);
				$this->orderBy('t_log_call.call_start_at','DESC');
				$this->limit(1);

				
				$query = $this->get();
				return $query;
			}
		} else {
			$this->select('t_user.nama as tsr, t_data_nasabah.nama as nasabah, t_log_call.*');
			$this->join('t_data_nasabah', 't_log_call.id_nasabah = t_data_nasabah.id');
			$this->join('t_login', 't_log_call.call_by = t_login.id');
			$this->join('t_user', 't_user.id_login = t_login.id');
			
			$query = $this->get();

			return $query;
		}
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

	public function updateShare($data, $limit)
	{
		$this->set($data);
		$this->where('assigned_to', NULL);
		$this->orderBy('id', 'ASC');
		$this->limit($limit);
		return $this->update();
	}
}
