<?php

namespace App\Models;

use CodeIgniter\Model;

class SpajModel extends Model
{
    protected $table = 't_spaj';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_data_nasabah', 'no_proposal', 'id_virtual_account', 'voice_record', 'nama', 'tempat_lahir',
        'tgl_lahir', 'alamat', 'kota', 'jns_asuransi',
        'provinsi', 'pos', 'jk', 'agama', 'berat_badan', 'tinggi_badan',
        'NIK', 'NPWP', 'telp1',
        'telp2', 'telp_rumah', 'telp_kantor',
        'fax', 'telp_cp', 'status', 'id_spaj',
        'pekerjaan', 'card_number', 'bank', 'expired_date',
        'wali_nama', 'wali_nama2', 'wali_nama3', 'wali_hubungan', 'wali_hubungan2', 'wali_hubungan3',
        'wali_tempat_lahir', 'wali_tempat_lahir2', 'wali_tempat_lahir3',
        'wali_tgl_lahir', 'wali_tgl_lahir2', 'wali_tgl_lahir2',
        'wali_jk', 'wali_jk2', 'wali_jk3', 'wali_NIK', 'wali_NIK2', 'wali_NIK3',
        'wali_telp1', 'wali2_telp1', 'wali3_telp1', 'wali_telp2', 'wali2_telp2', 'wali3_telp2',
        'wali_status', 'wali_status2', 'wali_status3',
        'checked', 'checked_by', 'id_produk', 'id_premi', 'id_payment',
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
  
