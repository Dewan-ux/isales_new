<?php
namespace App\Models;

use CodeIgniter\Model;

class PremiModel extends Model
{
    protected $table = 't_premi';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nominal', 'id_produk', 'satuan', 'carnam', 'kategori','aktif','up','created_by','created_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_premi.id', $param['id']); }
        if (isset($param['nominal'])) {$this->where('t_premi.nominal', $param['nominal']); }
		if (isset($param['satuan'])) { $this->where('t_premi.satuan', $param['satuan']); }
		if (isset($param['id_produk'])) { $this->where('t_premi.id_produk', $param['id_produk']); }
		if (isset($param['kategori'])) { $this->where('t_premi.kategori', $param['kategori']); }
		if (isset($param['up'])) { $this->where('t_premi.up', $param['up']); }
		if (isset($param['created_at'])) { $this->where('t_premi.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
		
		if (isset($param['carnam'])){
			$this->select('t_premi.*, t_produk.nama_produk');
			$this->join('t_produk', 't_premi.id_produk = t_produk.id'); 
		$query = $this->get();
		return $query;
		 }
		if(isset($param['list']))
		{
			if($param['list'] == 1){
				
				$this->select('t_produk.id as id_produk, t_produk.nama_produk, t_premi.id as id, t_premi.kategori, t_premi.nominal, t_premi.satuan, t_premi.up');
				$this->join('t_produk', 't_produk.id = t_premi.id_produk');
				
			}
		} else {
			$this->select('*');
		}
		$this->where('t_premi.aktif', '1');
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

	public function deleteAble($id)
	{
	    return $this->delete(['id' =>  $id]);
	}
}
