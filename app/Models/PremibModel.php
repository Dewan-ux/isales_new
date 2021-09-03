<?php
namespace App\Models;

use CodeIgniter\Model;

class PremibModel extends Model
{
    protected $table = 't_premi_pa_car';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nominal', 'id_produk_pa_car', 'satuan', 'manfaat', 'aktif','up','created_by','created_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_premi_pa_car.id', $param['id']); }
        if(isset($param['nominal'])) {$this->where('t_premi_pa_car.nominal', $param['nominal']); }
		if (isset($param['satuan'])) { $this->where('t_premi_pa_car.satuan', $param['satuan']); }
		if (isset($param['id_produk_pa_car'])) { $this->where('t_premi_pa_car.id_produk_pa_car', $param['id_produk_pa_car']); }
		if (isset($param['up'])) { $this->where('t_premi_pa_car.up', $param['up']); }
		if (isset($param['created_at'])) { $this->where('t_premi_pa_car.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
		
		if(isset($param['list']))
		{
			if($param['list'] == 1){
				
				$this->select('t_produk_pa_car.id as id_produk_pa_car, t_produk_pa_car.nama_produk, t_premi_pa_car.id as id, t_premi_pa_car.nominal, t_premi_pa_car.manfaat, t_premi_pa_car.satuan, t_premi_pa_car.up');
				$this->join('t_produk_pa_car', 't_produk_pa_car.id = t_premi_pa_car.id_produk_pa_car');
				
			}
		} else {
			$this->select('*');
		}
		$this->where('t_premi_pa_car.aktif', '1');
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
