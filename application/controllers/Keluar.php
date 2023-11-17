<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
        if (!$this->Auth_model->current_user()) {
            redirect('login/logout');
        }

        $this->load->model('Modeldata', 'model');
        $this->tahun = '2023/2024';
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function index()
    {
        $data['keluar'] = $this->model->getBy('pengeluaran', 'tahun', $this->tahun)->result();

        $this->load->view('head');
        $this->load->view('keluar', $data);
        $this->load->view('foot');
    }

    public function add()
    {
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('keluarAdd', $data);
        $this->load->view('foot');
    }

    public function save()
    {
        $user = $this->Auth_model->current_user();
        $data = [
            'id_keluar' => $this->uuid->v4(),
            'ket' => $this->input->post('ket', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tanggal' => $this->input->post('tanggal', true),
            'penerima' => $this->input->post('penerima', true),
            'kasir' => $user->nama,
            'tahun' => $this->tahun,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->model->simpan('pengeluaran', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil tersimpan');
            redirect('keluar');
        } else {
            $this->session->set_flashdata('ok', 'Data gagal tersimpan');
            redirect('keluar');
        }
    }

    public function delBayar($id)
    {
        $this->model->hapus('pengeluaran', 'id_keluar', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil terhapus');
            redirect('keluar');
        } else {
            $this->session->set_flashdata('error', 'Data gagal terhapus');
            redirect('keluar');
        }
    }

    public function edit($id)
    {
        $data['data'] = $this->model->getBy('pengeluaran', 'id_keluar', $id)->row();
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('keluarEdit', $data);
        $this->load->view('foot');
    }

    public function saveEdit()
    {
        $id = $this->input->post('id', true);
        $data = [
            'ket' => $this->input->post('ket', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tanggal' => $this->input->post('tanggal', true),
            'penerima' => $this->input->post('penerima', true),
        ];

        $this->model->edit('pengeluaran', 'id_keluar', $id, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil diupdate');
            redirect('keluar');
        } else {
            $this->session->set_flashdata('error', 'Data gagal diupdate');
            redirect('keluar');
        }
    }
}
