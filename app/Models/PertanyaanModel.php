<?php
namespace App\Models;

use CodeIgniter\Model;

class PertanyaanModel extends Model
{
    protected $table = 't_pertanyaan_kesehatan';
	protected $primaryKey = 'id';

	protected $allowedFields = ['pertanyaan', 'remark', 'created_by', 'created_at', 'updated_by', 'updated_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
        if (isset($param['pertanyaan'])) { $this->where('pertanyaan', $param['pertanyaan']); }
		if (isset($param['remark'])) { $this->where('remark', $param['remark']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
		if (isset($param['updated_at'])) { $this->where('updated_at', $param['updated_at']); }
		if (isset($param['created_by'])) { $this->where('created_by', $param['created_by']); }
		if (isset($param['updated_by'])) { $this->where('updated_by', $param['updated_by']); }
        
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

	public function deleteAble($id)
	{
	    return $this->delete(['id' =>  $id]);
	}
}
