<?php
namespace App\Models;

use CodeIgniter\Model;

class SharedModel extends Model
{
    protected $table = 't_shared_leads';
	protected $primaryKey = 'id';

	protected $allowedFields = ['share', 'shared_by','shared_to','created_at', 'created_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_shared_leads.id', $param['id']); }
		if (isset($param['share'])) { $this->where('t_shared_leads.share', $param['share']); }
		if (isset($param['shared_by'])) { $this->where('t_shared_leads.shared_by', $param['shared_by']); }
		if (isset($param['shared_to'])) { $this->where('t_shared_leads.shared_to', $param['shared_to']); }
		if (isset($param['created_at'])) { $this->where('t_shared_leads.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_shared_leads.created_by', $param['created_by']); }

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
