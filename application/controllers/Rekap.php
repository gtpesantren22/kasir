<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekap extends CI_Controller
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
    }

    public function harian()
    {
        $data['user'] = $this->Auth_model->current_user();
        $hariIni = date('Y-m-d');
        $data['bp'] = $this->model->getBy2Sum('pembayaran', 'tahun', $this->tahun, 'tgl', $hariIni, 'nominal')->row();
        $data['setoran'] = $this->model->getBy2Sum('setoran', 'tahun', $this->tahun, 'tgl_setor', $hariIni, 'nominal')->row();
        $data['keluar'] = $this->model->getBy2Sum('pengeluaran', 'tahun', $this->tahun, 'tanggal', $hariIni, 'nominal')->row();

        $data['bpData'] = $this->db->query("SELECT pembayaran.*, tb_santri.nama FROM pembayaran JOIN tb_santri ON pembayaran.nis=tb_santri.nis WHERE pembayaran.tahun = '$this->tahun' AND tgl = '$hariIni' ")->result();
        $data['setoranData'] = $this->model->getBy2('setoran', 'tahun', $this->tahun, 'tgl_setor', $hariIni)->result();
        $data['keluarData'] = $this->model->getBy2('pengeluaran', 'tahun', $this->tahun, 'tanggal', $hariIni)->result();

        $this->load->view('head');
        $this->load->view('rekapHarian', $data);
        $this->load->view('foot');
    }
}
