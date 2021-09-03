<?php
namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 't_sales';
	protected $primaryKey = 'id';

	protected $allowedFields = ['pdf','pdf_ho','pdf_faq', 'pdf_plan','pdf_dumb','pdf_kantor','created_at', 'created_by','updated_at','updated_by','aktif'];

	public function getAll($param = array())
	{
	if (isset($param['id'])) { $this->where('t_sales.id', $param['id']); }
	if (isset($param['pdf'])) {$this->where('t_sales.pdf', $param['pdf']); }
        if (isset($param['pdf_ho'])) {$this->where('t_sales.pdf_ho', $param['pdf_ho']); }
        if (isset($param['pdf_faq'])) {$this->where('t_sales.pdf_faq', $param['pdf_faq']); }
        if (isset($param['pdf_plan'])) {$this->where('t_sales.pdf_plan', $param['pdf_plan']); }
        if (isset($param['pdf_dumb'])) {$this->where('t_sales.pdf_dumb', $param['pdf_dumb']); }
        if (isset($param['pdf_kantor'])) {$this->where('t_sales.pdf_kantor', $param['pdf_kantor']); }
	if (isset($param['created_at'])) { $this->where('t_sales.created_at', $param['created_at']); }
        if (isset($param['created_by'])) { $this->where('t_login.created_by', $param['created_by']); }
        if (isset($param['updated_at'])) { $this->where('t_sales.updated_at', $param['updated_at']); }
        if (isset($param['updated_by'])) { $this->where('t_login.updated_by', $param['updated_by']); }

		if(isset($param['list']))
		{
			if($param['list'] == 1)
			{
				$this->select('id, created_at');
				$this->where('aktif', '1');
				$query = $this->get();
				return $query;
			}
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
