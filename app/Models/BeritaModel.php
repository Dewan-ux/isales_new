<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class BeritaModel extends Model
{
    protected $table = 't_berita';
	protected $primaryKey = 'id';
	public $ip = '';

    protected $allowedFields = ['judul','isi','kategori', 'id_tags','foto','updated_by','aktif','created_at',
    'updated_by','updated_at'];
	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_berita.id', $param['id']); }
        if(isset($param['judul'])) {$this->where('t_berita.judul', $param['judul']); }
        if(isset($param['isi'])) {$this->where('t_berita.isi', $param['isi']); }
        if(isset($param['kategori'])) {$this->where('t_berita.kategori', $param['kategori']); }
        if (isset($param['id_tags'])) { $this->where('t_berita.id_tags', $param['id_tags']); }
        if(isset($param['foto'])) {$this->where('foto', $param['foto']); }
		if (isset($param['created_at'])) { $this->where('t_berita.created_at', $param['created_at']); }
        if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
		if (isset($param['updated_by'])) { $this->where('t_login.updated_by', $param['updated_by']); }
		if (isset($param['updated_at'])) { $this->where('t_berita.updated_at', $param['updated_at']); }

        if(isset($param['list']))
		{
			if($param['list'] == '1'){
				$this->ip = $param['ip'];
				$this->select('t_berita.*, t_tags.tags');
				$this->join('t_tags', 't_tags.id = t_berita.id_tags');
				$this->where('t_berita.kategori', function (BaseBuilder $builder) {
					return $builder->select("
						 CASE 
						 WHEN `t_visitor`.`umur` < 25 THEN '0'
						 WHEN (`t_visitor`.`umur` >= 25 AND `t_visitor`.`umur` <= 35) THEN '1'
						 WHEN (`t_visitor`.`umur` >= 36 AND `t_visitor`.`umur` <= 40) THEN '2'
						 WHEN `t_visitor`.`umur` > 40 THEN '3'
						 END", FALSE)->from('t_visitor')->where('t_visitor.ip', $this->ip)->limit(1);
				});
				$this->orderBy('created_at','DESC')->limit(1);
				
			} else if($param['list'] == '4'){
				$this->ip = $param['ip'];
				$this->select('t_berita.*, t_tags.tags');
				$this->join('t_tags', 't_tags.id = t_berita.id_tags');
				$this->orWhere('t_berita.kategori', function (BaseBuilder $builder) {
					return $builder->select("
						 CASE `kategori`
						 WHEN (`t_visitor`.`umur` <= 30) THEN '1'
						 WHEN (`t_visitor`.`umur` >= 31 AND `t_visitor`.`umur` <= 40) THEN '2'
						 WHEN `t_visitor`.`umur` > 40 THEN '3'
						 END", FALSE)->from('t_visitor')->where('t_visitor.ip', $this->ip)->limit(1);
				});
				$this->orderBy('created_at','DESC')->limit($param['limit']);
			} else {
				$this->select('t_berita.*, t_tags.tags');
				$this->join('t_tags', 't_tags.id = t_berita.id_tags');
			}
		} else {
			$this->select('t_berita.*, t_tags.tags');
			$this->join('t_tags', 't_tags.id = t_berita.id_tags');
		}
		$this->where('t_berita.aktif', '1');
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
