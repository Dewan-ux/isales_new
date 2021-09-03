<?php
namespace App\Models;

use CodeIgniter\Model;

class DataNasabahModel extends Model
{
    protected $table = 't_data_nasabah';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nama', 'telepon', 'telepon2', 'status', 'assigned_by', 'assigned_to', 'assigned_at', 'updated_at', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
        if (isset($param['nama'])) { $this->where('nama', $param['nama']); }
		if (isset($param['status'])) { $this->whereIn('status', $param['status']); }
		if (isset($param['assigned_by'])) { $this->where('assigned_by', $param['assigned_by']); }
		if (isset($param['assigned_at'])) { $this->where('assigned_at', $param['assigned_at']); }
		if (isset($param['assigned_to'])) { $this->where('assigned_to', $param['assigned_to']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
        if (isset($param['updated_by'])) { $this->where('updated_by', $param['updated_by']); }
		
		if(isset($param['list'])){
			if($param['list'] == 'unsigned'){
				$this->select('*');
				$this->where('assigned_to is NOT NULL OR assigned_to = 0', null);
			}
		} else {
			$this->select('*');
		}

		if(isset($param['telepon2'])){
			$this->groupStart();
			if (isset($param['telepon'])) { $this->where('telepon', $param['telepon']); }
			if (isset($param['telepon'])) { $this->orWhere('telepon2', $param['telepon2']); }
			$this->groupEnd();
		}else{
			if (isset($param['telepon'])) { $this->where('telepon', $param['telepon']); 
			$this->limit(1); }
		}

		if(isset($param['admin'])){
			if($param['admin'] == 'dashboard'){
				$this->select(' MONTH(created_at) as bulan, count(t_data_nasabah.id) as total from t_data_nasabah');
				$this->where('created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)');
				$this->groupBy('month(created_at)');
				$this->orderBy('created_at ASC');
			}
		} else {
			$this->select('*');
		}

			if(isset($param['spaj'])){
			if($param['spaj'] == '1'){
				$this->select('t_spaj.id, t_spaj.no_proposal, t_spaj.nama, t_spaj.telp1, t_premi.nominal,
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

	public function updateShare($data, $limit)
	{
		$this->set($data);
		$this->where('assigned_to', NULL);
		$this->orderBy('id', 'ASC');
		$this->limit($limit);
		return $this->update();
	}
}
