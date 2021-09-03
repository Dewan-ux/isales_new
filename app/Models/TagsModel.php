<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class TagsModel extends Model
{
    protected $table = 't_tags';
	protected $primaryKey = 'id';

	protected $allowedFields = ['tags','created_by','created_at', 'updated_by','aktif','updated_at'];

	public function getAll($param = array())
	{
        if(isset($param['tags'])) {$this->where('t_tags.tags', $param['tags']); }
		if (isset($param['created_at'])) { $this->where('t_tags.created_at', $param['created_at']); }
        if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
        if (isset($param['updated_at'])) { $this->where('t_tags.updated_at', $param['updated_at']); }
        if (isset($param['updated_by'])) { $this->where('t_login.updated_by', $param['updated_by']); }
		
		if(isset($param['id']))
		{
			$this->ip = $param['ip'];
			$this->select('t_berita.*, t_tags.tags');
			$this->join('t_berita', 't_tags.id = t_berita.id_tags');
			$this->orWhere('t_berita.kategori', function (BaseBuilder $builder) {
				return $builder->select("
						CASE `kategori`
						WHEN (`t_visitor`.`umur` <= 30) THEN '1'
						WHEN (`t_visitor`.`umur` >= 31 AND `t_visitor`.`umur` <= 40) THEN '2'
						WHEN `t_visitor`.`umur` > 40 THEN '3'
						END", FALSE)->from('t_visitor')->where('t_visitor.ip', $this->ip);
			});
			$this->orderBy('created_at','DESC');
			$this->where('t_tags.id', $param['id']);	
		} else {
			$this->select('t_tags.*, (SELECT count(id) FROM t_berita WHERE id_tags = t_tags.id) as total');
		}
        $this->where('t_tags.aktif', '1');
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
