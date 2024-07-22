<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
        if (!$this->Auth_model->current_user()) {
            redirect('login/logout');
        }

        $this->load->model('Modeldata', 'model');
        $this->tahun = '2024/2025';
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function index()
    {
        $data['setor'] = $this->model->getBy('setoran', 'tahun', $this->tahun)->result();

        $this->load->view('head');
        $this->load->view('setor', $data);
        $this->load->view('foot');
    }

    public function add()
    {
        $data['lembaga'] = $this->model->getBySentral('lembaga', 'tahun', $this->tahun)->result();
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('setorAdd', $data);
        $this->load->view('foot');
    }

    public function save()
    {
        $user = $this->Auth_model->current_user();
        $data = [
            'id_setor' => $this->uuid->v4(),
            'lembaga' => $this->input->post('lembaga', true),
            'uraian' => $this->input->post('uraian', true),
            'bulan' => $this->input->post('bulan', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl', true),
            'penyetor' => $this->input->post('penyetor', true),
            'kasir' => $user->nama,
            'tahun' => $this->tahun,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->model->simpan('setoran', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil tersimpan');
            redirect('setor');
        }
    }

    public function delBayar($id)
    {
        $this->model->hapus('setoran', 'id_setor', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil terhapus');
            redirect('setor');
        } else {
            $this->session->set_flashdata('error', 'Data gagal terhapus');
            redirect('setor');
        }
    }
    public function edit($id)
    {
        $data['data'] = $this->model->getBy('setoran', 'id_setor', $id)->row();
        $data['lembaga'] = $this->model->getBySentral('lembaga', 'tahun', $this->tahun)->result();
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('setorEdit', $data);
        $this->load->view('foot');
    }
    public function saveEdit()
    {
        $id = $this->input->post('id', true);
        $data = [
            'lembaga' => $this->input->post('lembaga', true),
            'uraian' => $this->input->post('uraian', true),
            'bulan' => $this->input->post('bulan', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl', true),
            'penyetor' => $this->input->post('penyetor', true),
        ];

        $this->model->edit('setoran', 'id_setor', $id, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil diupdate');
            redirect('setor');
        } else {
            $this->session->set_flashdata('error', 'Data gagal diupdate');
            redirect('setor');
        }
    }
}
