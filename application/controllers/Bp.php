<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bp extends CI_Controller
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
        $data['data'] = $this->model->getByJoinSentral('tangg', 'tb_santri', 'nis', 'nis', 'tangg.tahun', $this->tahun)->result();

        $this->load->view('head');
        $this->load->view('bp', $data);
        $this->load->view('foot');
    }

    public function discrb($nis)
    {
        $data['bulan'] = $this->bulan;

        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();
        $data['sn'] = $this->model->getBySentral('tb_santri', 'nis', $nis)->row();
        $data['tgn'] = $this->model->getBy2Sentral('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->model->masukSentral($nis, $this->tahun)->row();
        $data['bayar'] = $this->model->getBy2Sentral('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();
        $data['hasil'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        $data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];


        $this->load->view('head');
        $this->load->view('discrb', $data);
        $this->load->view('foot');
    }

    public function addbayar()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $bulan_bayar = $this->input->post('bulan', true);

        $data = [
            'id_bayar' => $this->uuid->v4(),
            'nis' => $nis,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
            'at' => date('Y-m-d H:i:s'),
        ];

        $this->model->simpan('pembayaran', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Pembayaran berhasil diinput');
            redirect('bp/discrb/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'Pembayaran tidak berhasil diinput');
            redirect('bp/discrb/' . $nis);
        }
    }

    public function delBayar($id)
    {
        $data = $this->model->getBy('pembayaran', 'id_bayar', $id)->row();

        $this->model->hapus('pembayaran', 'id_bayar', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
            redirect('bp/discrb/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Tanggungan tidak berhasil dihapus');
            redirect('bp/discrb/' . $data->nis);
        }
    }

    public function bayar()
    {

        $data['bayar'] = $this->db->query("SELECT * FROM pembayaran JOIN tb_santri ON pembayaran.nis=tb_santri.nis WHERE tahun = '$this->tahun' ORDER BY at DESC ")->result();
        $data['bulan'] = $this->bulan;

        $this->load->view('head');
        $this->load->view('bayar', $data);
        $this->load->view('foot');
    }

    public function cetak($id)
    {
        $data['data'] = $this->db->query("SELECT * FROM pembayaran JOIN tb_santri ON pembayaran.nis=tb_santri.nis WHERE id_bayar = '$id' ")->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tangg'] = $this->model->getBySentral('tangg', 'nis', $data['data']->nis)->row();
        $data['santri'] = $this->model->getBy('tb_santri', 'nis', $data['data']->nis)->row();
        $data['tahun'] = $this->tahun;

        $this->load->view('cetak', $data);
    }
}
