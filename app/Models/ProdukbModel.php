<?php
namespace App\Models;

use CodeIgniter\Model;

class ProdukbModel extends Model
{
    protected $table = 't_produk_pa_car';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nama_produk','created_at', 'created_by','aktif'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_produk_pa_car.id', $param['id']); }
        if(isset($param['nama_produk'])) {$this->where('t_produk_pa_car.nama_produk', $param['nama_produk']); }
		if (isset($param['created_at'])) { $this->where('t_produk_pa_car.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }

        $this->select('*');
        $this->where('aktif', '1');
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
