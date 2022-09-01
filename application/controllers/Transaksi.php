<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		$this->load->library('tripay');
    }
	public function index()
	{
		$data['transaksi'] = $this->M_admin->select_select_where_orderBy('*', 'transaksi', array('delete_at' => null), 'create_at DESC')->result_array();

        $this->load->view('layouts/header');
		$this->load->view('transaksi', $data);
        $this->load->view('layouts/footer');
	}
    public function tambah() {
		$daftar_metode = $this->tripay->get_daftar_metode(false);
		$data['daftar_metode'] = json_decode($daftar_metode)->data;

		echo "<pre>";
		print_r($daftar_metode);
		echo "<pre>";

		$footer = array('page' => 'tambah_transaksi');
        $this->load->view('layouts/header');
        $this->load->view('transaksi_tambah', $data);
        $this->load->view('layouts/footer', $footer);
    }
	public function detail($id) {
		$data['transaksi'] = $this->M_admin->select_where('transaksi', array('id' => $id))->row_array();
		$get_transaksi_tripay = $this->tripay->detailTrasnaksi($data['transaksi']['reference']);

		$decode_transaksi_tripay = json_decode($get_transaksi_tripay);

		if($decode_transaksi_tripay->success) {
			$data['transaksi_tripay'] = $decode_transaksi_tripay->data;
		} else {
			$data['transaksi_tripay'] = null;
		}

		$data['barang_transaksi'] = $this->M_admin->select_where('produk_transaksi', array('reference_transaksi' => $data['transaksi']['reference']))->result_array();

		$this->load->view('layouts/header');
		$this->load->view('transaksi_detail', $data);
		$this->load->view('layouts/footer');
	}
	public function calculate_fee() {
		$post = $this->input->post();

		echo $this->tripay->calculate_total($post['metode'], $post['amount']);
	}
    public function tambah_aksi() {
        $post= $this->input->post();

		$amount = $post['sub_total_transaksi_input'];
		$barang = [];
		$barang_database = [];

		if($post['email_pembeli'] == null || $post['email_pembeli'] == '') {
			$email = 'xxx@gmail.com';
		} else {
			$email = $post['email_pembeli'];
		}

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

			array_push($barang, $data_barang_post);
			array_push($barang_database, $data_barang_post_database);
		}

		$tripay_authentication = $this->tripay->getAuthentication();

		$apiKey       = $tripay_authentication['apiKey'];
		$privateKey   = $tripay_authentication['privateKey'];
		$merchantCode = $tripay_authentication['merchantCode'];
		$merchantRef  = $tripay_authentication['merchantRef'];

		$generate_signature = $this->tripay->make_signature($amount);

		$data = [
			'method'         => $post['metode_pembayaran'],
			'merchant_ref'   => $merchantRef,
			'amount'         => $amount,
			'customer_name'  => $post['pembeli'],
			'customer_email' => $email,
			// 'order_items'    => [
			//     [
			//         'sku'         => 'FB-06',
			//         'name'        => 'Nama Produk 1',
			//         'price'       => 500000,
			//         'quantity'    => 1
			//     ],
			//     [
			//         'sku'         => 'FB-07',
			//         'name'        => 'Nama Produk 2',
			//         'price'       => 500000,
			//         'quantity'    => 1
			//     ]
			// ],
			'order_items' => $barang,
			'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
			'signature'    => $generate_signature
		];

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_FRESH_CONNECT  => true,
			CURLOPT_URL            => 'https://tripay.co.id/api/transaction/create',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
			CURLOPT_FAILONERROR    => false,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => http_build_query($data),
			CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
		]);

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);
		// print_r($response);
		if(empty($error)) {
			$decode_response = json_decode($response);
			$data = $decode_response->data;
			$data_database = array(
				'reference' => $data->reference,
				'payment_method' => $data->payment_method,
				'payment_name' => $data->payment_name,
				'customer_name' => $data->customer_name,
				'amount' => $data->amount,
				'fee_merchant' => $data->fee_merchant,
				'fee_customer' => $data->fee_customer,
				'total_fee' => $data->total_fee,
				'amount_received' => $data->amount_received,
				'pay_code' => $data->pay_code,
				'status' => $data->status,
				'expired_time' => $data->expired_time,
				'data_tripay' => $response,
				'create_at' => date('Y-m-d H:i:s'),
				'update_at' => date('Y-m-d H:i:s')
			);

			$barang_array_database = [];
			foreach ($barang_database as $a) {
				$array = $a;
				$array['reference_transaksi'] = $data->reference;
				$array['create_at'] = date('Y-m-d H:i:s');
				$array['update_at'] = date('Y-m-d H:i:s');
				array_push($barang_array_database, $array);
			}

			$this->M_admin->insert_data('transaksi', $data_database);

			$this->M_admin->insertBatch('produk_transaksi', $barang_array_database);

			redirect(base_url('transaksi'));
		}
		else {
			$error;
		}
    }
	function delete_transaksi($id) {
		$date = date('Y-m-d H:i:s');
		$this->M_admin->update_data('transaksi', array('delete_at' => $date), array('id' => $id));

		redirect(base_url('transaksi'));
	}
}
