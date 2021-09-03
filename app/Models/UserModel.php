<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 't_user';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nama', 'id_login', 'jk', 'foto', 'created_by', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_user.id', $param['id']); }
		if (isset($param['id_login'])) { $this->where('t_user.id_login', $param['id_login']); }
        if (isset($param['nama'])) { $this->where('t_user.nama', $param['nama']); }
		if (isset($param['jk'])) { $this->where('t_user.jk', $param['jk']); }
		if (isset($param['foto'])) { $this->where('t_user.foto', $param['foto']); }
		if (isset($param['created_at'])) { $this->where('t_user.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_user.created_by', $param['created_by']); }
		if (isset($param['updated_by'])) { $this->where('t_user.updated_by', $param['updated_by']); }
        
		$this->select('t_user.*');
		$query = $this->get();

		return $query;
	}

	public function addNew($data)
	{
		$data['query'] = $this->insert($data);
		$data['id'] = $this->insertID();

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

	public function deleteAble($id)
	{
	    return $this->delete(['id' =>  $id]);
	}
}
