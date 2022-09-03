<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;
class Transaksi extends CI_Controller {

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

		// Xendit::setApiKey('xnd_development_qrQTQB4rtO5eB0bEx8Lvmq10cYmkkd6Qa2dpnUcuHhRErAyHE8Pf4hYvaQ7vy5fL');
		Xendit::setApiKey('xnd_production_nkiWkvu5ozcX04Qrx6X1W1Dk0teEPJNhI9rP8qfwx2Cg30cCmKB28k3vrjAz');
		$this->load->library('tripay');
		// $this->load->library('xenditLib');
    }
	public function index()
	{
		$data['transaksi'] = $this->M_admin->select_select_where_orderBy('*', 'transaksi_xendit', array('delete_at' => null), 'create_at DESC')->result_array();
		
		// echo "<pre>";
		// print_r($data['transaksi']);
		// echo "</pre>";

        $this->load->view('layouts/header');
		$this->load->view('transaksi', $data);
        $this->load->view('layouts/footer');
	}
    public function tambah() {
		$data['daftar_metode'] = \Xendit\VirtualAccounts::getVABanks();

		// echo "<pre>";
		// print_r($data['daftar_metode']);
		// echo "</pre>";

		$footer = array('page' => 'tambah_transaksi');
        $this->load->view('layouts/header');
        $this->load->view('transaksi_tambah', $data);
        $this->load->view('layouts/footer', $footer);
    }
    public function cek_ip() {
		$daftar_metode = $this->tripay->get_daftar_metode(false);

		echo "<pre>";
		print_r($daftar_metode);
		echo "<pre>";
    }
	public function detail($id) {
		$data['transaksi'] = $this->M_admin->select_where('transaksi_xendit', array('id' => $id))->row_array();
		$id = $data['transaksi']['xendit_id'];
		$get_transaksi_tripay = \Xendit\VirtualAccounts::retrieve($id);

		// echo "<pre>";
		// print_r($get_transaksi_tripay);
		// echo "<pre>";
		$data['transaksi_tripay'] = $get_transaksi_tripay;
		$data['barang_transaksi'] = $this->M_admin->select_where('produk_transaksi', array('reference_transaksi' => $data['transaksi']['xendit_id']))->result_array();

		$this->load->view('layouts/header');
		$this->load->view('transaksi_detail', $data);
		$this->load->view('layouts/footer');
	}
	public function calculate_fee() {
		$post = $this->input->post();

		return $post['amount'];
	}
    public function tambah_aksi() {
        $post= $this->input->post();

		$amount = $post['total_transaksi_input'];
		$barang = [];
		$barang_database = [];

		if($post['email_pembeli'] == null || $post['email_pembeli'] == '') {
			$email = 'xxx@gmail.com';
		} else {
			$email = $post['email_pembeli'];
		}

		$nama_barang_description = '';
		foreach ($post['nama_barang'] as $key => $value) {
			$cek_database_barang = $this->M_admin->select_query("SELECT * FROM produk WHERE nama LIKE '%".$value."%'")->row_array();
			if($cek_database_barang != null) {
				$id = $cek_database_barang['id'];
				$sku = $cek_database_barang['sku'];
				$nama_barang = $cek_database_barang['nama'];
			}
			else {
				$cek_max_id = $this->M_admin->select_query("SELECT max(id) as id FROM produk")->row_array();

				if($cek_max_id != null) {
					$id = $cek_max_id['id']+1;
					$sku_baru = 'B-'.$id;
				} else {
					$id = 1;
					$sku_baru = 'B-1';
				}

				$this->M_admin->insert_data('produk', array('sku' => $sku_baru, 'nama' => $post['nama_barang'][$key]));
				
				$sku = $sku_baru;
				$nama_barang = $post['nama_barang'][$key];
			}

			$data_barang_post = array(
				'sku'         => $sku,
				'name'        => $nama_barang,
				'price'       => $post['harga_barang'][$key],
				'quantity'    => $post['qty_barang'][$key]
			);

			$data_barang_post_database = array(
				'id_produk'   => $id,
				'sku'         => $sku,
				'nama'        => $nama_barang,
				'harga'       => $post['harga_barang'][$key],
				'qty'    => $post['qty_barang'][$key]
			);
			$nama_barang_description .= $nama_barang.', ';

			array_push($barang, $data_barang_post);
			array_push($barang_database, $data_barang_post_database);
		}

		switch ($post['metode_pembayaran']) {
			case 'BCA' :
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
					"suggested_amount" 	=> $amount
				];
				break;
			case 'PERMATA' :
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'BNI':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'MANDIRI':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'SAHABAT_SAMPOERNA':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'CIMB':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'BSI':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			case 'DBS':
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
				];
				break;
			default:
				$params = [ 
					"external_id" 		=> "va-".date('YmdHis'),
					"bank_code" 		=> $post['metode_pembayaran'],
					"name" 				=> $post['pembeli'],
					"is_single_use" 	=> true,
					"is_closed"			=> true,
					"expected_amount"	=> $amount,
					"suggested_amount" 	=> $amount,
					"description"		=> $nama_barang_description
				];
				break;
		}

		echo "<pre>";
		print_r($params);
		echo "</pre>";

		$createVA = \Xendit\VirtualAccounts::create($params);

		echo "<pre>";
		print_r($createVA);
		echo "</pre>";
		$data_xendit = json_encode($createVA);

		if(!isset($createVA['error_code'])) {
			$data_database = array(
				'xendit_id' => $createVA['id'],
				'external_id' => $createVA['external_id'],
				'bank_code' => $createVA['bank_code'],
				'merchant_code' => $createVA['merchant_code'],
				'account_number' => $createVA['account_number'],
				'name' => $createVA['name'],
				'currency' => $createVA['currency'],
				'is_single_use' => $createVA['is_single_use'],
				'is_closed' => $createVA['is_closed'],
				'expected_amount' => isset($createVA['expected_amount']) ? $createVA['expected_amount'] : NULL,
				'suggested_amount' => isset($createVA['suggested_amount']) ? $createVA['suggested_amount'] : NULL,
				'expiration_date' => $createVA['expiration_date'],
				'description' => isset($createVA['description']) ? $createVA['description'] : '',
				'status' => $createVA['status'],
				'data_xendit' => $data_xendit,
				'pending_at' => date('Y-m-d H:i:s'),
				'create_at' => date('Y-m-d H:i:s'),
				'update_at' => date('Y-m-d H:i:s')
			);

			$barang_array_database = [];
			foreach ($barang_database as $a) {
				$array = $a;
				$array['reference_transaksi'] = $createVA['id'];
				$array['create_at'] = date('Y-m-d H:i:s');
				$array['update_at'] = date('Y-m-d H:i:s');
				array_push($barang_array_database, $array);
			}

			$this->M_admin->insert_data('transaksi_xendit', $data_database);

			$this->M_admin->insertBatch('produk_transaksi', $barang_array_database);

			redirect(base_url('transaksi'));
		}
		else {
			$error;
		}
    }
	function delete_transaksi($id) {
		$date = date('Y-m-d H:i:s');
		$this->M_admin->update_data('transaksi_xendit', array('delete_at' => $date), array('id' => $id));

		redirect(base_url('transaksi'));
	}
}
