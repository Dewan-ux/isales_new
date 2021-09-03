<?php
namespace App\Models;

use CodeIgniter\Model;

class LogLoginModel extends Model
{
    protected $table = 't_log_login';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_login', 'status', 'ipaddr'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_login.id', $param['id']); }
        if (isset($param['id_login'])) { $this->where('t_log_login.id_login', $param['id_login']); }
		if (isset($param['status'])) { $this->where('t_log_login.status', $param['status']); }
		if (isset($param['created_at'])) { $this->where('t_log_login.created_at', $param['created_at']); }
		
		if(isset($param['list'])){
			if($param['list'] == 'logoutminutes'){
				$sql = "SELECT
				(
				SELECT
					SUM(IFNULL((
						SELECT
							TIMESTAMPDIFF(
								SECOND,(
								SELECT
									created_at 
								FROM
									t_log_login 
								WHERE
									id_login = t.id_login 
									AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
									AND created_at <= t.created_at and status = '1'
									ORDER BY created_at DESC LIMIT 1
								),
								t.created_at 
							)),
						'0' 
					)) AS lama_toilet 
				FROM
					t_log_login t 
				WHERE
					id_login = :tsr_id: 
					AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
					AND STATUS = '2' 
				) AS toilet,
				(
				SELECT
					SUM(IFNULL((
						SELECT
							TIMESTAMPDIFF(
								SECOND,(
								SELECT
									created_at 
								FROM
									t_log_login 
								WHERE
									id_login = t.id_login 
									AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
									AND created_at <= t.created_at and status = '1'
									ORDER BY created_at DESC LIMIT 1
								),
								t.created_at 
							)),
						'0' 
					)) AS lama_istirahat 
				FROM
					t_log_login t 
				WHERE
					id_login = :tsr_id: 
					AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
					AND STATUS = '3' 
				) AS istirahat,
				(
				SELECT
					SUM(IFNULL((
						SELECT
							TIMESTAMPDIFF(
								SECOND,(
								SELECT
									created_at 
								FROM
									t_log_login 
								WHERE
									id_login = t.id_login 
									AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
									AND created_at <= t.created_at and status = '1'
									ORDER BY created_at DESC LIMIT 1
								),
								t.created_at 
							)),
						'0' 
					)) AS lama_shalat 
				FROM
					t_log_login t 
				WHERE
					id_login = :tsr_id: 
					AND DATE_FORMAT( created_at, '%Y-%m-%d' ) = STR_TO_DATE( :today:, '%Y-%m-%d' ) 
				AND STATUS = '4' 
				) AS shalat;";
				$query = $this->query($sql,[
					'tsr_id' => $param['tsr_id'],
					'today' => fullDayNow()
				]);
			} elseif($param['list'] == 1){
				$this->select('t_log_login.status, t_log_login.created_at waktu_logout');
				$this->join('t_login', 't_login.id = t_log_login.id_login', 'right');
				$this->whereNotIn('t_log_login.status', ['1']);
				$this->where('t_login.logged_in', '0');
				$this->where('date(t_log_login.created_at)', fullDayNow());
				$this->orderBy('t_log_login.created_at', 'DESC');
				$this->limit(1);
				$query = $this->get();
			}elseif($param['list'] == 2){
				$this->where('t_log_login.status', ['1']);
				$this->orderBy('t_log_login.created_at', 'DESC');
				$this->limit(1);
				$query = $this->get();
			}

		}else{
			$this->select('*');
			$query = $this->get();

		}

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
