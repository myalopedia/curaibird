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
	 * So any other public methods not prefixed swith an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    function __construct() {
        parent::__construct();

        $this->load->model('M_admin');

		// Xendit::setApiKey('xnd_development_qrQTQB4rtO5eB0bEx8Lvmq10cYmkkd6Qa2dpnUcuHhRErAyHE8Pf4hYvaQ7vy5fL');
		// Xendit::setApiKey('xnd_production_VKgAwRomNiUoM8dPDamXPKY4QvI9xl9ATSj68ELzAvCO0OJyeSiIyKcvcjX65U');
    }
	public function pembelian($id)
	{
		$data['transaksi'] = $this->M_admin->select_where('transaksi_xendit', array('id' => $id))->row_array();
		$id_xendit = $data['transaksi']['xendit_id'];
		// $get_transaksi_xendit = \Xendit\VirtualAccounts::retrieve($id_xendit);

		// $data['transaksi_xendit'] = $get_transaksi_xendit;

		$cara = $this->cara_pembayaran($data['transaksi']['bank_code'], $data['transaksi']['account_number']);

		$data['cara_pembayaran'] = $cara;
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
				'bank' => 'BNI',
				'content' => array(
					array(
						'judul' => 'Tata Cara Membayar Melalui Transfer ATM',
						'step' => array(
							'Masukkan kartu, pilih bahasa kemudian ketikkan 6 digit PIN ATM',
							'Pilih menu Lainnya',
							'Pilih Transfer kemudian pilih jenis rekening yang akan digunakan (misalnya Tabungan)',
							'Pilih Virtual Account Billing kemudian masukkan 16 digit nomornya <b>'.$noVA.'</b>. Jumlah yang harus dibayar akan muncul pada layar konfirmasi',
							'Jika sudah sesuai, lanjutkan transaksi dan simpan bukti transfer.',
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui iBank Personal', 
						'step' => array(
							'Buka browser kemudian akses ibank.bni.co.id',
							'Isi kolom user ID dan password',
							'Pilih menu Transfer kemudian klik pada pilihan Virtual Account Billing',
							'Masukkan nomor virtual account yang dituju <b>'.$noVA.'</b>dan pilih rekening yang akan digunakan untuk membayar',
							'Jumlah tagihan akan muncul pada layar konfirmasi. Jika sudah cocok, lanjutkan transaksi',
							'Masukkan token atau Kode Otentikasi Token',
							'Jika transaksi sukses, Anda akan memperoleh notifikasi.'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui Mobile Banking BNI',
						'step' => array(
							'Buka aplikasi BNI Mobile Banking lewat ponsel',
							'Isi user ID dan password',
							'Setelah terbuka, pilih menu Transfer kemudian tekan pilihan Virtual Account Billing',
							'Pilih rekening debet kemudian pilih input baru dan masukkan 16 digit nomor virtual account <b>'.$noVA.'</b>',
							'Jumlah tagihan akan muncul di layar. Lakukan konfirmasi kemudian ketikkan Password Transaksi',
							'Pembayaran selesai.'
						)
					)
				)
			),
			array(
				'bank' => 'MANDIRI',
				'content' => array(
					array(
						'judul' => 'Tata Cara Membayar Melalui ATM',
						'step' => array(
							'Masukkan kartu ATM dan PIN ATM.',
							'Pilih menu Bayar/Beli.',
							'Pilih opsi Lainnya > Multipayment.',
							'Masukkan kode biller perusahaan (biasanya sudah tercantum di instruksi pembayaran).',
							'Masukkan nomor Virtual account <b>'.$noVA.'</b> > Benar.',
							'Masukkan angka yang diminta untuk memilih tagihan > Ya.',
							'Layar akan menampilkan konfirmasi. Jika sesuai, pilih Ya.',
							'Selesai.'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui M-Banking Mandiri', 
						'step' => array(
							'Buka aplikasi m-banking.',
							'Masukkan username dan password.',
							'Pilih menu Bayar > One Time > Multipayment.',
							'Pilih Penyedia Jasa yang digunakan > Lanjut.',
							'Masukkan nomor Virtual account <b>'.$noVA.'</b> > Lanjut.',
							'Layar akan menampilkan konfirmasi. Jika sudah sesuai, masukkan PIN transaksi dan akhiri dengan OK.',
							'Selesai.'
						)
					),
					array(
						'judul' => 'Tata Cara Membayar Melalui I-Banking Mandiri',
						'step' => array(
							'Akses situs Mandiri Online, masukkan username dan password.',
							'Pilih menu Pembayaran > Multipayment.',
							'Pilih Penyedia Jasa yang digunakan.',
							'Masukkan nomor virtual account <b>'.$noVA.'</b> Mandiri dan nominal yang akan dibayarkan > Lanjut.',
							'Layar akan menampilkan konfirmasi. Apabila sesuai, lanjutkan dengan pilih Konfirmasi.',
							'Masukkan PIN token.',
							'Selesai.'
						)
					)
				)
			),
			array(
				'bank' => 'BSI',
				'content' => array(
					array(
						'judul' => 'Cara Transfer Virtual Account BSI Via ATM',
						'step' => array(
							'Datang ke outlet ATM terdekat',
							'Masukkan kartu ke mesin ATM',
							'Pilih bahasa Indonesia',
							'Masukkan 6 digit PIN',
							'Pilih menu utama di pojok kanan bawah',
							'Tekan menu Pembelian/Pembayaran',
							'Klik menu e-Commerce',
							'Pilih layanannya ',
							'Masukkan nomor atau kode virtual account <b>'.$noVA.'</b>',
							'Tekan menu Benar',
							'Konfirmasi semua datanya',
							'Klik menu Benar',
							'Tunggu sampai pembayaran berhasil',
							'Ambil bukti pembayarannya'
						)
					),
					array(
						'judul' => 'Cara Transfer Virtual Account BSi via BSI Mobile', 
						'step' => array(
							'Siapkan perangkat Anda',
							'Pastikan koneksi internet tersedia',
							'Buka aplikasi BSI Mobile',
							'Tunggu sampai masuk ke halaman utama',
							'Pilih menu Bayar',
							'Pilih menu e-Commerce',
							'Ketikkan password transaksi',
							'Masukkan kode virtual account <b>'.$noVA.'</b>',
							'Tekan menu Selanjutnya',
							'Input 6 digit PIN BSI Mobile',
							'Tekan menu Selanjutnya',
							'Pastikan untuk melakukan konfirmasi semua datanya',
							'Usahakan nominal sudah sesuai',
							'Tekan menu selanjutnya',
							'Pembayaran berhasil ',
							'Selesai',
						)
					)
				)
			)
		);

		$key = array_search($metode, array_column($cara, 'bank'));
		$return_cara_pembayaran = $cara[$key];
		if(is_numeric($key)) {
			$return_cara_pembayaran = $cara[$key];
		} else {
			$return_cara_pembayaran = false;
		}
		return $return_cara_pembayaran;
	}
}
