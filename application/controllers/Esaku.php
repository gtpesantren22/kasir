<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Esaku extends CI_Controller
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
        $data['data'] = $this->db->query("SELECT * FROM esaku LEFT JOIN tb_santri ON esaku.nis=tb_santri.nis WHERE esaku.tahun = '$this->tahun' ")->result();

        $this->load->view('esaku', $data);
    }

    public function sakuData()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("esaku.*, tb_santri.nama, tb_santri.k_formal, tb_santri.t_formal");
        $this->db->from('esaku');
        $this->db->join('tb_santri', 'esaku.nis=tb_santri.nis');
        $this->db->where('esaku.tahun', $this->tahun);
        $this->db->where('tb_santri.aktif', 'Y');
        $this->db->order_by('tb_santri.nama', 'ASC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('esaku.tanggal', $search_value);
            $this->db->or_like('tb_santri.nama', $search_value);
            $this->db->or_like('tb_santri.k_formal', $search_value);
            $this->db->or_like('tb_santri.t_formal', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $data[] = [
                $row_number++,
                $row->id_bayar,
                $row->tgl,
                $row->nama,
                $row->nominal,
                $row->k_formal . ' ' . $row->t_formal,
                $row->nis,
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        ];

        // Set content-type header and return JSON data
        header('Content-Type: application/json');
        echo json_encode($output);
        // var_dump($output);
    }

    public function discrb($nis)
    {
        $data['bulan'] = $this->bulan;

        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();
        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['hasil'] = $this->model->getBy2('esaku', 'nis', $nis, 'tahun', $this->tahun)->result();
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];
        $data['printers'] = $this->db->get('printers')->result();

        $this->load->view('head');
        $this->load->view('esakudtl', $data);
        $this->load->view('foot');
    }

    public function addbayar()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nis = $this->input->post('nis', true);
        $ket = $this->input->post('ket', true);
        $tahun = $this->tahun;

        $data = [
            'nis' => $nis,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'tahun' => $tahun,
            'kasir' => $kasir,
            'ket' => $ket,
            'waktu' => date('H:i:s'),
        ];

        $this->model->simpan('esaku', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Uang saku berhasil diinput');
            redirect('esaku/discrb/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'Uang saku tidak berhasil diinput');
            redirect('esaku/discrb/' . $nis);
        }
    }

    public function delBayar($id)
    {
        $data = $this->model->getBy('esaku', 'id_bayar', $id)->row();

        $this->model->hapus('esaku', 'id_bayar', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Esaku data berhasil dihapus');
            redirect('esaku/discrb/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Esaku data tidak berhasil dihapus');
            redirect('esaku/discrb/' . $data->nis);
        }
    }

    public function data_cetak($id)
    {
        $dataNota = $this->db->query("
        SELECT esaku.*, tb_santri.nama, tb_santri.desa, tb_santri.kec, tb_santri.kab, tb_santri.k_formal, tb_santri.jurusan, tb_santri.t_formal FROM esaku 
        JOIN tb_santri ON esaku.nis = tb_santri.nis 
        WHERE id_bayar = '$id'
    ")->row();

        $user = $this->Auth_model->current_user();

        echo json_encode([
            'judul'   => 'KWITANSI UANG SAKU',
            'pondok'  => 'Ponpes Darul Lughah Wal Karomah',
            'alamat'  => 'Jl. Mayjend Pandjaitan No.12 Kraksaan',
            'tanggal' => date('d-m-Y H:i:s'),
            'kasir'   => $user->nama,
            'nama'    => $dataNota->nama,
            'ket'    => $dataNota->ket,
            'waktu'    => $dataNota->waktu,
            'alamat_santri' => $dataNota->desa . '-' . $dataNota->kec . '-' . $dataNota->kab,
            'kelas'   => $dataNota->k_formal . ' ' . $dataNota->jurusan . ' ' . $dataNota->t_formal,
            'tgl_bayar' => $dataNota->tgl,
            'nominal' => number_format($dataNota->nominal, 0, ',', '.'),
            'tahun'   => $this->tahun,
            'printername' => $this->session->userdata('printername')
        ]);
    }

    public function changePrinter()
    {
        $printer = $this->input->post('printer', TRUE);
        $nis = $this->input->post('nis', TRUE);

        $this->session->set_userdata('printername', $printer);
        redirect('esaku/discrb/' . $nis);
    }
}
