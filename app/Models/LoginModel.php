<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 't_login';
	protected $primaryKey = 'id';

	protected $allowedFields = ['username', 'password', 'group' ,'role', 'serole', 'token', 'last_login', 'logged_in', 'active', 'created_by', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_login.id', $param['id']); }
		if (isset($param['username'])) { $this->where('t_login.username', $param['username']); }
		if (isset($param['email'])) { $this->where('t_login.email', $param['email']); }
		if (isset($param['password'])) { $this->where('t_login.password', $param['password']); }
		if (isset($param['role'])) { $this->where('t_login.role', $param['role']); }
		if (isset($param['token'])) { $this->where('t_login.token', $param['token']); }
		if (isset($param['last_login'])) { $this->where('t_login.last_login', $param['last_login']); }
		if (isset($param['logged_in'])) { $this->where('t_login.logged_in', $param['logged_in']); }
		if (isset($param['active'])) { $this->where('t_login.active', $param['active']); }
		if (isset($param['created_at'])) { $this->where('t_login.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
		if (isset($param['updated_by'])) { $this->where('t_login.updated_by', $param['updated_by']); }

		
		if (isset($param['serole']))
		{
			$this->select('t_login.*, t_user.id as id_user, t_user.nama, t_user.jk, t_user.foto');
			$this->join('t_user', 't_user.id_login = t_login.id', 'left');
			$this->whereIn('role', $param['serole']);
			$this->orderBy('t_user.nama','ASC');
			$this->groupBy('t_user.id');
				// $this->where('t_login.group',$param['group']);
			$query = $this->get();
			return $query;
		}
		
		if (isset($param['list']))
		{
			if($param['list'] == '3')
			{
				$this->select('t_login.id, t_user.id as id_user, t_user.nama, t_user.jk, t_user.foto, 
				IF(ISNULL((SELECT id FROM t_data_nasabah WHERE status NOT IN ("1", "2", "7", "9") AND assigned_to = t_login.id AND date(assigned_at) LIKE \''.fullDayNow().'%\' LIMIT 1)), "0", "2") AS assigned');
				$this->join('t_user', 't_user.id_login = t_login.id', 'left');
				$this->where('t_login.role', '3');
				$this->where('t_login.group', $param['group']);
				$this->orderBy('t_user.nama','ASC');
				$query = $this->get();
				return $query;
			} 
			else if($param['list'] == '4') 
			{
				$groupSql = isset($param['group']) ? " AND tsr_login.group = :group: " : "";
			
				$sql = "SELECT tsr_login.id as id, tsr.nama as nama_tsr, leader.nama as nama_leader, 
				(SELECT count(t_spaj.id) 
				FROM t_spaj JOIN t_data_nasabah as dn ON dn.id = t_spaj.id_data_nasabah 
				WHERE dn.assigned_to = tsr_login.id)  as total_case
				FROM t_data_nasabah
					JOIN t_login as leader_login ON leader_login.id = t_data_nasabah.assigned_by
					JOIN t_user as leader ON leader.id_login = leader_login.id
					JOIN t_login as tsr_login ON tsr_login.id = t_data_nasabah.assigned_to
					JOIN t_user as tsr ON tsr.id_login = tsr_login.id 
					WHERE tsr_login.role = '3' ".$groupSql.
					"GROUP BY tsr_login.id";
				
				$query = $this->query($sql, isset($param['group']) ? ['group'=>$param['group']] : []);
				return $query;
			}
			else if($param['list'] == '5') 
			{
				$groupSql = isset($param['group']) ? " AND tsr_login.group = :group: " : "";
			
				$sql = "SELECT tsr_login.id as id, tsr.nama as nama_tsr, leader.nama as nama_leader, 
				(SELECT count(t_spaj.id) 
				FROM t_spaj JOIN t_data_nasabah as dn ON dn.id = t_spaj.id_data_nasabah 
				WHERE dn.assigned_to = tsr_login.id AND t_spaj.jns_asuransi = 1)  as total_case
				FROM t_data_nasabah
					JOIN t_login as leader_login ON leader_login.id = t_data_nasabah.assigned_by
					JOIN t_user as leader ON leader.id_login = leader_login.id
					JOIN t_login as tsr_login ON tsr_login.id = t_data_nasabah.assigned_to
					JOIN t_user as tsr ON tsr.id_login = tsr_login.id 
					WHERE tsr_login.role = '3' ".$groupSql.
					"GROUP BY tsr_login.id";
				
				$query = $this->query($sql, isset($param['group']) ? ['group'=>$param['group']] : []);
				return $query;
			}
		} else if(isset($param['dashboard'])) {
			if($param['dashboard'] == 'list'){
				$sql = "SELECT `t_login`.`id` AS `id`, `t_user`.`nama` AS `nama`,(SELECT count( `t_log_call`.`id` ) 
					FROM
						`t_log_call` 
					WHERE
						`t_log_call`.`call_by` = `t_login`.`id` 
						AND DATE(`t_log_call`.`call_start_at`) ".$this->escapeString($param['filter']).") AS `jumlah_call`, (SELECT ifnull( sum( `t_shared_leads`.`share` ), 0 ) FROM
						`t_shared_leads` WHERE
						`t_shared_leads`.`shared_to` = `t_login`.`id` 
						AND DATE(`t_shared_leads`.`created_at`) ".$this->escapeString($param['filter'])." 
						) AS `jumlah_leads`,(
					SELECT
						count( `t_spaj`.`id` ) 
					FROM
						`t_spaj` 
					WHERE
						`t_spaj`.`created_by` = `t_login`.`id` 
						AND DATE(`t_spaj`.`created_at`) ".$this->escapeString($param['filter'])."
						) AS `jumlah_case`,(
					SELECT
						count( `log_call_status`.`id` ) 
					FROM
						`t_log_call` `log_call_status` 
					WHERE
						`log_call_status`.`call_by` = `t_login`.`id` 
						AND `log_call_status`.`status` = '2' 
						AND DATE(`log_call_status`.`call_start_at`) ".$this->escapeString($param['filter'])." 
					) AS `call_follow_up` 
				FROM
					(
						`t_login`
					JOIN `t_user` ON ( `t_login`.`id` = `t_user`.`id_login` )) 
				WHERE
					`t_login`.`role` = '3'";
				$sqlGroup = "";
				$params = ['filter'=>$param['filter']];

				if(isset($param['group']))
				{
					$sqlGroup = 'AND `t_login`.`group` = :group:';
					$params['group'] = $param['group'];
				}

				$sql = $sql.$sqlGroup;
				$query = $this->query($sql, $params);
				// print_r($this->getLastQuery());
				//  die();
				return $query;
			} else if($param['dashboard'] == 'performance') 
			{
				$sqltsr = " and `t_login`.`id` = :tsr_id:";
				$sql = "SELECT `t_login`.`id` AS `id`, `t_user`.`nama` AS `nama`,
				(SELECT ifnull( sum( `t_shared_leads`.`share` ), 0 ) FROM
						`t_shared_leads` WHERE
						`t_shared_leads`.`shared_to` = `t_login`.`id` 
						AND DATE(`t_shared_leads`.`created_at`) ".$this->escapeString($param['filter'])." 
						) AS `jumlah_leads`,(
					SELECT
						count( `t_spaj`.`id` ) 
					FROM
						`t_spaj` 
					WHERE
						`t_spaj`.`created_by` = `t_login`.`id` 
						AND DATE(`t_spaj`.`created_at`) ".$this->escapeString($param['filter'])."
						) AS `jumlah_case`
				FROM
					(
						`t_login`
					JOIN `t_user` ON ( `t_login`.`id` = `t_user`.`id_login` )) 
				WHERE
					`t_login`.`role` = '3'";
				if(isset($param['tsr_id']))
				{
					$sql = $sql.$sqltsr;
					$query = $this->query($sql, [
						'filter' => $param['filter'],
						'tsr_id' => $param['tsr_id']
					]);
				} else {
					$query = $this->query($sql, [
						'filter' => $param['filter']
					]);
				}
				
				return $query;
			}
		
		}

		if(isset($param['group'])){
			$this->select('t_login.*, t_user.id as id_user, t_user.nama, t_user.jk, t_user.foto');
			$this->join('t_user', 't_user.id_login = t_login.id', 'left');
			$this->orderBy('t_user.nama','ASC');
			$this->groupBy('t_user.id');
			$this->where('t_login.group',$param['group']);
			$query = $this->get();
			return $query;
		}
		if(isset($param['auth_group']))
		{
			if($param['auth_group'] == 'group')
			{
				$this->select('t_login.*, t_user.id as id_user, t_user.nama, t_user.jk, t_user.foto, leader_user.nama as leader_nama');
				$this->join('t_user', 't_user.id_login = t_login.id', 'left');
				$this->join('t_user as leader_user', 'leader_user.id_login = t_login.group', 'left');
				$this->whereIn('t_login.role', $param['roles']);
				$this->orderBy('t_user.nama','ASC');
				$this->groupBy('t_user.id');
				$query = $this->get();
				return $query;
			}
		} 
		$this->select('t_login.*, t_user.id as id_user, t_user.nama, t_user.jk, t_user.foto');
		$this->join('t_user', 't_user.id_login = t_login.id', 'left');
		$this->orderBy('t_user.nama','ASC');
		$this->groupBy('t_user.id');
			// $this->where('t_login.group',$param['group']);
		$query = $this->get();
		return $query;
	}

	public function addNew($data)
	{
		$data['query'] = $this->insert($data);
		$data['id'] = $this->insertID();

		return $data;
	}

	public function addNewBatch($datas)
	{
		$data['query'] = $this->insertBatch($datas);
		$data['id'] = $this->insertID();
		return $data;
	}

	public function getLastId($param){
		if(isset($param['role']))
		{
			$this->selectCount('id');
			$this->where('role', $param['role']);
			$query = $this->get();
			return $query;
		}
		
	}


	public function editAble($id, $data)
	{
	    return $this->update($id, $data);
	}

	public function deleteAble($id)
	{
	    return $this->delete(['id' =>  $id]);
	}


	public function logoutAll()
	{
		return $this->set('logged_in', '0');
	}
}
