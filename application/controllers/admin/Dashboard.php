<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
		$role = $this->session->userdata('role');

		if ($role == 'admin') {
			return;
		} else {
			$this->session->set_flashdata('warning', "You Don't Have Access");
			redirect(base_url() . 'auth/login');
			return;
		}
	}



	public function index()
	{

		$data['title']			= 'Dashboard';
		$data['page_title']     = 'Dashboard - Admin KasirKu';
		$data['nav_title']      = 'dashboard';
		$data['detail_title']   = 'dashboard';

		//sales total based on month
		foreach (getMonth() as $key => $value) {
			$this->dashboard->table = 'transaction';
			$data['sales_report'][$key]    = $this->dashboard->where('MONTH(created_at)', $key)
				->where('YEAR(created_at)', date('Y'))
				->get();

			$this->dashboard->table = 'product_in';
			$data['product_in_report'][$key]		= $this->dashboard->where('MONTH(created_at)', $key)
				->where('YEAR(created_at)', date('Y'))->get();

			$this->dashboard->table = 'transaction_detail';
			$data['items_sales_report'][$key]	= $this->dashboard
				->joinTransaction('transaction')
				->where('MONTH(transaction.created_at)', $key)
				->where('YEAR(created_at)', date('Y'))->get();
		}

		//sales total
		$this->dashboard->table = 'transaction';
		$data['sumTotal']		= $this->dashboard->select([
			'total', 'purchase_price_total', 'subtotal'
		])->where('YEAR(created_at)', date('Y'))->get();

		//product_in total
		$this->dashboard->table = 'product_in';
		$data['product_in_total'] = $this->dashboard->select([
			'stock_in'
		])->where('YEAR(created_at)', date('Y'))->get();



		//items sales total
		$this->dashboard->table = 'transaction_detail';
		$data['items_sales_total'] = $this->dashboard->select([
			'SUM(transaction_detail.qty) AS total'
		])->joinTransaction('transaction')
			->where('YEAR(transaction.created_at)', date('Y'))->first();




		$data['page']    		= 'pages/admin/dashboard/index';
		$this->view($data);
	}

	public function sales_report($year = '')
	{
		foreach (getMonth() as $key => $value) {
			$data['sales_report'][$key]    = $this->dashboard->where('MONTH(created_at)', $key)
				->where('YEAR(created_at)', $year == '' ? date('Y') : $year)
				->get();
		}

		echo json_encode($data['sales_report']);

		//print_r($data['content']['03']);
		//echo array_sum(array_column($data['content']['3'], 'total'));
	}

	public function tes_lagi()
	{
		$arr = array();
		foreach (getMonth() as $key => $value) {
			$data['content'][$key]    = $this->dashboard->where('MONTH(created_at)', $key)
				->where('YEAR(created_at)', date('Y'))
				->get();
			array_push($arr, array_sum(array_column($data['content'][$key], 'total')));
		}

		echo array_sum($arr);
	}
}
