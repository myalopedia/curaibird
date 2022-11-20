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
    public function __construct() {
        parent::__construct();

		if($this->session->userdata('status') != 'login_admin') {
			redirect(base_url('login'));
		}
		
        $this->load->model('M_admin');

		$this->apiKeyDuitku = "9d017fb7a781ac4f0ce2f9ca03bf4d0d";
		$this->merchantCode = "DS13568"; 


		// Xendit::setApiKey('xnd_development_qrQTQB4rtO5eB0bEx8Lvmq10cYmkkd6Qa2dpnUcuHhRErAyHE8Pf4hYvaQ7vy5fL');
		// Xendit::setApiKey('xnd_production_VKgAwRomNiUoM8dPDamXPKY4QvI9xl9ATSj68ELzAvCO0OJyeSiIyKcvcjX65U');
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
		// $data['daftar_metode'] = \Xendit\VirtualAccounts::getVABanks();
		$getPaymentMethod = $this->getPaymentMethod();
		$data['daftar_metode'] = $getPaymentMethod["paymentFee"];

		// $get_transaksi_tripay = \Xendit\VirtualAccounts::retrieve($id);

		// echo "<pre>";
		// print_r($data['daftar_metode']);
		// echo "<pre>";

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
		$getPaymentMethod = $this->getPaymentMethod();

		// $get_transaksi_tripay = \Xendit\VirtualAccounts::retrieve($id);

		// echo "<pre>";
		// print_r($getPaymentMethod);
		// echo "<pre>";
		// $data['transaksi_tripay'] = $get_transaksi_tripay;
		// $data['barang_transaksi'] = $this->M_admin->select_where('produk_transaksi', array('reference_transaksi' => $data['transaksi']['xendit_id']))->result_array();

		$this->load->view('layouts/header');
		$this->load->view('transaksi_detail', $data);
		$this->load->view('layouts/footer');
	}
	public function calculate_fee() {
		$post = $this->input->post();

		return $post['amount'];
	}

	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function getPaymentMethod() {
		$input = json_decode(file_get_contents("php://input"), true);

		$requestBody = array (
			'order' => array (
				"invoice_number" => "INV-12312312-0001",
        		"amount" => 10000
			),
			"virtual_account_info" => array (
				"billing_type" => "FIX_BILL",
				"expired_time" => 60,
				"reusable_status" => false,
				"info1" => "Merchant Demo Store",
			),
			'customer' => array (
				'name' => 'siti mahmudah',
        		"email" => "anton@example.com"
			),
		);

		$requestId = rand(1, 100000); // Change to UUID or anything that can generate unique value
		$dateTime = gmdate("Y-m-d H:i:s");
		$isoDateTime = date(DATE_ISO8601, strtotime($dateTime));
		$dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";
		$clientId = "BRN-0238-1665140904819"; // Change with your Client ID
		$secretKey = "SK-075ro3QSNKaKXE0JYhzI"; // Change with your Secret Key

		$getUrl = 'https://api.doku.com';

		$targetPath = '/bri-virtual-account/v2/payment-code';
		$url = $getUrl . $targetPath;

		// Generate digest
		$digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

		// Prepare signature component
		$componentSignature = "Client-Id:".$clientId ."\n".
							"Request-Id:".$requestId . "\n".
							"Request-Timestamp:".$dateTimeFinal ."\n".
							"Request-Target:".$targetPath ."\n".
							"Digest:".$digestValue;

		// Generate signature
		$signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

		// Execute request
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Client-Id:' . $clientId,
			'Request-Id:' . $requestId,
			'Request-Timestamp:' . $dateTimeFinal,
			'Signature:' . "HMACSHA256=" . $signature,
		));

		// Set response json
		$responseJson = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		// Echo the response
		if (is_string($responseJson) && $httpCode == 200) {
			echo $responseJson;
			return json_decode($responseJson, true);
		} else {
			echo $responseJson;
			return null;
		}
	}
	public function tambah_aksi() {
		$post= $this->input->post();
		$amount = $post['total_transaksi_input'];

		$merchantCode = $this->merchantCode; // dari duitku
		// $apiKey = '9d017fb7a781ac4f0ce2f9ca03bf4d0d'; // dari duitku
		$apiKey =  $this->apiKeyDuitku;
		$paymentAmount = $amount;
		$paymentMethod = $post['metode_pembayaran']; // VC = Credit Card
		$merchantOrderId = time() . ''; // dari merchant, unik
		$productDetails = 'Jasa Alopedia';
		$email = $post['pembeli'].'@gmail.com'; // email pelanggan anda
		$phoneNumber = '087721191226'; // nomor telepon pelanggan anda (opsional)
		$additionalParam = ''; // opsional
		$merchantUserInfo = ''; // opsional
		$customerVaName = $post['pembeli']; // tampilan nama pada tampilan konfirmasi bank
		$callbackUrl = 'http://example.com/callback'; // url untuk callback
		$returnUrl = 'http://example.com/return'; // url untuk redirect
		$expiryPeriod = 10; // atur waktu kadaluarsa dalam hitungan menit
		$signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

		// Customer Detail
		$firstName = "John";
		$lastName = "";

		// Address
		$alamat = "Jalan Boja - Limbangan";
		$city = "Kendal";
		$postalCode = "51381";
		$countryCode = "ID";

		$address = array(
			'firstName' => $firstName,
			'lastName' => $lastName,
			'address' => $alamat,
			'city' => $city,
			'postalCode' => $postalCode,
			'phone' => $phoneNumber,
			'countryCode' => $countryCode
		);

		$customerDetail = array(
			'firstName' => $firstName,
			'lastName' => $lastName,
			'email' => $email,
			'phoneNumber' => $phoneNumber,
			'billingAddress' => $address,
			'shippingAddress' => $address
		);


		$item1 = array(
			'name' => 'Jasa Alopedia',
			'price' => $paymentAmount,
			'quantity' => 1);

		$itemDetails = array(
			$item1
		);

		/*Khusus untuk metode pembayaran OL dan SL
		$accountLink = array (
			'credentialCode' => '7cXXXXX-XXXX-XXXX-9XXX-944XXXXXXX8',
			'ovo' => array (
				'paymentDetails' => array ( 
					0 => array (
						'paymentType' => 'CASH',
						'amount' => 40000,
					),
				),
			),
			'shopee' => array (
				'useCoin' => false,
				'promoId' => '',
			),
		);*/

		$params = array(
			'merchantCode' => $merchantCode,
			'paymentAmount' => $paymentAmount,
			'paymentMethod' => $paymentMethod,
			'merchantOrderId' => $merchantOrderId,
			'productDetails' => $productDetails,
			'additionalParam' => $additionalParam,
			'merchantUserInfo' => $merchantUserInfo,
			'customerVaName' => $customerVaName,
			'email' => $email,
			'phoneNumber' => $phoneNumber,
			// 'accountLink' => $accountLink,
			'itemDetails' => $itemDetails,
			'customerDetail' => $customerDetail,
			'callbackUrl' => $callbackUrl,
			'returnUrl' => $returnUrl,
			'signature' => $signature,
			'expiryPeriod' => $expiryPeriod
		);

		$params_string = json_encode($params);
		//echo $params_string;
		$url = 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'; // Sandbox
		// $url = 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'; // Production
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($params_string))                                                                       
		);   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		//execute post
		$request = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($httpCode == 200)
		{
			$result = json_decode($request, true);
			//header('location: '. $result['paymentUrl']);
			echo "paymentUrl :". $result['paymentUrl'] . "<br />";
			echo "merchantCode :". $result['merchantCode'] . "<br />";
			echo "reference :". $result['reference'] . "<br />";
			echo "vaNumber :". $result['vaNumber'] . "<br />";
			echo "amount :". $result['amount'] . "<br />";
			echo "statusCode :". $result['statusCode'] . "<br />";
			echo "statusMessage :". $result['statusMessage'] . "<br />";

			$data_database = array(
				'xendit_id' => $result['reference'],
				'external_id' => $result['reference'],
				'bank_code' => $paymentMethod,
				'merchant_code' => $result['merchantCode'],
				'account_number' => $result['vaNumber'],
				'name' =>  $customerVaName,
				'currency' => 'IDR',
				'is_single_use' => 0,
				'is_closed' => 0,
				'expected_amount' => $result['amount'],
				'suggested_amount' => $result['amount'],
				'expiration_date' => $expiryPeriod,
				'description' => json_encode($itemDetails),
				'status' => $result['statusCode'],
				'data_xendit' => json_encode($result),
				'pending_at' => date('Y-m-d H:i:s'),
				'create_at' => date('Y-m-d H:i:s'),
				'update_at' => date('Y-m-d H:i:s')
			);

			$this->M_admin->insert_data('transaksi_xendit', $data_database);

			redirect(base_url('transaksi'));
		}
		else
		{
			$request = json_decode($request);
			$error_message = "Server Error " . $httpCode ." ". $request->Message;
			echo $error_message;
		}
	}
    public function tambah_aksi_xendit() {
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
		// foreach ($post['nama_barang'] as $key => $value) {
		// 	$cek_database_barang = $this->M_admin->select_query("SELECT * FROM produk WHERE nama LIKE '%".$value."%'")->row_array();
		// 	if($cek_database_barang != null) {
		// 		$id = $cek_database_barang['id'];
		// 		$sku = $cek_database_barang['sku'];
		// 		$nama_barang = $cek_database_barang['nama'];
		// 	}
		// 	else {
		// 		$cek_max_id = $this->M_admin->select_query("SELECT max(id) as id FROM produk")->row_array();

		// 		if($cek_max_id != null) {
		// 			$id = $cek_max_id['id']+1;
		// 			$sku_baru = 'B-'.$id;
		// 		} else {
		// 			$id = 1;
		// 			$sku_baru = 'B-1';
		// 		}

		// 		$this->M_admin->insert_data('produk', array('sku' => $sku_baru, 'nama' => $post['nama_barang'][$key]));
				
		// 		$sku = $sku_baru;
		// 		$nama_barang = $post['nama_barang'][$key];
		// 	}

		// 	$data_barang_post = array(
		// 		'sku'         => $sku,
		// 		'name'        => $nama_barang,
		// 		'price'       => $post['harga_barang'][$key],
		// 		'quantity'    => $post['qty_barang'][$key]
		// 	);

		// 	$data_barang_post_database = array(
		// 		'id_produk'   => $id,
		// 		'sku'         => $sku,
		// 		'nama'        => $nama_barang,
		// 		'harga'       => $post['harga_barang'][$key],
		// 		'qty'    => $post['qty_barang'][$key]
		// 	);
		// 	$nama_barang_description .= $nama_barang.', ';

		// 	array_push($barang, $data_barang_post);
		// 	array_push($barang_database, $data_barang_post_database);
		// }

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
					"description"		=> $post['pembeli']
				];
				break;
		}

		// echo "<pre>";
		// print_r($params);
		// echo "</pre>";

		$createVA = \Xendit\VirtualAccounts::create($params);

		// echo "<pre>";
		// print_r($createVA);
		// echo "</pre>";
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

			// $this->M_admin->insertBatch('produk_transaksi', $barang_array_database);

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
