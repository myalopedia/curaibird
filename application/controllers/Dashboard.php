<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    function __construct() {
        parent::__construct();

		if($this->session->userdata('status') != 'login_admin') {
			redirect(base_url('login'));
		}

        $this->load->model('M_admin');
    }
	public function index()
	{
		$data['transaksi'] = $this->M_admin->select_query('SELECT sum(amount_received) as total_transaksi FROM transaksi WHERE status = "PAID"')->row_array();
		
		$data['transaksi_terakhir'] = $this->M_admin->select_query('SELECT * FROM transaksi WHERE status = "PAID" ORDER BY create_at DESC limit 1')->row_array();

		$mount_now = date('m');
		$int_mount_now = (int)$mount_now;
		for ($i=0; $i < 7; $i++) { 
			
			$date_select = date('d-m-Y',strtotime("-".$i." months"));
			$month_select = date('m', strtotime($date_select));
			$year_select = date('Y', strtotime($date_select));
			$transaksi_success_perbulan = $this->M_admin->select_query("SELECT sum(amount_received) as total_transaksi FROM transaksi WHERE status = 'PAID' AND MONTH(create_at) = '$month_select' AND YEAR(create_at) = '$year_select' AND delete_at IS NULL")->row_array();
			$transaksi_fail_perbulan = $this->M_admin->select_query("SELECT sum(amount_received) as total_transaksi FROM transaksi WHERE status != 'PAID' AND MONTH(create_at) = '$month_select' AND YEAR(create_at) = '$year_select' AND delete_at IS NULL")->row_array();
			$data['transaksi_success_perbulan'][$i] = $transaksi_success_perbulan['total_transaksi']? $transaksi_success_perbulan['total_transaksi'] : 0;
			$data['transaksi_fail_perbulan'][$i] = $transaksi_fail_perbulan['total_transaksi']? $transaksi_fail_perbulan['total_transaksi'] : 0;
			$data['transaksi_date'][$i] = $date_select;
		}
		// print_r($data['transaksi_success_perbulan']);
		// print_r($data['transaksi_fail_perbulan']);
		// print_r($data['transaksi_date']);

		$this->load->view('layouts/header');
		$this->load->view('dashboard', $data);
		$this->load->view('layouts/footer');
		$this->load->view('dashboard_script_component', $data);
	}
}
