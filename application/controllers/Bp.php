<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class Bp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model');
        $this->sentral = $this->load->database('sentral', true);
        if (!$this->Auth_model->current_user()) {
            redirect('login/logout');
        }

        $this->load->model('Modeldata', 'model');

        $this->tahun = $this->model->getBy('settings', 'namaset', 'tahun')->row('isiset');
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    }

    public function index()
    {
        $data['data'] = $this->model->getByJoinSentral('tangg', 'tb_santri', 'nis', 'nis', 'tangg.tahun', $this->tahun)->result();

        $this->load->view('bp', $data);
    }

    public function bpData()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->sentral->select("tangg.*, tb_santri.nama, tb_santri.k_formal, tb_santri.t_formal");
        $this->sentral->from('tangg');
        $this->sentral->join('tb_santri', 'tangg.nis=tb_santri.nis');
        $this->sentral->where('tangg.tahun', $this->tahun);
        $this->sentral->where('tb_santri.aktif', 'Y');
        $this->sentral->order_by('tb_santri.nama', 'ASC');

        // Filter search
        if (!empty($search_value)) {
            $this->sentral->group_start();
            $this->sentral->like('tangg.briva', $search_value);
            $this->sentral->or_like('tb_santri.nama', $search_value);
            $this->sentral->or_like('tb_santri.k_formal', $search_value);
            $this->sentral->or_like('tb_santri.t_formal', $search_value);
            $this->sentral->group_end();
        }

        $total_records = $this->sentral->count_all_results('', false); // Count total records without limit

        $this->sentral->limit($length, $start);
        $query = $this->sentral->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $data[] = [
                $row_number++,
                $row->id_tangg,
                $row->nis,
                $row->nama,
                $row->briva,
                ($row->me_ju * 2) + ($row->ju_ap * 10),
                $row->k_formal . ' ' . $row->t_formal,
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
        $data['tgn'] = $this->model->getBy2Sentral('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->model->masukSentral($nis, $this->tahun)->row();
        $data['bayar'] = $this->model->getBy2Sentral('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();
        $data['hasil'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();
        $data['dekos'] = $this->model->getBy('dekos', 'nis', $nis);
        $data['printers'] = $this->db->get('printers')->result();

        $data['tmpKos'] = array("-", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];


        $this->load->view('head');
        $this->load->view('discrb', $data);
        $this->load->view('foot');
    }

    public function buatKos($nis)
    {
        $sn = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data = [
            'nis' => $nis,
            't_kos' => $sn->t_kos,
        ];
        $this->model->simpan('dekos', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hasitory sudah dibuat');
            redirect('bp/discrb/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'Hasitory gagal dibuat');
            redirect('bp/discrb/' . $nis);
        }
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

    public function delDekos($id)
    {
        $data = $this->model->getBy('dekos', 'id_dekos', $id)->row();

        $this->model->hapus('dekos', 'id_dekos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Dekosan berhasil dihapus');
            redirect('bp/discrb/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Dekosan tidak berhasil dihapus');
            redirect('bp/discrb/' . $data->nis);
        }
    }
    public function gantiKos()
    {
        $nis = $this->input->post('nis', true);
        $tanggal = $this->input->post('tanggal', true);
        $t_kos = $this->input->post('t_kos', true);

        $kosBaru = [
            'nis' => $nis,
            'masuk' => $tanggal,
            't_kos' => $t_kos,
        ];
        $kosEdit = ['keluar' => $tanggal];
        $santri = ['t_kos' => $t_kos];

        $this->model->edit2('dekos', 'nis', $nis, 'keluar', '0000-00-00', $kosEdit);
        $this->model->simpan('dekos', $kosBaru);
        $this->model->editSantri('tb_santri', 'nis', $nis, $santri);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Dekosan berhasil dipindah');
            redirect('bp/discrb/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'Dekosan tidak berhasil dipindah');
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

        // $data['bayar'] = $this->db->query("SELECT * FROM pembayaran JOIN tb_santri ON pembayaran.nis=tb_santri.nis WHERE tahun = '$this->tahun' ORDER BY at DESC ")->result();
        $data['bulan'] = $this->bulan;

        // $this->load->view('head');
        $this->load->view('bayar', $data);
        // $this->load->view('foot');
    }
    public function bayarData()
    {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("pembayaran.*, tb_santri.nama");
        $this->db->from('pembayaran');
        $this->db->join('tb_santri', 'pembayaran.nis=tb_santri.nis');
        $this->db->where('pembayaran.tahun', $this->tahun);
        $this->db->where('tb_santri.aktif', 'Y');
        $this->db->order_by('pembayaran.at', 'DESC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('pembayaran.tgl', $search_value);
            $this->db->or_like('tb_santri.nama', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $briva = $this->model->getBy2Sentral('tangg', 'nis', $row->nis, 'tahun', $row->tahun)->row();
            $data[] = [
                $row_number++,
                $row->id_bayar,
                $row->tgl,
                $row->nama,
                $row->bulan,
                $row->nominal,
                $row->kasir,
                $briva->briva,
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

    public function cetak($id)
    {
        $data = $this->db->query("SELECT * FROM pembayaran JOIN tb_santri ON pembayaran.nis=tb_santri.nis WHERE id_bayar = '$id' ")->row();
        $user = $this->Auth_model->current_user();
        $tangg = $this->model->getBy2Sentral('tangg', 'nis', $data->nis, 'tahun', $this->tahun)->row();
        $santri = $this->model->getBy('tb_santri', 'nis', $data->nis)->row();
        $tahun = $this->tahun;

        // $this->load->view('cetak', $data);

        // $dataNota = $this->db->query("
        // SELECT pembayaran.*, tb_santri.nama, tb_santri.desa, tb_santri.kec, tb_santri.kab, tb_santri.k_formal, tb_santri.jurusan, tb_santri.t_formal FROM pembayaran 
        // JOIN tb_santri ON pembayaran.nis = tb_santri.nis 
        // WHERE id_bayar = '$id' ")->row();

        // $user = $this->Auth_model->current_user();

        echo json_encode([
            'judul'   => 'KWITANSI PEMBAYARAN BP',
            'pondok'  => 'Ponpes Darul Lughah Wal Karomah',
            'alamat'  => 'Jl. Mayjend Pandjaitan No.12 Kraksaan',
            'tanggal' => date('d-m-Y H:i:s'),
            'kasir'   => $user->nama,
            'nama'    => $santri->nama,
            'ket'    => $data->bulan,
            'briva'    => $tangg->briva,
            'alamat_santri' => $santri->desa . '-' . $santri->kec . '-' . $santri->kab,
            'kelas'   => $santri->k_formal . ' ' . $santri->jurusan . ' ' . $santri->t_formal,
            'tgl_bayar' => $data->at,
            'nominal' => number_format($data->nominal, 0, ',', '.'),
            'tahun'   => $this->tahun,
            'penerima'   => $data->kasir,
        ]);
    }

    public function piutang()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['data'] = $this->model->getBy('piutang', 'tahun', $this->tahun)->result();

        $this->load->view('piutang', $data);
    }

    public function savePiutang()
    {

        $sql = $this->db->query("SELECT COUNT(*) as jml FROM piutang WHERE tahun = '$this->tahun'")->row();

        $data  = [
            'id_piutang' => uniqCodeCust($sql->jml, 'PU', 3),
            'nama_piutang' => $this->input->post('nama', true),
            'ket' => $this->input->post('ket', true),
            'tahun' => $this->tahun
        ];
        $this->model->simpan('piutang', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Piutang berhasil tambah');
            redirect('bp/piutang');
        } else {
            $this->session->set_flashdata('error', 'Piutang tidak berhasil tambah');
            redirect('bp/piutang');
        }
    }

    public function editPiutang()
    {
        $id_piutang = $this->input->post('id', true);
        $data  = [
            'id_piutang' => $id_piutang,
            'nama_piutang' => $this->input->post('nama', true),
            'ket' => $this->input->post('ket', true)
        ];
        $this->model->edit('piutang', 'id_piutang', $id_piutang, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Piutang berhasil diupdate');
            redirect('bp/piutang');
        } else {
            $this->session->set_flashdata('error', 'Piutang tidak berhasil diupdate');
            redirect('bp/piutang');
        }
    }

    public function delPiutang($id)
    {
        $data = $this->model->getBy('piutang', 'id_piutang', $id)->row();

        $this->model->hapus('piutang', 'id_piutang', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Piutang berhasil dihapus');
            redirect('bp/piutang/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Piutang tidak berhasil dihapus');
            redirect('bp/piutang/' . $data->nis);
        }
    }

    public function pos()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['data'] = $this->model->getBy('pos', 'tahun', $this->tahun)->result();
        $data['piutang'] = $this->model->getBy('piutang', 'tahun', $this->tahun)->result();

        $this->load->view('pos', $data);
    }
    public function savePos()
    {
        $sql = $this->db->query("SELECT COUNT(*) as jml FROM pos WHERE tahun = '$this->tahun'")->row();

        $data  = [
            'id_pos' => uniqCodeCust($sql->jml, 'POS', 3),
            'id_piutang' => $this->input->post('piutang', true),
            'nama_pos' => $this->input->post('nama', true),
            'ket' => $this->input->post('ket', true),
            'tahun' => $this->tahun
        ];
        $this->model->simpan('pos', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'pos berhasil tambah');
            redirect('bp/pos');
        } else {
            $this->session->set_flashdata('error', 'pos tidak berhasil tambah');
            redirect('bp/pos');
        }
    }
    public function editPos()
    {
        $id = $this->input->post('id', true);
        $data  = [
            'id_piutang' => $this->input->post('piutang', true),
            'nama_pos' => $this->input->post('nama', true),
            'ket' => $this->input->post('ket', true)
        ];
        $this->model->edit('pos', 'id_pos', $id, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'pos berhasil diupdate');
            redirect('bp/pos');
        } else {
            $this->session->set_flashdata('error', 'pos tidak berhasil diupdate');
            redirect('bp/pos');
        }
    }

    public function delPos($id)
    {

        $this->model->hapus('pos', 'id_pos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'pos berhasil dihapus');
            redirect('bp/pos');
        } else {
            $this->session->set_flashdata('error', 'pos tidak berhasil dihapus');
            redirect('bp/pos');
        }
    }

    public function jenis()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['data'] = $this->model->getBy('jenis_tagihan', 'tahun', $this->tahun)->result();
        $data['pos'] = $this->model->getBy('pos', 'tahun', $this->tahun)->result();

        $this->load->view('jenis', $data);
    }

    public function saveJenis()
    {
        $sql = $this->db->query("SELECT COUNT(*) as jml FROM jenis_tagihan WHERE tahun = '$this->tahun'")->row();
        $data  = [
            'id_jenis' => uniqCodeCust($sql->jml, 'JNS', 3),
            'id_pos' => $this->input->post('pos', true),
            'nama_tagihan' => $this->input->post('nama', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'jenjang' => $this->input->post('jenjang', true),
            'tahun' => $this->tahun
        ];
        $this->model->simpan('jenis_tagihan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'pos berhasil tambah');
            redirect('bp/jenis');
        } else {
            $this->session->set_flashdata('error', 'jenis tidak berhasil tambah');
            redirect('bp/jenis');
        }
    }
    public function editJenis()
    {
        $id_jenis = $this->input->post('id', true);
        $data  = [
            'id_pos' => $this->input->post('pos', true),
            'nama_tagihan' => $this->input->post('nama', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'jenjang' => $this->input->post('jenjang', true),
            'tahun' => $this->tahun
        ];
        $this->model->edit('jenis_tagihan', 'id_jenis', $id_jenis, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'jenis berhasil diedit');
            redirect('bp/jenis');
        } else {
            $this->session->set_flashdata('error', 'jenis tidak berhasil diedit');
            redirect('bp/jenis');
        }
    }

    public function delJenis($id)
    {

        $this->model->hapus('jenis_tagihan', 'id_jenis', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'jenis berhasil dihapus');
            redirect('bp/jenis');
        } else {
            $this->session->set_flashdata('error', 'jenis tidak berhasil dihapus');
            redirect('bp/jenis');
        }
    }

    public function buat()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        // $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();
        $data['santri'] = $this->db->query("SELECT tagihan.*, tb_santri.nis, tb_santri.nama, tb_santri.k_formal, tb_santri.t_formal, SUM(tagihan.nominal) as total FROM tagihan JOIN tb_santri ON tagihan.nis=tb_santri.nis WHERE tb_santri.aktif = 'Y' AND tagihan.tahun = '$this->tahun' GROUP BY tagihan.nis ")->result();

        $this->load->view('buat', $data);
    }

    public function tanggungan($nis)
    {
        $data['santriData'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();
        $data['santri'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tagihan'] = $this->db->query("SELECT *, tagihan.nominal AS nom_jadi FROM tagihan JOIN jenis_tagihan ON tagihan.id_jenis=jenis_tagihan.id_jenis JOIN pos ON pos.id_pos=jenis_tagihan.id_pos WHERE tagihan.nis = $nis AND tagihan.tahun = '$this->tahun' AND jenis_tagihan.tahun = '$this->tahun' AND pos.tahun = '$this->tahun' ORDER BY jenis_tagihan.id_pos ASC ")->result();
        $data['keringanan'] = $this->model->getBy2('keringanan', 'nis', $nis, 'tahun', $this->tahun)->result();
        $tanggTotal = $this->model->getBy2Sum('tagihan', 'nis', $nis, 'tahun', $this->tahun, 'nominal')->row();
        $data['tanggTotal'] = $tanggTotal ? $tanggTotal->jml : 0;

        $this->load->view('detailTangg', $data);
    }

    public function genarate($nis)
    {
        $cek = $this->model->getBy2('tagihan', 'nis', $nis, 'tahun', $this->tahun)->num_rows();

        if ($cek < 1) {
            $tagihan = $this->model->getBy('jenis_tagihan', 'tahun', $this->tahun)->result();
            foreach ($tagihan as $stg) {
                $data = [
                    'nis' => $nis,
                    'id_jenis' => $stg->id_jenis,
                    'nominal' => $stg->nominal,
                    'tahun' => $this->tahun,
                ];

                $this->model->simpan('tagihan', $data);
            }
            $data2 = [
                'nis' => $nis,
                'jumlah' => 0,
                'tahun' => $this->tahun,
            ];
            $this->model->simpan('keringanan', $data2);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'tanggungan berhasil dibuat');
                redirect('bp/tanggungan/' . $nis);
            } else {
                $this->session->set_flashdata('error', 'tanggungan tidak berhasil dibuat');
                redirect('bp/tanggungan/' . $nis);
            }
        } else {
            $this->session->set_flashdata('error', 'tanggungan sudah ada. Silahkan reset dahhulu');
            redirect('bp/tanggungan/' . $nis);
        }
    }

    public function reset($nis)
    {
        $this->model->hapus2('tagihan', 'nis', $nis, 'tahun', $this->tahun);
        $this->model->hapus2('keringanan', 'nis', $nis, 'tahun', $this->tahun);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'tanggungan berhasil direset');
            redirect('bp/tanggungan/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'tanggungan tidak berhasil direset');
            redirect('bp/tanggungan/' . $nis);
        }
    }

    public function editRingan()
    {
        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);
        $data = ['jumlah' => $this->input->post('jumlah', true)];
        $this->model->edit('keringanan', 'id_keringanan', $id, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'tanggungan berhasil diupdate');
            redirect('bp/tanggungan/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'tanggungan tidak berhasil diupdate');
            redirect('bp/tanggungan/' . $nis);
        }
    }

    public function editTagihan()
    {
        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);
        $data = ['nominal' => rmRp($this->input->post('nominal', true))];
        $this->model->edit('tagihan', 'id_tagihan', $id, $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'tanggungan berhasil diupdate');
            redirect('bp/tanggungan/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'tanggungan tidak berhasil diupdate');
            redirect('bp/tanggungan/' . $nis);
        }
    }
    public function delTagihan($id)
    {
        $nis = $this->model->getBy('tagihan', 'id_tagihan', $id)->row();
        $this->model->hapus('tagihan', 'id_tagihan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'jenis berhasil dihapus');
            redirect('bp/tanggungan/' . $nis->nis);
        } else {
            $this->session->set_flashdata('error', 'jenis tidak berhasil dihapus');
            redirect('bp/tanggungan/' . $nis->nis);
        }
    }

    public function calculate()
    {
        $num1 = $this->input->post('num1', true);
        $num2 = $this->input->post('num2', true);
        $operator = $this->input->post('operator', true);
        $result = '';

        // Lakukan perhitungan berdasarkan operator
        switch ($operator) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                // Hindari pembagian oleh nol
                $result = ($num2 != 0) ? $num1 / $num2 : 'Undefined';
                break;
            default:
                $result = 'Invalid Operator';
        }

        // Kirim hasil ke klien
        echo $result;
    }

    public function template()
    {
        $data = array(
            array('NIS'),
            array('ID Jenis'),
            array('Nominal'),
            array('Tahun'),
            array('Keringanan (%)'),
            array('Lmb Extra'),
        );

        // Transposisi data
        $transposedData = call_user_func_array('array_map', array_merge([null], $data));

        // Load PhpSpreadsheet library
        $spreadsheet = new Spreadsheet();

        // Set judul sheet
        $spreadsheet->getActiveSheet()->setTitle('Data Export');

        // Isi data ke dalam sheet
        $spreadsheet->getActiveSheet()->fromArray($transposedData, null, 'A1');

        // Set header untuk membuat file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template Export Tagihan.xlsx"');
        header('Cache-Control: max-age=0');

        // Save ke php://output (output stream)
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function uploadTagihan()
    {
        // Load library dan helper
        $this->load->helper('file');

        // Konfigurasi upload file
        $config['upload_path'] = 'template/assets/static/'; // Direktori penyimpanan file
        $config['allowed_types'] = 'xls|xlsx'; // Jenis file yang diizinkan
        $config['max_size'] = 10240; // Ukuran maksimum file (dalam kilobytes)

        // Memuat library upload
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('berkas')) {
            // Jika upload gagal, tampilkan pesan error
            $error = $this->upload->display_errors();
            echo $error;
        } else {
            // Jika upload berhasil, dapatkan informasi file
            $data = $this->upload->data();
            $file_path = $data['full_path'];
            // Load file Excel menggunakan library PHPExcel
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $objPHPExcel = $reader->load($file_path);

            // Mendapatkan data dari worksheet pertama
            $worksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $worksheet->getHighestDataRow();

            // echo $highestRow;

            // Mulai dari baris kedua (untuk melewati header)
            for ($row = 2; $row <= $highestRow; $row++) {

                $nis = rmRp($worksheet->getCell('A' . $row)->getValue());
                $id_jenis = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('B' . $row)->getValue());
                $nominal = $worksheet->getCell('C' . $row)->getValue() == '' ? 0 : $worksheet->getCell('C' . $row)->getValue();

                $data = [
                    'nis' => $nis,
                    'id_jenis' => $id_jenis,
                    'nominal' => $nominal,
                    'tahun' => $worksheet->getCell('D' . $row)->getValue(),
                ];
                $data2 = [
                    'nis' => $nis,
                    'jumlah' => $worksheet->getCell('E' . $row)->getValue(),
                    'lembaga' => $worksheet->getCell('F' . $row)->getValue(),
                    'tahun' => $worksheet->getCell('D' . $row)->getValue(),
                ];

                $cek = $this->model->getBy2('tagihan', 'nis', $nis, 'id_jenis', $id_jenis)->num_rows();
                $cek2 = $this->model->getBy2('keringanan', 'nis', $nis, 'tahun', $this->tahun)->num_rows();

                // echo $nis . '<br>';
                if ($cek < 1) {
                    $this->model->simpan('tagihan', $data);
                    // echo 'tagihan ksosng harus tambah <br>';
                } else if ($cek > 0) {
                    $this->model->edit2('tagihan', 'nis', $nis, 'id_jenis', $id_jenis, $data);
                    // echo 'tagihan ada dan update saja <br>';
                } else if (!$cek) {
                    $this->model->simpan('tagihan', $data);
                    // echo 'tagihan ksosng harus tambah <br>';
                }

                if ($cek2 < 1) {
                    $this->model->simpan('keringanan', $data2);
                    // echo 'keringanan ksoong tambah kan data<br>';
                } else if ($cek > 0) {
                    // echo 'keringanan ada update saja <br>';
                    $this->model->edit('keringanan', 'nis', $nis, $data2);
                } elseif (!$cek2) {
                    $this->model->simpan('keringanan', $data2);
                    // echo 'keringanan ksoong tambah kan data<br>';
                }
            }


            unlink($file_path);

            $this->session->set_flashdata('ok', 'Upload Selesai');
            redirect('bp/buat');
        }
    }

    public function changePrinter()
    {
        $printer = $this->input->post('printer', TRUE);
        $nis = $this->input->post('nis', TRUE);

        $this->session->set_userdata('printername', $printer);
        redirect('bp/discrb/' . $nis);
    }
}
