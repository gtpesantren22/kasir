<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
        if (!$this->Auth_model->current_user()) {
            redirect('login/logout');
        }
        $user = $this->Auth_model->current_user('nama');
        $this->user = $user->nama;
        $this->load->model('Modeldata', 'model');
        $this->tahun = $this->model->getBy('settings', 'namaset', 'tahun')->row('isiset');
    }

    public function index()
    {
        $data['user'] = $this->Auth_model->current_user();
        $bp = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();
        $setor = $this->model->getBySum('setoran', 'tahun', $this->tahun, 'nominal')->row();

        $data['sumData'] = $this->model->getBy2Sum('tabungan', 'tahun', $this->tahun, 'jenis', 'masuk', 'nominal')->row();
        $data['sumkeluar'] = $this->model->getBy2Sum('tabungan', 'tahun', $this->tahun, 'jenis', 'keluar', 'nominal')->row();
        $data['sumAdmin'] = $this->model->getBy2Sum('tabungan', 'tahun', $this->tahun, 'ket', 'Biaya admin', 'nominal')->row();

        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();


        // $this->load->view('head');
        $this->load->view('tabungan', $data);
        // $this->load->view('foot');
    }

    public function tabunganData()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("id_tabungan, tabungan.nis, tb_santri.nama, SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) AS total, SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) AS pakai ");
        $this->db->from('tabungan');
        $this->db->join('tb_santri', 'tabungan.nis=tb_santri.nis');
        $this->db->where('tabungan.tahun', $this->tahun);
        $this->db->where('tb_santri.aktif', 'Y');
        $this->db->group_by('tabungan.nis');
        $this->db->order_by('tb_santri.nama', 'ASC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('tabungan.nis', $search_value);
            $this->db->or_like('tb_santri.nama', $search_value);
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
                $row->id_tabungan,
                $row->nis,
                $row->nama,
                $row->total,
                $row->pakai,
                $row->total - $row->pakai
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
    public function rincianTabungan($nis)
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("*");
        $this->db->from('tabungan');
        $this->db->where('nis', $nis);
        $this->db->where('tahun', $this->tahun);
        $this->db->order_by('tanggal', 'DESC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('tanggal', $search_value);
            $this->db->or_like('nominal', $search_value);
            $this->db->or_like('jenis', $search_value);
            $this->db->or_like('ket', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $data[] = [
                $row_number++, // 0
                $row->id_tabungan, // 1
                $row->nis, // 2
                $row->nominal, // 3
                $row->tanggal, // 4
                $row->ket, // 5
                $row->jenis, // 6
                $row->kasir // 7
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

    public function saveTabungan()
    {
        $data = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $this->input->post('nis', true),
            "nominal" => rmRp($this->input->post('jumlah', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "ket" => $this->input->post('ketr', true),
            "jenis" => 'masuk',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];

        $this->model->simpan('tabungan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('tabungan');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('tabungan');
        }
    }

    public function delTabungan($id)
    {
        $this->model->hapus('tabungan', 'id_tabungan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('tabungan');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('tabungan');
        }
    }

    public function outTabungan()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        // $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        // $dekos = $this->input->post('dekos', true);
        $bulan_bayar = $this->input->post('bulan', true);
        $ket = $this->input->post('ket', true);
        $admin = $this->input->post('admin', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        // $dpBr = $this->model->getBy3('tanggungan', 'nis', $nis, 'tahun', $this->tahun, 'bulan', $bulan_bayar)->row();

        // $by = $nominal + $this->input->post('masuk', true);
        // $ttl = $this->input->post('ttl', true);
        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        $hpNo = $dp->hp;
        // $hpNo = '089682351413';
        $hpNo2 = '085236924510';

        // $dataBayar = [
        //     'id_bayar' => $this->uuid->v4(),
        //     'nis' => $nis,
        //     'tgl' => $tgl,
        //     'nominal' => $nominal,
        //     'bulan' => $bulan_bayar,
        //     'tahun' => $tahun,
        //     'kasir' => $kasir,
        //     'at' => date('Y-m-d H:i:s'),
        // ];
        // $data2 = [
        //     'nis' => $nis,
        //     'nominal' => 300000,
        //     'bulan' => $bulan_bayar,
        //     'tahun' => $tahun,
        //     'tgl' => $tgl,
        //     'penerima' => $kasir,
        //     'stts' => 1,
        //     'waktu' => date('Y-m-d H:i'),
        // ];
        $dataTabungan = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $nis,
            "nominal" => $nominal,
            "tanggal" => $tgl,
            "ket" => $ket,
            "jenis" => 'keluar',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];
        $dataAdmin = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $nis,
            "nominal" => rmRp($admin),
            "tanggal" => $tgl,
            "ket" => 'Biaya admin',
            "jenis" => 'keluar',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];

        // No. BRIVA : *' . $dpBr->briva . '*
        //         $pesan = '
        // *KWITANSI PEMBAYARAN ELEKTRONIK*
        // *PP DARUL LUGHAH WAL KAROMAH*
        // Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :

        // Nama : *' . $nama . '*
        // Alamat : *' . $alm . '* 
        // Nominal Pembayaran: *' . rupiah($nominal) . '*
        // Tanggal Bayar : *' . $tgl . '*
        // Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
        // Penerima: *' . $kasir . '*

        // Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
        // *Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
        // *https://wa.me/6282329641926*

        // Terimakasih';

        $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
        $cekTabungan = $this->db->query("SELECT SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) AS total, SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) AS pakai FROM tabungan WHERE nis = '$nis' AND tahun = '$tahun' ")->row();
        $saldo = $cekTabungan->total - $cekTabungan->pakai;
        if ($cek < 1) {
            if ($saldo < $nominal) {
                $this->session->set_flashdata('error', 'Saldo tidak cukup');
                redirect('tabungan');
            } else {
                // if ($dekos == 'Y') {
                //     $this->model->inputDb2('kos', $data2);
                //     $this->model->simpan('pembayaran', $data);
                //     $this->model->simpan('tabungan', $dataTabungan);
                //     if ($admin != 0 || $admin != '') {
                //         $this->model->simpan('tabungan', $dataAdmin);
                //     }

                //     if ($this->db->affected_rows() > 0) {
                //         kirim_person($this->apiKey, $hpNo, $pesan);
                //         kirim_person($this->apiKey, $hpNo2, $pesan);
                //         $this->session->set_flashdata('ok', 'Tabungan berhasil diinput');
                //         redirect('tabungan');
                //     } else {
                //         $this->session->set_flashdata('error', 'Tabungan tidak berhasil diinput');
                //         redirect('tabungan');
                //     }
                // } else {
                // }
                // $this->model->simpan('pembayaran', $dataBayar);
                $this->model->simpan('tabungan', $dataTabungan);
                if ($admin != 0 || $admin != '') {
                    $this->model->simpan('tabungan', $dataAdmin);
                }

                if ($this->db->affected_rows() > 0) {
                    // kirim_person($this->apiKey, $hpNo, $pesan);
                    // kirim_person($this->apiKey, $hpNo2, $pesan);
                    $this->session->set_flashdata('ok', 'Tabungan berhasil diinput');
                    redirect('tabungan');
                } else {
                    $this->session->set_flashdata('error', 'Tabungan tidak berhasil diinput');
                    redirect('tabungan');
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Maaf pembayaran bulan ini sudah ada');
            redirect('tabungan');
        }
    }

    public function cetakNotaTabungan($id)
    {

        $data['data'] = $this->db->query("SELECT * FROM tabungan WHERE id_tabungan = '$id' ")->row();
        $data['user'] = $this->Auth_model->current_user();
        // $data['tangg'] = $this->model->getBy2Sentral('tangg', 'nis', $data['data']->nis, 'tahun', $this->tahun)->row();
        $data['santri'] = $this->model->getBy('tb_santri', 'nis', $data['data']->nis)->row();
        $data['tahun'] = $this->tahun;

        $this->load->view('cetakTabungan', $data);
    }

    public function getDetailSantri()
    {
        $nis = $this->input->post('nis');
        $data = $this->model->getBy('tb_santri', 'nis', $nis)->row();

        echo json_encode(['nama' => $data->nama]);
    }

    public function rekap()
    {
        $data['user'] = $this->Auth_model->current_user();
        $this->load->view('rekapTabungan', $data);
    }

    public function tampil_rekap()
    {
        $dari = $this->input->post('dari', true);
        $sampai = $this->input->post('sampai', true);

        $data['judul'] = $dari == $sampai ? 'Rekap Tabungan : HARI INI' : "Rekap tabungan : $dari s/d $sampai";
        $data['data'] = $this->db->query("SELECT tabungan.*, tb_santri.nama FROM tabungan JOIN tb_santri ON tabungan.nis=tb_santri.nis WHERE tabungan.tanggal >= '$dari' AND tabungan.tanggal <= '$sampai' AND tabungan.tahun = '$this->tahun' ")->result();

        $total = $this->db->query("SELECT SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) AS debit, SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) AS kredit FROM tabungan WHERE tanggal >= '$dari' AND tanggal <= '$sampai' AND tahun = '$this->tahun' GROUP BY tahun ")->row();

        $data['debit'] = $total ? $total->debit : 0;
        $data['kredit'] = $total ? $total->kredit : 0;
        $data['saldo'] = $data['debit'] - $data['kredit'];

        $this->load->view('showRekap', $data);
    }
}
