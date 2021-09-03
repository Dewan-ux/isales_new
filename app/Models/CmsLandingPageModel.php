<?php
namespace App\Models;

use CodeIgniter\Model;

class CmsLandingPageModel extends Model
{
    protected $table = 't_cms_landingpage';
	protected $primaryKey = 'id';

	protected $allowedFields = ['foto_brosur','foto_banner','created_at', 'updated_at', 'created_by', 'updated_by', 'aktif'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_cms_landingpage.id', $param['id']); }
        if(isset($param['foto_brosur'])) {$this->where('t_cms_landingpage.foto_brosur', $param['foto_brosur']); }
        if (isset($param['foto_banner'])) { $this->where('t_cms_landingpage.foto_banner', $param['foto_banner']); }
		if (isset($param['created_at'])) { $this->where('t_cms_landingpage.created_at', $param['created_at']); }
		if (isset($param['updated_at'])) { $this->where('t_cms_landingpage.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_cms_landingpage.created_by', $param['created_by']); }
		if (isset($param['updated_by'])) { $this->where('t_cms_landingpage.created_by', $param['created_by']); }
		if(isset($param['foto'])){
			$this->select($param['foto']);
			$this->orderBy('created_at', 'DESC');
			$this->limit(1);
			$this->where('aktif', '1');
			$query = $this->get();
			return $query;
		} 
		
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
