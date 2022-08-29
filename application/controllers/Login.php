<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
    }
	public function index()
	{
		$this->load->view('login');
	}
    
    public function aksi_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['username' => $username])->row_array();
		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		if ($user) {
            if (password_verify($password, $user['password'])) {
                $data = [
                    'status' => 'login_admin',
                    'nama' => $user['nama'],
                    'username' => $user['username'],
                    'level' => $user['level']
                ];

                $this->session->set_userdata($data);
                redirect(base_url('dashboard'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password salah!</div>');
            }
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User tidak terdaftar!</div>');
		}

		// redirect('admin/login');
    }
    function logout()
	{
		
		$this->session->sess_destroy();
		$this->session->set_flashdata('success', "Berhasil Logout dari akun");
		redirect(base_url('login'));
	}
}
