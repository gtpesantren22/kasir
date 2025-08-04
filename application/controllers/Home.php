<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
        if (!$this->Auth_model->current_user()) {
            redirect('login/logout');
        }

        $this->load->model('Modeldata', 'model');
        $this->tahun = $this->model->getBy('settings', 'namaset', 'tahun')->row('isiset');
    }

    public function index()
    {
        $data['user'] = $this->Auth_model->current_user();
        $bp = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();
        $setor = $this->model->getBySum('setoran', 'tahun', $this->tahun, 'nominal')->row();

        $data['masuk'] = $bp->jml + $setor->jml;
        $data['keluar'] = $this->model->getBySum('pengeluaran', 'tahun', $this->tahun, 'nominal')->row();
        $data['saldo'] = $data['masuk'] - $data['keluar']->jml;
        $data['tahun'] = $this->tahun;

        $this->load->view('head');
        $this->load->view('index', $data);
        $this->load->view('foot');
    }
    public function gantiTahun()
    {
        $tahun = $this->input->post('tahun', true);
        $this->model->edit('settings', 'namaset', 'tahun', ['isiset' => $tahun]);
        redirect('home');
    }
}
