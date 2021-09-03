<?php
namespace App\Models;

use CodeIgniter\Model;

class LogUploadCampaignModel extends Model
{
    protected $table = 't_log_upload_campaign';
	protected $primaryKey = 'id';

	protected $allowedFields = ['id', 'carnam', 'total', 'id_campaign','created_at', 'created_by'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_log_upload_campaign.id', $param['id']); }
        if (isset($param['total'])) { $this->where('t_log_upload_campaign.total', $param['total']); }
		if (isset($param['id_campaign'])) { $this->where('t_log_upload_campaign.id_campaign', $param['id_campaign']); }
		if (isset($param['created_at'])) { $this->where('t_log_upload_campaign.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('t_log_upload_campaign.created_by', $param['created_by']); }
		if (isset($param['carnam'])){
			$this->select('t_log_upload_campaign.*, t_campaign.campaign');
			$this->join('t_campaign', 't_log_upload_campaign.id_campaign = t_campaign.id'); 
		$query = $this->get();
		return $query;
		 }
		
		$this->select('t_log_upload_campaign.*, t_campaign.campaign');
		$this->join('t_campaign', 't_log_upload_campaign.id_campaign = t_campaign.id'); 
		$this->whereNotIn('t_campaign.campaign', ['Landing Page']);
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
