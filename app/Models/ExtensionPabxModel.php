<?php
namespace App\Models;

use CodeIgniter\Model;
class ExtensionPabxModel extends Model
{
    protected $table = 't_extension_pabx';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id_login','extension', 'secret', 'device_owner', 'actvie', 'created_at', 'created_by', 'updated_at', 'updated_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
		if (isset($param['id_login'])) { $this->where('id_login', $param['id_login']); }
        if (isset($param['extension'])) { $this->where('extension', $param['extension']); }
		if (isset($param['secret'])) { $this->whereIn('secret', $param['secret']); }
		if (isset($param['device_owner'])) { $this->where('device_owner', $param['device_owner']); }
		if (isset($param['active'])) { $this->where('active', $param['active']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('created_by', $param['created_by']); }
		if (isset($param['updated_at'])) { $this->where('updated_at', $param['updated_at']); }
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
    
    public function editByLogin($id, $data)
	{
		$this->where('id_login', $id);
		$this->set($data);
	    return $this->update();
	}

}
