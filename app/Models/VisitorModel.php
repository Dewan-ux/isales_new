<?php
namespace App\Models;

use CodeIgniter\Model;

class VisitorModel extends Model
{
    protected $table = 't_visitor';
	protected $primaryKey = 'id';

	protected $allowedFields = ['nama', 'telepon','id_campaign', 'segment', 'email', 'jumlah_kopi', 'budget_kopi', 'asuransi', 'ip', 'sent', 'created_by', 'created_at'];
	

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
		if (isset($param['id_campaign'])) { $this->where('id_campaign', $param['id_campaign']); }
        if (isset($param['nama'])) { $this->where('nama', $param['nama']); }
		if (isset($param['telepon'])) { $this->whereIn('telepon', $param['telepon']); }
		if (isset($param['email'])) { $this->whereIn('email', $param['email']); }
        if (isset($param['segment'])) { $this->where('segment', $param['segment']); }
		if (isset($param['sent'])) { $this->where('sent', $param['sent']); }
		if (isset($param['jumlah_kopi'])) { $this->whereIn('jumlah_kopi', $param['email']); }
		if (isset($param['umur'])) { $this->where('umur', $param['umur']); }
		if (isset($param['ip'])) { $this->where('ip', $param['ip']); }
		if (isset($param['sent'])) { $this->where('sent', $param['sent']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('created_by', $param['created_by']); }
		
		if(isset($param['count'])){
			if($param['count'] == 'available'){
				$this->select('count(distinct telepon) as available');
				$this->orderBy('created_at','DESC');
				$query = $this->get();
				return $query;
				
			}
		}
		if(isset($param['limit'])){
			if($param['limit'] > 0)
			{
				$this->select('*');
				$this->orderBy('rand()');
				$this->limit($param['limit']);
				$query = $this->get();
				return $query;
			}
		}

		if(isset($param['dashboard']))
		{
			if($param['dashboard'] == 'admin')
			{
				$sql = "select a.Date, 
				(SELECT count(t_visitor.id) FROM t_visitor WHERE date(t_visitor.created_at) = a.Date and t_visitor.id_campaign = '1') as total
				FROM (
					select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a) ) DAY as Date
					from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
					cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
					cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
					cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as d
				) a WHERE a.Date BETWEEN ? AND ? GROUP BY a.Date" ;
				$query = $this->query($sql, [
					$param['start_date'],
					$param['end_date']
				]);
				return $query;
			}
		}
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

	public function addNewBatch($data)
	{
		$data['query'] = $this->insertBatch($data);
		return $data;
	}
}
