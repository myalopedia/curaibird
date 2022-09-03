<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;
class Pelanggan extends CI_Controller {

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
		$this->load->library('tripay');
		Xendit::setApiKey('xnd_development_qrQTQB4rtO5eB0bEx8Lvmq10cYmkkd6Qa2dpnUcuHhRErAyHE8Pf4hYvaQ7vy5fL');
		// $this->load->library('xenditLib');
    }
	public function index()
	{
		$data['pelanggan'] = $this->M_admin->select_all('pelanggan')->result_array();

        $this->load->view('layouts/header');
		$this->load->view('pelanggan', $data);
        $this->load->view('layouts/footer');
	}
    public function tambah() {
		$data['list_bank'] = \Xendit\VirtualAccounts::getVABanks();

		// echo "<pre>";
		// print_r($data['list_bank']);
		// echo "<pre>";

		$footer = array('page' => 'tambah_transaksi');
        $this->load->view('layouts/header');
        $this->load->view('pelanggan_tambah', $data);
        $this->load->view('layouts/footer', $footer);
    }
    public function tambah_aksi() {
		$cek_id = $this->M_admin->select_query("SELECT max(id) as max_id FROM pelanggan")->row_array();

		if($cek_id != null) {
			$max_id = $cek_id['max_id']+1;
		}
		else {
			$max_id = 1;
		}

		$params = [ 
			"external_id" => "va-123",
			"bank_code" => "BRI",
			"name" => $post[''],
		];

		$createVA = \Xendit\VirtualAccounts::create($params);
		var_dump($createVA);

		return $createVA;
    }
	function delete_transaksi($id) {
		$date = date('Y-m-d H:i:s');
		$this->M_admin->update_data('transaksi', array('delete_at' => $date), array('id' => $id));

		redirect(base_url('transaksi'));
	}
}
