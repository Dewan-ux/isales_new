<?php
namespace App\Models;

use CodeIgniter\Model;
class DataNasabahModel extends Model
{
    protected $table = 't_data_nasabah';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nama', 'telepon', 'telepon2', 'id_spaj', 'status', 'sent_to','id_spaj','utilized', 'assigned_by', 'assigned_to', 'assigned_at', 'updated_at', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
        if (isset($param['nama'])) { $this->where('nama', $param['nama']); }
		if (isset($param['status'])) { $this->whereIn('status', $param['status']); }
		if (isset($param['id_spaj'])) { $this->whereIn('id_spaj', $param['id_spaj']); }
		if (isset($param['assigned_by'])) { $this->where('assigned_by', $param['assigned_by']); }
		if (isset($param['assigned_at'])) { $this->where('assigned_at', $param['assigned_at']); }
		if (isset($param['sent_to'])) { $this->where('sent_to', $param['sent_to']); }
		if (isset($param['assigned_to'])) { $this->where('assigned_to', $param['assigned_to']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
        if (isset($param['updated_by'])) { $this->where('updated_by', $param['updated_by']); }
		
		if(isset($param['list'])){
			if($param['list'] == 'unsigned'){
				$this->select('count(id) as leads_available');
				$this->where('assigned_to is NULL OR assigned_to = 0', null);
			} else if ($param['list'] == '1') {
				$this->select('t_data_nasabah.*, IFNULL((SELECT checked FROM t_spaj WHERE id_data_nasabah = t_data_nasabah.id LIMIT 1), NULL) as checked');
			}
			$this->orderBy('assigned_at', 'DESC');

			$query = $this->get();
			return $query;
		} 
		if(isset($param['telepon2'])){
			$this->groupStart();
			if (isset($param['telepon'])) { $this->where('telepon', $param['telepon']); }
			if (isset($param['telepon'])) { $this->orWhere('telepon2', $param['telepon2']); }
			$this->groupEnd();
			$query = $this->get();
			return $query;
		}else if (isset($param['telepon'])) {
			$this->where('telepon', $param['telepon']); 
			$query = $this->get();
			return $query;
		}

		if(isset($param['admin'])){
			if($param['admin'] == 'dashboard'){
				$this->select('m.monthname as bulan, count(t_data_nasabah.id) as total');
				$this->join('view_month as m', 'm.monthnum = MONTH(created_at)', 'right outer');
				$this->where('year(CURDATE()) = YEAR(created_at) or id is NULL');
				$this->groupBy('m.monthnum');
				$this->orderBy('m.monthnum ASC');
				$query = $this->get();
				return $query;
			}
		}

		if(isset($param['spaj'])){
			if($param['spaj'] == '1'){
				$this->select('t_spaj.id, t_spaj.no_proposal, t_spaj.jns_asuransi, t_spaj.nama, t_spaj.telp1, t_premi.nominal,
				t_produk.nama_produk, t_user.nama as tsr_nama,t_spaj.created_at as tanggal,t_spaj.checked');
				$this->join('t_spaj',' t_spaj.id_data_nasabah = t_data_nasabah.id');
				$this->join('t_login',' t_spaj.created_by = t_login.id');
				$this->join('t_user ',' t_login.id = t_user.id_login');
				$this->join('t_premi ',' t_spaj.id_premi = t_premi.id');
				$this->join('t_produk ',' t_premi.id_produk = t_produk.id');
				$this->orderBy('t_spaj.created_at','DESC');
				$query = $this->get();
				return $query;
			}
		}
		
		$this->select('*');
		$query = $this->get();
		return $query;
	}

	public function addNew($datas)
	{
		$data['query'] = $this->insert($datas);
		$data['id'] = $this->insertID;
		return $data;
	}

	public function addNewBatch($data)
	{
		
		$data['query'] = $this->insertBatch($data);
		return $data;
	}

	public function editAble($id, $data)
	{
	    return $this->update($id, $data);
	}

	public function updateShare($data, $limit, $id)
	{
		foreach($data as $key => $value)
		{
			$this->set($key, $value);
		}
		$this->set('utilized', 'ifnull(utilized, 0)+1', FALSE);
		$this->where('sent_to', $id);
		$this->where('assigned_to', NULL);
		$this->orWhere('assigned_to', '0');
		$this->orderBy('id', 'ASC');
		$this->limit($limit);
		$this->update();
		return $this->db->affectedRows();
	}

	public function resetShare($data,$status, $id)
	{		
		$this->set($data);
		$this->whereIn("`t_data_nasabah`.`status`", $status);
		if(isset($id)){
			$this->where('`t_data_nasabah`.`assigned_by`', $id);
		}

		// $this->whereNotIn("id", function(BaseBuilder $builder){
		// 	return $builder->select("id_nasabah")->from("t_log_call")->where("status", '2');
		// });

		$this->update();
		return $this->db->affectedRows();
	}

	public function resetThinking($data, $status, $id)
	{
		$this->set($data);
		$this->whereIn("`t_data_nasabah`.`status`", $status);
		$this->where('date(assigned_at) >= date(date_sub(now(), interval 1 week))',null,false);
		$this->where('date(assigned_at) <= date(now())',null,false);
		$this->update();
		return $this->db->affectedRows();
	}
	public function ExportData1(){
		 $this->select('t_spaj.id
						, t_spaj.no_proposal
						, t_spaj.jns_asuransi
						, t_spaj.nama
						, t_spaj.telp1
						, t_premi.nominal
						, t_produk.nama_produk
						, t_user.nama as tsr_nama
						, t_spaj.created_at as tanggal
						, t_user.nama as nama_sales
						, t_spaj.created_at
						, t_spaj.checked');
				$this->join('t_spaj',' t_spaj.id_data_nasabah = t_data_nasabah.id');
				$this->join('t_login',' t_spaj.created_by = t_login.id');
				$this->join('t_user ',' t_login.id = t_user.id_login');
				$this->join('t_premi ',' t_spaj.id_premi = t_premi.id');
				$this->join('t_produk ',' t_premi.id_produk = t_produk.id');
				$this->orderBy('t_spaj.created_at','DESC');
				
		$query = $this->get();
		return $query->getResultArray();
	}
}
