<?php
namespace App\Models;

use CodeIgniter\Model;

class PekerjaanModel extends Model
{
    protected $table = 't_pekerjaan';
	protected $primaryKey = 'id';

	protected $allowedFields = ['pekerjaan','created_at', 'created_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_pekerjaan.id', $param['id']); }
		if (isset($param['pekerjaan'])) { $this->where('t_pekerjaan.pekerjaan', $param['pekerjaan']); }
		if (isset($param['created_at'])) { $this->where('t_pekerjaan.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_pekerjaan.created_by', $param['created_by']); }

		$this->select('t_pekerjaan.pekerjaan');
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
