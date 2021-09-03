<?php
namespace App\Models;

use CodeIgniter\Model;

class KesehatanNasabahModel extends Model
{
    protected $table = 't_kesehatan_nasabah';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_spaj', 'id_pertanyaan', 'jawaban','remark','created_by', 'created_at'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
        if (isset($param['id_spaj'])) { $this->where('id_spaj', $param['id_spaj']); }
		if (isset($param['id_pertanyaan'])) { $this->where('id_pertanyaan', $param['id_pertanyaan']); }
		if (isset($param['jawaban'])) { $this->where('jawaban', $param['jawaban']); }
		if (isset($param['remark'])) { $this->where('remark', $param['remark']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('created_by', $param['created_by']); }
		if (isset($param['created_by'])) { $this->where('created_by', $param['created_by']); }
		
		if(isset($param['detail']))
		{
			$this->select('t_kesehatan_nasabah.id, t_kesehatan_nasabah.id_pertanyaan, t_kesehatan_nasabah.jawaban, t_kesehatan_nasabah.remark, t_pertanyaan_kesehatan.pertanyaan');
			$this->join('t_pertanyaan_kesehatan', 't_pertanyaan_kesehatan.id = t_kesehatan_nasabah.id_pertanyaan');
			$this->where('id_spaj', $param['id_spaj']);
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
