<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Others extends CI_Controller
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

        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function index()
    {
        $data['setor'] = $this->model->getBy('others', 'tahun', $this->tahun)->result();

        $this->load->view('head');
        $this->load->view('others', $data);
        $this->load->view('foot');
    }

    public function add()
    {
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('otherAdd', $data);
        $this->load->view('foot');
    }

    public function save()
    {
        $user = $this->Auth_model->current_user();
        $data = [
            'dari' => $this->input->post('dari', true),
            'ket' => $this->input->post('ket', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tanggal' => $this->input->post('tanggal', true),
            'kasir' => $user->nama,
            'tahun' => $this->tahun,
            'created' => date('Y-m-d H:i:s'),
        ];

        $this->model->simpan('others', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil tersimpan');
            redirect('others');
        } else {
            $this->session->set_flashdata('error', 'Data gagal tersimpan');
            redirect('others');
        }
    }

    public function delBayar($id)
    {
        $this->model->hapus('others', 'id_other', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil terhapus');
            redirect('others');
        } else {
            $this->session->set_flashdata('error', 'Data gagal terhapus');
            redirect('others');
        }
    }

    public function print($id)
    {
        $data['data'] = $this->model->getBy('others', 'id_other', $id)->row();

        $this->load->view('cetak_lain', $data);
    }
    public function printV2($id)
    {
        $data['data'] = $this->model->getBy('others', 'id_other', $id)->row();

        $this->load->view('cetak_lain2', $data);
    }
}
