<?php
namespace App\Models;

use CodeIgniter\Model;

class CampaignModel extends Model
{
    protected $table = 't_campaign';
	protected $primaryKey = 'id';

	protected $allowedFields = ['campaign','created_at', 'created_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_campaign.id', $param['id']); }
        if (isset($param['campaign'])) { $this->where('t_campaign.campaign', $param['campaign']); }
		if (isset($param['created_at'])) { $this->where('t_campaign.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_campaign.created_by', $param['created_by']); }
		if (isset($param['landingpage'])) { $this->whereNotIn('t_campaign.campaign', [$param['landingpage']]); }
		if(isset($param['not']))
		{
			if($param['not'] == 'Landing Page')
			{
				$this->whereNotIn('campaign', ['Landing Page']);
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
}
