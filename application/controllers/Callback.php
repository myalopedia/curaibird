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
    public function callback_xendit_bayar()
    {
        $json = file_get_contents("php://input");
        
        $data = json_decode($json);

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        $where = array('xendit_id' => $data->callback_virtual_account_id, );
        $set = array('status' => 'PAID', 'paid_at' => date('Y-m-d H:i:s'));
        $this->M_admin->update_data('transaksi_xendit', $set, $where);
    }
    public function callback_xendit_status()
    {
        $json = file_get_contents("php://input");
        
        $data = json_decode($json);
        
        if( $data->status == 'ACTIVE' )
        {
            // pembayaran sukses, lanjutkan proses sesuai sistem Anda, contoh:
            $where = array('xendit_id' => $data->id, );
            $set = array('status' => $data->status, 'active_at' => date('Y-m-d H:i:s'));
            $this->M_admin->update_data('transaksi_xendit', $set, $where);
        }
        elseif( $data->status == 'INACTIVE' )
        {
            // pembayaran kadaluarsa, lanjutkan proses sesuai sistem Anda, contoh:
            $where = array('xendit_id' => $data->id, );
            $set = array('status' => $data->status, 'inactive_at' => date('Y-m-d H:i:s'));
            $this->M_admin->update_data('transaksi_xendit', $set, $where);
        }
        elseif( $data->status == 'PENDING' )
        {
            // pembayaran gagal, lanjutkan proses sesuai sistem Anda, contoh:
            $where = array('xendit_id' => $data->id, );
            $set = array('status' => $data->status, 'pending_at' => date('Y-m-d H:i:s'));
            $this->M_admin->update_data('transaksi_xendit', $set, $where);
        }
    }
}