	public function getAll($param = array())
	{
		if (isset($param['id'])) { $this->where('t_spaj.id', $param['id']); }
		if (isset($param['id_data_nasabah'])) { $this->where('t_spaj.id_data_nasabah', $param['id_data_nasabah']); }
		if (isset($param['no_polis'])) { $this->where('t_spaj.no_polis', $param['no_polis']); }
		if (isset($param['no_proposal'])) { $this->where('t_spaj.no_proposal', $param['no_proposal']); }
        if (isset($param['voice_record'])) { $this->where('t_spaj.voice_record', $param['voice_record']); }
		if (isset($param['nama'])) { $this->where('t_spaj.nama', $param['nama']); }
		if (isset($param['tempat_lahir'])) { $this->where('t_spaj.tempat_lahir', $param['tempat_lahir']); }
		if (isset($param['tgl_lahir'])) { $this->where('t_spaj.tgl_lahir', $param['assitgl_lahirgned_at']); }
		if (isset($param['alamat'])) { $this->where('t_spaj.alamat', $param['alamat']); }
		if (isset($param['kota'])) { $this->where('t_spaj.kota', $param['kota']); }
		if (isset($param['provinsi'])) { $this->where('t_spaj.provinsi', $param['provinsi']); }
		if (isset($param['pos'])) { $this->where('t_spaj.pos', $param['pos']); }
		if (isset($param['jk'])) { $this->where('t_spaj.jk', $param['jk']); }
		if (isset($param['agama'])) { $this->where('t_spaj.agama', $param['agama']); }
		if (isset($param['berat_badan'])) { $this->where('t_spaj.berat_badan', $param['berat_badan']); }
		if (isset($param['tinggi_badan'])) { $this->where('t_spaj.tinggi_badan', $param['tinggi_badan']); }
		if (isset($param['NIK'])) { $this->where('t_spaj.NIK', $param['NIK']); }
        if (isset($param['NPWP'])) { $this->where('t_spaj.NPWP', $param['NPWP']); }
        if (isset($param['telp1'])) { $this->where('t_spaj.telp1', $param['telp1']); }
        if (isset($param['telp2'])) { $this->where('t_spaj.telp2', $param['telp2']); }
        if (isset($param['telp_rumah'])) { $this->where('t_spaj.telp_rumah', $param['telp_rumah']); }
        if (isset($param['telp_kantor'])) { $this->where('t_spaj.telp_kantor', $param['telp_kantor']); }
        if (isset($param['fax'])) { $this->where('t_spaj.fax', $param['fax']); }
        if (isset($param['telp_cp'])) { $this->where('t_spaj.telp_cp', $param['telp_cp']); }
        if (isset($param['status'])) { $this->where('t_spaj.status', $param['status']); }
        if (isset($param['pekerjaan'])) { $this->where('t_spaj.pekerjaan', $param['pekerjaan']); }
        if (isset($param['card_number'])) { $this->where('t_spaj.card_number', $param['card_number']); }
        if (isset($param['expired_date'])) { $this->where('t_spaj.expired_date', $param['expired_date']); }
        if (isset($param['bank'])) { $this->where('t_spaj.bank', $param['bank']); }
        if (isset($param['wali_nama'])) { $this->where('t_spaj.wali_nama', $param['wali_nama']); }
        if (isset($param['wali_nama2'])) { $this->where('t_spaj.wali_nama2', $param['wali_nama2']); }
        if (isset($param['wali_nama3'])) { $this->where('t_spaj.wali_nama3', $param['wali_nama3']); }
        if (isset($param['wali_hubungan'])) { $this->where('t_spaj.wali_hubungan', $param['wali_hubungan']); }
        if (isset($param['wali_hubungan2'])) { $this->where('t_spaj.wali_hubungan2', $param['wali_hubungan2']); }
        if (isset($param['wali_hubungan3'])) { $this->where('t_spaj.wali_hubungan3', $param['wali_hubungan3']); }
        if (isset($param['wali_tempat_lahir'])) { $this->where('t_spaj.wali_tempat_lahir', $param['wali_tempat_lahir']); }
        if (isset($param['wali_tempat_lahir2'])) { $this->where('t_spaj.wali_tempat_lahir2', $param['wali_tempat_lahir2']); }
        if (isset($param['wali_tempat_lahir3'])) { $this->where('t_spaj.wali_tempat_lahir3', $param['wali_tempat_lahir3']); }
        if (isset($param['wali_tgl_lahir'])) { $this->where('t_spaj.wali_tgl_lahir', $param['wali_tgl_lahir']); }
        if (isset($param['wali_tgl_lahir2'])) { $this->where('t_spaj.wali_tgl_lahir2', $param['wali_tgl_lahir2']); }
        if (isset($param['wali_tgl_lahir3'])) { $this->where('t_spaj.wali_tgl_lahir3', $param['wali_tgl_lahir3']); }
        if (isset($param['wali_jk'])) { $this->where('t_spaj.wali_jk', $param['wali_jk']); }
        if (isset($param['wali_jk2s'])) { $this->where('t_spaj.wali_jk2', $param['wali_jk2']); }
        if (isset($param['wali_jk3'])) { $this->where('t_spaj.wali_jk3', $param['wali_jk3']); }
        if (isset($param['wali_NIK'])) { $this->where('t_spaj.wali_NIK', $param['wali_NIK']); }
        if (isset($param['wali_NIK2'])) { $this->where('t_spaj.wali_NIK2', $param['wali_NIK2']); }
        if (isset($param['wali_NIK3'])) { $this->where('t_spaj.wali_NIK3', $param['wali_NIK3']); }
        if (isset($param['wali_telp1'])) { $this->where('t_spaj.wali_telp1', $param['wali_telp1']); }
        if (isset($param['wali2_telp1'])) { $this->where('t_spaj.wali2_telp1', $param['wali2_telp1']); }
        if (isset($param['wali3_telp1'])) { $this->where('t_spaj.wali3_telp1', $param['wali3_telp1']); }
        if (isset($param['wali_telp2'])) { $this->where('t_spaj.wali_telp2', $param['wali_telp2']); }
        if (isset($param['wali2_telp2'])) { $this->where('t_spaj.wali2_telp2', $param['wali2_telp2']); }
        if (isset($param['wali3_telp2'])) { $this->where('t_spaj.wali3_telp2', $param['wali3_telp2']); }
        if (isset($param['wali_status'])) { $this->where('t_spaj.wali_status', $param['wali_status']); }
        if (isset($param['wali_status2'])) { $this->where('t_spaj.wali_status2', $param['wali_status2']); }
        if (isset($param['wali_status3'])) { $this->where('t_spaj.wali_status3', $param['wali_status3']); }
        if (isset($param['id_produk'])) { $this->where('t_spaj.id_produk', $param['id_produk']); }
        if (isset($param['id_premi'])) { $this->where('t_spaj.id_premi', $param['id_premis']); }
        if (isset($param['id_payment'])) { $this->where('t_spaj.id_payment', $param['id_payment']); }
        if (isset($param['checked'])) { $this->whereIn('t_spaj.checked', $param['checked']); }
        if (isset($param['checked_by'])) { $this->whereIn('t_spaj.checked_by', $param['checked_by']); }
        if (isset($param['created_at'])) { $this->where('t_spaj.created_at', $param['created_at']); }
		

        if(isset($param['id_login'])){
            $this->select("t_user.nama as nama_tsr, t_spaj.id AS id_spaj, t_data_nasabah.id as id_nasabah,  t_spaj.telp1,  t_spaj.nama, t_spaj.created_at, t_spaj.checked, t_spaj.voice_record, IF(t_spaj.checked = concat(12, ''), (SELECT t_log_confirm_interest.remark FROM t_log_confirm_interest WHERE id_spaj = t_spaj.id ORDER BY t_log_confirm_interest.call_end_at DESC LIMIT 1), NULL) as remark_ci");
            $this->join('t_login', 't_login.id = t_spaj.created_by');
            $this->join('t_user', 't_user.id_login = t_login.id');
            $this->join('t_data_nasabah', 't_data_nasabah.id = t_spaj.id_data_nasabah');
            $this->where('t_spaj.created_by', $param['id_login']);
            $this->orderBy('t_spaj.created_at', 'DESC');


            $query = $this->get();
            return $query;
        }
        if (isset($param['detail'])) {
            if ($param['detail'] == 3) {
                $this->select('t_login.id as tsr_id, t_data_nasabah.id as id_data_nasabah, 
                    t_spaj.*, t_virtual_account.no_spaj, t_virtual_account.virtual_account, t_produk.id as id_produk, t_produk.nama_produk,
                    t_premi.id as id_premi, t_premi.nominal, t_premi.satuan, ' .
                    // 't_payment.id as id_payment, t_payment.payment,'.
                    '(SELECT t_log_fup_spaj.remark FROM t_log_fup_spaj 
                WHERE t_log_fup_spaj.status = t_spaj.checked 
                AND t_spaj.id = t_log_fup_spaj.id_spaj ORDER BY t_log_fup_spaj.created_at DESC LIMIT 1) as remark,' . "
                IF(t_spaj.checked = concat(12, ''), (SELECT t_log_confirm_interest.remark FROM t_log_confirm_interest WHERE id_spaj = t_spaj.id ORDER BY t_log_confirm_interest.call_end_at DESC LIMIT 1), NULL) as remark_ci");
                $this->join('t_data_nasabah', 't_data_nasabah.id = t_spaj.id_data_nasabah');
                $this->join('t_login', 't_login.id = t_data_nasabah.assigned_to');
                $this->join('t_virtual_account', 't_virtual_account.id = t_spaj.id_virtual_account');
                $this->join('t_produk', 't_produk.id = t_spaj.id_produk');
                $this->join('t_premi', 't_premi.id = t_spaj.id_premi');
                // $this->join('t_payment', 't_payment.id = t_spaj.id_payment');
                $query = $this->get();
                return $query;
            }
        }

        if (isset($param['max'])) {
            $this->selectMax(strval($param['max']));
            $query = $this->get();
            return $query;
        }
    }

