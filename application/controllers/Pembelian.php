<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

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
	public function pembelian($id)
	{
		$data['transaksi'] = $this->M_admin->select_where('transaksi', array('id' => $id))->row_array();
		$get_transaksi_tripay = $this->tripay->detailTrasnaksi($data['transaksi']['reference']);

		$decode_transaksi_tripay = json_decode($get_transaksi_tripay);

		$data['transaksi_tripay'] = $decode_transaksi_tripay->data;

		$data['barang_transaksi'] = $this->M_admin->select_where('produk_transaksi', array('reference_transaksi' => $data['transaksi']['reference']))->result_array();

		$this->load->view('pembelian', $data);
	}
}
