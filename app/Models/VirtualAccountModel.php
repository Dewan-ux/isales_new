<?php
namespace App\Models;

use CodeIgniter\Model;

class VirtualAccountModel extends Model
{
    protected $table = 't_virtual_account';
	protected $primaryKey = 'id';

	protected $allowedFields = ['batch_number','virtual_account','no_spaj','type_spaj','jenis_spaj','generated_at', 'used_by','created_at', 'created_by', 'updated_at', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_virtual_account.id', $param['id']); }
		if (isset($param['virtual_account'])) { $this->where('t_virtual_account.virtual_account', $param['virtual_account']); }
		if (isset($param['no_spaj'])) { $this->where('t_virtual_account.no_spaj', $param['no_spaj']); }
		if (isset($param['used_by'])) { $this->where('t_virtual_account.used_by', $param['used_by']); }
		if (isset($param['created_at'])) { $this->where('t_virtual_account.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_virtual_account.created_by', $param['created_by']); }
		if (isset($param['updated_at'])) { $this->where('t_virtual_account.updated_at', $param['updated_at']); }
		if (isset($param['updated_by'])) { $this->where('t_virtual_account.updated_by', $param['updated_by']); }


        if(isset($param['unused'])){
            if($param['unused'] == 1){
                $this->select('t_virtual_account.id, t_virtual_account.no_spaj');
                $this->where('used_by', NULL);
                $this->orderBy('rand()');
                $this->limit(1);
                $query = $this->get();
                return $query;
            }
        }

        $this->select('t_virtual_account.*, if(isnull(t_virtual_account.used_by), "Available", "Not Available") as used');
        $this->orderBy('t_virtual_account.created_at', 'DESC');
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
