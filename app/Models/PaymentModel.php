<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 't_payment';
	protected $primaryKey = 'id';

	protected $allowedFields = ['payment','created_at', 'created_by','aktif'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_payment.id', $param['id']); }
		if (isset($param['payment'])) { $this->where('t_payment.payment', $param['payment']); }
		if (isset($param['aktif'])) { $this->where('t_payment.aktif', $param['aktif']); }
		if (isset($param['created_at'])) { $this->where('t_login.created_at', $param['created_at']); }
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
