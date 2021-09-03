<?php
namespace App\Models;

use CodeIgniter\Model;
class ReportingModel extends Model
{
    protected $table = 't_reporting';
	protected $primaryKey = 'id';

	protected $allowedFields = ['reporting', 'start_date', 'id_campaign', 'id_produk', 'tsr_ids', 'end_date', 'export_by', 'export_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
		if (isset($param['start_date'])) { $this->where('start_date', $param['start_date']); }
		if (isset($param['end_date'])) { $this->where('end_date', $param['end_date']); }
		if (isset($param['export_by'])) { $this->where('export_by', $param['export_by']); }
		if (isset($param['export_at'])) { $this->where('export_at', $param['export_at']); }
		if (isset($param['id_campaign'])) { $this->where('id_campaign', $param['id_campaign']); }
		if (isset($param['id_produk'])) { $this->where('id_produk', $param['id_produk']); }

		if(isset($param['id']) && isset($param['reporting'])){
				if($param['reporting'] == 0)
				{
					//DPR
					// $this->select('view_reporting_dpr.*');
					// $this->join('view_reporting_dpr','view_reporting_dpr.datelist >= t_reporting.start_date and view_reporting_dpr.datelist <= t_reporting.end_date');
					// if(isset($param['leader_id']))
					// {
					// 	$this->where('view_reporting_dpr.leader_id', $param['leader_id']);
					// }
					// $this->where('t_reporting.id', $param['id']);
					// $this->groupBy('view_reporting_dpr.datelist');
					// $query = $this->get();
					$param['leader_id'] = isset($param['leader_id']) ? $this->db->escapeString($param['leader_id']) : NULL;

					$arr = [
						$param['leader_id'],
						strval($param['id']),	
						$param['id'],
					];
					
					$sql = "SELECT t_reporting.start_date, t_reporting.end_date, view_reporting_dpr.* FROM (SELECT @leader_id := ? p ) parm, (SELECT @reporting_id := ? p2 ) parms, view_reporting_dpr
					JOIN t_reporting ON t_reporting.id = view_reporting_dpr.reporting_id
					WHERE t_reporting.id = ? ";

					$query = $this->query($sql,$arr);
					return $query;

				} else  if($param['reporting'] == 1){
					//APR
					$param['leader_id'] = isset($param['leader_id']) ? $this->db->escapeString($param['leader_id']) : NULL;

					$arr = [
						$param['leader_id'],
						$param['id'],	
					];
					$tsrQuery = "";
					if(isset($param['tsr_ids']))
					{
						if(count($param['tsr_ids']) > 0){
							$tsrQuery = "WHERE view_reporting_apr.id_login IN ?";
							$arr[] = $param['tsr_ids'];
						}
					}
					
					$sql = "SELECT t_reporting.start_date, t_reporting.end_date, view_reporting_apr.* FROM (SELECT @leader_id := ? p ) parm, (SELECT @reporting_id := ? p2 ) parms,
					view_reporting_apr
					JOIN t_reporting ON t_reporting.id = view_reporting_apr.reporting_id ".$tsrQuery;

					$query = $this->query($sql, $arr);
					// print_r($this->getLastQuery());
					return $query;

				} else {
					//reporting excel
					$param['start_date'] = isset($param['start_date']) ? $param['start_date'] : date('Y-m-d');
					$param['end_date'] = isset($param['end_date']) ? $param['end_date'] : date('Y-m-d');
					$sql = "SELECT distinct t_spaj.*,
					 t_virtual_account.virtual_account,t_virtual_account.no_spaj, tsr_user.nama as tsr_nama, leader_user.nama as leader_nama,
					 qa_login.id as qa_id, t_log_fup_spaj.created_at as checked_at, t_produk.nama_produk, t_log_fup_spaj.created_at as date_qa,
					 t_premi.satuan, t_premi.nominal, tsr_login.id as seller_id,
					 (SELECT status FROM t_log_fup_spaj WHERE id_spaj = t_spaj.id ORDER BY created_at ASC LIMIT 1) as first_status
					  FROM t_spaj
					JOIN  t_virtual_account ON t_spaj.id_virtual_account = t_virtual_account.id
					JOIN t_log_fup_spaj ON t_spaj.id = t_log_fup_spaj.id_spaj
					JOIN t_login as qa_login ON qa_login.id = t_log_fup_spaj.created_by
					JOIN t_user as qa_user ON qa_login.id = qa_user.id_login
					JOIN t_login as tsr_login ON tsr_login.id = t_spaj.created_by
					JOIN t_user as tsr_user ON tsr_user.id_login = tsr_login.id
					JOIN t_login as leader_login ON leader_login.id = tsr_login.group
					JOIN t_user as leader_user ON leader_user.id_login = leader_login.id
					JOIN t_premi ON t_premi.id = t_spaj.id_premi 
					JOIN t_produk ON t_premi.id_produk = t_produk.id
					".
					// "JOIN t_payment ON t_payment.id = t_spaj.id_payment AND t_payment.aktif = '1'".
					"WHERE date(t_spaj.created_at) between :start_date: and :end_date: and t_spaj.checked = '1'
					GROUP BY t_spaj.id
					ORDER BY t_spaj.created_at";
					$query = $this->query($sql, ['start_date' => $param['start_date'], 'end_date' => $param['end_date']]);
					return $query;
				}
		}

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
