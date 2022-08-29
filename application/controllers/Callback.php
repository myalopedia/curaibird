<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends CI_Controller {

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

        $this->load->model('M_admin');
		$this->load->library('tripay');
    }
	public function index()
	{
		$data['transaksi'] = $this->M_admin->select_all('transaksi')->result_array();

        $this->load->view('layouts/header');
		$this->load->view('transaksi', $data);
        $this->load->view('layouts/footer');
	}
    public function callback_tripay()
    {
        $auth = $this->tripay->getAuthentication();
        $privateKey = $auth['privateKey'];

        // ambil data JSON
        $json = file_get_contents("php://input");

        // ambil callback signature
        $callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';

        // generate signature untuk dicocokkan dengan X-Callback-Signature
        $signature = hash_hmac('sha256', $json, $privateKey);

        // validasi signature
        if( $callbackSignature !== $signature ) {
            exit("Invalid Signature"); // signature tidak valid, hentikan proses
        }

        $data = json_decode($json);
        $event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

        print_r($data);

        if( $event == 'payment_status' )
        {

            // lakukan validasi status
            if( $data->status == 'PAID' )
            {
                // pembayaran sukses, lanjutkan proses sesuai sistem Anda, contoh:
                $where = array('reference' => $data->reference, );
                $set = array('status' => $data->status, 'paid_at' => date('Y-m-d H:i:s'));
                $this->M_admin->update_data('transaksi', $set, $where);
            }
            elseif( $data->status == 'EXPIRED' )
            {
                // pembayaran kadaluarsa, lanjutkan proses sesuai sistem Anda, contoh:
                $where = array('reference' => $data->reference, );
                $set = array('status' => $data->status, 'expired_at' => date('Y-m-d H:i:s'));
                $this->M_admin->update_data('transaksi', $set, $where);
            }
            elseif( $data->status == 'FAILED' )
            {
                // pembayaran gagal, lanjutkan proses sesuai sistem Anda, contoh:
                $where = array('reference' => $data->reference, );
                $set = array('status' => $data->status, 'failed_at' => date('Y-m-d H:i:s'));
                $this->M_admin->update_data('transaksi', $set, $where);
            }
        }
    }
}
