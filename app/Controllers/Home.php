<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$tm = time();
		if($tm % 2 == 0){
			return redirect()->to(base_url('landingpage/belanjaonline'));
		}else{
			return redirect()->to(base_url('landingpage/cangkirkopi'));
		}
	}

	//--------------------------------------------------------------------
}