    public function getAllPa($param = array())
    {
        if (isset($param['id'])) {
            $this->where('t_spaj.id', $param['id']);
        }
        if (isset($param['id_data_nasabah'])) {
            $this->where('t_spaj.id_data_nasabah', $param['id_data_nasabah']);
        }
        if (isset($param['no_polis'])) {
            $this->where('t_spaj.no_polis', $param['no_polis']);
        }
        if (isset($param['no_proposal'])) {
            $this->where('t_spaj.no_proposal', $param['no_proposal']);
        }
        if (isset($param['voice_record'])) {
            $this->where('t_spaj.voice_record', $param['voice_record']);
        }
        if (isset($param['nama'])) {
            $this->where('t_spaj.nama', $param['nama']);
        }
        if (isset($param['tempat_lahir'])) {
            $this->where('t_spaj.tempat_lahir', $param['tempat_lahir']);
        }
        if (isset($param['tgl_lahir'])) {
            $this->where('t_spaj.tgl_lahir', $param['assitgl_lahirgned_at']);
        }
        if (isset($param['alamat'])) {
            $this->where('t_spaj.alamat', $param['alamat']);
        }
        if (isset($param['kota'])) {
            $this->where('t_spaj.kota', $param['kota']);
        }
        if (isset($param['provinsi'])) {
            $this->where('t_spaj.provinsi', $param['provinsi']);
        }
        if (isset($param['pos'])) {
            $this->where('t_spaj.pos', $param['pos']);
        }
        if (isset($param['jk'])) {
            $this->where('t_spaj.jk', $param['jk']);
        }
        if (isset($param['agama'])) {
            $this->where('t_spaj.agama', $param['agama']);
        }
        if (isset($param['berat_badan'])) {
            $this->where('t_spaj.berat_badan', $param['berat_badan']);
        }
        if (isset($param['tinggi_badan'])) {
            $this->where('t_spaj.tinggi_badan', $param['tinggi_badan']);
        }
        if (isset($param['NIK'])) {
            $this->where('t_spaj.NIK', $param['NIK']);
        }
        if (isset($param['NPWP'])) {
            $this->where('t_spaj.NPWP', $param['NPWP']);
        }
        if (isset($param['telp1'])) {
            $this->where('t_spaj.telp1', $param['telp1']);
        }
        if (isset($param['telp2'])) {
            $this->where('t_spaj.telp2', $param['telp2']);
        }
        if (isset($param['telp_rumah'])) {
            $this->where('t_spaj.telp_rumah', $param['telp_rumah']);
        }
        if (isset($param['telp_kantor'])) {
            $this->where('t_spaj.telp_kantor', $param['telp_kantor']);
        }
        if (isset($param['fax'])) {
            $this->where('t_spaj.fax', $param['fax']);
        }
        if (isset($param['telp_cp'])) {
            $this->where('t_spaj.telp_cp', $param['telp_cp']);
        }
        if (isset($param['status'])) {
            $this->where('t_spaj.status', $param['status']);
        }
        if (isset($param['pekerjaan'])) {
            $this->where('t_spaj.pekerjaan', $param['pekerjaan']);
        }
        if (isset($param['card_number'])) {
            $this->where('t_spaj.card_number', $param['card_number']);
        }
        if (isset($param['expired_date'])) {
            $this->where('t_spaj.expired_date', $param['expired_date']);
        }
        if (isset($param['bank'])) {
            $this->where('t_spaj.bank', $param['bank']);
        }
        if (isset($param['wali_nama'])) {
            $this->where('t_spaj.wali_nama', $param['wali_nama']);
        }
        if (isset($param['wali_nama2'])) {
            $this->where('t_spaj.wali_nama2', $param['wali_nama2']);
        }
        if (isset($param['wali_nama3'])) {
            $this->where('t_spaj.wali_nama3', $param['wali_nama3']);
        }
        if (isset($param['wali_hubungan'])) {
            $this->where('t_spaj.wali_hubungan', $param['wali_hubungan']);
        }
        if (isset($param['wali_hubungan2'])) {
            $this->where('t_spaj.wali_hubungan2', $param['wali_hubungan2']);
        }
        if (isset($param['wali_hubungan3'])) {
            $this->where('t_spaj.wali_hubungan3', $param['wali_hubungan3']);
        }
        if (isset($param['wali_tempat_lahir'])) {
            $this->where('t_spaj.wali_tempat_lahir', $param['wali_tempat_lahir']);
        }
        if (isset($param['wali_tempat_lahir2'])) {
            $this->where('t_spaj.wali_tempat_lahir2', $param['wali_tempat_lahir2']);
        }
        if (isset($param['wali_tempat_lahir3'])) {
            $this->where('t_spaj.wali_tempat_lahir3', $param['wali_tempat_lahir3']);
        }
        if (isset($param['wali_tgl_lahir'])) {
            $this->where('t_spaj.wali_tgl_lahir', $param['wali_tgl_lahir']);
        }
        if (isset($param['wali_tgl_lahir2'])) {
            $this->where('t_spaj.wali_tgl_lahir2', $param['wali_tgl_lahir2']);
        }
        if (isset($param['wali_tgl_lahir3'])) {
            $this->where('t_spaj.wali_tgl_lahir3', $param['wali_tgl_lahir3']);
        }
        if (isset($param['wali_jk'])) {
            $this->where('t_spaj.wali_jk', $param['wali_jk']);
        }
        if (isset($param['wali_jk2s'])) {
            $this->where('t_spaj.wali_jk2', $param['wali_jk2']);
        }
        if (isset($param['wali_jk3'])) {
            $this->where('t_spaj.wali_jk3', $param['wali_jk3']);
        }
        if (isset($param['wali_NIK'])) {
            $this->where('t_spaj.wali_NIK', $param['wali_NIK']);
        }
        if (isset($param['wali_NIK2'])) {
            $this->where('t_spaj.wali_NIK2', $param['wali_NIK2']);
        }
        if (isset($param['wali_NIK3'])) {
            $this->where('t_spaj.wali_NIK3', $param['wali_NIK3']);
        }
        if (isset($param['wali_telp1'])) {
            $this->where('t_spaj.wali_telp1', $param['wali_telp1']);
        }
        if (isset($param['wali2_telp1'])) {
            $this->where('t_spaj.wali2_telp1', $param['wali2_telp1']);
        }
        if (isset($param['wali3_telp1'])) {
            $this->where('t_spaj.wali3_telp1', $param['wali3_telp1']);
        }
        if (isset($param['wali_telp2'])) {
            $this->where('t_spaj.wali_telp2', $param['wali_telp2']);
        }
        if (isset($param['wali2_telp2'])) {
            $this->where('t_spaj.wali2_telp2', $param['wali2_telp2']);
        }
        if (isset($param['wali3_telp2'])) {
            $this->where('t_spaj.wali3_telp2', $param['wali3_telp2']);
        }
        if (isset($param['wali_status'])) {
            $this->where('t_spaj.wali_status', $param['wali_status']);
        }
        if (isset($param['wali_status2'])) {
            $this->where('t_spaj.wali_status2', $param['wali_status2']);
        }
        if (isset($param['wali_status3'])) {
            $this->where('t_spaj.wali_status3', $param['wali_status3']);
        }
        if (isset($param['id_produk'])) {
            $this->where('t_spaj.id_produk', $param['id_produk']);
        }
        if (isset($param['id_premi'])) {
            $this->where('t_spaj.id_premi', $param['id_premis']);
        }
        if (isset($param['id_payment'])) {
            $this->where('t_spaj.id_payment', $param['id_payment']);
        }
        if (isset($param['checked'])) {
            $this->whereIn('t_spaj.checked', $param['checked']);
        }
        if (isset($param['checked_by'])) {
            $this->whereIn('t_spaj.checked_by', $param['checked_by']);
        }
        if (isset($param['created_at'])) {
            $this->where('t_spaj.created_at', $param['created_at']);
        }

        $this->where('jns_asuransi', 1);

        if (isset($param['id_login'])) {
            $this->select("t_user.nama as nama_tsr, t_spaj.id AS id_spaj, t_data_nasabah.id as id_nasabah,  t_spaj.telp1,  t_spaj.nama, t_spaj.created_at, t_spaj.checked, t_spaj.voice_record, IF(t_spaj.checked = concat(12, ''), (SELECT t_log_confirm_interest.remark FROM t_log_confirm_interest WHERE id_spaj = t_spaj.id ORDER BY t_log_confirm_interest.call_end_at DESC LIMIT 1), NULL) as remark_ci");
            $this->join('t_login', 't_login.id = t_spaj.created_by');
            $this->join('t_user', 't_user.id_login = t_login.id');
            $this->join('t_data_nasabah', 't_data_nasabah.id = t_spaj.id_data_nasabah');
            $this->where('t_spaj.created_by', $param['id_login']);
            $this->orderBy('t_spaj.created_at', 'DESC');


            $query = $this->get();
            return $query;
        }

        if (isset($param['detail'])) {
            if ($param['detail'] == 3) {
                $this->select('t_login.id as tsr_id, t_data_nasabah.id as id_data_nasabah, 
                    t_spaj.*, t_virtual_account.no_spaj, t_virtual_account.virtual_account, t_produk_pa_car.id as id_produk, t_produk_pa_car.nama_produk,
                    t_premi_pa_car.id as id_premi, t_premi_pa_car.nominal, t_premi_pa_car.satuan, ' .
                    // 't_payment.id as id_payment, t_payment.payment,'.
                    '(SELECT t_log_fup_spaj.remark FROM t_log_fup_spaj 
                WHERE t_log_fup_spaj.status = t_spaj.checked 
                AND t_spaj.id = t_log_fup_spaj.id_spaj ORDER BY t_log_fup_spaj.created_at DESC LIMIT 1) as remark,' . "
                IF(t_spaj.checked = concat(12, ''), (SELECT t_log_confirm_interest.remark FROM t_log_confirm_interest WHERE id_spaj = t_spaj.id ORDER BY t_log_confirm_interest.call_end_at DESC LIMIT 1), NULL) as remark_ci");
                $this->join('t_data_nasabah', 't_data_nasabah.id = t_spaj.id_data_nasabah');
                $this->join('t_login', 't_login.id = t_data_nasabah.assigned_to');
                $this->join('t_virtual_account', 't_virtual_account.id = t_spaj.id_virtual_account');
                $this->join('t_produk_pa_car', 't_produk_pa_car.id = t_spaj.id_produk');
                $this->join('t_premi_pa_car', 't_premi_pa_car.id = t_spaj.id_premi');
                // $this->join('t_payment', 't_payment.id = t_spaj.id_payment');
                $query = $this->get();
                return $query;
            }
        }

        if (isset($param['max'])) {
            $this->selectMax(strval($param['max']));
            $query = $this->get();
            return $query;
        }
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
