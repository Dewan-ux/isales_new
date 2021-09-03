<?php namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $landingPage = [
		'nama' => 'required|alpha_space',
		'telepon' => 'required|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]',
	];

	public $landingPage_errors = [
		'telepon' => [
			'regex_match' => 'Must be valid phone number start with +62 / 0 followed by 9 or more numbers at Phone Number 1',
		]
	];

	public $createUserM = [
		'username' => 'required|alpha_numeric|min_length[5]|is_unique[t_login.username]',
		'password' => 'required|regex_match[/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/]'
	];

	public $updaetUserM = [
		'username' => 'required|alpha_numeric|min_length[5]|',
		'password' => 'required|regex_match[/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/]'
	];

	public $changePassword = [
		'curr_password' => 'required|regex_match[/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/]',
		'new_password' => 'required|regex_match[/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/]',
		'con_password' => 'required|regex_match[/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/]|matches[new_password]',
	];

	public $changePassword_errors = [
		'curr_password' => [
			'regex_match' => 'Password must contain 8 or more characters and atleast 1 number, 1 special character, 1 uppercase and 1 lowercase letter.'
		],
		'new_password' => [
			'regex_match' => 'Password must contain 8 or more characters and atleast 1 number, 1 special character, 1 uppercase and 1 lowercase letter.'
		],
		'con_password' => [
			'regex_match' => 'Password must contain 8 or more characters and atleast 1 number, 1 special character, 1 uppercase and 1 lowercase letter.',
			'matches' => 'Confirm Password not same with your new password.'
		]
	];

	public $createSpaj = [
		'id_data_nasabah' => 'required|integer',
		'voice_record'		=> 'permit_empty|valid_base64',
		'nama'		=> 'required|alpha_space|min_length[5]',
		'tempat_lahir'		=> 'required|alpha_space|min_length[3]',
		'tgl_lahir'		=> 'required|valid_date[Y-m-d]',
		'alamat'		=> 'required|min_length[5]',
		'kota'		=> 'required|min_length[3]',
		'provinsi'		=> 'required|min_length[3]',
		'pos'		=> 'required|integer|max_length[5]|min_length[5]',
		'jk'		=> 'required|alpha_space|max_length[1]',
		'agama'		=> 'required|alpha_space|min_length[4]',
		'NIK'		=> 'required|integer|min_length[16]|max_length[16]',
		'NPWP'		=> 'permit_empty|integer|min_length[16]',
		'telp1' => 'required|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'telp2' => 'permit_empty|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'telp_rumah'=> 'permit_empty|min_length[8]',
		'telp_kantor'=> 'permit_empty|min_length[8]',
		'fax'		=> 'permit_empty|min_length[8]',
		'telp_cp'	=> 'permit_empty|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'status'	=> 'required|in_list[0,1,2]',
		'pekerjaan' => 'required',
		'bank' => 'required',
		'card_number' => 'required',
		'expired_date' 		=> 'required|valid_date[m/y]',
		'wali_nama'		=> 'required|alpha_space|min_length[5]',
		'wali_nama2'		=> 'permit_empty|alpha_space|min_length[5]',
		'wali_nama3'		=> 'permit_empty|alpha_space|min_length[5]',
		'wali_hubungan'		=> 'required|integer',
		'wali_hubungan2'		=> 'permit_empty|integer',
		'wali_hubungan3'		=> 'permit_empty|integer',
		'wali_tempat_lahir'		=> 'required|alpha_space|min_length[3]',
		'wali_tempat_lahir2'		=> 'permit_empty|alpha_space|min_length[3]',
		'wali_tempat_lahir3'		=> 'permit_empty|alpha_space|min_length[3]',
		'wali_tgl_lahir'		=> 'required|valid_date[Y-m-d]',
		'wali_tgl_lahir2'		=> 'permit_empty|valid_date[Y-m-d]',
		'wali_tgl_lahir3'		=> 'permit_empty|valid_date[Y-m-d]',
		'wali_jk'		=> "required|in_list[L,P,O]",
		'wali_jk2'		=> "permit_empty|in_list[L,P,O]",
		'wali_jk3'		=> "permit_empty|in_list[L,P,O]",
		'wali_NIK'		=> 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_NIK2'		=> 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_NIK3'		=> 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_telp1'		=> 'permit_empty|min_length[8]',
		'wali2_telp1'		=> 'permit_empty|min_length[8]',
		'wali3_telp1'		=> 'permit_empty|min_length[8]',
		'wali_telp2'		=> 'permit_empty|min_length[8]',
		'wali2_telp2'		=> 'permit_empty|min_length[8]',
		'wali3_telp2'		=> 'permit_empty|min_length[8]',
		'wali_status'		=> 'required|in_list[0,1,2]',
		'wali2_status'		=> 'permit_empty|in_list[0,1,2]',
		'wali3_status'		=> 'permit_empty|in_list[0,1,2]',
		'berat_badan'		=> 'integer|max_length[4]',
		'tinggi_badan'		=> 'integer|max_length[4]',
		'id_produk'		=> 'required|integer',
		'id_premi'		=> 'required|integer',
 		'pertanyaan'	=> [
			'id_pertanyaan' => 'required',
			'jawaban' => 'required',
			'remark' => 'required',
		]
	];

	public $createSpaj_errors = [

	];

	public $createSpaj_Pa = [
		'id_data_nasabah' => 'required|integer',
		'voice_record'        => 'permit_empty|valid_base64',
		'nama'        => 'required|alpha_space|min_length[5]',
		'tempat_lahir'        => 'required|alpha_space|min_length[3]',
		'tgl_lahir'        => 'required|valid_date[Y-m-d H:i:s]',
		'alamat'        => 'required|min_length[5]',
		'kota'        => 'required|min_length[3]',
		'provinsi'        => 'required|min_length[3]',
		'pos'        => 'required|integer|max_length[5]|min_length[5]',
		'jk'        => 'required|alpha_space|max_length[1]',
		'agama'        => 'required|alpha_space|min_length[4]',
		'NIK'        => 'required|integer|min_length[16]|max_length[16]',
		'NPWP'        => 'permit_empty|integer|min_length[16]',
		'telp1' => 'required|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'telp2' => 'permit_empty|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'telp_rumah' => 'permit_empty|min_length[8]',
		'telp_kantor' => 'permit_empty|min_length[8]',
		'fax'        => 'permit_empty|min_length[8]',
		'telp_cp'    => 'permit_empty|regex_match[/^(^\+62?|^0)(?=.{9,}$)([0-9]).*$/]|min_length[8]',
		'status'    => 'required|in_list[0,1,2]',
		'pekerjaan' => 'required',
		'bank' => 'required',
		'card_number' => 'required',
		'expired_date'         => 'required|valid_date[m/y]',
		'wali_nama'        => 'required|alpha_space|min_length[5]',
		'wali_nama2'        => 'permit_empty|alpha_space|min_length[5]',
		'wali_nama3'        => 'permit_empty|alpha_space|min_length[5]',
		'wali_hubungan'        => 'required|integer',
		'wali_hubungan2'        => 'permit_empty|integer',
		'wali_hubungan3'        => 'permit_empty|integer',
		'wali_tempat_lahir'        => 'required|alpha_space|min_length[3]',
		'wali_tempat_lahir2'        => 'permit_empty|alpha_space|min_length[3]',
		'wali_tempat_lahir3'        => 'permit_empty|alpha_space|min_length[3]',
		'wali_tgl_lahir'        => 'required|valid_date[Y-m-d]',
		'wali_tgl_lahir2'        => 'permit_empty|valid_date[Y-m-d]',
		'wali_tgl_lahir3'        => 'permit_empty|valid_date[Y-m-d]',
		'wali_jk'        => "required|in_list[L,P,O]",
		'wali_jk2'        => "permit_empty|in_list[L,P,O]",
		'wali_jk3'        => "permit_empty|in_list[L,P,O]",
		'wali_NIK'        => 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_NIK2'        => 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_NIK3'        => 'permit_empty|integer|min_length[16]|max_length[16]',
		'wali_telp1'        => 'permit_empty|min_length[8]',
		'wali2_telp1'        => 'permit_empty|min_length[8]',
		'wali3_telp1'        => 'permit_empty|min_length[8]',
		'wali_telp2'        => 'permit_empty|min_length[8]',
		'wali2_telp2'        => 'permit_empty|min_length[8]',
		'wali3_telp2'        => 'permit_empty|min_length[8]',
		'wali_status'        => 'required|in_list[0,1,2]',
		'wali2_status'        => 'permit_empty|in_list[0,1,2]',
		'wali3_status'        => 'permit_empty|in_list[0,1,2]',
		'id_produk'        => 'required|integer',
		'id_premi'        => 'required|integer',
	];

	public $createUserM_errors = [
		'username' => [
			'is_unique'	=> 'Username Already Exists!',
			'alpha_numeric' => 'Username must contain only alpha numeric characters',
			'min_length' => 'Username Must contain 5 or more characters'
		],
		'password' => [
			'regex_match' => 'Password must contain 8 or more characters and atleast 1 number, 1 special character, 1 uppercase and 1 lowercase letter.'
		]
	];

	public $login = [
		'username' => 'required',
		// 'email'    => 'required',
		'password' => 'required'
	];


	public $logout = [
		'token' => 'required',
		'status' => 'required|in_list[0,2,3,4]'
	];


	public $logout_errors = [
		'status' => [
			'in_list' => 'Value must be one of, 0, 2, 3, 4. 0=Selesai/Offline, 2=Toilet, 3=Istirahat, 4=Shalat'
		]
	];

	public $authenticate = [
		'token' => 'required'
	];


	// MAIN

		// LEADER

			public $reset_share = [
				'status' => 'required|in_list[2,4,5,6,8,11]',
			];

			public $json_status = [
				'json_status' => 'required|valid_json',
			];

			public $phone_format = [
				'telepon' => 'regex_match[/^(^\+62?|^\62?|^0)(?=.{9,}$)([0-9]).*$/]',

			];
			
			public $doshareleads = [
				'tsr_id' => 'required|integer',
				'share' => 'required|integer'
			];

			public $share_campaign = [
				'limit' => 'required|integer',
				'id_login' => 'required|integer',
				'id_campaign' => 'required|integer'
			];

			public $tsrId = [
				'tsr_id' => 'required|integer'
			];

			public $dashboard = [
				'token'		=> 'required',
				'filter'		=> 'permit_empty',
			];

			public $listcampaign = [
				'start_date'		=> 'required|valid_date[Y-m-d]',
				'end_date'		=> 'required|valid_date[Y-m-d]',
			];

			public $download_recording = [
				'start_date'		=> 'required|valid_date[Y-m-d]',
				'end_date'		=> 'required|valid_date[Y-m-d]',
			];

			public $downloadziprecording = [
			'date' => 'required|valid_date[Y-m-d]'
			];

			public $performanceById = [
				'token'		=> 'required',
				'filter'		=> 'permit_empty',
				'tsr_id'		=> 'required|integer',
			];

			public $performance = [
				'token'		=> 'required',
				'filter'		=> 'permit_empty',
			];

			public $reqInterfrensi = [
				'token_call' => 'required',
			];

			public $interfrensi = [
				'token_call' => 'required',
				'status' => 'required|in_list[2,3]'
			];

			public $endInterfrensi = [
				'token_call' => 'required',
			];

			public $export = [
				'start_date' => 'required|valid_date[Y-m-d]',
				'end_date' => 'required|valid_date[Y-m-d]',
			];

			public $reporting = [
				'token' => 'required',
			];

			public $salesPdf = [
				'token' => 'required',
			];

			public $reporting_apr = [
				'token' => 'required',
				'start_date' => 'required|valid_date[Y-m-d]',
				'end_date' => 'required|valid_date[Y-m-d]',
				'tsr_ids' => 'permit_empty',
			];

			public $recordingExt = [
				'extension'		=> 'required|min_length[6]',
				'destination'		=> 'required|min_length[8]',
			];

			public $uploadVa = [
				'batch_va' => 'required',
			];

			public $upload_campaign = [
				'batch_campaign' => 'required',
			];

			public $campaignFileUpload = [
				'campaign_file'         => 'uploaded[campaign_file]|ext_in[campaign_file,xls,xlsx]|max_size[campaign_file,10000]',
			];

			public $campaignFileUpload_errors = [
				'campaign_file'=> [
					'ext_in'    => 'File Excel hanya boleh diisi dengan xls atau xlsx.',
					'max_size'  => 'File Excel product maksimal 10mb',
					'uploaded'  => 'File Excel product wajib diisi'
				]
			];

			public $vaFileUpload = [
				'va_file'         => 'uploaded[va_file]|ext_in[va_file,xls,xlsx]|max_size[va_file,2000]',
			];

			public $vaFileUpload_errors = [
				'va_file'=> [
					'ext_in'    => 'File Excel hanya boleh diisi dengan xls atau xlsx.',
					'max_size'  => 'File Excel product maksimal 1mb',
					'uploaded'  => 'File Excel product wajib diisi'
				]
			];

			public $exportCsv_errors = [

			];

		//TSR

			public $startConfirmInterest = [
				'nasabah_id' => 'required|integer',
				'telepon' => 'required',
				'id_spaj' => 'required|integer'
			];
			public $calling = [
				'nasabah_id' => 'required|integer',
				'telepon' => 'required',
			];

			public $endConfirmInterest = [
				'token_ci' => 'required',
				'remark' => 'required',
			];

			public $endCall = [
				'token_call' => 'required',
				'status'	 => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]'
			];

			public $endCall_errors = [
				'status' => [
					'in_list' => 'Value must be one of, 1,2,3,4,5,6,7,8. 0= Baru; 1= SUKSES SPAJ; 2= Telepon Kembali; 3= Salah Sambung; 4= Tidak di Angkat; 5= Diangkat Orang Lain; 6= Di Angkat Tidak Ada Suara; 7= Nomor Tidak Valid; 8= Gagal Order;'
				]
			];

			public $leads = [
				'nasabah_id' => 'required',
			];
			// ADMIN
			public $payment = [
				'token' => 'required',
				'id' 	=> 'required|integer'
			];

			public $forceLogout = [
				'password' => 'required',
				'id' => 'required|integer'
			];

			public $premi = [
				'token'	 => 'required',
				'id'	 => 'required|integer'
			];

			public $premis = [
				'token'	 => 'required',
				'id_premi'	 => 'required'
			];
			public $sales = [
				'token' => 'required',
				'id' => 'required|integer'
			];
			public $berita = [
				'token' => 'required',
				'id' => 'required|integer'
			];
			public $tags = [
				'token' => 'required',
				'id' => 'required|integer'
			];
			public $createPayment = [
				'token'		=> 'required',
				'payment'		=> 'required|alpha_space|min_length[5]',
			];
			public $updatePayment = [
				'token'		=> 'required',
				'id'		=> 'required',
				'payment'	=> 'required|alpha_space|min_length[5]',
			];
			public $deletePayment = [
				'token'			=> 'required',
				'id'	=> 'required',
			];

			public $createCmsLandingPage = [
				'token'		=> 'required',
				'foto_brosur'=> 'required|valid_base64',
				'foto_banner'=> 'required|valid_base64',
			];
			public $updateCmsLandingPage = [
				'token'		=> 'required',
				'id'		=> 'required',
				'foto_brosur'=> 'required|valid_base64',
				'foto_banner'=> 'required|valid_base64',
			];
			public $deleteCmsLandingPage = [
				'token'			=> 'required',
				'id'	=> 'required',
			];

			public $createProduk = [
				'token'			=> 'required',
				'nama_produk'	=> 'required|alpha_space|min_length[5]',
			];
			public $updateProduk = [
				'token'			=> 'required',
				'id'			=> 'required',
				'nama_produk'	=> 'required|alpha_space|min_length[5]',
			];
			public $deleteProduk = [
				'token'	=> 'required',
				'id'	=> 'required',
			];
			
			public $tambahPremi = [
				'token'   		   => 'required',
				'nominal' 		   => 'required',
				'satuan'  		   => 'required|in_list[Bulanan,Tahunan]',
				'id_produk_pa_car' => 'required',
				'manfaat' => 'required',
			];

			public $ubahPremi = [
				'token'		=> 'required',
				'nominal'	=> 'required',
				'id_produk_pa_car' => 'required',
				'satuan'	=> 'required|in_list[Bulanan,Tahunan]',
			];
			
			public $createPremi = [
				'token'		=> 'required',
				'nominal'	=> 'required',
				'satuan'	=> 'required|in_list[Bulanan,Tahunan]',
				'id_produk'	=> 'required',
				'kategori'	=> 'required|in_list[1,2,3,4,5,6,7]',
			];
			public $updatePremi = [
				'token'		=> 'required',
				'nominal'	=> 'required',
				'kategori'	=> 'required|in_list[1,2,3,4,5,6,7]',
				'satuan'	=> 'required|in_list[Bulanan,Tahunan]',
				'id_produk'	=> 'required',
			];

			public $deletePremi = [
				'token'	=> 'required',
				'id'	=> 'required',
			];

			public $deleteAuth = [
				'token'		=>	'required',
				'id'		=>	'required',
			];

			public $updateAuthGroup = [
				'token'		=>	'required',
				'tsr_id'		=>	'required|integer',
				'leader_id'		=>	'permit_empty|integer',
			];

			public $createSales = [
				'token'	=> 'required',
				'pdf'	=> 'permit_empty|valid_base64'
			];
			public $updateSales = [
				'token'			=> 'required',
				'id'			=> 'required',
				'pdf'			=> 'permit_empty|valid_base64'
			];
			public $deleteSales = [
				'token'	=> 'required',
				'id'	=> 'required',
			];

			public $createBerita = [
				'token'		=> 'required',
				'judul'		=> 'required',
				'isi'		=> 'required',
				'kategori'	=> 'required',
				'id_tags'		=> 'required',
				'foto'		=> 'permit_empty|valid_base64',
			];

			public $updateBerita = [
				'token'		=> 'required',
				'id'		=> 'required',
				'judul'		=> 'required',
				'isi'		=> 'required',
				'kategori'	=> 'required',
				'id_tags'		=> 'required',
				'foto'		=> 'permit_empty|valid_base64',
			];

			public $deleteBerita = [
				'token'			=> 'required',
				'id'	=> 'required',
			];

			public $createTags = [
				'token'	=> 'required',
				'tags'	=> 'required'
			];

			public $updateTags = [
				'token'	=> 'required',
				'id'	=> 'required',
				'tags'	=> 'required'
			];

			public $deleteTags = [
				'token'	=> 'required',
				'id'	=> 'required',
			];

			public $user = [
				'token' => 'required',
				'id' => 'required|integer'
			];

			public $produk = [
				'token' => 'required',
				'id' => 'required|integer'
			];

			public $demoUser = [
				'length'		=> 'required|integer',
				'role'		=> 'required|in_list[1,2,3,4,5]',
				'group'		=> 'permit_empty|integer'
			];

			public $createUser = [
				'token'		=> 'required',
				'username'	=> 'required|alpha_numeric|min_length[5]|is_unique[t_login.username]',
				'nama'		=> 'required|alpha_space|min_length[5]',
				'jk'		=> 'required|in_list[L,P]',
				'foto'		=> 'permit_empty|valid_base64',
				'role'		=> 'required|in_list[1,2,3,4,5]'
			];

			public $createUser_errors = [
				'username' => [
					'is_unique'	=> 'Username Already Exists!',
					'alpha_numeric' => 'Username must contain only alpha numeric characters',
					'min_length' => 'Must contain 5 or more characters'
				],
				'nama' => [
					'min_length' => 'Must contain 5 or more characters'
				],
				'jk' => [
					'in_list' => 'Value must be one of L or P. L for male P for female'
				],
				'role'	=> [
					'in_list' => 'Invalid role!'
				]
			];

			public $updateUser = [
				'token'		=> 'required',
				'username'	=> 'required|alpha_numeric|min_length[5]',
				'nama'		=> 'required|alpha_space|min_length[5]',
				'jk'		=> 'required|in_list[L,P]',
				'foto'		=> 'permit_empty|valid_base64',
				'role'		=> 'required|in_list[1,2,3,4,5]'
			];

			public $updateUser_errors = [
				'username' => [
					'alpha_numeric' => 'Username must contain only alpha numeric characters',
					'min_length' => 'Must contain 5 or more characters'
				],
				'nama' => [
					'min_length' => 'Must contain 5 or more characters'
				],
				'jk' => [
					'in_list' => 'Value must be one of L or P. L for male P for female'
				],
				'role'	=> [
					'in_list' => 'Invalid role!'
				]
			];

			
		//QA
		public $checkSpaj = [
			'id' => 'required|integer',
			'token' => 'required',
			'checked' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]',
			'remark' => 'permit_empty'
		];


		public $recordingListSpaj = [
			'telp'		=> 'required|min_length[8]',
		];

		public $listOrders = [
			'tsr_id' => 'required|integer',
			'token' => 'required',
		];

		//Berita
		public $calon_nasabah = [
			'ip' => 'required',
		];

		public $limit_berita = [
			'ip' => 'required',
			'limit' => 'required|integer',
		];

		public $berita_id = [
			'ip' => 'required',
			'id' => 'required',
		];

	//PABX
	public $pabxPusher = [
		'extension' => 'required',
		'destination' => 'required',
		'method' => 'required',
		'sip_code' => 'required',
		'progress_time' => 'required'
	];

}

