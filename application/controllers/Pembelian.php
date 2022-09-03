<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;
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

		// Xendit::setApiKey('xnd_development_qrQTQB4rtO5eB0bEx8Lvmq10cYmkkd6Qa2dpnUcuHhRErAyHE8Pf4hYvaQ7vy5fL');
		Xendit::setApiKey('xnd_production_nkiWkvu5ozcX04Qrx6X1W1Dk0teEPJNhI9rP8qfwx2Cg30cCmKB28k3vrjAz');
    }
	public function pembelian($id)
	{
		$data['transaksi'] = $this->M_admin->select_where('transaksi_xendit', array('id' => $id))->row_array();
		$id_xendit = $data['transaksi']['xendit_id'];
		$get_transaksi_xendit = \Xendit\VirtualAccounts::retrieve($id_xendit);

		$data['transaksi_xendit'] = $get_transaksi_xendit;

		$data['barang_transaksi'] = $this->M_admin->select_where('produk_transaksi', array('reference_transaksi' => $id_xendit))->result_array();

		$this->load->view('pembelian', $data);
	}
	public function cara_pembayaran($metode, $noVA) {
		$cara = array(
			array(
				'bank' => 'BRI',
				'content' => array(
					array(
						'judul' => 'Tata Cara Membayar Melalui ATM',
						'step' => array(
							'Nasabah melakukan pembayaran melalui ATM Bank BRI',
							'Pilih Menu Transaksi Lain',
							'Pilih Menu Pembayaran',
							'Pilih Menu Lainnya',
							'Pilih Menu BRIVA',
							'Masukan 16 digit Nomor Virtual Account <b>'.$noVA.'</b>',
							'Proses Pembayaran (Ya/Tidak)',
							'Harap Simpan Struk Transaksi yang anda dapatkan'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui Mobile Banking BRI', 
						'step' => array(
							'Nasabah melakukan pembayaran melalui Mobile/SMS Banking BRI',
							'Nasabah memilih Menu Pembayaran melalui Menu Mobile/SMS Banking BRI',
							'Nasabah memilih Menu BRIVA',
							'Masukan 16 digit Nomor Virtual Account <b>'.$noVA.'</b>',
							'Masukan Jumlah Pembayaran sesuai Tagihan',
							'Masukan PIN Mobile/SMS Banking BRI',
							'Nasabah mendapat Notifikasi Pembayaran'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui Internet Banking BRI',
						'step' => array(
							'Nasabah melakukan pembayaran melalui Internet Banking BRI',
							'Nasabah memilih Menu Pembayaran',
							'Nasabah memilih Menu BRIVA',
							'Masukan Kode Bayar dengan 16 digit Nomor Virtual Account <b>'.$noVA.'</b>',
							'Masukan Password Internet Banking BRI',
							'Masukan mToken Internet Banking BRI',
							'Nasabah mendapat Notifikasi Pembayaran'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui ATM Bank Lain', 
						'step' => array(
							'Setelah memasukkan kartu ATM dan nomor PIN, pilih menu Transaksi Lainnya',
							'Pilih menu Transfer',
							'Pilih menu Ke Rek Bank Lain',
							'Masukan Kode Bank Tujuan : BRI (Kode Bank : <b>002</b>) lalu klik Benar',
							'Masukkan jumlah pembayaran sesuai tagihan. Klik Benar',
							'Masukan Nomor Virtual Account <b>'.$noVA.'</b>',
							'Pilih dari rekening apa pembayaran akan di-debet',
							'Sistem akan memverifikasi data yang dimasukkan. Pilih Benar untuk memproses pembayaran',
							'Harap Simpan Struk Transaksi yang anda dapatkan '
						)
					)
				),
			),
			array(
				'bank' => 'BCA',
				'content' => array(
					array(
						'judul' => 'Tata Cara Membayar Melalui Transfer ATM',
						'step' => array(
							'Masukkan kartu ke mesin ATM',
							'Masukkan 6 digit PIN Anda',
							'Pilih Transaksi Lainnya',
							'Pilih Transfer',
							'Lanjut ke ke Rekening BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account <b>'.$noVA.'</b>, kemudian tekan Benar',
							'Masukkan jumlah yang akan dibayarkan, selanjutnya tekan Benar',
							'Validasi pembayaran Anda. Pastikan semua detail transaksi yang ditampilkan sudah benar, kemudian pilih Ya',
							'Pembayaran Anda telah selesai. Tekan Tidak untuk menyelesaikan transaksi, atau tekan Ya untuk melakukan transaksi lainnya'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui BCA Mobile', 
						'step' => array(
							'Silahkan login pada aplikasi BCA Mobile',
							'Pilih m-BCA, lalu masukkan kode akses m-BCA',
							'Pilih m-Transfer',
							'Lanjut ke BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account <b>'.$noVA.'</b>, atau pilih dari Daftar Transfer',
							'Lalu, masukkan jumlah yang akan dibayarkan',
							'Masukkan PIN m-BCA Anda',
							'Transaksi telah berhasil'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui KlikBCA Pribadi',
						'step' => array(
							'Silahkan login pada aplikasi KlikBCA Individual',
							'Masukkan User ID dan PIN Anda',
							'Pilih Transfer Dana',
							'Pilih Transfer ke BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account Anda atau pilih dari Daftar Transfer',
							'Masukkan jumlah yang akan dibayarkan',
							'Validasi pembayaran. Pastikan semua datanya sudah benar, lalu masukkan kode yang diperoleh dari KEYBCA APPLI 1, kemudian klik Kirim'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui KlikBCA Bisnis', 
						'step' => array(
							'Setelah memasukkan kartu ATM dan nomor PIN, pilih menu Transaksi Lainnya',
							'Pilih menu Transfer',
							'Pilih menu Ke Rek Bank Lain',
							'Masukan Kode Bank Tujuan : BRI (Kode Bank : <b>002</b>) lalu klik Benar',
							'Masukkan jumlah pembayaran sesuai tagihan. Klik Benar',
							'Masukan Nomor Virtual Account <b>'.$noVA.'</b>',
							'Pilih dari rekening apa pembayaran akan di-debet',
							'Sistem akan memverifikasi data yang dimasukkan. Pilih Benar untuk memproses pembayaran',
							'Harap Simpan Struk Transaksi yang anda dapatkan '
						)
					)
				)
			),
			array(
				'bank' => 'BCA',
				'content' => array(
					array(
						'judul' => 'Tata Cara Membayar Melalui Transfer ATM',
						'step' => array(
							'Masukkan kartu ke mesin ATM',
							'Masukkan 6 digit PIN Anda',
							'Pilih Transaksi Lainnya',
							'Pilih Transfer',
							'Lanjut ke ke Rekening BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account <b>'.$noVA.'</b>, kemudian tekan Benar',
							'Masukkan jumlah yang akan dibayarkan, selanjutnya tekan Benar',
							'Validasi pembayaran Anda. Pastikan semua detail transaksi yang ditampilkan sudah benar, kemudian pilih Ya',
							'Pembayaran Anda telah selesai. Tekan Tidak untuk menyelesaikan transaksi, atau tekan Ya untuk melakukan transaksi lainnya'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui BCA Mobile', 
						'step' => array(
							'Silahkan login pada aplikasi BCA Mobile',
							'Pilih m-BCA, lalu masukkan kode akses m-BCA',
							'Pilih m-Transfer',
							'Lanjut ke BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account <b>'.$noVA.'</b>, atau pilih dari Daftar Transfer',
							'Lalu, masukkan jumlah yang akan dibayarkan',
							'Masukkan PIN m-BCA Anda',
							'Transaksi telah berhasil'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui KlikBCA Pribadi',
						'step' => array(
							'Silahkan login pada aplikasi KlikBCA Individual',
							'Masukkan User ID dan PIN Anda',
							'Pilih Transfer Dana',
							'Pilih Transfer ke BCA Virtual Account',
							'Masukkan nomor BCA Virtual Account Anda atau pilih dari Daftar Transfer',
							'Masukkan jumlah yang akan dibayarkan',
							'Validasi pembayaran. Pastikan semua datanya sudah benar, lalu masukkan kode yang diperoleh dari KEYBCA APPLI 1, kemudian klik Kirim'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui KlikBCA Bisnis', 
						'step' => array(
							'Setelah memasukkan kartu ATM dan nomor PIN, pilih menu Transaksi Lainnya',
							'Pilih menu Transfer',
							'Pilih menu Ke Rek Bank Lain',
							'Masukan Kode Bank Tujuan : BRI (Kode Bank : <b>002</b>) lalu klik Benar',
							'Masukkan jumlah pembayaran sesuai tagihan. Klik Benar',
							'Masukan Nomor Virtual Account <b>'.$noVA.'</b>',
							'Pilih dari rekening apa pembayaran akan di-debet',
							'Sistem akan memverifikasi data yang dimasukkan. Pilih Benar untuk memproses pembayaran',
							'Harap Simpan Struk Transaksi yang anda dapatkan '
						)
					)
				)
			)
		);
	}
}
