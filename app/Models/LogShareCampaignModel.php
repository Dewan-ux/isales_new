<?php
namespace App\Models;

use CodeIgniter\Model;
class LogShareCampaignModel extends Model
{
    protected $table = 't_log_share_campaign';
	protected $primaryKey = 'id';

	protected $allowedFields = ['total', 'id_login', 'id_campaign', 'created_at', 'created_by', 'log_gagal', 'log_kirim', 'cardetail'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('isales_nasabah.t_log_share_campaign.id', $param['id']); }
        if (isset($param['total'])) { $this->where('isales_nasabah.t_log_share_campaign.total', $param['total']); }
        if (isset($param['id_campaign'])) { $this->where('isales_nasabah.t_log_share_campaign.id_campaign', $param['id_campaign']); }
		if (isset($param['id_login'])) { $this->where('isales_nasabah.t_log_share_campaign.id_login', $param['id_login']); }
		if (isset($param['created_at'])) { $this->where('isales_nasabah.t_log_share_campaign.created_at', $param['created_at']); }
		if (isset($param['created_by'])) { $this->where('isales_nasabah.t_log_share_campaign.created_by', $param['created_by']); }
		
		$this->select('user.nama, isales_nasabah.t_log_share_campaign.*');
		$this->join('isales.t_user user', 'user.id_login = isales_nasabah.t_log_share_campaign.id_login', 'left');
		if(isset($param['cardetail'])){ $this->select('isales_nasabah.t_campaign.campaign'); $this->join('t_campaign', 't_campaign.id = t_log_share_campaign.id_campaign', 'left'); }
		$this->orderBy('isales_nasabah.t_log_share_campaign.created_at', 'DESC');
		$query = $this->get();
		return $query;
	}

	public function addNew($data)
	{
		$datas['query'] = $this->insert($data);
		$datas['id'] = $this->insertID;
		
		return $datas;
	}

	public function editAble($id, $data)
	{
	    return $this->update($id, $data);
	}
}
