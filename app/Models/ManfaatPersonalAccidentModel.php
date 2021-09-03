<?php
namespace App\Models;

use CodeIgniter\Model;

class ManfaatPersonalAccidentModel extends Model
{
    protected $table = 't_manfaat_pa_car';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id', 'id_produk_pa_car', 'manfaat','up'];

	public function getAll($param = array())
	{
		if (isset($param['id_produk_pa_car'])) { $this->where('id_produk_pa_car', $param['id_produk_pa_car']); }
       
		
		if(isset($param['list']))
		{
			if($param['list'] == 1){
				
				$this->select('*');
				
			}
		} else {
			$this->select('*');
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

	public function deleteAble($id)
	{
	    return $this->delete(['id' =>  $id]);
	}
}
