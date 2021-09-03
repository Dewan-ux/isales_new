<?php
namespace App\Models;

use CodeIgniter\Model;
class RecordingModel extends Model
{
    protected $table = 't_recording';
	protected $primaryKey = 'id';

	protected $allowedFields = ['download_hash', 'recording_file', 'extension', 'download', 'did', 'destination', 'direction', 'recording_date', 'created_at','start_date','end_date', 'list'];

	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('id', $param['id']); }
		if (isset($param['download_hash'])) { $this->where('download_hash', $param['download_hash']); }
		if (isset($param['recording_file'])) { $this->where('recording_file', $param['recording_file']); }
		if (isset($param['extension'])) { $this->like('extension', $param['extension'], 'before'); }
		if (isset($param['did'])) { $this->where('did', $param['did']); }
		if (isset($param['destination'])) { $this->where('destination', $param['destination']); }
		if (isset($param['direction'])) { $this->where('direction', $param['direction']); }
		if (isset($param['download'])) { $this->where('download', $param['download']); }
		if (isset($param['recording_date'])) { $this->where('recording_date', $param['recording_date']); }
		if (isset($param['created_at'])) { $this->where('created_at', $param['created_at']); }

		if(isset($param['list'])){
			if($param['list'] == '1'){
				$this->select('*');
				$this->where('t_recording.recording_date between "'.date('Y-m-d H:i:s', strtotime("$param[start_date] 00:00:00")).'" and "'.date('Y-m-d H:i:s', strtotime("$param[end_date] 23:59:59")).'"');
				$this->orderBy('created_at', 'DESC');

				$query = $this->get();
				return $query;
		 	}
		}
		if(isset($param['d_hash'])){
			$this->select('download_hash');
			$this->whereIn('download_hash', $param['d_hash']);
			
			$query = $this->get()->getResultArray();
			$array = array_map(function($value){
                        return $value['download_hash'];
                    } , $query);
			return $array;
		}
					
		$this->select('*');
		$query = $this->get();
		return $query;
	}

	public function list($recording_file, $recording_date)
	{
		$this->select('t.recording.recording_date, t_recording.recording_file');
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

}
